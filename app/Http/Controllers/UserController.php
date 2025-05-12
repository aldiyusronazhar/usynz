<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for Managing Users"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="List of users"
     *     )
     * )
     */
    public function index()
    {
        $this->logRequest('GET', '/users');
        return response()->json(User::all());
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function show($id)
    {
        $this->logRequest('GET', "/users/{$id}");
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        return response()->json($user);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","age","phone_number"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="age", type="integer", example=25),
     *             @OA\Property(property="phone_number", type="string", example="08123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Update a user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","age","phone_number"},
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="jane@example.com"),
     *             @OA\Property(property="age", type="integer", example=30),
     *             @OA\Property(property="phone_number", type="string", example="08987654321")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $this->logRequest('PUT', "/users/{$id}", $request->all());

        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'age' => 'required|integer',
            'phone_number' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update($request->only(['name', 'email', 'age', 'phone_number']));
        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Delete a user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
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

