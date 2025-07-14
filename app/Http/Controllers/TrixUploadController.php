<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrixUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'attachment' => 'required|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('trix-attachments', 'public_real');
            $url = asset('storage/' . $path);

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'File not found.'], 422);
    }
}
