<?php

namespace Carpentree\Core\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attributes.first_name' => 'required|string',
            'attributes.last_name' => 'required|string',
            'attributes.email' => 'required|email|unique:users,email',
            'attributes.password' => 'required|string|min:6',

            // Roles
            'relationships.roles.data.*.id' => 'exists:roles,id'
        ];
    }
}
