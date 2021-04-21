<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(5)->create();

        $user = User::find(1);
        $user->name = 'wsy';
        $user->email = '872789604@qq.com';
        $user->password = '$2y$10$r9J68VsR9kN1.AKZV3DC3OHNh0Vt1UKoMf6A2d5ItwUroSxFn/aKu';
        $user->save();

    }
}
