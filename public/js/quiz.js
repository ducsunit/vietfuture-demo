const $ = (sel, el = document) => el.querySelector(sel);
const params = new URLSearchParams(location.search);
const kidId = (function(){
  // Ưu tiên phiên sau kích hoạt: nếu server đã gắn session student_id, client sẽ không cần kidId
  const k = params.get("kid");
  return k || "";
})();
const age = "";
const bookUid = params.get("book") || "";
const lessonId = params.get("lesson") || "";
let studentName = '';
let LESSON = null; // dữ liệu bài học lấy từ API
// Answers must be initialized before any event bindings
var answers = {};

// Không dùng localStorage nữa; giữ stub không truy cập trình duyệt
const LS = {
  get(k, def) { return def; },
  set(k, v) { /* no-op */ },
};
async function ensureName() { /* không còn yêu cầu nhập tên ở client; đã nhập ở bước activate */ }

(async function init() {
  await ensureName();
  $("#kidTag").textContent = `• Đã kích hoạt •`;
  await loadLesson();
  // lấy điểm từ DB
  try {
    const r = await fetch('/api/points', { headers: { Accept: 'application/json' } });
    const j = await r.json();
    if (typeof j.point === 'number') userPoints = j.point;
  } catch (e) {}
  render();
})();

async function loadLesson() {
  try {
    if (!bookUid || !lessonId) {
      LESSON = null;
      return;
    }
    const q = new URLSearchParams({ book: bookUid, lesson: lessonId }).toString();
    const res = await fetch(`/api/lesson?${q}`, { headers: { Accept: 'application/json' } });
    const data = await res.json();
    LESSON = data && data.lesson ? data.lesson : null;
  } catch (e) {
    LESSON = null;
  }
}

function getQuiz() {
  if (!LESSON) return { title: 'Bài học', timeLimitSec: 90, questions: [] };
  return {
    title: LESSON.title || 'Bài học',
    timeLimitSec: 90,
    questions: LESSON.questions || [],
  };
}

const REWARDS = {
  stickers: [
    { id: "stk-phao", name: "Phao cứu hộ", emoji: "🛟", points: 10 },
    { id: "stk-ca-heo", name: "Bạn cá heo", emoji: "🐬", points: 15 },
    { id: "stk-sao-bien", name: "Sao biển", emoji: "⭐", points: 20 },
    { id: "stk-cua", name: "Cua biển", emoji: "🦀", points: 25 },
    { id: "stk-rong", name: "Rồng biển", emoji: "🐉", points: 30 },
  ],
  badges: [
    { id: "bd-hero", name: "Người hùng an toàn nước", emoji: "🏅", points: 50 },
    { id: "bd-swimmer", name: "Vận động viên bơi lội", emoji: "🏊‍♂️", points: 40 },
    { id: "bd-lifeguard", name: "Cứu hộ viên", emoji: "🚑", points: 60 },
  ],
  backgrounds: [
    { id: "bg-ocean", name: "Nền đại dương", emoji: "🌊", points: 35 },
    { id: "bg-beach", name: "Nền bãi biển", emoji: "🏖️", points: 45 },
  ],
};

let current = 0;
let selected = null;
let orderWorking = [];
let timeLeft = 0; // will derive from getQuiz()
let timerId = null;
var answers = {};

// Hệ thống điểm và phần quà
let userPoints = 0;
let userRewards = [];
let userBadges = [];
let userBackgrounds = [];

