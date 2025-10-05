<?php

// app/Http/Controllers/SettingsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting; // Import your Setting model

class SettingsController extends Controller
{
    /**
     * Display the settings management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all settings or specific ones
        $settings = [
            'company_name' => Setting::get('company_name', 'Your WMS Company'),
            'default_currency' => Setting::get('default_currency', 'USD'),
            'enable_email_notifications' => Setting::get('enable_email_notifications', false),
            // Add more settings as needed
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update the application settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'default_currency' => 'nullable|string|max:3|alpha', // e.g., USD, EUR, PKR
            'enable_email_notifications' => 'boolean',
            // Add validation rules for other settings
        ]);

        // Update settings
        Setting::set('company_name', $request->input('company_name'));
        Setting::set('default_currency', $request->input('default_currency'));
        Setting::set('enable_email_notifications', $request->has('enable_email_notifications'), 'boolean'); // Handle checkbox

        // You can use a loop if you have many settings with a consistent naming convention
        // foreach ($request->except('_token', '_method') as $key => $value) {
        //     Setting::set($key, $value);
        // }


        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }
}
