<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookContentSeeder extends Seeder
{
    public function run(): void
    {
        // Seed nội dung cho BOOK-A
        $book = Book::firstOrCreate(['book_uid' => 'BOOK-A'], ['title' => 'Sách Kỹ năng sống A']);

        $content = [
            'lessons' => [
                [
                    'id' => 'an-toan-nuoc',
                    'title' => 'An toàn nước',
                    'questions' => [
                        [
                            'id' => 'q1', 'type' => 'single',
                            'text' => 'Thấy bạn trượt chân xuống hồ, em làm gì đầu tiên?',
                            'options' => [
                                ['id' => 'a', 'text' => 'Nhảy xuống kéo bạn lên'],
                                ['id' => 'b', 'text' => 'Gọi người lớn và ném vật nổi cho bạn', 'correct' => true],
                                ['id' => 'c', 'text' => 'Đứng nhìn và la hét']
                            ],
                            'explain' => 'Giữ bình tĩnh, gọi người lớn, ném vật nổi.'
                        ],
                        [
                            'id' => 'q2', 'type' => 'order',
                            'text' => 'Sắp xếp đúng thứ tự khi thấy nguy hiểm:',
                            'items' => ['Rời xa mép nước', 'Gọi người lớn', 'Giữ bình tĩnh'],
                            'answer' => ['Giữ bình tĩnh', 'Rời xa mép nước', 'Gọi người lớn']
                        ],
                        [
                            'id' => 'q3', 'type' => 'single',
                            'text' => 'Khi đi thuyền, em nên làm gì?',
                            'options' => [
                                ['id' => 'a', 'text' => 'Mặc áo phao', 'correct' => true],
                                ['id' => 'b', 'text' => 'Ngồi sát mép thuyền'],
                                ['id' => 'c', 'text' => 'Thò tay xuống nước cho mát']
                            ],
                            'explain' => 'Luôn mặc áo phao.'
                        ]
                    ]
                ]
            ]
        ];

        $book->content = json_encode($content, JSON_UNESCAPED_UNICODE);
        $book->save();
    }
}