function render() {
  const QUIZ = getQuiz();
  const root = $("#view");
  if (!LESSON) {
    root.innerHTML = `<div class="card"><h2>Không tìm thấy nội dung bài học</h2><p>Vui lòng kiểm tra tham số book/lesson hoặc liên hệ quản trị.</p></div>`;
    return;
  }
  // derive timer on first render
  if (!timeLeft) timeLeft = QUIZ.timeLimitSec || 90;
  const q = QUIZ.questions[current];
  if (!q) return renderResult();
  const qType = String(q.type || (q.options ? 'single' : (q.items ? 'order' : ''))).toLowerCase();
  const prog = `Câu ${current + 1}/${QUIZ.questions.length}`;
  root.innerHTML = `
      <div class="card">
        <div class="progress"><div class="muted">${prog}</div></div>
        <h2>${QUIZ.title} — ${prog}</h2>
        <p>${q.text}</p>
        <div id="zone"></div>
        <div class="foot">
          <button class="btn btn-ghost" onclick="prevQ()" ${
            current === 0 ? "disabled" : ""
          }>◀ Trước</button>
          <button class="btn btn-primary" id="nextBtn">${
            current === QUIZ.questions.length - 1 ? "Nộp bài ▶" : "Tiếp ▶"
          }</button>
        </div>
      </div>`;
  $("#nextBtn").addEventListener("click", nextQ);
  if (qType === "single") renderSingle(q);
  if (qType === "order") renderOrder(q);
}

function renderSingle(q) {
  const zone = $("#zone");
  zone.className = "choices";
  zone.innerHTML = q.options
    .map((o) => `<div class="choice" data-id="${o.id}">${o.text}</div>`)
    .join("");
  // Event delegation for reliability
  zone.addEventListener("click", (e) => {
    const el = e.target.closest('.choice');
    if (!el || !zone.contains(el)) return;
    selected = el.dataset.id;
    answers[q.id] = selected;
    zone.querySelectorAll('.choice').forEach((c) => c.setAttribute('aria-selected', 'false'));
    el.setAttribute('aria-selected', 'true');
  });
}

function renderOrder(q) {
  const zone = $("#zone");
  orderWorking = [...q.items];
  drawOrderList(zone, orderWorking, q);
}
function drawOrderList(zone, arr, q) {
  zone.innerHTML = `<div class="grid">${arr
    .map((t, i) => `<div class="choice" draggable="true" data-i="${i}">⇅ ${t}</div>`)
    .join("")}</div>`;
  zone.querySelectorAll(".choice").forEach((el) => {
    el.addEventListener("dragstart", (e) => {
      e.dataTransfer.setData("text/plain", el.dataset.i);
    });
    el.addEventListener("dragover", (e) => {
      e.preventDefault();
    });
    el.addEventListener("drop", (e) => {
      e.preventDefault();
      const from = +e.dataTransfer.getData("text/plain");
      const to = +el.dataset.i;
      const copy = [...orderWorking];
      const [mv] = copy.splice(from, 1);
      copy.splice(to, 0, mv);
      orderWorking = copy;
      drawOrderList(zone, orderWorking, q);
    });
  });
  answers[q.id] = orderWorking;
}

function prevQ() {
  current = Math.max(0, current - 1);
  render();
}
function nextQ() {
  const QUIZ = getQuiz();
  const total = (QUIZ && Array.isArray(QUIZ.questions)) ? QUIZ.questions.length : 0;
  if (current < total - 1) {
    current++;
    selected = null;
    render();
  } else {
    renderResult();
  }
}

