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
        'type', 'ext', 'title', 'description',
        'table_name', 'table_id', 'tag',
        'img_width', 'img_height'
    ];

    protected $columnHasRelations = ['user_id', 'table_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function remove($id, $columnName = null)
    {
        $query = $this->rawFetch($id, $columnName);

        // check if exists or tried to delete the users authorization
        $file = $query->first();
        if (!$file) {
            return false;
        }

        // delete the file
        if (fileDestroy('private/' . $file->file_name)) {
            return (bool)$query->delete();
        }

        return true;
    }

    /**
     * File types
     *
     * @return array
     */
    public function types()
    {
        return ['video', 'photo', 'doc', 'file', 'audio'];
    }

    /**
     * Helper: Get filename
     *
     * @param $id
     * @param $table
     * @param null $tag
     *
     * @return null
     */
    public function lookForFilename($id, $table, $tag = NULL)
    {
        $file = $this->lookForFile($id, $table, $tag);
        return $file->exists ? $file->info->file_name : null;
    }

    /**
     * Helper: Get file details
     *
     * @param $id
     * @param $table
     * @param null $tag
     *
     * @param null $default
     * @return mixed
     */
    public function lookForFile($id, $table, $tag = NULL, $default = NULL)
    {
        $file = File::where('table_name', $table)->where('table_id', $id);

        if ($tag) {
            $file->where('tag', $tag);
        }

        $file = $file->orderBy('created_at', 'DESC')->first();
        if ($file) {
            $this->dataFormatting($file);
        }

        $object = new \stdClass();
        $object->exists = $file ? TRUE : FALSE;
        $object->primary = $file ? fetchImage($file->file_name, $default) : fetchImage(NULL, $default);
        $object->meta = $file;
        return $object;
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        if ($row->type === 'photo') {
            $row->cover = fetchImage($row->file_name);
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
        } else if ($row->type === 'photo' && $this->hasParams('to_base64_img') == 1) {
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
