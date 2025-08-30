const $ = (sel, el = document) => el.querySelector(sel);
const params = new URLSearchParams(location.search);
const kidId = params.get("kid") || "";
const age = "";
const bookUid = params.get("book") || "";
const lessonId = params.get("lesson") || "";
let studentName = "";
let userId = null;
let LESSON = null; // dữ liệu bài học lấy từ API
// Answers must be initialized before any event bindings
var answers = {};

// Không dùng localStorage nữa; giữ stub không truy cập trình duyệt
const LS = {
    get(k, def) {
        return def;
    },
    set(k, v) {
        /* no-op */
    },
};

async function ensureName() {
    // Lấy thông tin user từ session
    try {
        const r = await fetch("/api/points", {
            headers: { Accept: "application/json" },
        });
        const j = await r.json();
        if (j.userId) {
            userId = j.userId;
            userPoints = j.point || 0;

            // Lấy display_name từ database
            try {
                const nameResponse = await fetch("/api/get-display-name", {
                    headers: { Accept: "application/json" },
                });
                if (nameResponse.ok) {
                    const nameData = await nameResponse.json();
                    if (nameData.ok && nameData.display_name) {
                        studentName = nameData.display_name;
                    } else {
                        // Nếu chưa có display_name, yêu cầu người dùng nhập
                        studentName = j.username || `User_${userId}`;
                        await promptForDisplayName();
                    }
                } else {
                    studentName = j.username || `User_${userId}`;
                }
            } catch (nameError) {
                console.log("Could not get display name:", nameError);
                studentName = j.username || `User_${userId}`;
            }
        }
    } catch (e) {
        console.log("Could not get user info:", e);
    }
}

async function promptForDisplayName() {
    if (window.Swal && typeof window.Swal.fire === "function") {
        try {
            const result = await Swal.fire({
                title: "Thiết lập tên hiển thị",
                html: `
          <p>Hãy nhập tên hiển thị để sử dụng trong quiz và cộng đồng:</p>
          <input type="text" id="displayNameInput" class="swal2-input" placeholder="VD: Minh Anh" maxlength="100">
        `,
                focusConfirm: false,
                confirmButtonText: "Lưu tên",
                allowOutsideClick: false,
                allowEscapeKey: false,
                preConfirm: () => {
                    const name =
                        document.getElementById("displayNameInput").value;
                    if (!name || name.trim().length < 2) {
                        Swal.showValidationMessage(
                            "Tên phải có ít nhất 2 ký tự"
                        );
                        return false;
                    }
                    return name.trim();
                },
            });

            if (result.isConfirmed && result.value) {
                await setDisplayName(result.value);
                studentName = result.value;
            }
        } catch (error) {
            console.log("Error prompting for display name:", error);
        }
    }
}

async function setDisplayName(name) {
    try {
        const response = await fetch("/api/set-display-name", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
            },
            body: JSON.stringify({ name: name }),
        });

        if (response.ok) {
            const data = await response.json();
            console.log("Display name set successfully:", data.display_name);
        } else {
            console.error("Failed to set display name");
        }
    } catch (error) {
        console.error("Error setting display name:", error);
    }
}

(async function init() {
    await ensureName();
    await loadLesson();
    // lấy điểm từ DB
    try {
        const r = await fetch("/api/points", {
            headers: { Accept: "application/json" },
        });
        const j = await r.json();
        if (typeof j.point === "number") userPoints = j.point;
    } catch (e) {}
    updateHeaderPoints();
    render();
})();

async function loadLesson() {
    try {
        if (!bookUid || !lessonId) {
            console.log("Missing bookUid or lessonId:", { bookUid, lessonId });
            LESSON = null;
            return;
        }
        const q = new URLSearchParams({
            book: bookUid,
            lesson: lessonId,
        }).toString();
        console.log("Loading lesson with params:", q);
        const res = await fetch(`/api/lesson?${q}`, {
            headers: { Accept: "application/json" },
        });

        if (!res.ok) {
            console.error("API request failed:", res.status, res.statusText);
            const errorData = await res.json().catch(() => ({}));
            console.error("Error details:", errorData);
            LESSON = null;
            return;
        }

        const data = await res.json();
        console.log("API response:", data);
        LESSON = data && data.lesson ? data.lesson : null;

        if (!LESSON) {
            console.warn("No lesson found in response:", data);
        } else {
            console.log("Lesson loaded successfully:", LESSON.title);
        }
    } catch (e) {
        console.error("Error loading lesson:", e);
        LESSON = null;
    }
}