async function renderResult() {
  const QUIZ = getQuiz();
  clearInterval(timerId);

  // Tính điểm dựa trên kết quả
  let earnedPoints = 0;
  let correctAnswers = 0;

  (QUIZ.questions || []).forEach((q) => {
    if (q.type === "single") {
      if (answers[q.id] && q.options.find((o) => o.id === answers[q.id] && o.correct)) {
        correctAnswers++;
        earnedPoints += 10;
      }
    } else if (q.type === "order") {
      const userAnswer = answers[q.id] || [];
      if (JSON.stringify(userAnswer) === JSON.stringify(q.answer)) {
        correctAnswers++;
        earnedPoints += 15;
      }
    }
  });

  // Thêm điểm bonus nếu trả lời đúng tất cả
  if (correctAnswers === QUIZ.questions.length) {
    earnedPoints += 25; // Bonus hoàn hảo
  }

  // Không cập nhật điểm cục bộ; điểm lấy từ DB sau khi gửi

  const root = $("#view");
  let html = `<div class="card"><h2>🎉 Kết quả & Giải thích</h2>`;

  // Hiển thị điểm đạt được
  html += `<div style='background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; padding:20px; border-radius:12px; margin-bottom:24px; text-align:center;'>`;
  html += `<h3 style='margin:0 0 8px 0; font-size:24px;'>🏆 Điểm số của bạn</h3>`;
  html += `<div style='font-size:32px; font-weight:bold; margin-bottom:8px;'>+${earnedPoints} điểm</div>`;
  html += `<div style='font-size:18px;'>Tổng điểm: <strong>${userPoints}</strong> điểm</div>`;
  if (correctAnswers === QUIZ.questions.length) {
    html += `<div style='margin-top:12px; padding:8px 16px; background:rgba(255,255,255,0.2); border-radius:8px; display:inline-block;'>🎯 Hoàn hảo! +25 điểm bonus</div>`;
  }
  html += `</div>`;

  QUIZ.questions.forEach((q) => {
    html += `<div style='margin-bottom:32px; padding:20px; border:1px solid #e2e8f0; border-radius:12px; background:#fafbfc;'><p style='margin:0 0 16px 0; font-size:16px;'><b>${q.text}</b></p>`;
    if (q.type === "single") {
      html += `<div style='display:grid; gap:12px; margin-bottom:16px;'>`;
      q.options.forEach((o) => {
        const isCorrect = o.correct;
        const chosen = answers[q.id];
        let cls = "choice";
        if (chosen === o.id) {
          cls += isCorrect ? " correct" : " incorrect";
        }
        if (isCorrect && chosen !== o.id) {
          cls += " correct";
        }
        html += `<div class='${cls}' style='margin-bottom:0;'>${o.text}</div>`;
      });
      html += `</div>`;

      if (
        answers[q.id] !== undefined &&
        !q.options.find((o) => o.id === answers[q.id] && o.correct)
      ) {
        const correctOption = q.options.find((o) => o.correct);
        const userOption = q.options.find((o) => o.id === answers[q.id]);
        html += `<div style='background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:12px; margin-top:8px;'>`;
        html += `<p style='margin:0 0 8px 0; color:#dc2626; font-weight:600;'>❌ Đáp án của bạn: ${userOption.text}</p>`;
        html += `<p style='margin:0 0 8px 0; color:#059669; font-weight:600;'>✅ Đáp án đúng: ${correctOption.text}</p>`;
        html += `<p style='margin:0; color:#374151; font-size:14px;'>💡 <strong>Giải thích:</strong> ${q.explain || "Hãy đọc kỹ câu hỏi và chọn đáp án phù hợp nhất."}</p>`;
        html += `</div>`;
      } else if (answers[q.id] !== undefined && q.options.find((o) => o.id === answers[q.id] && o.correct)) {
        html += `<div style='background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:12px; margin-top:8px;'>`;
        html += `<p style='margin:0; color:#059669; font-weight:600;'>✅ Chính xác! ${q.explain || "Bạn đã trả lời đúng."}</p>`;
        html += `</div>`;
      }
    }

    if (q.type === "order") {
      const userAnswer = answers[q.id] || [];
      const isCorrect = JSON.stringify(userAnswer) === JSON.stringify(q.answer);

      html += `<div style='margin-top:12px;'>`;
      html += `<p style='margin:0 0 8px 0; font-weight:600;'>Thứ tự bạn chọn:</p>`;
      html += `<div style='background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:12px;'>`;
      html += `<p style='margin:0; color:#475569;'>${userAnswer.join(" → ")}</p>`;
      html += `</div>`;

      html += `<p style='margin:0 0 8px 0; font-weight:600;'>Thứ tự đúng:</p>`;
      html += `<div style='background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:12px; margin-bottom:12px;'>`;
      html += `<p style='margin:0; color:#059669;'>${q.answer.join(" → ")}</p>`;
      html += `</div>`;

      if (isCorrect) {
        html += `<div style='background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:12px;'>`;
        html += `<p style='margin:0; color:#059669; font-weight:600;'>✅ Hoàn hảo! Bạn đã sắp xếp đúng thứ tự.</p>`;
        html += `</div>`;
      } else {
        html += `<div style='background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:12px;'>`;
        html += `<p style='margin:0; color:#dc2626; font-weight:600;'>❌ Thứ tự chưa chính xác</p>`;
        html += `<p style='margin:8px 0 0 0; color:#374151; font-size:14px;'>💡 <strong>Lưu ý:</strong> Hãy nhớ rằng khi gặp tình huống nguy hiểm, việc giữ bình tĩnh luôn là bước đầu tiên quan trọng nhất.</p>`;
        html += `</div>`;
      }
      html += `</div>`;
    }
    html += `</div>`;
  });

  // Thêm nút vào cửa hàng phần quà
  html += `<div class='foot' style='justify-content:space-between;'>`;
  html += `<a class='btn btn-ghost' href='#' onclick='location.reload()'>🔄 Làm lại</a>`;
  html += `<div style='display:flex; gap:12px;'>`;
  html += `<button class='btn btn-ghost' onclick='showCollection()'>📚 Bộ sưu tập</button>`;
  html += `<button class='btn btn-primary' onclick='showRewardShop()'>🛍️ Cửa hàng phần quà</button>`;
  html += `</div>`;
  html += `</div></div>`;

  root.innerHTML = html;
  playSuccessSound();
  confettiBurst();

  // Gửi tiến trình lên server
  try {
    await fetch('/demo/progress', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (window.Laravel && window.Laravel.csrfToken) || '',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        kidId,
        lesson: lessonId,
        score: earnedPoints, // chỉ gửi điểm vừa kiếm được
        age,
        name: studentName
      })
    });
    // đồng bộ điểm từ DB về UI
    const r = await fetch('/api/points', { headers: { Accept: 'application/json' } });
    const j = await r.json();
    if (typeof j.point === 'number') userPoints = j.point;
    updateHeaderPoints();
  } catch (e) {}
}

