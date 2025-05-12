<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $this->logRequest('GET', '/users');
        return response()->json(User::all());
    }

    public function show($id)
    {
        $this->logRequest('GET', "/users/{$id}");
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $this->logRequest('POST', '/users', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'age' => 'required|integer',
            'phone_number' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create($request->only(['name', 'email', 'age', 'phone_number']));
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $this->logRequest('PUT', "/users/{$id}", $request->all());

        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'age' => 'required|integer',
            'phone_number' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update($request->only(['name', 'email', 'age', 'phone_number']));
        return response()->json($user);
    }

    public function destroy($id)
    {
        $this->logRequest('DELETE', "/users/{$id}");
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    private function logRequest($method, $url, $data = null)
    {
        $log = "[" . now() . "] $method $url" . ($data ? " | Data: " . json_encode($data) : '');
        file_put_contents(storage_path('logs/log.txt'), $log . PHP_EOL, FILE_APPEND);
    }
}