function getQuiz() {
    if (!LESSON) return { title: "Bài học", timeLimitSec: 90, questions: [] };
    
    // Tạo quiz về kỹ năng cứu đuối và an toàn nước
    const waterSafetyQuiz = {
        title: "Bí kíp Số 12: Biết cách cứu người bị ngã xuống nước",
        timeLimitSec: 90,
        questions: [
            {
                id: "q1",
                type: "single",
                text: "Khi gặp người bị đuối nước, điều quan trọng nhất cần làm đầu tiên là gì?",
                options: [
                    { id: "a1", text: "Nhảy ngay xuống nước để cứu", correct: false },
                    { id: "a2", text: "Bình tĩnh đánh giá tình huống và lập kế hoạch cứu", correct: true },
                    { id: "a3", text: "Hét to để thu hút sự chú ý", correct: false },
                    { id: "a4", text: "Chạy đi tìm người lớn", correct: false }
                ],
                explain: "Việc giữ bình tĩnh và đánh giá tình huống là bước đầu tiên quan trọng nhất. Không nên vội vàng nhảy xuống nước mà không có kế hoạch."
            },
            {
                id: "q2",
                type: "single",
                text: "Trong trường hợp nào bạn TUYỆT ĐỐI KHÔNG nên nhảy xuống nước để cứu người?",
                options: [
                    { id: "b1", text: "Khi bạn không biết bơi hoặc bơi không giỏi", correct: true },
                    { id: "b2", text: "Khi nạn nhân là người thân", correct: false },
                    { id: "b3", text: "Khi nước không quá sâu", correct: false },
                    { id: "b4", text: "Khi có nhiều người xung quanh", correct: false }
                ],
                explain: "Tuyệt đối không nhảy xuống cứu nếu bản thân không bơi giỏi, không biết cách cứu đuối, và tự thấy trọng lượng của nạn nhân quá lớn so với sức lực và khả năng của mình."
            },
            {
                id: "q3",
                type: "single",
                text: "Khi cứu người trong nước sâu, bạn nên tiếp cận nạn nhân từ hướng nào?",
                options: [
                    { id: "c1", text: "Từ phía trước mặt nạn nhân", correct: false },
                    { id: "c2", text: "Từ phía sau lưng nạn nhân", correct: true },
                    { id: "c3", text: "Từ bên cạnh nạn nhân", correct: false },
                    { id: "c4", text: "Từ bất kỳ hướng nào", correct: false }
                ],
                explain: "Phải tiếp cận nạn nhân từ phía sau vì người đuối nước thường hoảng loạn và sẽ bám chặt lấy người cứu, có thể khiến cả hai cùng chìm."
            },
            {
                id: "q4",
                type: "single",
                text: "Trước khi nhảy xuống nước cứu người, bạn nên chuẩn bị gì?",
                options: [
                    { id: "d1", text: "Cởi bỏ quần áo để bơi nhanh hơn", correct: false },
                    { id: "d2", text: "Mặc áo phao hoặc mang theo phao cứu sinh", correct: true },
                    { id: "d3", text: "Uống nhiều nước để có sức", correct: false },
                    { id: "d4", text: "Không cần chuẩn bị gì", correct: false }
                ],
                explain: "Nên mặc áo phao hoặc mang theo phao cứu sinh vì phản ứng đầu tiên của người đuối nước là bám chặt, nên cần có vật nổi để đảm bảo an toàn cho cả hai."
            },
            {
                id: "q5",
                type: "order",
                text: "Sắp xếp các bước cứu người đuối nước theo thứ tự đúng:",
                items: [
                    "Bình tĩnh đánh giá tình huống",
                    "Tìm vật dụng cứu hộ (phao, dây, gậy)",
                    "Tiếp cận nạn nhân từ phía sau",
                    "Đưa nạn nhân lên bờ an toàn",
                    "Thực hiện sơ cứu nếu cần"
                ],
                answer: [
                    "Bình tĩnh đánh giá tình huống",
                    "Tìm vật dụng cứu hộ (phao, dây, gậy)",
                    "Tiếp cận nạn nhân từ phía sau",
                    "Đưa nạn nhân lên bờ an toàn",
                    "Thực hiện sơ cứu nếu cần"
                ]
            },
            {
                id: "q6",
                type: "single",
                text: "Khi cứu người trong nước sâu, phương pháp nào sau đây được khuyến nghị?",
                options: [
                    { id: "e1", text: "Nắm tóc và kéo nạn nhân trên mặt nước", correct: true },
                    { id: "e2", text: "Ôm chặt nạn nhân để giữ ấm", correct: false },
                    { id: "e3", text: "Để nạn nhân bám vào vai", correct: false },
                    { id: "e4", text: "Kéo nạn nhân bằng chân", correct: false }
                ],
                explain: "Nắm tóc và kéo nạn nhân trên mặt nước là phương pháp an toàn, giữ cho mũi và miệng nạn nhân nhô lên khỏi mặt nước, đồng thời tránh bị nạn nhân bám chặt."
            },
            {
                id: "q7",
                type: "single",
                text: "Trong trường hợp nước nông gần bờ, cách cứu nào an toàn nhất?",
                options: [
                    { id: "f1", text: "Nằm xuống bờ và đưa tay kéo nạn nhân", correct: true },
                    { id: "f2", text: "Đứng trên bờ và cúi xuống kéo", correct: false },
                    { id: "f3", text: "Nhảy xuống nước để đẩy nạn nhân lên", correct: false },
                    { id: "f4", text: "Ném đá để nạn nhân bám vào", correct: false }
                ],
                explain: "Nằm xuống bờ và đưa tay kéo nạn nhân là cách an toàn nhất vì tránh được việc bị kéo xuống nước và có thể giữ thăng bằng tốt."
            },
            {
                id: "q8",
                type: "single",
                text: "Khi không có phao cứu sinh, bạn có thể sử dụng vật dụng nào để cứu người?",
                options: [
                    { id: "g1", text: "Áo, khăn tắm, gậy dài", correct: true },
                    { id: "g2", text: "Điện thoại di động", correct: false },
                    { id: "g3", text: "Chìa khóa xe", correct: false },
                    { id: "g4", text: "Tiền bạc", correct: false }
                ],
                explain: "Áo, khăn tắm, gậy dài đều có thể dùng làm vật dụng cứu hộ tạm thời để nạn nhân bám vào và được kéo lên bờ an toàn."
            }
        ]
    };
    
    return waterSafetyQuiz;
}

