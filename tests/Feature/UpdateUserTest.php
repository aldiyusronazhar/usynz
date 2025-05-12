<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test PUT update an existing user (update).
     *
     * @return void
     */
    public function test_update_user()
    {
        $user = User::create([
            'name' => 'Nasir Ceyo',
            'email' => 'nasir.ceyo@example.com',
            'phone_number' => '081234567890',
            'age' => 25,
        ]);

        $updateData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'age' => 30,
            'phone_number' => '08123456789',
        ];

        $response = $this->put("/api/users/{$user->id}", $updateData);
        $response->assertStatus(200);
        $response->assertJson([
            'name' => $updateData['name'],
            'email' => $updateData['email'],
            'age' => $updateData['age'],
            'phone_number' => $updateData['phone_number'],
        ]);
    }
}

