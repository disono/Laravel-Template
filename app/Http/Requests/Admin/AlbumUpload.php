<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin;

class AlbumUpload extends AdminRequest
{
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

            'image.*' => 'image|max:' . config_file_size(),
        ];
    }
}
