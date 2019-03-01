<?php

namespace Carpentree\Core\Http\Controllers;

use Carpentree\Core\Http\Resources\TemporaryMediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Models\Media;

class UploadTemporaryMedia extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'media' => 'required|array',
            'media.*' => 'mimes:' . config('carpentree.core.media.allowed_mimes')
        ]);

        $data = [];

        foreach ($validatedData['media'] as $file)
        {
            /** @var UploadedFile $file */
            $path = $file->storeAs(config('carpentree.core.media.temp_path'), $file->hashName(), ['disk' => config('carpentree.core.media.temp_disk')]);
            $data[] = [
                'url' => Storage::url($path),
                'path' => $path
            ];
        }

        return response()->json(array('data' => $data));
    }
}
