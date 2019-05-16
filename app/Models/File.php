<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class File extends BaseModel
{
    protected $tableName = 'files';
    protected $writableColumns = [
        'user_id', 'file_name',

        // types: video, photo, doc, file, audio
        'type', 'ext', 'title', 'description',
        'table_name', 'table_id', 'tag'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function types()
    {
        return ['video', 'photo', 'doc', 'file', 'audio'];
    }

    /**
     * Delete data
     *
     * @param $id
     * @param null $columnName
     *
     * @return bool
     */
    public function remove($id, $columnName = null)
    {
        $query = $this->rawFetch($id, $columnName);

        // check if exists or tried to delete the users authorization
        $_r = $query->first();
        if (!$_r) {
            return false;
        }

        $save = false;

        // delete the file
        if (fileDestroy('private/' . $_r->file_name)) {
            $save = (bool)$query->delete();
        }

        return $save;
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    protected function dataFormatting($row)
    {
        if ($row->type === 'photo') {
            $row->cover = fetchImage($row->file_name, 'assets/img/placeholders/no_image.png');
            $row->icon = url('assets/img/placeholders/image.png');
        } else if ($row->type === 'video') {
            $row->cover = url('assets/img/placeholders/video.png');
        } else if ($row->type === 'doc') {
            $row->cover = url('assets/img/placeholders/document.png');
        } else if ($row->type === 'file') {
            $row->cover = url('assets/img/placeholders/file.png');
        } else if ($row->type === 'audio') {
            $row->cover = url('assets/img/placeholders/audio.png');
        }

        if ($row->type === 'video') {
            $row->path = url('stream/video/' . $row->file_name);
        } else if ($row->type === 'audio') {
            $row->path = url('stream/audio/' . $row->file_name);
        } else if ($row->type === 'photo' && $this->hasParams('to_base64_img')) {
            $row->path = imgPathToBase64('private/' . $row->file_name);
        } else {
            $row->path = url('private/' . $row->file_name);
        }

        return $row;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
