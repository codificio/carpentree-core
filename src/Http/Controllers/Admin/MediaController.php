<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\User\UpdateMediaRequest;
use Carpentree\Core\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Permission\Exceptions\UnauthorizedException;

class MediaController extends Controller
{

    public function update(UpdateMediaRequest $request)
    {
        if (!Auth::user()->can('media.update')) {
            throw UnauthorizedException::forPermissions(['media.update']);
        }

        // TODO: refactoring of media update

        $media = DB::transaction(function() use ($request) {

            $id = $request->input('id');
            $media = Media::findOrFail($id);

            if ($request->has('attributes')) {
                $attributes = $request->input('attributes');
                $media = $media->fill($attributes);
            }

            if ($request->has('attributes.order')) {
                $order = $request->input('attributes.order');
                $media->order = $order;
            }

            if ($request->has('attributes.alt')) {
                $alt = $request->input('attributes.alt');
                $media->setCustomProperty('alt', $alt);
            }

            $media->save();

            return $media;

        });

        return MediaResource::make($media);
    }

}
