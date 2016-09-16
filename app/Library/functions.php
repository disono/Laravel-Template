<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
include_once 'methods.php';

/*
 * --------------------------------------------------------------------------
 * Add only method here
 * --------------------------------------------------------------------------
 */
if (!function_exists('db_filter_id')) {
    /**
     * Filter all id
     *
     * @param array $data
     * @param null $column_name
     * @return array
     */
    function db_filter_id($data = [], $column_name = null)
    {
        return App\Library\Helpers\WBDBHelper::filterID($data, $column_name);
    }
}

if (!function_exists('paginate')) {
    /**
     * Custom pagination
     *
     * @param $collections
     * @param int $pagination_num
     * @return \Illuminate\Pagination\Paginator
     */
    function paginate($collections, $pagination_num = 0)
    {
        return App\Library\Helpers\WBDBHelper::paginate($collections, $pagination_num);
    }
}

if (!function_exists('exclude_slug')) {
    /**
     * Exclude slugs name
     *
     * @return string
     */
    function exclude_slug()
    {
        return App\Library\Helpers\WBDBHelper::excludeSlug();
    }
}

/*
 * --------------------------------------------------------------------------
 * WBHttp method
 * --------------------------------------------------------------------------
 */
if (!function_exists('success_json_response')) {
    /**
     * Success JSON response
     *
     * @param array $data
     * @param null $links
     * @param null $pagination
     * @param null $extra
     * @return \Illuminate\Http\JsonResponse
     */
    function success_json_response($data = [], $links = null, $pagination = null, $extra = null)
    {
        return App\Library\Helpers\WBHttp::successJSONResponse($data, $links, $pagination, $extra);
    }
}

if (!function_exists('failed_json_response')) {
    /**
     * Failed JSON response
     *
     * @param array $errors
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    function failed_json_response($errors = [], $status = 422)
    {
        return App\Library\Helpers\WBHttp::failedJSONResponse($errors, $status);
    }
}

if (!function_exists('download_image')) {
    /**
     * Download image from remote
     *
     * @param $file_name
     * @param $url
     * @param int $quality
     * @return bool
     */
    function download_image($file_name, $url = null, $quality = 75)
    {
        return App\Library\Helpers\WBHttp::downloadImage($file_name, $url, $quality);
    }
}

if (!function_exists('get_ip_address')) {
    /**
     * Get user IP Address
     *
     * @return mixed
     */
    function get_ip_address()
    {
        return App\Library\Helpers\WBHttp::ip();
    }
}

if (!function_exists('get_user_agent')) {
    /**
     * Get user user agent
     *
     * @return mixed
     */
    function get_user_agent()
    {
        return App\Library\Helpers\WBHttp::userAgent();
    }
}

/*
 * --------------------------------------------------------------------------
 * WBFile method
 * --------------------------------------------------------------------------
 */
if (!function_exists('delete_file')) {
    /**
     * Delete file
     *
     * @param $path
     * @return bool
     */
    function delete_file($path)
    {
        return App\Library\Helpers\WBFile::delete($path);
    }
}

if (!function_exists('filename_creator')) {
    /**
     * File name creator
     *
     * @return string
     */
    function filename_creator()
    {
        return App\Library\Helpers\WBFile::filenameCreator();
    }
}

if (!function_exists('error_logger')) {
    /**
     * File name creator
     *
     * @param null $message
     */
    function error_logger($message = null)
    {
        App\Library\Helpers\WBFile::errorLogger($message);
    }
}

if (!function_exists('get_image')) {
    /**
     * Get image
     *
     * @param $source
     * @param null $type
     * @return string
     */
    function get_image($source, $type = null)
    {
        return App\Library\Helpers\WBFile::getImg($source, $type);
    }
}

if (!function_exists('encode_base64_image')) {
    /**
     * Create base64 image (Local file only)
     *
     * @param $filename
     * @param $filetype
     * @return null|string
     */
    function encode_base64_image($filename, $filetype = 'png')
    {
        return App\Library\Helpers\WBFile::encodeBASE64Image($filename, $filetype);
    }
}

if (!function_exists('upload_image')) {
    /**
     * Upload image
     *
     * @param $file
     * @param array $image_options
     * @param null $old_file
     * @param string $destinationPath
     * @param bool $nameOnly
     * @return string
     */
    function upload_image($file, $image_options = [], $old_file = null, $destinationPath = 'private/img', $nameOnly = false)
    {
        return App\Library\Helpers\WBFile::uploadImage($file, $image_options, $old_file, $destinationPath, $nameOnly);
    }
}

