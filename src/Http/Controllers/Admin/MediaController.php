<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\Media\CreateMediaRequest;
use Carpentree\Core\Http\Resources\MediaResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Spatie\Permission\Exceptions\UnauthorizedException;

class MediaController extends Controller
{

    /**
     * @param CreateMediaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(CreateMediaRequest $request)
    {
        if (!Auth::user()->can('media.upload')) {
            throw UnauthorizedException::forPermissions(['media.upload']);
        }

        $files = $request->file('media');
        $media = new Collection();
        foreach ($files as $file) {
            $media->add(MediaUploader::fromSource($file)->upload());
        }

        return MediaResource::collection($media)->response()->setStatusCode(201);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($id)
    {
        if (!Auth::user()->can('media.delete')) {
            throw UnauthorizedException::forPermissions(['media.delete']);
        }

        /** @var Media $media */
        $media = Media::findOrFail($id);

        if ($media->delete($id)) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 202);
        }
    }

}
