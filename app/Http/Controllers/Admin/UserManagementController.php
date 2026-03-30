<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    /**
     * GET /admin/users
     */
    public function index()
    {
        $users = User::withCount(['transactions', 'businesses', 'bills'])
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * GET /admin/users/create
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * POST /admin/users
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'mobile'        => 'required|digits:10|unique:users,mobile',
            'gender'        => 'nullable|in:male,female,other',
            'user_types'    => 'nullable|array',
            'user_types.*'  => 'in:employee,farmer,proprietor,business_owner,shopkeeper,transporter',
            'reminder_time' => 'nullable|date_format:H:i',
            'is_active'     => 'nullable|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required'     => 'Name is required.',
            'mobile.required'   => 'Mobile number is required.',
            'mobile.digits'     => 'Mobile must be exactly 10 digits.',
            'mobile.unique'     => 'This mobile number is already registered.',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * GET /admin/users/{id}
     */
    public function show(string $id)
    {
        $user = User::withCount(['transactions', 'businesses', 'bills'])
            ->findOrFail($id);

        $recentTransactions = $user->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->take(10)
            ->get();

        $totalCredit = $user->transactions()->where('type', 'credit')->sum('amount');
        $totalDebit  = $user->transactions()->where('type', 'debit')->sum('amount');

        return view('admin.users.show', compact(
            'user',
            'recentTransactions',
            'totalCredit',
            'totalDebit'
        ));
    }

    /**
     * GET /admin/users/{id}/edit
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * PUT /admin/users/{id}
     */

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'mobile'        => 'required|digits:10|unique:users,mobile,' . $id,
            'gender'        => 'nullable|in:male,female,other',
            'user_types'    => 'nullable|array',
            'user_types.*'  => 'in:employee,farmer,proprietor,business_owner,shopkeeper,transporter',
            'reminder_time' => 'nullable|date_format:H:i,H:i:s', // ← H:i:s bhi accept karo
            'is_active'     => 'nullable|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required'   => 'Name is required.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.digits'   => 'Mobile must be exactly 10 digits.',
            'mobile.unique'   => 'This mobile number is already registered.',
        ]);

        // reminder_time ko H:i:s → H:i convert karo (DB time column ke liye)
        if (!empty($validated['reminder_time'])) {
            $validated['reminder_time'] = substr($validated['reminder_time'], 0, 5);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    /**
     * DELETE /admin/users/{id}
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * PATCH /admin/users/{id}/toggle-status
     */
    public function toggleStatus(string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        $msg = $user->is_active ? 'User activated.' : 'User deactivated.';
        return back()->with('success', $msg);
    }
}
