<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function create()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        // Log incoming request data
        Log::info('Store User Request:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer' // Validate as integer
        ]);

        $role = Role::findOrFail($request->input('role_id'));

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $role->id // Save role_id instead of role_name
        ]);

        // Log created user data
        Log::info('User Created:', $user->toArray());

        return response()->json(['success' => 'User created successfully']);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return response()->json(['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|integer' // Validate as integer
        ]);

        $role = Role::findOrFail($request->input('role_id'));

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $role->id // Update role_id instead of role_name
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);
        }

        return response()->json(['success' => 'User updated successfully']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => 'User deleted successfully']);
    }
    public function getUserRoles($userId)
    {
        $user = User::find($userId);
        if ($user) {
            return response()->json(['role_id' => $user->role_id]);
        }

        return response()->json(['error' => 'User not found.'], 404);
    }
}
