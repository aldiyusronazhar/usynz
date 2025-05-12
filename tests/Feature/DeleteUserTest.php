<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test DELETE user (destroy).
     *
     * @return void
     */
    public function test_delete_user()
    {
        $user = User::create([
            'name' => 'Nasir Ceyo',
            'email' => 'nasir.ceyo@example.com',
            'phone_number' => '081234567890',
            'age' => 25,
        ]);

        $response = $this->delete("/api/users/{$user->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'User deleted',
        ]);
    }

    /**
     * Test GET and DELETE for user not found.
     *
     * @return void
     */
    public function test_user_not_found()
    {
        $response = $this->get('/api/users/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found'
        ]);

        $response = $this->delete('/api/users/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found'
        ]);
    }
}

