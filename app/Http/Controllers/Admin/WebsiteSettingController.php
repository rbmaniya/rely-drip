<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\UpdateWebsiteSettingRequest;
use App\Models\ActivityLog;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    public function edit(): View
    {
        $settings = WebsiteSetting::allSettings();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(UpdateWebsiteSettingRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['logo', 'favicon']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        WebsiteSetting::setMany($data);

        ActivityLog::record('settings.updated', 'Website settings updated.');

        return back()->with('success', 'Website settings updated successfully.');
    }
}
