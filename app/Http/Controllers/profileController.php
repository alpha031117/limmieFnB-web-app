<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class profileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     */
    public function show()
    {
        $user = Auth::user(); // get logged-in user
        return view('user.profile', compact('user'));
    }

     public function editForm()
    {
        $user = Auth::user(); // get logged-in user
        return view('user.edit-profile', compact('user'));
    }


    /**
     * Update the authenticated user's profile.
     */
 public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|max:50',
        ]);

        // Update fields
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($user->save()) {
            return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * (Optional) Show profile by user ID.
     */
    public function showById($id)
    {
        $user = Profile::findOrFail($id);
        return view('profile.show', compact('user'));
    }
}




