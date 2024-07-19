<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
               'name'=>'管理者',
               'email'=>'admin@example.com',
               'phone'=>'12345678',
               'type'=>1,
               'password'=> bcrypt('password'),
            ],
            [
               'name'=>'ユーザー',
               'email'=>'user@example.com',
               'phone'=>'11111111',
               'type'=>0,
               'dp'=>100000,
               'point'=>100000,
               'password'=> bcrypt('password'),
            ],
            [
                'name'=>'ユーザー',
                'email'=>'staff@example.com',
                'phone'=>'11111112',
                'type'=>0,
                'dp'=>100000,
                'point'=>100000,
                'password'=> bcrypt('password'),
             ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
