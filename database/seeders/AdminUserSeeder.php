<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'wongnarin.s@msu.ac.th'],
            [
                'name'      => 'วงศ์นรินทร์ สุขวิชัย',
                'role'      => 'admin',
                'google_id' => '',
                'avatar'    => null,
            ]
        );
    }
}
