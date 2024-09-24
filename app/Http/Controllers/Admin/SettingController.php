<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\ImageUpload;

class SettingController extends Controller
{
    use ImageUpload;

    public function index() {

        $setting = Setting::latest()->first();

        return view('admin.settings.index', compact('setting'));

    }
    public function create() {

        return view('admin.settings.create');
    }

    public function store(Request $request) {


        $request->validate([
            'favicon'=>'nullable|image|max:2048',
            'fatherland_logo' => 'nullable|image|max:2048',
            'fatherland_url'=>'nullable|url',
            'linkedin_logo' => 'nullable|image|max:2048',
            'linkedin_url'=>'nullable|url',
            'facebook_logo' => 'nullable|image|max:2048',
            'facebook_url'=>'nullable|url',
            'instagram_logo' => 'nullable|image|max:2048',
            'instagram_url'=>'nullable|url'
        ]);

        $favicon = null;
        $fatherlandLogo = null;
        $instagramLogo = null;
        $facebookLogo = null;
        $linkedinLogo = null;
       
            if ($request->hasFile('favicon')) {
                $logo = $request->file('favicon');
                $favicon = $this->uploadImage($logo);
            }
            if ($request->hasFile('fatherland_logo')) {
                $logo = $request->file('fatherland_logo');
                $fatherlandLogo = $this->uploadImage($logo);
            }
            if ($request->hasFile('instagram_logo')) {
                $logo = $request->file('instagram_logo');
                $instagramLogo = $this->uploadImage($logo);
            }
            if ($request->hasFile('facebook_logo')) {
                $logo = $request->file('facebook_logo');
                $facebookLogo = $this->uploadImage($logo);
            }
            if ($request->hasFile('linkedin_logo')) {
                $logo = $request->file('linkedin_logo');
                $linkedinLogo = $this->uploadImage($logo);
            }



        $setting = new Setting();

        $setting->favicon =  $favicon;
        $setting->fatherland_logo = $fatherlandLogo;
        $setting->fatherland_url = $request->input('fatherland_url');
        $setting->linkedin_logo = $linkedinLogo;
        $setting->linkedin_url = $request->input('linkedin_url');
        $setting->facebook_logo = $facebookLogo;
        $setting->facebook_url = $request->input('facebook_url');
        $setting->instagram_logo = $instagramLogo;
        $setting->instagram_url = $request->input('instagram_url');
        $setting->save();


        return view('admin.settings.index');
    }

    public function update(Request $request, Setting $setting)
    {

        $logoFields = ['favicon', 'fatherland_logo', 'linkedin_logo', 'facebook_logo', 'instagram_logo'];
        $logoPaths = [];

        // Validate each logo field
        foreach ($logoFields as $logoField) {
            if ($request->hasFile($logoField)) {
                $logo = $request->file($logoField);
                $logoPath = $this->uploadImage($logo); // Assuming you have an uploadImage method

                if (!$logoPath) {
                    return redirect()->back()->with('error', 'Failed to upload ' . $logoField . ' image');
                }

                $logoPaths[$logoField] = $logoPath;
            }
        }

        // Update setting with new logo paths
        foreach ($logoPaths as $field => $path) {
            $setting->{$field} = $path;
        }

        // Update other setting fields
        $setting->fatherland_url = $request->input('fatherland_url');
        $setting->linkedin_url = $request->input('linkedin_url');
        $setting->facebook_url = $request->input('facebook_url');
        $setting->instagram_url = $request->input('instagram_url');

        // Save setting
        $setting->save();

        return redirect()->route('admin.settings.index');
    }

    public function show(setting $setting)
    {
        return view('admin.settings.show', compact('setting'));
    }

    public function edit(setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }
    public function destroy(setting $setting)
    {

        $setting->delete();

        return back();
    }
}
