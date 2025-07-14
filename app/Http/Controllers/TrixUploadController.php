<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrixUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('attachment')->store('public/trix-attachments');

        return response()->json([
            'url' => Storage::url($path),
        ]);
    }

    public function summernoteStore(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('file')->store('posts/content', 'public');

        return response()->json([
            'url' => Storage::url($path),
        ]);
    }
}
