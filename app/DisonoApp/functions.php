<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

include_once 'methods.php';
include_once 'database.php';
include_once 'ecommerce.php';

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
        return App\DisonoApp\Helpers\WBDatabase::filterID($data, $column_name);
    }
}

if (!function_exists('paginate')) {
    /**
     * Custom pagination
     *
     * @param $collections
     * @param int $pagination_num
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function paginate($collections, $pagination_num = 0)
    {
        return App\DisonoApp\Helpers\WBDatabase::paginate($collections, $pagination_num);
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
        return App\DisonoApp\Helpers\WBDatabase::excludeSlug();
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
        return App\DisonoApp\Helpers\WBHttp::successJSONResponse($data, $links, $pagination, $extra);
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
        return App\DisonoApp\Helpers\WBHttp::failedJSONResponse($errors, $status);
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
        return App\DisonoApp\Helpers\WBHttp::downloadImage($file_name, $url, $quality);
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
        return App\DisonoApp\Helpers\WBHttp::ip();
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
        return App\DisonoApp\Helpers\WBHttp::userAgent();
    }
}

if (!function_exists('node_connector')) {
    /**
     * NodeJS Connector
     *
     * @param $path
     *
     * @return mixed
     */
    function node_connector($path)
    {
        return App\DisonoApp\Helpers\WBHttp::NodeJSConnector($path);
    }
}

if (!function_exists('socket_emit')) {
    /**
     * SocketIO emit
     *
     * @param $name
     * @param array $data
     * @param null $uri
     */
    function socket_emit($name, $data = [], $uri = null)
    {
        App\DisonoApp\Helpers\WBHttp::SocketIOEmit($name, $data, $uri);
    }
}

if (!function_exists('fcm_send')) {
    /**
     * FCM send
     *
     * @param $token
     * @param $title
     * @param $body
     * @param string $sound
     *
     * @return bool
     */
    function fcm_send($token, $title, $body, $sound = 'default')
    {
        return App\DisonoApp\Helpers\WBHttp::FCMSend($token, $title, $body, $sound);
    }
}

if (!function_exists('fcm_topic')) {
    /**
     * FCM topic
     *
     * @param $topic_name
     * @param $title
     * @param $body
     * @param string $sound
     * @return mixed
     */
    function fcm_topic($topic_name, $title, $body, $sound = 'default')
    {
        return App\DisonoApp\Helpers\WBHttp::FCMTopic($topic_name, $title, $body, $sound);
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
     *
     * @return bool
     */
    function delete_file($path)
    {
        return App\DisonoApp\Helpers\WBFile::delete($path);
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
        return App\DisonoApp\Helpers\WBFile::filenameCreator();
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
        App\DisonoApp\Helpers\WBFile::errorLogger($message);
    }
}

if (!function_exists('get_image')) {
    /**
     * Get image
     *
     * @param $source
     * @param null $type
     * @param bool $path_only
     * @return string
     */
    function get_image($source, $type = null, $path_only = false)
    {
        return App\DisonoApp\Helpers\WBFile::getImg($source, $type, $path_only);
    }
}

if (!function_exists('encode_base64_image')) {
    /**
     * Create base64 image (Local file only)
     *
     * @param $filename
     * @param $filetype
     *
     * @return null|string
     */
    function encode_base64_image($filename, $filetype = 'png')
    {
        return App\DisonoApp\Helpers\WBFile::encodeBASE64Image($filename, $filetype);
    }
}

if (!function_exists('upload_any_file')) {
    /**
     * Upload any file
     *
     * @param $file
     * @param $destinationPath
     * @param null $old_file
     *
     * @return string
     */
    function upload_any_file($file, $destinationPath = 'private/any', $old_file = null)
    {
        return App\DisonoApp\Helpers\WBFile::uploadFile($file, $destinationPath, $old_file);
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
     *
     * @return string
     */
    function upload_image($file, $image_options = [], $old_file = null, $destinationPath = 'private/img', $nameOnly = false)
    {
        return App\DisonoApp\Helpers\WBFile::uploadImage($file, $image_options, $old_file, $destinationPath, $nameOnly);
    }
}

if (!function_exists('create_folder')) {
    /**
     * Create folder
     *
     * @param $path
     *
     * @return bool
     */
    function create_folder($path)
    {
        return App\DisonoApp\Helpers\WBFile::createFolder($path);
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
        return App\DisonoApp\Helpers\WBFormat::money($number, $sign);
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
        return App\DisonoApp\Helpers\WBConfig::settings($key);
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
        return App\DisonoApp\Helpers\WBConfig::header($get_header);
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
        return App\DisonoApp\Helpers\WBConfig::perPage();
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
        return App\DisonoApp\Helpers\WBConfig::fileSize($type);
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
        return App\DisonoApp\Helpers\WBConfig::imageQuality($file_size, $quality);
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
        return App\DisonoApp\Helpers\WBConfig::minAge();
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
        return App\DisonoApp\Helpers\WBConfig::maxAge();
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
        return App\DisonoApp\Helpers\WBDate::sqlDate($date, $date_only);
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
        return App\DisonoApp\Helpers\WBDate::sqlTime($date);
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
        return App\DisonoApp\Helpers\WBDate::humanDate($date, $date_only);
    }
}

if (!function_exists('human_time')) {
    /**
     * Human readable time
     *
     * @param null $time
     * @return false|string
     */
    function human_time($time = null)
    {
        return App\DisonoApp\Helpers\WBDate::humanTime($time);
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
        return App\DisonoApp\Helpers\WBDate::formatDate($date);
    }
}

if (!function_exists('expired_at')) {
    /**
     * Expired at
     *
     * @param int $minute
     * @return bool|string
     */
    function expired_at($minute = 120)
    {
        return App\DisonoApp\Helpers\WBDate::expiredAt($minute);
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
        return App\DisonoApp\Helpers\WBDate::countYears($then, $current);
    }
}

if (!function_exists('count_hours')) {
    /**
     * Count hours
     *
     * @param $start
     * @param $end
     * @return integer
     */
    function count_hours($start, $end)
    {
        return App\DisonoApp\Helpers\WBDate::countHours($start, $end);
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
        return App\DisonoApp\Helpers\WBView::theme($file, $data);
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
        return App\DisonoApp\Helpers\WBView::current_theme();
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
        return App\DisonoApp\Helpers\WBView::admin_view($file, $data);
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
        return App\DisonoApp\Helpers\WBUrl::randomURLExtension();
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
        return App\DisonoApp\Helpers\WBUrl::urlTitle($str, $separator, $lowercase);
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
        return App\DisonoApp\Helpers\WBUrl::profileUrl($id);
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
        return App\DisonoApp\Helpers\WBUrl::activeUrl($url);
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
        return App\DisonoApp\Helpers\WBAuth::authUser();
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
        return App\DisonoApp\Helpers\WBAuth::authorizeMe($roles, $user_id, $route_check);
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
        return App\DisonoApp\Helpers\WBAuth::authorizeRoute($user_id, $abort);
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
        return App\DisonoApp\Helpers\WBAuth::APICheckAuth($input_name);
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
        return App\DisonoApp\Helpers\WBAuth::APIAuthenticateJWT($response);
    }
}

if (!function_exists('init_token_key')) {
    /**
     * Initialize token key
     */
    function init_token_key()
    {
        App\DisonoApp\Helpers\WBAuth::initializeTokenKey();
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
        return App\DisonoApp\Helpers\WBAuth::resourceAuthorize($user);
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
    function send_sms($number, $message)
    {
        return App\DisonoApp\Helpers\WBMessaging::SMS($number, $message);
    }
}