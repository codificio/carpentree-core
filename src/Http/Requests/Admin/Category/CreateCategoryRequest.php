<?php

namespace Carpentree\Core\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attributes.type' => 'required|string',
            'attributes.slug' => 'required|string',
            'attributes.name' => 'required|string',
            'attributes.description' => 'nullable|string',

            'relationships.parent.data' => 'nullable',
            'relationships.parent.data.id' => 'exists:categories,id'
        ];
    }
}
