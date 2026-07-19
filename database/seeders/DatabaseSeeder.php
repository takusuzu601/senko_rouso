<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 管理ログイン用アカウント(このアカウントのみ)
        User::updateOrCreate(
            ['email' => 'takusuzu601@gmail.com'],
            [
                'name' => 'SENKO Union',
                'password' => bcrypt('biggy0227'),
            ]
        );

        $this->call(AnnouncementSeeder::class);
    }
}
