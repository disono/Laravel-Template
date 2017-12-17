<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\APDApp\Helpers;

use App\Models\Setting;

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
        $settings = Setting::single($key, 'key');

        if (!$settings) {
            return (object)[
                'name' => null, 'key' => null, 'value' => null, 'created_at' => null
            ];
        }

        return $settings;
    }

    /**
     * Pagination number of items per page
     *
     * @return integer
     */
    public static function perPage()
    {
        return (int)self::settings('pagination')->value;
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
            return (int)self::settings('file_size_limit_image')->value;
        } else {
            return (int)self::settings('file_size_limit')->value;
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
        return (int)self::settings('minimum_age_for_registration')->value;
    }

    /**
     * Maximum age limit
     *
     * @return int
     */
    public static function maxAge()
    {
        return (int)self::settings('maximum_age_for_registration')->value;
    }
}