<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 20 comments test với for loop
        for ($i = 1; $i <= 20; $i++) {
            Comment::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'User #' . $i,
                'email' => 'user' . $i . '@example.com',
                'content' => 'Đây là bình luận số ' . $i . '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'id_post' => rand(1, 10), // Random post ID từ 1-10
                'type_post' => rand(1, 3), // Random type 1, 2, hoặc 3
                'status' => rand(0, 1), // Random status: 0 (pending) hoặc 1 (active)
            ]);
        }

        $this->command->info('✅ Đã tạo 20 comments test với UUID và dữ liệu random!');
    }
}