// --- Sound -----------------------------------------------------
let audioCtx;
function playSuccessSound() {
  try {
    audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
    const now = audioCtx.currentTime;
    const notes = [523.25, 659.25, 783.99];
    notes.forEach((freq, i) => {
      const o = audioCtx.createOscillator();
      const g = audioCtx.createGain();
      o.frequency.value = freq;
      o.type = "sine";
      g.gain.setValueAtTime(0.001, now + i * 0.12);
      g.gain.exponentialRampToValueAtTime(0.15, now + i * 0.12 + 0.02);
      g.gain.exponentialRampToValueAtTime(0.001, now + i * 0.12 + 0.25);
      o.connect(g);
      g.connect(audioCtx.destination);
      o.start(now + i * 0.12);
      o.stop(now + i * 0.12 + 0.26);
    });
  } catch (e) {}
}

const cvs = $("#confetti");
const ctx = cvs.getContext("2d");
function confettiBurst() {
  cvs.width = innerWidth;
  cvs.height = innerHeight;
  const parts = Array.from({ length: 80 }, () => ({
    x: Math.random() * cvs.width,
    y: -10,
    v: 2 + Math.random() * 3,
    s: 2 + Math.random() * 5,
    r: Math.random() * Math.PI,
  }));
  let t = 0;
  const maxT = 120;
  function tick() {
    ctx.clearRect(0, 0, cvs.width, cvs.height);
    parts.forEach((p) => {
      p.y += p.v;
      p.r += 0.05;
      ctx.save();
      ctx.translate(p.x, p.y);
      ctx.rotate(p.r);
      ctx.fillRect(-p.s / 2, -p.s / 2, p.s, p.s);
      ctx.restore();
    });
    if (++t < maxT) requestAnimationFrame(tick);
    else ctx.clearRect(0, 0, cvs.width, cvs.height);
  }
  tick();
}

