<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        // Validate and process the contact form submission
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Here you could send an email, save to DB, etc.
        // For now, just redirect back with a success message
        return back()->with('success', 'Thank you for contacting us!');
    }
}