// Rewards data will be loaded from database
let REWARDS = {
    sticker: [],
    badge: [],
};

let current = 0;
let selected = null;
let orderWorking = [];
let timeLeft = 0; // seconds remaining for the current question
let timerId = null;
var answers = {};

// Hệ thống điểm và phần quà - loaded from database
let userPoints = 0;
let userOwnedRewards = {
    sticker: [],
    badge: [],
};

function render() {
    const QUIZ = getQuiz();
    const root = $("#view");
    if (!LESSON) {
        root.innerHTML = `
    <div class="card">
      <h2>🔍 Không tìm thấy nội dung bài học</h2>
      <p style="text-align: center; font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #667eea; margin: 1rem 0;">
        🤔 Vui lòng kiểm tra tham số book/lesson hoặc liên hệ quản trị.
      </p>
      <div style="text-align: center; margin-top: 2rem;">
        <a href="${window.location.origin}" class="btn btn-primary">
          🏠 Về trang chủ
        </a>
      </div>
    </div>`;
        return;
    }
    // derive timer on first render
    // Reset per-question timer
    stopTimer();
    timeLeft = getPerQuestionTimeSec();
    const q = QUIZ.questions[current];
    if (!q) return renderResult();
    const qType = String(
        q.type || (q.options ? "single" : q.items ? "order" : "")
    ).toLowerCase();
    const prog = `Câu ${current + 1}/${QUIZ.questions.length}`;
    const progressPercent = ((current + 1) / QUIZ.questions.length) * 100;
    root.innerHTML = `
      <div class="card">
        <div class="quiz-progress">
          <div class="progress-bar">
            <div class="progress-fill" style="width: ${progressPercent}%"></div>
          </div>
          <div class="progress-text">📚 ${prog} • ⏰ <span id="timeLeftText">${timeLeft}s</span></div>
        </div>
        <h2>🎯 ${q.text}</h2>
        ${q.image ? `<div class="quiz-image-wrap"><img class="quiz-image" src="${q.image}" alt="question" loading="lazy"></div>` : ""}
        <div id="zone"></div>
        <div class="foot" style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
          <button class="btn btn-ghost" onclick="prevQ()" ${
              current === 0 ? "disabled" : ""
          }>
            <span>◀</span>
            <span>Câu trước</span>
          </button>
          <button class="btn btn-primary" id="nextBtn">
            ${
                current === QUIZ.questions.length - 1
                    ? "<span>🏁</span> <span>Hoàn thành</span>"
                    : "<span>Tiếp tục</span> <span>▶</span>"
            }
          </button>
        </div>
      </div>`;
    $("#nextBtn").addEventListener("click", nextQ);
    if (qType === "single") renderSingle(q);
    if (qType === "order") renderOrder(q);

    // Start countdown for this question
    startQuestionTimer();
}