// Hàm hiển thị cửa hàng phần quà
function showRewardShop() {
  const root = $("#view");
  let html = `<div class="card"><h2>🛍️ Cửa hàng phần quà</h2>`;

  // Hiển thị điểm hiện tại
  html += `<div style='background:#f0f9ff; border:1px solid #0ea5e9; border-radius:8px; padding:16px; margin-bottom:24px; text-align:center;'>`;
  html += `<div style='font-size:24px; font-weight:bold; color:#0ea5e9;'>${userPoints} điểm</div>`;
  html += `<div style='color:#0369a1;'>Điểm khả dụng</div>`;
  html += `</div>`;

  // Phần Stickers
  html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🎨 Stickers</h3>`;
  html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:32px;'>`;
  REWARDS.stickers.forEach((sticker) => {
    const isOwned = userRewards.includes(sticker.id);
    const canAfford = userPoints >= sticker.points;
    html += `<div style='border:2px solid ${isOwned ? "#10b981" : "#e5e7eb"}; border-radius:12px; padding:16px; background:${isOwned ? "#ecfdf5" : "#f9fafb"}; text-align:center;'>`;
    html += `<div style='font-size:32px; margin-bottom:8px;'>${sticker.emoji}</div>`;
    html += `<div style='font-weight:600; margin-bottom:4px;'>${sticker.name}</div>`;
    html += `<div style='color:#6b7280; font-size:14px; margin-bottom:12px;'>${sticker.points} điểm</div>`;
    if (isOwned) {
      html += `<div style='color:#059669; font-weight:600;'>✅ Đã sở hữu</div>`;
    } else if (canAfford) {
      html += `<button class='btn btn-primary' style='width:100%;' onclick='buyReward("sticker", "${sticker.id}")'>Mua ngay</button>`;
    } else {
      html += `<div style='color:#ef4444; font-weight:600;'>❌ Không đủ điểm</div>`;
    }
    html += `</div>`;
  });
  html += `</div>`;

  // Phần Badges
  html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🏅 Huy hiệu</h3>`;
  html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:32px;'>`;
  REWARDS.badges.forEach((badge) => {
    const isOwned = userBadges.includes(badge.id);
    const canAfford = userPoints >= badge.points;
    html += `<div style='border:2px solid ${isOwned ? "#f59e0b" : "#e5e7eb"}; border-radius:12px; padding:16px; background:${isOwned ? "#fffbeb" : "#f9fafb"}; text-align:center;'>`;
    html += `<div style='font-size:32px; margin-bottom:8px;'>${badge.emoji}</div>`;
    html += `<div style='font-weight:600; margin-bottom:4px;'>${badge.name}</div>`;
    html += `<div style='color:#6b7280; font-size:14px; margin-bottom:12px;'>${badge.points} điểm</div>`;
    if (isOwned) {
      html += `<div style='color:#d97706; font-weight:600;'>✅ Đã sở hữu</div>`;
    } else if (canAfford) {
      html += `<button class='btn btn-primary' style='width:100%;' onclick='buyReward("badge", "${badge.id}")'>Mua ngay</button>`;
    } else {
      html += `<div style='color:#ef4444; font-weight:600;'>❌ Không đủ điểm</div>`;
    }
    html += `</div>`;
  });
  html += `</div>`;

  // Phần Backgrounds
  html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🖼️ Nền tùy chỉnh</h3>`;
  html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:32px;'>`;
  REWARDS.backgrounds.forEach((bg) => {
    const isOwned = userBackgrounds.includes(bg.id);
    const canAfford = userPoints >= bg.points;
    html += `<div style='border:2px solid ${isOwned ? "#8b5cf6" : "#e5e7eb"}; border-radius:12px; padding:16px; background:${isOwned ? "#f3f4f6" : "#f9fafb"}; text-align:center;'>`;
    html += `<div style='font-size:32px; margin-bottom:8px;'>${bg.emoji}</div>`;
    html += `<div style='font-weight:600; margin-bottom:4px;'>${bg.name}</div>`;
    html += `<div style='color:#6b7280; font-size:14px; margin-bottom:12px;'>${bg.points} điểm</div>`;
    if (isOwned) {
      html += `<div style='color:#7c3aed; font-weight:600;'>✅ Đã sở hữu</div>`;
    } else if (canAfford) {
      html += `<button class='btn btn-primary' style='width:100%;' onclick='buyReward("background", "${bg.id}")'>Mua ngay</button>`;
    } else {
      html += `<div style='color:#ef4444; font-weight:600;'>❌ Không đủ điểm</div>`;
    }
    html += `</div>`;
  });
  html += `</div>`;

  // Nút quay lại và xem bộ sưu tập
  html += `<div class='foot' style='justify-content:space-between;'>`;
  html += `<button class='btn btn-ghost' onclick='renderResult()'>◀ Quay lại kết quả</button>`;
  html += `<button class='btn btn-ghost' onclick='showCollection()'>📚 Bộ sưu tập của tôi</button>`;
  html += `</div>`;
  html += `</div>`;

  root.innerHTML = html;
}

