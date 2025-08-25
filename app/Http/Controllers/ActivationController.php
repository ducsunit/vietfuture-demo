<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function showForm(Request $request)
    {
        return view('activate', [
            'book' => $request->query('book'),
            'kid' => $request->query('kid'),
            'lesson' => $request->query('lesson'),
        ]);
    }

    public function activate(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'kid' => 'required|string',
            'name' => 'required|string|max:100',
            'age' => 'required|string',
        ]);

        // Tìm mã kích hoạt và ánh xạ ra sách
        $code = ActivationCode::with('book')->where('code', $data['code'])->first();
        if (!$code || !$code->book) {
            return back()->withErrors(['code' => 'Mã kích hoạt không hợp lệ.']);
        }
        if ($code->activated_at) {
            return back()->withErrors(['code' => 'Mã kích hoạt đã được sử dụng.']);
        }

        // Lưu hoặc cập nhật thông tin học sinh lần đầu
        $student = Student::where('kid_uid', $data['kid'])->first();
        if (!$student) {
            $student = new Student();
            $student->kid_uid = (string) \Illuminate\Support\Str::uuid();
        }
        $student->name = $data['name'];
        $student->age = $data->age ?? $data['age'];
        $student->save();

        // Đánh dấu mã kích hoạt đã gán cho học sinh này và đã sử dụng
        $code->student_id = $student->id;
        $code->activated_at = now();
        $code->save();

        // Đánh dấu session đã kích hoạt theo sách (cho trình duyệt hiện tại)
        session([
            'activated_book_' . $code->book->book_uid => true,
            'student_id' => $student->id,
            'student_name' => $student->name,
            'student_age' => $student->age,
        ]);

        // Redirect đến bài học tương ứng nếu có tham số lesson/kid
        $params = [ 'book' => $code->book->book_uid ];
        if ($request->filled('lesson')) $params['lesson'] = $request->string('lesson');
        return redirect()->route('demo.quiz', $params)->with('status', 'Kích hoạt thành công!');
    }
}


