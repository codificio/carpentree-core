<?php

namespace Carpentree\Core\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ListUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filter.query' => 'string',
            'sort' => 'string|filled'
        ];
    }
}
