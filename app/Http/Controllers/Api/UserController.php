<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * GET /api/users
     * Retrieve all users
     */
    public function index()
    {
        $users = User::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data'    => $users
        ], 200);
    }

    /**
     * GET /api/users/{id}
     * Retrieve a single user
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data'    => $user
        ], 200);
    }

    /**
     * POST /api/users
     * Create a new user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'is_admin' => ['boolean'],
            ]);

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_admin' => $request->boolean('is_admin', false),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data'    => $user
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT / PATCH /api/users/{id}
     * Update an existing user
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'name'     => ['sometimes', 'required', 'string', 'max:255'],
                'email'    => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
                'is_admin' => ['boolean'],
            ]);

            $data = [];

            if (isset($validated['name'])) {
                $data['name'] = $validated['name'];
            }

            if (isset($validated['email'])) {
                $data['email'] = $validated['email'];
            }

            if ($request->filled('password')) {
                $data['password'] = Hash::make($validated['password']);
            }

            if ($request->has('is_admin')) {
                $data['is_admin'] = $request->boolean('is_admin');
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data'    => $user
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/users/{id}
     * Delete a user
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Optional: Prevent deleting the currently authenticated admin
        // if (auth()->check() && auth()->id() === $user->id) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You cannot delete your own account'
        //     ], 403);
        // }

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}