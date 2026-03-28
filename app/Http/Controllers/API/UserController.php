<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // GET /api/users  (Admin only)
    public function index(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $users = User::paginate(15);
        return response()->json($users);
    }

    // GET /api/users/{user}  (Admin only)
    public function show(Request $request, User $user)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json($user);
    }

    // PUT /api/users/{user}  (Admin only)
    public function update(Request $request, User $user)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:50',
            'last_name'  => 'sometimes|string|max:50',
            'email'      => 'sometimes|email|unique:users,email,' . $user->user_id . ',user_id',
            'password'   => 'sometimes|string|min:8|confirmed',
            'phone'      => 'sometimes|nullable|string|max:20',
            'address'    => 'sometimes|nullable|string|max:255',
            'birthdate'  => 'sometimes|nullable|date',
            'student_id' => 'sometimes|nullable|string|unique:users,student_id,' . $user->user_id . ',user_id',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated.', 'user' => $user]);
    }

    // DELETE /api/users/{user}  (Admin only)
    public function destroy(Request $request, User $user)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted.']);
    }

    // PATCH /api/users/{user}/role  (Admin only)
    public function assignRole(Request $request, User $user)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $request->validate(['role' => 'required|in:Admin,Student']);
        $user->update(['role' => $request->role]);

        return response()->json(['message' => 'Role updated.', 'user' => $user]);
    }

    // GET /api/profile
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    // PUT /api/profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:50',
            'last_name'  => 'sometimes|string|max:50',
            'email'      => 'sometimes|email|unique:users,email,' . $user->user_id . ',user_id',
            'password'   => 'sometimes|string|min:8|confirmed',
            'phone'      => 'sometimes|nullable|string|max:20',
            'address'    => 'sometimes|nullable|string|max:255',
            'birthdate'  => 'sometimes|nullable|date',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'Profile updated.', 'user' => $user]);
    }

    // POST /api/profile/picture
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $path = $request->file('picture')->store('profile_pictures', 'public');
        $user->update(['profile_picture' => $path]);

        return response()->json([
            'message'         => 'Profile picture updated.',
            'profile_picture' => $path,
        ]);
    }
}