function renderSingle(q) {
    const zone = $("#zone");
    zone.className = "choices";
    const letters = ["A", "B", "C", "D", "E", "F"];
    zone.innerHTML = q.options
        .map(
            (o, index) => `
      <div class="choice" data-id="${o.id}">
        <div class="choice-letter">${letters[index] || index + 1}</div>
        <div class="choice-text">${o.text}</div>
      </div>
    `
        )
        .join("");
    // Event delegation for reliability
    zone.addEventListener("click", (e) => {
        const el = e.target.closest(".choice");
        if (!el || !zone.contains(el)) return;
        selected = el.dataset.id;
        answers[q.id] = selected;
        zone.querySelectorAll(".choice").forEach((c) => {
            c.setAttribute("aria-selected", "false");
            c.classList.remove("selected");
        });
        el.setAttribute("aria-selected", "true");
        el.classList.add("selected");

        // Add fun click effect
        el.style.transform = "scale(0.95)";
        setTimeout(() => {
            el.style.transform = "";
        }, 150);
    });
}

// Hàm trang bị huy hiệu
async function equipBadge(rewardId) {
    try {
        const response = await fetch("/api/rewards/equip", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
                Accept: "application/json",
            },
            body: JSON.stringify({
                reward_id: rewardId,
                action: "equip",
            }),
        });

        const result = await response.json();
        if (result.success) {
            await Swal.fire({
                title: "✅ Đã trang bị huy hiệu!",
                text: result.message,
                icon: "success",
                confirmButtonText: "OK",
                timer: 2000,
            });
            // Refresh collection if we're in collection view
            if (typeof showCollection === 'function') {
                showCollection();
            }
        } else {
            await Swal.fire({
                title: "❌ Lỗi",
                text: result.error || "Không thể trang bị huy hiệu",
                icon: "error",
                confirmButtonText: "OK",
            });
        }
    } catch (error) {
        console.error("Error equipping badge:", error);
        await Swal.fire({
            title: "❌ Lỗi",
            text: "Không thể kết nối đến server",
            icon: "error",
            confirmButtonText: "OK",
        });
    }
}

// Hàm gỡ huy hiệu
async function unequipBadge(rewardId) {
    try {
        const response = await fetch("/api/rewards/equip", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
                Accept: "application/json",
            },
            body: JSON.stringify({
                reward_id: rewardId,
                action: "unequip",
            }),
        });

        const result = await response.json();
        if (result.success) {
            await Swal.fire({
                title: "✅ Đã gỡ huy hiệu!",
                text: result.message,
                icon: "success",
                confirmButtonText: "OK",
                timer: 2000,
            });
            // Refresh collection if we're in collection view
            if (typeof showCollection === 'function') {
                showCollection();
            }
        } else {
            await Swal.fire({
                title: "❌ Lỗi",
                text: result.error || "Không thể gỡ huy hiệu",
                icon: "error",
                confirmButtonText: "OK",
            });
        }
    } catch (error) {
        console.error("Error unequipping badge:", error);
        await Swal.fire({
            title: "❌ Lỗi",
            text: "Không thể kết nối đến server",
            icon: "error",
            confirmButtonText: "OK",
        });
    }
}











