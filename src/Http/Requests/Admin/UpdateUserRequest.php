<?php

namespace Carpentree\Core\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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

            'attributes.first_name' => 'string',
            'attributes.last_name' => 'string',
            'attributes.email' => 'email|unique:users,email',
            'attributes.password' => 'string|min:6',

            // Roles
            'relationships.roles.data.*.id' => 'exists:roles,id',

            // Meta fields
            'relationships.meta.data.*.attributes.key' => 'string',
            'relationships.meta.data.*.attributes.value' => 'required_with:relationships.meta.data.*.attributes.key|string'
        ];
    }
}
