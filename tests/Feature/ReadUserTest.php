<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test GET list of users (index).
     *
     * @return void
     */
    public function test_get_users_list()
    {
        User::create([
            'name' => 'Nasir Ceyo',
            'email' => 'nasir.ceyo@example.com',
            'phone_number' => '081234567890',
            'age' => 25,
        ]);

        User::create([
            'name' => 'Ilham',
            'email' => 'ilham@example.com',
            'phone_number' => '081234567891',
            'age' => 30,
        ]);

        $response = $this->get('/api/users');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /**
     * Test GET user by ID (show).
     *
     * @return void
     */
    public function test_get_user_by_id()
    {
        $user = User::create([
            'name' => 'Nasir Ceyo',
            'email' => 'nasir.ceyo@example.com',
            'phone_number' => '081234567890',
            'age' => 25,
        ]);

        $response = $this->get("/api/users/{$user->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}

