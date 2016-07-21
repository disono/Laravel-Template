<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_category_id', 'user_id', 'name', 'slug', 'content', 'template', 'start_date', 'start_time', 'end_date', 'end_time'
    ];

    private static $params;

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function get($params = [])
    {
        $select[] = 'pages.*';

        $select[] = DB::raw('page_categories.name as category_name, page_categories.description as category_description, page_categories.slug as category_slug');

        $query = self::select($select)
            ->join('page_categories', 'pages.page_category_id', '=', 'page_categories.id');

        if (isset($params['id'])) {
            $query->where('pages.id', $params['id']);
        }

        if (isset($params['user_id'])) {
            $query->where('pages.user_id', $params['user_id']);
        }

        if (isset($params['page_category_id'])) {
            $query->where('pages.page_category_id', $params['page_category_id']);
        }

        if (isset($params['slug'])) {
            $query->where('pages.slug', $params['slug']);
        }

        if (isset($params['category_slug'])) {
            $query->where('page_categories.slug', $params['category_slug']);
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->Where(function ($query) {
                $query->where('pages.name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('pages.slug', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('pages.content', 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        if (isset($params['object'])) {
            return $query;
        } else {
            if (isset($params['single'])) {
                return self::_format($query->first(), $params);
            } else if (isset($params['all'])) {
                return self::_format($query->get(), $params);
            } else {
                $query = paginate($query);

                return self::_format($query, $params);
            }
        }
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

        return self::get([
            'single' => true,
            $column => $id
        ]);
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
        $columns = [
            'page_category_id', 'user_id', 'name', 'slug', 'content', 'template', 'start_date', 'start_time', 'end_date', 'end_time'
        ];

        foreach ($inputs as $key => $value) {
            if (in_array($key, $columns)) {
                $store = self::_values($store, $value, $key);
            }
        }

        $store['created_at'] = sql_date();
        $id = (int)self::insertGetId($store);
        
        // upload cover
        self::_uploadImage($id, $inputs);
        
        return $id;
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
        // delete all related images to page
        Image::destroySource($id, 'page');

        return (bool)self::destroy($id);
    }

    /**
     * Update data
     *
     * @param $id
     * @param array $inputs
     * @param null $column_name
     * @return bool
     */
    public static function edit($id, $inputs = [], $column_name = null)
    {
        $update = [];
        $query = null;
        $columns = [
            'page_category_id', 'name', 'slug', 'content', 'template', 'start_date', 'start_time', 'end_date', 'end_time'
        ];

        if (!$column_name) {
            $column_name = 'id';
        }

        if ($id && !is_array($column_name)) {
            $query = self::where($column_name, $id);
        } else {
            $i = 0;
            foreach ($column_name as $key => $value) {
                if (!in_array($key, $columns)) {
                    return false;
                }
                if (!$i) {
                    $query = self::where($key, $value);
                } else {
                    if ($query) {
                        $query->where($key, $value);
                    }
                }
                $i++;
            }
        }

        foreach ($inputs as $key => $value) {
            if (in_array($key, $columns)) {
                $update = self::_values($update, $value, $key);
            }
        }

        // upload cover
        self::_uploadImage($id, $inputs);

        return (bool)$query->update($update);
    }

    /**
     * Check for values
     *
     * @param $values
     * @param $value
     * @param $key
     * @return mixed
     */
    private static function _values($values, $value, $key) {
        if (!is_numeric($value)) {
            if ($value) {
                if ($key == 'start_date') {
                    $values[$key] = sql_date($value, true);
                } else if ($key == 'start_time') {
                    $values[$key] = sql_time($value);
                } else if ($key == 'end_date') {
                    $values[$key] = sql_date($value, true);
                } else if ($key == 'end_time') {
                    $values[$key] = sql_time($value);
                } else {
                    $values[$key] = $value;
                }
            }
        } else {
            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * Upload image
     *
     * @param $id
     * @param $inputs
     */
    private static function _uploadImage($id, $inputs) {
        if (isset($inputs['image']) && $id) {
            if ($inputs['image']) {
                try {
                    $page = self::single($id);

                    if ($page) {
                        // delete all previous cover
                        $images = Image::get([
                            'type' => 'page',
                            'source_id' => $page->id
                        ]);

                        // delete old cover
                        foreach ($images as $row) {
                            Image::remove($row->id);
                        }

                        // upload new cover
                        upload_image($inputs['image'], [
                            'user_id' => $page->user_id,
                            'source_id' => $page->id,
                            'title' => $page->name,
                            'type' => 'page',
                            'crop_auto' => true
                        ]);
                    }
                } catch (\Exception $e) {
                    error_logger('AdminPageStore:Class' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Add formatting on data
     *
     * @param $query
     * @param array $params
     * @return null
     */
    private static function _format($query, $params = [])
    {
        if (isset($params['single'])) {
            if (!$query) {
                return null;
            }

            // images
            $query->images = Image::getAll([
                'type' => 'page',
                'source_id' => $query->id
            ]);

            // default image
            $query->cover = get_image((count($query->images)) ? $query->images[0]->filename : null);

            // format
            $query->formatted_start_date = ($query->start_date) ? date('F d, Y', strtotime($query->start_date)) : null;
            $query->formatted_end_date = ($query->end_date) ? date('F d, Y', strtotime($query->end_date)) : null;
            $query->formatted_start_time = ($query->start_time) ? date('h:i A', strtotime($query->start_time)) : null;
            $query->formatted_end_time = ($query->end_time) ? date('h:i A', strtotime($query->end_time)) : null;
        } else {
            foreach ($query as $row) {
                // images
                $row->images = Image::getAll([
                    'type' => 'page',
                    'source_id' => $row->id
                ]);

                // default image
                $row->cover = get_image((count($row->images)) ? $row->images[0]->filename : null);
                
                // format
                $row->formatted_start_date = ($row->start_date) ? date('F d, Y', strtotime($row->start_date)) : null;
                $row->formatted_end_date = ($row->end_date) ? date('F d, Y', strtotime($row->end_date)) : null;
                $row->formatted_start_time = ($row->start_time) ? date('h:i A', strtotime($row->start_time)) : null;
                $row->formatted_end_time = ($row->end_time) ? date('h:i A', strtotime($row->end_time)) : null;
            }
        }

        return $query;
    }
}
