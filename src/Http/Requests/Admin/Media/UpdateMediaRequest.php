<?php

namespace Carpentree\Core\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer',

            'attributes.order' => 'integer',
            'attributes.alt' => 'string'
        ];
    }
}
