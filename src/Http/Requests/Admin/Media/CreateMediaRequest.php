<?php

namespace Carpentree\Core\Http\Requests\Admin\Media;

use Illuminate\Foundation\Http\FormRequest;

class CreateMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $maxFileSize = config('carpentree.media.max_file_size', 4096);

        return [
            'media' => "required|array",
            'media.*' => "file|max:$maxFileSize"
        ];
    }
}