// Hàm mua phần quà
function buyReward(type, rewardId) {
  let reward;
  let points;

  if (type === "sticker") {
    reward = REWARDS.stickers.find((s) => s.id === rewardId);
    if (userRewards.includes(rewardId)) {
      alert("Bạn đã sở hữu sticker này rồi!");
      return;
    }
  } else if (type === "badge") {
    reward = REWARDS.badges.find((b) => b.id === rewardId);
    if (userBadges.includes(rewardId)) {
      alert("Bạn đã sở hữu huy hiệu này rồi!");
      return;
    }
  } else if (type === "background") {
    reward = REWARDS.backgrounds.find((bg) => bg.id === rewardId);
    if (userBackgrounds.includes(rewardId)) {
      alert("Bạn đã sở hữu nền này rồi!");
      return;
    }
  }

  if (!reward) return;

  if (userPoints >= reward.points) {
    // gọi API trừ điểm trên DB
    fetch('/api/redeem', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': (window.Laravel && window.Laravel.csrfToken) || '',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ cost: reward.points })
    }).then(r => r.json()).then(j => {
      if (j.ok) {
        userPoints = j.point;
        alert(`🎉 Chúc mừng! Bạn đã mua thành công ${reward.name}!`);
        updateHeaderPoints();
        showRewardShop();
      } else {
        alert(j.error || 'Không thể quy đổi.');
      }
    }).catch(() => alert('Không thể quy đổi.'));
  } else {
    alert("❌ Không đủ điểm để mua phần quà này!");
  }
}

