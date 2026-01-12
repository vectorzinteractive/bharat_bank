<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $imageName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('media/editor'), $imageName);

            $appUrl = config('app.url');
        $url = $appUrl . '/media/editor/' . $imageName;

            return response()->json(['location' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
