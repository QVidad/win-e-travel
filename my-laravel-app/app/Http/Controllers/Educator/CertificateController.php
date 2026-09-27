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
            $file = $request->file('university_logo');
            $setting->university_logo_path = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        if ($request->hasFile('college_logo')) {
            $file = $request->file('college_logo');
            $setting->college_logo_path = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        if ($request->hasFile('signature_image')) {
            $file = $request->file('signature_image');
            $setting->signature_image_path = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $setting->background_image_path = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        $setting->save();

        return redirect()->back()->with('success', 'Certificate settings updated successfully.');
    }
}
