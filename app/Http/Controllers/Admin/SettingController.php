<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Settings\GeneralSettings;
use App\Http\Requests\UpdateSettingRequest;
class SettingController extends Controller
{
    public function index(GeneralSettings $settings)
    {
        return view('admin.settings.index', compact('settings'));
    }
    
    public function update(
        UpdateSettingRequest $request,
        GeneralSettings $settings
    ) {

        $settings->site_name = $request->site_name;

        $settings->site_email = $request->site_email;

        $settings->site_phone = $request->site_phone;

        $settings->site_address = $request->site_address;


        $settings->facebook_url = $request->facebook_url;
        $settings->twitter_url = $request->twitter_url;
        $settings->instagram_url = $request->instagram_url;

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                \Storage::disk('public')->delete($settings->logo);
            }
            $logo = $request->file('logo')
                ->store('settings', 'public');

            $settings->logo = $logo;
        }

        $settings->save();

        return back()->with(
            'success',
            'Settings updated successfully'
        );
    }
    
    
}
