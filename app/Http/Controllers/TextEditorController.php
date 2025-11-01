<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TextEditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $path = public_path('tmp/uploads');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $file = $request->file('upload');
            $filename = md5(time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);
            $absoluteUrl = 'tmp/uploads/' . $filename;
            $img = webp_variants($absoluteUrl, [320, 640, 960, 1280]);
            unlink($absoluteUrl);
            dd($img);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => [
                'message' => 'No file uploaded.',
            ],
        ]);
    }
}
