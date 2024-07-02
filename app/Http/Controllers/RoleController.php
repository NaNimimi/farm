<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * List all roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $roles = Role::all();
            return response()->json($roles, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching roles'], 500);
        }
    }

    /**
     * Assign a role to a user.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,title', // Adjusted to match the title column in roles table
        ]);

        // Assuming User model exists and $user->assignRole() is a custom method you've implemented
        $user = User::find($userId);
        if ($user) {
            $user->assignRole($request->input('role'));
            return response()->json(['success' => 'Role assigned successfully.']);
        }

        return response()->json(['error' => 'User not found.'], 404);
    }

    /**
     * Revoke a role from a user.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,title', // Adjusted to match the title column in roles table
        ]);

        // Assuming User model exists and $user->removeRole() is a custom method you've implemented
        $user = User::find($userId);
        if ($user) {
            $user->removeRole($request->input('role'));
            return response()->json(['success' => 'Role revoked successfully.']);
        }

        return response()->json(['error' => 'User not found.'], 404);
    }

    /**
     * Add a new role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:roles,title',
            'name' => 'required|string' // Add validation for the 'name' field
        ]);
    
        try {
            // Logging request data for debugging
            \Log::info('Creating role with data:', $request->all());
    
            $role = new Role();
            $role->title = $request->input('title');
            $role->name = $request->input('name'); // Set the 'name' field
            $role->save();
    
            return response()->json(['success' => 'Role created successfully.', 'role' => $role], 201);
        } catch (\Exception $e) {
            // Logging exception for debugging
            \Log::error('Error creating role:', ['message' => $e->getMessage()]);
    
            return response()->json(['error' => 'Error creating role'], 500);
        }
    }
    
    
    /**
     * Remove a role.
     *
     * @param int $roleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($roleId)
    {
        try {
            $role = Role::find($roleId);
            if ($role) {
                $role->delete();
                return response()->json(['success' => 'Role deleted successfully.'], 200);
            }

            return response()->json(['error' => 'Role not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting role'], 500);
        }
    }
}
