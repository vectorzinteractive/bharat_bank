<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        // validate input
        $request->validate([
            'name'  => 'required|string|max:15',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // update user
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Profile updated successfully',
        ]);
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|min:8|max:12',
        'new_password'     => 'required|min:8|max:12',
    ]);

    $user = auth()->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Current password is incorrect.'
        ]);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Password updated successfully!'
    ]);
}


}
