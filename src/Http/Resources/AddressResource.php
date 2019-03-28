<?php

namespace Carpentree\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class AddressResource extends JsonResource
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
            'type' => 'addresses',
            'id' => $this->id,
            'locale' => App::getLocale(),

            'attributes' => [
                'full_name' => $this->full_name,
                'address_line' => $this->address_line,
                'country' => $this->country,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'phone_number' => $this->phone_number,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],

            'relationships' => [
                // Type
                'type' => [
                    'data' => [
                        'id' => $this->type->id,
                        'attributes' => [
                            'name' => $this->type->name
                        ]
                    ]
                ],
            ]

        ];
    }

}
