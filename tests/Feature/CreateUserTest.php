<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test POST create a new user.
     *
     * @return void
     */
    public function test_create_user()
    {
        $data = [
            'name' => 'Azhar',
            'email' => 'azhar@gmail.com',
            'age' => 30,
            'phone_number' => '08123456789',
        ];

        $response = $this->post('/api/users', $data);
        $response->assertStatus(201);
        $response->assertJson([
            'name' => $data['name'],
            'email' => $data['email'],
            'age' => $data['age'],
            'phone_number' => $data['phone_number'],
        ]);
    }
}

