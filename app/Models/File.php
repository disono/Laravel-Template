<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class File extends BaseModel
{
    // types: video, photo, doc, file

    protected static $tableName = 'files';
    protected static $writableColumns = [
        'user_id', 'file_name', 'type', 'ext', 'title', 'description',
        'table_name', 'table_id', 'tag'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Delete data
     *
     * @param $id
     * @param null $columnName
     *
     * @return bool
     */
    public static function remove($id, $columnName = null)
    {
        $query = self::rawFetch($id, $columnName);

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
    protected static function dataFormatting($row)
    {
        if ($row->type === 'photo') {
            $row->cover = fetchImage($row->file_name, 'assets/img/placeholders/no_image.png');
        } else if ($row->type === 'video') {
            $row->cover = url('assets/img/placeholders/video.png');
        } else if ($row->type === 'doc') {
            $row->cover = url('assets/img/placeholders/document.png');
        } else if ($row->type === 'file') {
            $row->cover = url('assets/img/placeholders/file.png');
        }

        $row->path = url('private/' . $row->file_name);

        return $row;
    }
}
