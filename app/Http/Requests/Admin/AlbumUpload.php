<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AlbumUpload extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'album_id' => 'required|integer|exists:image_albums,id',
            'title' => 'required|max:100',
            'description' => 'max:500',

            'image' => 'image|max:' . config_file_size(),
        ];
    }
}
