<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Models;

use Illuminate\Support\Facades\Schema;

class PageView extends AppModel
{
    protected static $writable_columns = [
        'user_id', 'http_referrer', 'current_url',
        'ip_address', 'browser', 'type', 'source_id'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writable_columns);
        parent::__construct($attributes);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function get($params = [])
    {
        $table_name = (new self)->getTable();
        $select[] = $table_name . '.*';
        $query = self::select($select);

        // where equal
        $query = self::_whereEqual($query, $params, self::$writable_columns, $table_name);

        // exclude and include
        $query = self::_excInc($query, $params, self::$writable_columns, $table_name);

        // search
        $query = self::_search($query, $params, self::$writable_columns, $table_name);

        $query->orderBy('created_at', 'DESC');

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
     * Get all data no pagination
     *
     * @param array $params
     * @return null
     */
    public static function getAll($params = [])
    {
        $params['all'] = true;
        return self::get($params);
    }

    /**
     * Store the page views
     *
     * @param null $type
     * @param null $source_id
     */
    public static function store($type = null, $source_id = null)
    {
        $me = me();

        if (!Schema::hasTable('page_views')) {
            return;
        }

        // check if this user is already visited the site
        if (request()->session()->has('visitor_counter')) {
            return;
        }

        // save the counter
        request()->session()->put('visitor_counter', 'true');

        // insert to database
        self::insert([
            'user_id' => ($me) ? $me->id : 0,
            'http_referrer' => (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null,
            'current_url' => (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : null,
            'ip_address' => get_ip_address(),
            'browser' => (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null,

            'type' => $type,
            'source_id' => $source_id,

            'created_at' => sql_date()
        ]);
    }
}
