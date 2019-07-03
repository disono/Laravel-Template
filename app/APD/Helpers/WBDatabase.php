<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

if (!function_exists('dbArrayColumns')) {
    /**
     * Get database column values only
     *
     * @param $data
     * @param string $columnName
     * @return array
     */
    function dbArrayColumns($data, $columnName = 'id')
    {
        $_data = [];
        foreach ($data as $row) {
            $_data[] = $row->$columnName;
        }

        return $_data;
    }
}

if (!function_exists('dbCleanInput')) {
    /**
     * Clean input
     *
     * @param $input
     * @return mixed
     */
    function dbCleanInput($input)
    {
        if (!$input) {
            return $input;
        }

        return is_string($input) ? filter_var($input, FILTER_SANITIZE_STRING) : $input;
    }
}