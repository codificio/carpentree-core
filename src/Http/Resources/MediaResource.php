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
                'name' => $this->name,
                'url' => $this->getUrl(),
                'mime_type' => $this->mime_type,
                'order' => $this->order_column,
                'alt' => $this->when($this->hasCustomProperty('alt'), $this->getCustomProperty('alt'))
            ]
        ];
    }
}
