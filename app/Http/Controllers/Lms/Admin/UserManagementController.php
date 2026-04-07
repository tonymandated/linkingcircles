<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display list of users
     */
    public function index(): View
    {
        $this->authorize('users.view');

        $users = User::with('roles')
            ->paginate(25);

        return view('lms.admin.users.index', ['users' => $users]);
    }

    /**
     * Show user creation form
     */
    public function create(): View
    {
        $this->authorize('users.create');

        $roles = Role::whereNotIn('name', ['super_admin'])->get();

        return view('lms.admin.users.create', ['roles' => $roles]);
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $this->authorize('users.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->roles()->sync($validated['roles']);

        return redirect()->route('lms.admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show user edit form
     */
    public function edit(User $user): View
    {
        $this->authorize('users.update');

        $user->load('roles');
        $roles = Role::whereNotIn('name', ['super_admin'])->get();

        return view('lms.admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoleIds' => $user->roles->pluck('id')->toArray(),
        ]);
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('users.update');

        // Prevent editing super admin
        if ($user->hasRole('super_admin')) {
            abort(403, 'Cannot edit super admin user.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        $user->roles()->sync($validated['roles']);

        return redirect()->route('lms.admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $this->authorize('users.delete');

        // Prevent deleting super admin
        if ($user->hasRole('super_admin')) {
            abort(403, 'Cannot delete super admin user.');
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            abort(403, 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('lms.admin.users.index')->with('success', 'User deleted successfully.');
    }
}
