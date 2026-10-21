<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            $existing = Setting::where('key', $key)->first();
            Setting::set(
                $key,
                $value,
                $existing->group ?? 'general',
                $existing->type ?? 'string'
            );
        }

        ActivityLog::record('settings', 'update', 'Settings updated');

        return back()->with('success', 'Settings saved successfully.');
    }
}
