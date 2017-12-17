<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MediaFile extends AppModel
{
    protected static $table_name = 'media_files';
    protected static $writable_columns = [
        'user_id',
        'source_id', 'source_type',
        'title', 'description',
        'file_name', 'file_type', 'file_ext'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writable_columns);
        parent::__construct($attributes);
    }

    /**
     * Get single data
     *
     * @param $id
     * @param string $column
     * @return null
     */
    public static function single($id, $column = 'id')
    {
        if (!$id) {
            return null;
        }
        return self::fetch([
            'single' => true,
            $column => $id
        ]);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function fetch($params = [])
    {
        $tn = self::$table_name;
        $select[] = $tn . '.*';
        $select[] = DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name');
        $query = self::select($select);

        // user
        $query->join('users', $tn . '.user_id', '=', 'users.id');

        // where equal
        $query = self::_whereEqual($query, $params, self::$writable_columns, $tn);

        // exclude and include
        $query = self::_excInc($query, $params, self::$writable_columns, $tn);

        // search
        $query = self::_search($query, $params, self::$writable_columns, $tn);

        $query->orderBy('created_at', 'DESC');

        return static::_readyFormatting($params, $query);
    }

    /**
     * Get all data no pagination
     *
     * @param array $params
     * @return null
     */
    public static function fetchAll($params = [])
    {
        $params['all'] = true;
        return self::fetch($params);
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    public static function _dataFormatting($row)
    {
        // file path
        $row->path = ($row->file_type == 'video') ? url('stream/video/' . $row->file_name) : url('private/' . $row->file_name);

        // icon
        $row->icon = url('assets/img/icons/' . $row->file_type . '.png');

        return $row;
    }

    /**
     * Store new data
     *
     * @param array $inputs
     * @return bool
     */
    public static function store($inputs = [])
    {
        $store = [];

        // process file types
        $inputs = self::_processFileUpload($inputs);

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                $store[$key] = $value;
            }
        }

        $store['created_at'] = sql_date();
        return (int)self::insertGetId($store);
    }

    /**
     * Delete source
     *
     * @param $source_id
     * @param $source_type
     * @throws \Exception
     */
    public static function destroySource($source_id, $source_type)
    {
        $image = self::where('source_id', $source_id)->where('source_type', $source_type)->get();

        foreach ($image as $row) {
            self::remove($row->id);
        }
    }

    /**
     * Delete data
     *
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function remove($id)
    {
        $file = self::find($id);

        if ($file) {
            self::_deleteFile($file->file_name);
        }

        return (bool)self::destroy($id);
    }

    /**
     * Multiple delete
     *
     * @param $source_id
     * @param $source_type
     * @return bool
     * @throws \Exception
     */
    public static function batchRemove($source_id, $source_type)
    {
        $success = true;
        $files = self::getAll([
            'source_id' => $source_id,
            'source_type' => $source_type
        ]);

        foreach ($files as $row) {
            if (!self::remove($row->id)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Delete image
     *
     * @param $file
     */
    private static function _deleteFile($file)
    {
        delete_file('private/' . $file);
    }

    /**
     * Process uploads
     *
     * @param $inputs
     * @return mixed
     */
    public static function _processFileUpload($inputs)
    {
        if (!isset($inputs['file'])) {
            return $inputs;
        }

        // processing file
        $file = $inputs['file'];
        $file_name = upload_any_file($file, 'private');

        if ($file_name && $file) {
            $file_type = 'others';
            $ext = $file->getClientOriginalExtension();

            if (in_array($ext, ['mp4', '3gp'])) {
                $file_type = 'video';
            } else if (in_array($ext, ['doc', 'docx', 'pdf'])) {
                $file_type = 'document';
            } else if (in_array($ext, ['zip', 'rar'])) {
                $file_type = 'archive';
            } else if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $file_type = 'image';
            }

            $inputs['file_ext'] = $ext;
            $inputs['file_name'] = $file_name;
            $inputs['file_type'] = $file_type;
        }

        return $inputs;
    }
}