if (!function_exists('upload_image_only')) {
    /**
     * Upload image only returns filename
     *
     * @param $file
     * @param null $old_file
     * @param $destinationPath
     * @return string
     */
    function upload_image_only($file, $old_file = null, $destinationPath = 'private/img')
    {
        return upload_image($file, [], $old_file, $destinationPath, true);
    }
}

/*
 * --------------------------------------------------------------------------
 * WBFormat method
 * --------------------------------------------------------------------------
 */
if (!function_exists('money')) {
    /**
     * Money formatted
     *
     * @param $number
     * @param string $sign
     * @return string
     */
    function money($number, $sign = '&#8369;')
    {
        return App\Library\Helpers\WBFormat::money($number, $sign);
    }
}

/*
 * --------------------------------------------------------------------------
 * WBConfig method
 * --------------------------------------------------------------------------
 */
if (!function_exists('app_settings')) {
    /**
     * Get application settings
     *
     * @param $key
     * @return mixed
     */
    function app_settings($key)
    {
        return App\Library\Helpers\WBConfig::settings($key);
    }
}

if (!function_exists('app_header')) {
    /**
     * HTML header options
     *
     * @params string $get_header
     * @param $get_header
     * @return string $header_top
     */
    function app_header($get_header)
    {
        return App\Library\Helpers\WBConfig::header($get_header);
    }
}

if (!function_exists('config_per_page')) {
    /**
     * Pagination number of items per page
     *
     * @return integer
     */
    function config_per_page()
    {
        return App\Library\Helpers\WBConfig::perPage();
    }
}

if (!function_exists('config_file_size')) {
    /**
     * Default file sizes
     *
     * @param string $type
     * @return int
     */
    function config_file_size($type = 'image')
    {
        return App\Library\Helpers\WBConfig::fileSize($type);
    }
}

if (!function_exists('config_img_quality')) {
    /**
     * Reduce quality to 75% if file-size is more than 3MB
     *
     * @param $file_size
     * @param int $quality
     * @return int|null
     */
    function config_img_quality($file_size, $quality = 85)
    {
        return App\Library\Helpers\WBConfig::imageQuality($file_size, $quality);
    }
}

if (!function_exists('config_min_age')) {
    /**
     * Minimum age limit
     *
     * @return int
     */
    function config_min_age()
    {
        return App\Library\Helpers\WBConfig::minAge();
    }
}

if (!function_exists('config_max_age')) {
    /**
     * Maximum age limit
     *
     * @return int
     */
    function config_max_age()
    {
        return App\Library\Helpers\WBConfig::maxAge();
    }
}

/*
 * --------------------------------------------------------------------------
 * WBDate method
 * --------------------------------------------------------------------------
 */

if (!function_exists('sql_date')) {
    /**
     * Sql date formatter
     *
     * @param null $date
     * @param bool $date_only
     * @return bool|string
     */
    function sql_date($date = null, $date_only = false)
    {
        return App\Library\Helpers\WBDate::sqlDate($date, $date_only);
    }
}

if (!function_exists('sql_time')) {
    /**
     * Sql time formatter
     *
     * @param null $date
     * @return bool|string
     */
    function sql_time($date = null)
    {
        return App\Library\Helpers\WBDate::sqlTime($date);
    }
}

if (!function_exists('human_date')) {
    /**
     * Human readable date and time
     *
     * @param null $date
     * @param bool|false $date_only
     * @return bool|string
     */
    function human_date($date = null, $date_only = false)
    {
        return App\Library\Helpers\WBDate::humanDate($date, $date_only);
    }
}

if (!function_exists('date_formatting')) {
    /**
     * Format date and time
     *
     * @param $date
     * @return array
     */
    function date_formatting($date)
    {
        return App\Library\Helpers\WBDate::formatDate($date);
    }
}

if (!function_exists('count_years')) {
    /**
     * Expired at
     *
     * @param int $minute
     * @return bool|string
     */
    function expired_at($minute = 120)
    {
        return App\Library\Helpers\WBDate::expiredAt($minute);
    }
}

if (!function_exists('count_years')) {
    /**
     * Count years
     *
     * @param $then
     * @param null $current
     * @return integer
     */
    function count_years($then, $current = null)
    {
        return App\Library\Helpers\WBDate::countYears($then, $current);
    }
}

