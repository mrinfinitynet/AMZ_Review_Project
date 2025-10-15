<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendSetting;
use App\Models\Package;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    // Frontend Settings Index
    public function index()
    {
        $settings = FrontendSetting::all()->pluck('value', 'key');
        $packages = Package::orderBy('order')->get();

        return view('admin.pages.frontend.index', compact('settings', 'packages'));
    }

    // Update Frontend Settings
    public function updateSettings(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'site_title' => 'required|string|max:255',
            'site_tagline' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:50',
            'telegram_username' => 'nullable|string|max:100',
            'facebook_url' => 'nullable|url',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'cta_text' => 'required|string|max:100',
        ]);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(public_path('uploads'), $logoName);
            FrontendSetting::set('logo', 'uploads/' . $logoName);
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $faviconFile->getClientOriginalExtension();
            $faviconFile->move(public_path('uploads'), $faviconName);
            FrontendSetting::set('favicon', 'uploads/' . $faviconName);
        }

        foreach ($request->except(['_token', 'logo', 'favicon']) as $key => $value) {
            FrontendSetting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Frontend settings updated successfully!');
    }

    // Packages Management
    public function packages()
    {
        $packages = Package::orderBy('order')->get();
        return view('admin.pages.frontend.packages', compact('packages'));
    }

    // Store Package
    public function storePackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:month,year',
            'features' => 'required|string',
            'order' => 'nullable|integer'
        ]);

        // Process features - split by newlines and trim each line
        $featuresArray = array_filter(array_map('trim', explode("\n", $request->features)));

        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'features' => array_values($featuresArray), // Re-index array
            'is_popular' => $request->has('is_popular') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'order' => $request->order ?? 0
        ]);

        return redirect()->back()->with('success', 'Package created successfully!');
    }

    // Update Package
    public function updatePackage(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:month,year',
            'features' => 'required|string',
            'order' => 'nullable|integer'
        ]);

        // Process features - split by newlines and trim each line
        $featuresArray = array_filter(array_map('trim', explode("\n", $request->features)));

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'features' => array_values($featuresArray), // Re-index array
            'is_popular' => $request->has('is_popular') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'order' => $request->order ?? 0
        ]);

        return redirect()->back()->with('success', 'Package updated successfully!');
    }

    // Delete Package
    public function deletePackage($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->back()->with('success', 'Package deleted successfully!');
    }
}