function renderOrder(q) {
    const zone = $("#zone");
    orderWorking = [...q.items];
    drawOrderList(zone, orderWorking, q);
}
function drawOrderList(zone, arr, q) {
    zone.innerHTML = `<div class="grid">${arr
        .map(
            (t, i) =>
                `<div class="choice" draggable="true" data-i="${i}">⇅ ${t}</div>`
        )
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
    stopTimer();
    current = Math.max(0, current - 1);
    render();
}
function nextQ() {
    stopTimer();
    const QUIZ = getQuiz();
    const total =
        QUIZ && Array.isArray(QUIZ.questions) ? QUIZ.questions.length : 0;
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
    stopTimer();

    // Tính điểm dựa trên kết quả
    let earnedPoints = 0;
    let correctAnswers = 0;

    (QUIZ.questions || []).forEach((q) => {
        if (q.type === "single") {
            if (
                answers[q.id] &&
                q.options.find((o) => o.id === answers[q.id] && o.correct)
            ) {
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
        html += `<div style='margin-bottom:32px; padding:20px; border:1px solid #e2e8f0; border-radius:12px; background:#fafbfc;'>`;
        html += `<p style='margin:0 0 16px 0; font-size:16px;'><b>${q.text}</b></p>`;
        if (q.image) {
            html += `<div class='quiz-image-wrap'><img class='quiz-image' src='${q.image}' alt='question' loading='lazy'></div>`;
        }
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
                const userOption = q.options.find(
                    (o) => o.id === answers[q.id]
                );
                html += `<div style='background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:12px; margin-top:8px;'>`;
                html += `<p style='margin:0 0 8px 0; color:#dc2626; font-weight:600;'>❌ Đáp án của bạn: ${userOption.text}</p>`;
                html += `<p style='margin:0 0 8px 0; color:#059669; font-weight:600;'>✅ Đáp án đúng: ${correctOption.text}</p>`;
                html += `<p style='margin:0; color:#374151; font-size:14px;'>💡 <strong>Giải thích:</strong> ${
                    q.explain ||
                    "Hãy đọc kỹ câu hỏi và chọn đáp án phù hợp nhất."
                }</p>`;
                if (q.explain_image) {
                    html += `<div class='quiz-explain-image-wrap' style='margin-top:8px;'><img class='quiz-explain-image' src='${q.explain_image}' alt='explain' loading='lazy'></div>`;
                }
                html += `</div>`;
            } else if (
                answers[q.id] !== undefined &&
                q.options.find((o) => o.id === answers[q.id] && o.correct)
            ) {
                html += `<div style='background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:12px; margin-top:8px;'>`;
                html += `<p style='margin:0; color:#059669; font-weight:600;'>✅ Chính xác! ${
                    q.explain || "Bạn đã trả lời đúng."
                }</p>`;
                if (q.explain_image) {
                    html += `<div class='quiz-explain-image-wrap' style='margin-top:8px;'><img class='quiz-explain-image' src='${q.explain_image}' alt='explain' loading='lazy'></div>`;
                }
                html += `</div>`;
            }
        }

        if (q.type === "order") {
            const userAnswer = answers[q.id] || [];
            const isCorrect =
                JSON.stringify(userAnswer) === JSON.stringify(q.answer);

            html += `<div style='margin-top:12px;'>`;
            html += `<p style='margin:0 0 8px 0; font-weight:600;'>Thứ tự bạn chọn:</p>`;
            html += `<div style='background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:12px;'>`;
            html += `<p style='margin:0; color:#475569;'>${userAnswer.join(
                " → "
            )}</p>`;
            html += `</div>`;

            html += `<p style='margin:0 0 8px 0; font-weight:600;'>Thứ tự đúng:</p>`;
            html += `<div style='background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:12px; margin-bottom:12px;'>`;
            html += `<p style='margin:0; color:#059669;'>${q.answer.join(
                " → "
            )}</p>`;
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

    html += `<button class='btn btn-primary' onclick='showRewardShop()'>🛍️ Cửa hàng phần quà</button>`;
    html += `</div>`;
    html += `</div></div>`;

    root.innerHTML = html;
    playSuccessSound();
    confettiBurst();

    // Gửi tiến trình lên server
    try {
        await fetch("/demo/progress", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    (window.Laravel && window.Laravel.csrfToken) || "",
                Accept: "application/json",
            },
            body: JSON.stringify({
                kidId,
                lesson: lessonId,
                score: earnedPoints, // chỉ gửi điểm vừa kiếm được
                age,
                name: studentName,
                userId: userId, // gửi userId để controller có thể tìm user chính xác
            }),
        });
        // đồng bộ điểm từ DB về UI
        const r = await fetch("/api/points", {
            headers: { Accept: "application/json" },
        });
        const j = await r.json();
        if (typeof j.point === "number") userPoints = j.point;
        updateHeaderPoints();
    } catch (e) {}

    // Ask to redo the quiz using SweetAlert2
    try {
        if (window.Swal && typeof window.Swal.fire === "function") {
            setTimeout(async () => {
                const result = await Swal.fire({
                    title: "Bạn có muốn làm lại bài không?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Làm lại",
                    cancelButtonText: "Đóng",
                });
                if (result.isConfirmed) {
                    // Simplest reset is reload
                    location.reload();
                }
            }, 200);
        }
    } catch (e) {}
}

// --- Sound -----------------------------------------------------
let audioCtx;
function playSuccessSound() {
    try {
        audioCtx =
            audioCtx ||
            new (window.AudioContext || window.webkitAudioContext)();
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

// Hàm hiển thị cửa hàng phần quà - sử dụng database
async function showRewardShop() {
    const root = $("#view");

    try {
        // Load rewards from database
        const response = await fetch("/api/rewards");
        const data = await response.json();

        REWARDS = data.rewards;
        userPoints = data.user_points;

        let html = `<div class="card"><h2>🛍️ Cửa hàng phần quà</h2>`;

        // Hiển thị điểm hiện tại
        html += `<div style='background:#f0f9ff; border:1px solid #0ea5e9; border-radius:8px; padding:16px; margin-bottom:24px; text-align:center;'>`;
        html += `<div style='font-size:24px; font-weight:bold; color:#0ea5e9;'>${userPoints} điểm</div>`;
        html += `<div style='color:#0369a1;'>Điểm khả dụng</div>`;
        html += `</div>`;

        // Phần Stickers
        if (REWARDS.sticker && REWARDS.sticker.length > 0) {
            html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🎨 Stickers</h3>`;
            html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:32px;'>`;
            REWARDS.sticker.forEach((sticker) => {
                const isOwned = sticker.is_owned;
                const canAfford = sticker.can_afford;
                html += `<div style='border:2px solid ${
                    isOwned ? "#10b981" : "#e5e7eb"
                }; border-radius:12px; padding:16px; background:${
                    isOwned ? "#ecfdf5" : "#f9fafb"
                }; text-align:center;'>`;
                html += `<div style='font-size:32px; margin-bottom:8px;'>${sticker.emoji}</div>`;
                html += `<div style='font-weight:600; margin-bottom:4px;'>${sticker.name}</div>`;
                html += `<div style='color:#6b7280; font-size:14px; margin-bottom:12px;'>${sticker.points} điểm</div>`;
                if (isOwned) {
                    html += `<div style='color:#059669; font-weight:600;'>✅ Đã sở hữu</div>`;
                } else if (canAfford) {
                    html += `<button class='btn btn-primary' style='width:100%;' onclick='buyReward("${sticker.id}")'>Mua ngay</button>`;
                } else {
                    html += `<div style='color:#ef4444; font-weight:600;'>❌ Không đủ điểm</div>`;
                }
                html += `</div>`;
            });
            html += `</div>`;
        }

        // Phần Badges
        html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🏅 Huy hiệu</h3>`;
        html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:32px;'>`;
        if (REWARDS.badge) {
            REWARDS.badge.forEach((badge) => {
                const isOwned = badge.is_owned;
                const canAfford = badge.can_afford;
                html += `<div style='border:2px solid ${
                    isOwned ? "#f59e0b" : "#e5e7eb"
                }; border-radius:12px; padding:16px; background:${
                    isOwned ? "#fffbeb" : "#f9fafb"
                }; text-align:center;'>`;
                html += `<div style='font-size:32px; margin-bottom:8px;'>${badge.emoji}</div>`;
                html += `<div style='font-weight:600; margin-bottom:4px;'>${badge.name}</div>`;
                html += `<div style='color:#6b7280; font-size:14px; margin-bottom:12px;'>${badge.points} điểm</div>`;
                if (isOwned) {
                    html += `<div style='color:#d97706; font-weight:600; margin-bottom:8px;'>✅ Đã sở hữu</div>`;
                    html += `<button class='btn btn-secondary' style='width:100%;' onclick='equipBadge("${badge.id}")'>Trang bị</button>`;
                } else if (canAfford) {
                    html += `<button class='btn btn-primary' style='width:100%;' onclick='buyReward("${badge.id}")'>Mua ngay</button>`;
                } else {
                    html += `<div style='color:#ef4444; font-weight:600;'>❌ Không đủ điểm</div>`;
                }
                html += `</div>`;
            });
        }
        html += `</div>`;



        // Thêm nút quay lại
        html += `<div style='margin-top: 32px; text-align: center;'>`;
        html += `<button class='btn btn-secondary' onclick='goBack()' style='padding: 12px 24px; font-size: 16px;'>`;
        html += `⬅️ Quay lại`;
        html += `</button>`;
        html += `</div>`;

        html += `</div>`;
        root.innerHTML = html;
    } catch (error) {
        console.error("Error loading rewards:", error);
        root.innerHTML = `<div class="card"><h2>❌ Không thể tải cửa hàng</h2><p>Vui lòng thử lại sau.</p></div>`;
    }
}

