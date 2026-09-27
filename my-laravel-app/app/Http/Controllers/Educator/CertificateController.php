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
            $filename = time() . '_uni.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/certificates'), $filename);
            $setting->university_logo_path = '/uploads/certificates/' . $filename;
        }

        if ($request->hasFile('college_logo')) {
            $file = $request->file('college_logo');
            $filename = time() . '_college.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/certificates'), $filename);
            $setting->college_logo_path = '/uploads/certificates/' . $filename;
        }

        if ($request->hasFile('signature_image')) {
            $file = $request->file('signature_image');
            $filename = time() . '_sig.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/certificates'), $filename);
            $setting->signature_image_path = '/uploads/certificates/' . $filename;
        }

        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = time() . '_bg.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/certificates'), $filename);
            $setting->background_image_path = '/uploads/certificates/' . $filename;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Certificate settings updated successfully.');
    }
}
