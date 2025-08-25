<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\ActivationCode;

class ActivationSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 2 sách mẫu
        $bookA = Book::firstOrCreate(['book_uid' => 'BOOK-A'], ['title' => 'BOOK-A']);
        $bookB = Book::firstOrCreate(['book_uid' => 'BOOK-B'], ['title' => 'BOOK-B']);

        // Các mã kích hoạt mẫu cho mỗi sách
        $codesA = [
            'A1-7X2C-9QHP', 'A1-4MNB-2RTY', 'A1-PL90-KQ3Z', 'A1-ZX12-CV89', 'A1-QQ11-WWER'
        ];
        $codesB = [
            'B1-7X2C-9QHP', 'B1-4MNB-2RTY', 'B1-PL90-KQ3Z', 'B1-ZX12-CV89', 'B1-QQ11-WWER'
        ];

        foreach ($codesA as $code) {
            ActivationCode::firstOrCreate([
                'code' => $code,
                'book_id' => $bookA->id,
            ]);
        }

        foreach ($codesB as $code) {
            ActivationCode::firstOrCreate([
                'code' => $code,
                'book_id' => $bookB->id,
            ]);
        }
    }
}


