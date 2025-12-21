<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\SystemConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SystemConfigurationController extends Controller
{
    public function index()
    {
        // Get the first (and likely only) system configuration record
        $configuration = SystemConfiguration::first();

        // If no configuration exists, create a default one
        if (!$configuration) {
            $configuration = SystemConfiguration::create([
                'hotel_name_en' => 'Hotel Management System',
                'hotel_name_kh' => 'ប្រព័ន្ធគ្រប់គ្រងសណ្ឋាគារ',
                'system_title' => 'HMS',
                'watermark_title' => 'HMS',
            ]);
        }

        return view('settings.system-configuration.index', compact('configuration'));
    }

    public function edit(Request $request)
    {
        $configuration = SystemConfiguration::first();

        if (!$configuration) {
            $configuration = new SystemConfiguration();
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'hotel_name_en' => 'required|string|max:255',
                'hotel_name_kh' => 'nullable|string|max:255',
                'location_en' => 'nullable|string|max:1000',
                'location_kh' => 'nullable|string|max:1000',
                'phone_number' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'watermark_title' => 'nullable|string|max:255',
                'system_title' => 'nullable|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->only([
                'hotel_name_en',
                'hotel_name_kh',
                'location_en',
                'location_kh',
                'phone_number',
                'email',
                'watermark_title',
                'system_title',
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($configuration->logo_path && Storage::disk('public')->exists($configuration->logo_path)) {
                    Storage::disk('public')->delete($configuration->logo_path);
                }

                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo_path'] = $logoPath;
            }

            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                // Delete old favicon if exists
                if ($configuration->favicon_path && Storage::disk('public')->exists($configuration->favicon_path)) {
                    Storage::disk('public')->delete($configuration->favicon_path);
                }

                $faviconPath = $request->file('favicon')->store('favicons', 'public');
                $data['favicon_path'] = $faviconPath;
            }

            if ($configuration->exists) {
                $configuration->update($data);
                $message = __('global.system_configuration_updated_successfully');
            } else {
                $configuration = SystemConfiguration::create($data);
                $message = __('global.system_configuration_created_successfully');
            }

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'redirect' => route('settings.system-configuration.index'),
                'delay' => 2000
            ]);
        }

        return view('settings.system-configuration.edit', compact('configuration'));
    }
}
