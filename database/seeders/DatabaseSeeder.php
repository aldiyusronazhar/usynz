<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Azhar Zayyan',
                'email' => 'azharzayyan@gmail.com',
                'age' => 22,
                'phone_number' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zahra Zalfa',
                'email' => 'zahrazalfa@gmail.com',
                'age' => 20,
                'phone_number' => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zikri Zamzami',
                'email' => 'zikrizamzami@gmail.com',
                'age' => 25,
                'phone_number' => '081234567892',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zayn Zavier',
                'email' => 'zaynzavier@gmail.com',
                'age' => 23,
                'phone_number' => '081234567893',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nazla Zuleyka',
                'email' => 'nazlazuleyka@gmail.com',
                'age' => 21,
                'phone_number' => '081234567894',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zaki Zulfikar',
                'email' => 'zakizulfikar@gmail.com',
                'age' => 26,
                'phone_number' => '081234567895',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zara Zawiyah',
                'email' => 'zarazawiyah@gmail.com',
                'age' => 24,
                'phone_number' => '081234567896',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zihan Zaitun',
                'email' => 'zihanzaitun@gmail.com',
                'age' => 27,
                'phone_number' => '081234567897',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zulfi Zaki',
                'email' => 'zulfi.zaki@gmail.com',
                'age' => 29,
                'phone_number' => '081234567898',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zuleyka Zahra',
                'email' => 'zuleykazahra@gmail.com',
                'age' => 22,
                'phone_number' => '081234567899',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

