<?php

namespace Carpentree\Core\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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

            'attributes.type' => 'string',
            'attributes.slug' => 'string',
            'attributes.name' => 'string',
            'attributes.description' => 'nullable|string',

            'relationships.parent.data' => 'nullable',
            'relationships.parent.data.id' => 'exists:categories,id',

            'relationships.before.data' => 'nullable',
            'relationships.before.data.id' => 'exists:categories,id'
        ];
    }
}