// Hàm hiển thị bộ sưu tập
function showCollection() {
  const root = $("#view");
  let html = `<div class="card"><h2>📚 Bộ sưu tập của tôi</h2>`;

  // Thống kê tổng quan
  const totalStickers = userRewards.length;
  const totalBadges = userBadges.length;
  const totalBackgrounds = userBackgrounds.length;

  html += `<div style='background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; margin-bottom:24px;'>`;
  html += `<h3 style='margin:0 0 12px 0; color:#374151;'>📊 Thống kê</h3>`;
  html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); gap:12px;'>`;
  html += `<div style='text-align:center; padding:12px; background:#ecfdf5; border-radius:8px;'>`;
  html += `<div style='font-size:24px; font-weight:bold; color:#059669;'>${totalStickers}</div>`;
  html += `<div style='color:#047857; font-size:14px;'>Stickers</div>`;
  html += `</div>`;
  html += `<div style='text-align:center; padding:12px; background:#fffbeb; border-radius:8px;'>`;
  html += `<div style='font-size:24px; font-weight:bold; color:#d97706;'>${totalBadges}</div>`;
  html += `<div style='color:#b45309; font-size:14px;'>Huy hiệu</div>`;
  html += `</div>`;
  html += `<div style='text-align:center; padding:12px; background:#f3f4f6; border-radius:8px;'>`;
  html += `<div style='font-size:24px; font-weight:bold; color:#7c3aed;'>${totalBackgrounds}</div>`;
  html += `<div style='color:#6d28d9; font-size:14px;'>Nền tùy chỉnh</div>`;
  html += `</div>`;
  html += `</div>`;
  html += `</div>`;

  // Hiển thị Stickers đã sở hữu
  if (userRewards.length > 0) {
    html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🎨 Stickers của tôi</h3>`;
    html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); gap:12px; margin-bottom:24px;'>`;
    userRewards.forEach((rewardId) => {
      const sticker = REWARDS.stickers.find((s) => s.id === rewardId);
      if (sticker) {
        html += `<div style='border:2px solid #10b981; border-radius:12px; padding:16px; background:#ecfdf5; text-align:center;'>`;
        html += `<div style='font-size:32px; margin-bottom:8px;'>${sticker.emoji}</div>`;
        html += `<div style='font-weight:600; font-size:14px; color:#047857;'>${sticker.name}</div>`;
        html += `</div>`;
      }
    });
    html += `</div>`;
  }

  // Hiển thị Badges đã sở hữu
  if (userBadges.length > 0) {
    html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🏅 Huy hiệu của tôi</h3>`;
    html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); gap:12px; margin-bottom:24px;'>`;
    userBadges.forEach((badgeId) => {
      const badge = REWARDS.badges.find((b) => b.id === badgeId);
      if (badge) {
        html += `<div style='border:2px solid #f59e0b; border-radius:12px; padding:16px; background:#fffbeb; text-align:center;'>`;
        html += `<div style='font-size:32px; margin-bottom:8px;'>${badge.emoji}</div>`;
        html += `<div style='font-weight:600; font-size:14px; color:#b45309;'>${badge.name}</div>`;
        html += `</div>`;
      }
    });
    html += `</div>`;
  }

  // Hiển thị Backgrounds đã sở hữu
  if (userBackgrounds.length > 0) {
    html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🖼️ Nền tùy chỉnh của tôi</h3>`;
    html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); gap:12px; margin-bottom:24px;'>`;
    userBackgrounds.forEach((bgId) => {
      const bg = REWARDS.backgrounds.find((b) => b.id === bgId);
      if (bg) {
        html += `<div style='border:2px solid #8b5cf6; border-radius:12px; padding:16px; background:#f3f4f6; text-align:center;'>`;
        html += `<div style='font-size:32px; margin-bottom:8px;'>${bg.emoji}</div>`;
        html += `<div style='font-weight:600; font-size:14px; color:#6d28d9;'>${bg.name}</div>`;
        html += `</div>`;
      }
    });
    html += `</div>`;
  }

  // Thông báo nếu chưa có gì
  if (userRewards.length === 0 && userBadges.length === 0 && userBackgrounds.length === 0) {
    html += `<div style='text-align:center; padding:40px; color:#6b7280;'>`;
    html += `<div style='font-size:48px; margin-bottom:16px;'>📦</div>`;
    html += `<div style='font-size:18px; font-weight:600; margin-bottom:8px;'>Bộ sưu tập trống</div>`;
    html += `<div style='font-size:14px;'>Hãy hoàn thành bài quiz và mua phần quà để bắt đầu bộ sưu tập!</div>`;
    html += `</div>`;
  }

  // Nút quay lại
  html += `<div class='foot'><button class='btn btn-ghost' onclick='showRewardShop()'>◀ Quay lại cửa hàng</button></div>`;
  html += `</div>`;

  root.innerHTML = html;
}

// Hiển thị điểm trên header
function updateHeaderPoints() {
  const kidTag = $("#kidTag");
  kidTag.textContent = `• ID: ${kidId} • Tuổi: ${age} • ${userPoints} điểm`;
}

window.addEventListener("hashchange", render);
updateHeaderPoints();
render();