/*
 * --------------------------------------------------------------------------
 * WBView method
 * --------------------------------------------------------------------------
 */
if (!function_exists('theme')) {
    /**
     * Theme
     *
     * @param null $file
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function theme($file = null, $data = [])
    {
        return App\Library\Helpers\WBView::theme($file, $data);
    }
}

if (!function_exists('current_theme')) {
    /**
     * Check for current theme
     *
     * @return string
     */
    function current_theme()
    {
        return App\Library\Helpers\WBView::current_theme();
    }
}

if (!function_exists('admin_view')) {
    /**
     * Admin view
     *
     * @param null $file
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function admin_view($file = null, $data = [])
    {
        return App\Library\Helpers\WBView::admin_view($file, $data);
    }
}

/*
 * --------------------------------------------------------------------------
 * WBUrl method
 * --------------------------------------------------------------------------
 */
if (!function_exists('url_ext')) {
    /**
     * For dev purposes only (url)
     *
     * @return integer
     */
    function url_ext()
    {
        return App\Library\Helpers\WBUrl::randomURLExtension();
    }
}

if (!function_exists('url_title')) {
    /**
     * Create url from string e.g my url to this => my-url-to-this
     *
     * @param $str
     * @param $separator
     * @param $lowercase
     * @return string
     */
    function url_title($str, $separator = '-', $lowercase = true)
    {
        return App\Library\Helpers\WBUrl::urlTitle($str, $separator, $lowercase);
    }
}

if (!function_exists('profile_url')) {
    /**
     * Create user profile URL
     *
     * @param $id
     * @return string
     */
    function profile_url($id)
    {
        return App\Library\Helpers\WBUrl::profileUrl($id);
    }
}

if (!function_exists('active_url')) {
    /**
     * Is current url active
     *
     * @param null $url
     * @return string
     */
    function active_url($url)
    {
        return App\Library\Helpers\WBUrl::activeUrl($url);
    }
}

/*
 * --------------------------------------------------------------------------
 * WBAuth method
 * --------------------------------------------------------------------------
 */
if (!function_exists('me')) {
    /**
     * Get authenticated user
     *
     * @return null
     */
    function me()
    {
        return App\Library\Helpers\WBAuth::authUser();
    }
}

if (!function_exists('authorize_me')) {
    /**
     * Authorization on routes
     *
     * @param array $roles
     * @param $user_id
     * @param bool $route_check
     * @return mixed
     */
    function authorize_me($roles = [], $user_id, $route_check = true)
    {
        return App\Library\Helpers\WBAuth::authorizeMe($roles, $user_id, $route_check);
    }
}

if (!function_exists('authorize_route')) {
    /**
     * Authorize routes
     *
     * @param int $user_id
     * @param bool $abort
     * @return bool
     */
    function authorize_route($user_id = 0, $abort = true)
    {
        return App\Library\Helpers\WBAuth::authorizeRoute($user_id, $abort);
    }
}

if (!function_exists('api_auth')) {
    /**
     * Check if user exists
     *
     * @param string $input_name
     * @return bool
     */
    function api_auth($input_name = 'authenticated_id')
    {
        return App\Library\Helpers\WBAuth::APICheckAuth($input_name);
    }
}

if (!function_exists('api_auth_jwt')) {
    /**
     * Check if user has token
     *
     * @param bool $response
     * @return string
     */
    function api_auth_jwt($response = false)
    {
        return App\Library\Helpers\WBAuth::APIAuthenticateJWT($response);
    }
}

if (!function_exists('init_token_key')) {
    /**
     * Initialize token key
     */
    function init_token_key()
    {
        App\Library\Helpers\WBAuth::initializeTokenKey();
    }
}

if (!function_exists('resource_authorize')) {
    /**
     * Is resource authorize
     *
     * @param $user
     * @return bool
     */
    function resource_authorize($user)
    {
        return App\Library\Helpers\WBAuth::resourceAuthorize($user);
    }
}

/*
 * --------------------------------------------------------------------------
 * WBMessaging method
 * --------------------------------------------------------------------------
 */
if (!function_exists('sendSMS')) {
    /**
     * Send SMS
     *
     * @param $number
     * @param $message
     * @return bool
     */
    function sendSMS($number, $message)
    {
        return App\Library\Helpers\WBMessaging::SMS($number, $message);
    }
}