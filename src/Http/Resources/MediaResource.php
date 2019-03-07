<?php

namespace Carpentree\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'media',
            'id' => $this->id,

            'attributes' => [
                'size' => $this->size,
                "mime_type"=> $this->mime_type,
                "extension"=> $this->extension,
                "filename"=> $this->filename,
                "url" => $this->getUrl(),
                "updated_at"=> $this->updated_at,
                "created_at"=> $this->created_at,
            ]
        ];
    }
}
