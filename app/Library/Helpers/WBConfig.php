<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Library\Helpers;

class WBConfig
{
    /**
     * HTML header options
     *
     * @params string $get_header
     * @param $get_header
     * @return string $header_top
     */
    public static function header($get_header)
    {
        $header_top = array(
            'description' => self::settings('description')->value,
            'keywords' => self::settings('keywords')->value,
            'author' => self::settings('author')->value,
            'title' => self::settings('title')->value
        );

        if (array_key_exists($get_header, $header_top)) {
            return $header_top[$get_header];
        } else {
            return null;
        }
    }

    /**
     * Get application settings
     *
     * @param $key
     * @return mixed
     */
    public static function settings($key)
    {
        $settings = \App\Models\Setting::where('key', $key);

        if ($settings->count()) {
            return $settings->first();
        }

        return (object)[
            'name' => null, 'key' => null, 'value' => null, 'created_at' => null
        ];
    }

    /**
     * Pagination number of items per page
     *
     * @return integer
     */
    public static function perPage()
    {
        return 10;
    }

    /**
     * Default file sizes
     *
     * @param string $type
     * @return int
     */
    public static function fileSize($type = 'image')
    {
        // file-size is on kilobytes
        if ($type == 'image') {
            return 3000;
        } else {
            return 3000;
        }
    }

    /**
     * Reduce quality to 75% if file-size is more than 3MB
     *
     * @param $file_size
     * @param int $quality
     * @return int|null
     */
    public static function imageQuality($file_size, $quality = 85)
    {
        if ($file_size > 3145728) {
            return $quality;
        } else {
            return null;
        }
    }

    /**
     * Minimum age limit
     *
     * @return int
     */
    public static function minAge()
    {
        return 13;
    }

    /**
     * Maximum age limit
     *
     * @return int
     */
    public static function maxAge()
    {
        return 60;
    }
}