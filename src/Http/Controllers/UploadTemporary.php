<?php

namespace Carpentree\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadTemporary extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'files' => 'required|array',
            'files.*' => 'mimes:' . config('carpentree.core.media.allowed_mimes')
        ]);

        $urls = [];

        foreach ($validatedData['files'] as $file)
        {
            /** @var UploadedFile $file */
            $path = $file->storeAs(config('carpentree.core.media.temp_path'), $file->hashName(), ['disk' => 'public']);
            $urls[] = Storage::url($path);
        }

        return response()->json($urls);
    }
}