// Hàm mua phần quà - sử dụng database
async function buyReward(rewardId) {
    try {
        const response = await fetch("/api/rewards/purchase", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
                Accept: "application/json",
            },
            body: JSON.stringify({ reward_id: rewardId }),
        });

        const result = await response.json();

        if (result.success) {
            // Show success message
            await Swal.fire({
                title: "🎉 Thành công!",
                text: result.message,
                icon: "success",
                confirmButtonText: "Tuyệt vời!",
                timer: 3000,
            });

            // Update user points
            userPoints = result.user_points;
            updateHeaderPoints();

            // Refresh shop
            showRewardShop();
        } else {
            await Swal.fire({
                title: "❌ Lỗi",
                text: result.error || "Không thể mua phần quà",
                icon: "error",
                confirmButtonText: "OK",
            });
        }
    } catch (error) {
        console.error("Error purchasing reward:", error);
        await Swal.fire({
            title: "❌ Lỗi",
            text: "Không thể kết nối đến server",
            icon: "error",
            confirmButtonText: "OK",
        });
    }
}

// Hàm hiển thị bộ sưu tập
// Hàm hiển thị bộ sưu tập - sử dụng database
async function showCollection() {
    const root = $("#view");

    try {
        // Load user's owned rewards
        const response = await fetch("/api/rewards/user");
        const data = await response.json();

        userOwnedRewards = data.rewards || {
            sticker: [],
            badge: [],
        };
        userPoints = data.user_points;

        let html = `<div class="card"><h2>📚 Bộ sưu tập của bạn</h2>`;

        // Thống kê tổng quan
        const totalStickers = userOwnedRewards.sticker?.length || 0;
        const totalBadges = userOwnedRewards.badge?.length || 0;

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
        html += `</div>`;
        html += `</div>`;

        // Phần Stickers đã sở hữu
        if (userOwnedRewards.sticker && userOwnedRewards.sticker.length > 0) {
            html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🎨 Stickers của bạn</h3>`;
            html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:16px; margin-bottom:32px;'>`;
            userOwnedRewards.sticker.forEach((sticker) => {
                html += `<div style='border:2px solid #10b981; border-radius:12px; padding:16px; background:#ecfdf5; text-align:center; display:flex; flex-direction:column; min-height:260px;'>`;
                html += `<div style='font-size:36px; min-height:48px; display:flex; align-items:center; justify-content:center; margin-bottom:8px;'>${sticker.emoji}</div>`;
                html += `<div style='font-weight:600; color:#059669;'>${sticker.name}</div>`;
                html += `<div style='font-size:12px; color:#6b7280; margin-top:auto;'>Mua ngày ${new Date(
                    sticker.purchased_at
                ).toLocaleDateString("vi-VN")}</div>`;
                html += `</div>`;
            });
            html += `</div>`;
        }

        // Phần Badges đã sở hữu
        if (userOwnedRewards.badge && userOwnedRewards.badge.length > 0) {
            html += `<h3 style='margin:24px 0 16px 0; color:#374151;'>🏅 Huy hiệu của bạn</h3>`;
            html += `<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:32px; align-items:stretch;'>`;
            userOwnedRewards.badge.forEach((badge) => {
                html += `<div style='border:2px solid #f59e0b; border-radius:12px; padding:16px; background:#fffbeb; text-align:center; display:flex; flex-direction:column; min-height:260px;'>`;
                html += `<div style='font-size:36px; min-height:48px; display:flex; align-items:center; justify-content:center; margin-bottom:8px;'>${badge.emoji}</div>`;
                html += `<div style='font-weight:600; color:#d97706;'>${badge.name}</div>`;
                if (badge.is_equipped) {
                    html += `<div style='color:#059669; font-weight:600; margin:8px 0;'>✅ Đang sử dụng</div>`;
                    html += `<button class='btn btn-secondary' style='width:100%; margin-top:auto;' onclick='unequipBadge("${badge.id}")'>Gỡ huy hiệu</button>`;
                } else {
                    html += `<button class='btn btn-primary' style='width:100%; margin-top:auto;' onclick='equipBadge("${badge.id}")'>Trang bị</button>`;
                }
                html += `<div style='font-size:12px; color:#6b7280; margin-top:auto;'>Mua ngày ${new Date(
                    badge.purchased_at
                ).toLocaleDateString("vi-VN")}</div>`;
                html += `</div>`;
            });
            html += `</div>`;
        }



        // Thông báo nếu chưa có gì
        if (
            totalStickers === 0 &&
            totalBadges === 0
        ) {
            html += `<div style='text-align:center; padding:40px; color:#6b7280;'>`;
            html += `<div style='font-size:48px; margin-bottom:16px;'>📦</div>`;
            html += `<div style='font-size:18px; font-weight:600; margin-bottom:8px;'>Bộ sưu tập trống</div>`;
            html += `<div style='font-size:14px;'>Hãy hoàn thành bài quiz và mua phần quà để bắt đầu bộ sưu tập!</div>`;
            html += `</div>`;
        }

        // Thêm nút quay lại
        html += `<div style='margin-top: 32px; text-align: center;'>`;
        html += `<button class='btn btn-secondary' onclick='goBack()' style='padding: 12px 24px; font-size: 16px;'>`;
        html += `⬅️ Quay lại`;
        html += `</button>`;
        html += `</div>`;
        html += `</div>`;

        root.innerHTML = html;
    } catch (error) {
        console.error("Error loading collection:", error);
        root.innerHTML = `<div class="card"><h2>❌ Không thể tải bộ sưu tập</h2><p>Vui lòng thử lại sau.</p></div>`;
    }
}

