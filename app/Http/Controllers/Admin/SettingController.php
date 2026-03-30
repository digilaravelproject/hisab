<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();
        $selectedUser = null;
        $settings = null;

        if ($request->filled('user_id')) {
            $selectedUser = User::find($request->query('user_id'));
        }

        if (! $selectedUser && $users->isNotEmpty()) {
            $selectedUser = $users->first();
        }

        if ($selectedUser) {
            $settings = $selectedUser->settings()->firstOrNew([
                'user_id' => $selectedUser->id,
            ], [
                'notifications_enabled' => false,
                'biometric_enabled' => false,
            ]);
        }

        return view('admin.settings.index', compact('users', 'selectedUser', 'settings'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'notifications_enabled' => 'sometimes|boolean',
            'pin_code' => 'nullable|string|max:10',
            'biometric_enabled' => 'sometimes|boolean',
            'daily_reminder_time' => 'nullable|date_format:H:i',
            'weekly_budget_limit' => 'nullable|numeric|min:0',
            'monthly_budget_limit' => 'nullable|numeric|min:0',
        ]);

        $validated += [
            'notifications_enabled' => $request->has('notifications_enabled'),
            'biometric_enabled' => $request->has('biometric_enabled'),
        ];

        $user->settings()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('admin.settings', ['user_id' => $user->id])
            ->with('success', 'User settings updated successfully.');
    }
}
