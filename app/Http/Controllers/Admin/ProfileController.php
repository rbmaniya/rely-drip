<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdatePasswordRequest;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', ['admin' => $request->user('admin')]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $admin = $request->user('admin');
        $data = $request->safe()->except('avatar');

        if ($request->hasFile('avatar')) {
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('admins', 'public');
        }

        $admin->update($data);

        ActivityLog::record('profile.updated', 'Admin profile updated.');

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user('admin')->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        ActivityLog::record('profile.password_changed', 'Admin password changed.');

        return back()->with('success', 'Password updated successfully.');
    }
}
