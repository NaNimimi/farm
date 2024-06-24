<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
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
            'role' => 'required|string|exists:roles,name',
        ]);

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
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::find($userId);
        if ($user) {
            $user->removeRole($request->input('role'));
            return response()->json(['success' => 'Role revoked successfully.']);
        }

        return response()->json(['error' => 'User not found.'], 404);
    }
}
