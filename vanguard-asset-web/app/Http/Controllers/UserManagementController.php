<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SecurityLogService;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display user list (Admin only).
     */
    public function index()
    {
        $users = User::withCount(['approvalRequests', 'borrowedAssets'])->get();
        return view('users.index', compact('users'));
    }

    /**
     * Update a user's role (Admin only).
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,manager,staff',
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $validated['role']]);

        SecurityLogService::log(
            'role_changed',
            "Role user '{$user->name}' diubah dari '{$oldRole}' ke '{$validated['role']}'.",
            'warning',
            auth()->id(),
            'App\\Models\\User',
            (string) $user->id,
            ['old_role' => $oldRole, 'new_role' => $validated['role']]
        );

        return redirect()->route('users.index')->with('success', "Role {$user->name} berhasil diubah ke " . ucfirst($validated['role']) . ".");
    }
}
