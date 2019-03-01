<?php

namespace Carpentree\Core\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FlushTemporaryMediaService
{
    /**
     * @param int $minutes  Remove files that are older than $minutes
     */
    public function flush($minutes = 30)
    {
        $disk = Storage::disk(config('carpentree.core.media.temp_disk'));

        $files = $disk->allFiles(config('carpentree.core.media.temp_path'));
        foreach ($files as $file)
        {
            /** @var UploadedFile $file */
            $lastModified = Carbon::createFromTimestamp($disk->lastModified($file), config('app.timezone'));
            $now = Carbon::now(config('app.timezone'));

            $diffInMinutes = $now->diffInMinutes($lastModified);

            if ($diffInMinutes > $minutes)
            {
                $disk->delete($file);
            }
        }
    }
}