// Hiển thị điểm trên header
function updateHeaderPoints() {
    const kidTag = $("#kidTag");
    if (userId && studentName) {
        kidTag.textContent = `• ${studentName} • ${userPoints} điểm`;
    } else if (userId) {
        kidTag.textContent = `• User ${userId} • ${userPoints} điểm`;
    } else {
        kidTag.textContent = `• ${userPoints} điểm`;
    }
}

// Function để quay lại trang chính
function goBack() {
    // Kiểm tra xem đang ở trang nào và quay lại trang tương ứng
    if (window.showDashboard) {
        window.showDashboard();
    } else if (window.showCommunity) {
        window.showCommunity();
    } else {
        // Fallback: quay lại trang quiz
        window.location.href = '/quiz';
    }
}



window.addEventListener("hashchange", render);
updateHeaderPoints();
render();

// ----------------- Timer helpers (per-question) -----------------
function getPerQuestionTimeSec() {
    // default 20s per question; could be adjusted or derived later
    return 20;
}

function startQuestionTimer() {
    const QUIZ = getQuiz();
    const total = QUIZ.questions?.length || 0;
    const isLast = current === total - 1;
    const timeEl = document.getElementById("timeLeftText");
    stopTimer();
    timerId = setInterval(() => {
        timeLeft = Math.max(0, (timeLeft || 0) - 1);
        if (timeEl) timeEl.textContent = `${timeLeft}s`;
        if (timeLeft <= 0) {
            stopTimer();
            if (isLast) {
                renderResult();
            } else {
                nextQ();
            }
        }
    }, 1000);
}

function stopTimer() {
    if (timerId) {
        clearInterval(timerId);
        timerId = null;
    }
}
