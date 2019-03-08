<?php

namespace Carpentree\Core\Http\Requests\Admin\Address;

use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attributes.full_name' => 'string',
            'attributes.address_line' => 'string',
            'attributes.country' => 'string',
            'attributes.city' => 'string',
            'attributes.state' => 'string',
            'attributes.postal_code' => 'string',
            'attributes.phone_number' => 'string',

            'relationships.user.data' => 'required',
            'relationships.user.data.id' => 'exists:users,id',

            'relationships.type.data' => 'required',
            'relationships.type.data.id' => 'exists:address_types,id',
        ];
    }
}
