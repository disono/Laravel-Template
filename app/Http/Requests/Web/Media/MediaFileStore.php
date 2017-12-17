<?php

namespace App\Http\Requests\Web\Media;

use App\Http\Requests\Web\WebRequest;

class MediaFileStore extends WebRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'max:100',
            'description' => 'max:100',
            'file' => 'required|mimes:jpeg,jpg,png,gif,pdf,zip,rar,doc,docx,mp4,3gp|max:' . config_file_size(),
        ];
    }
}
