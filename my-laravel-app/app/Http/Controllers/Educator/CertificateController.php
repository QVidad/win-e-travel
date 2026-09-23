<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\CertificateSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CertificateController extends Controller
{
    public function edit()
    {
        $setting = CertificateSetting::first();
        if (!$setting) {
            $setting = CertificateSetting::create([]);
        }

        return Inertia::render('Educator/Certificate/Edit', [
            'settings' => $setting
        ]);
    }

    public function update(Request $request)
    {
        $setting = CertificateSetting::first();
        if (!$setting) {
            $setting = CertificateSetting::create([]);
        }

        $validated = $request->validate([
            'university_name' => 'required|string|max:255',
            'college_name' => 'required|string|max:255',
            'signer_name' => 'required|string|max:255',
            'signer_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'university_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'college_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $setting->university_name = $validated['university_name'];
        $setting->college_name = $validated['college_name'];
        $setting->signer_name = $validated['signer_name'];
        $setting->signer_title = $validated['signer_title'];
        $setting->description = $validated['description'] ?? '';

        if ($request->hasFile('university_logo')) {
            if ($setting->university_logo_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $setting->university_logo_path));
            }
            $path = $request->file('university_logo')->store('certificates', 'public');
            $setting->university_logo_path = '/storage/' . $path;
        }

        if ($request->hasFile('college_logo')) {
            if ($setting->college_logo_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $setting->college_logo_path));
            }
            $path = $request->file('college_logo')->store('certificates', 'public');
            $setting->college_logo_path = '/storage/' . $path;
        }

        if ($request->hasFile('signature_image')) {
            if ($setting->signature_image_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $setting->signature_image_path));
            }
            $path = $request->file('signature_image')->store('certificates', 'public');
            $setting->signature_image_path = '/storage/' . $path;
        }

        if ($request->hasFile('background_image')) {
            if ($setting->background_image_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $setting->background_image_path));
            }
            $path = $request->file('background_image')->store('certificates', 'public');
            $setting->background_image_path = '/storage/' . $path;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Certificate settings updated successfully.');
    }
}
