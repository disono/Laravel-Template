<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */
use SimpleSoftwareIO\QrCode\Facades\QrCode;

if (!function_exists('is_selected')) {
    /**
     * Is item selected
     *
     * @param $item
     * @param $data
     * @return null|string
     */
    function is_selected($item, $data)
    {
        return ($item == $data) ? 'selected' : null;
    }
}

if (!function_exists('has_input')) {
    /**
     * Check input with data
     *
     * @param array $data
     * @param $name
     * @return array
     */
    function has_input($data, $name)
    {
        if (old($name)) {
            return old($name);
        }

        if (is_object($data)) {
            return $data->$name;
        }

        if (is_array($data)) {
            return $data[$name];
        }

        return null;
    }
}

if (!function_exists('generate_qr')) {
    /**
     * Generate QR-Code
     *
     * @param null $data
     * @return string
     */
    function generate_qr($data = null)
    {
        return 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(100)->generate($data));
    }
}

if (!function_exists('is_img_data_base64')) {
    /**
     * Check if image is base64
     *
     * @param null $data
     * @param bool $append
     * @return bool|null|string
     */
    function is_img_data_base64($data = null, $append = false)
    {
        if (!$append) {
            return (strpos($data, 'data:image/png;base64,') !== false || strpos($data, 'data:image/jpeg;base64,') !== false) ? true : false;
        }

        if ((strpos($data, 'data:image/png;base64,') === true)) {
            return 'data:image/png;base64,' . $data;
        } else if ((strpos($data, 'data:image/jpeg;base64,') === true)) {
            return 'data:image/jpeg;base64,' . $data;
        } else {
            return $data;
        }
    }
}

if (!function_exists('app_title')) {
    /**
     * Formatting header title
     *
     * @param $title
     * @return string
     */
    function app_title($title)
    {
        return ' - ' . ucfirst($title);
    }
}

if (!function_exists('rand_token')) {
    /**
     * Create random token
     *
     * @return string
     */
    function rand_token()
    {
        return str_random(64) . time();
    }
}

if (!function_exists('authenticated_id')) {
    /**
     * Get the authenticated ID
     *
     * @return array|\Illuminate\Http\Request|string
     */
    function authenticated_id()
    {
        return (request()->header('authenticated_id')) ? request()->header('authenticated_id') : request('authenticated_id', 0);
    }
}

if (!function_exists('get_request_value')) {
    /**
     * Get request value
     *
     * @param $key
     * @param null $default
     * @return array|\Illuminate\Http\Request|string
     */
    function get_request_value($key, $default = null)
    {
        return (request()->header($key)) ? request()->header($key) : request($key, $default);
    }
}

if (!function_exists('request_value')) {
    /**
     * Get request value
     *
     * @param $request
     * @param $name
     * @param string $default
     * @param bool $encrypt
     * @return string
     */
    function request_value($request, $name, $default = '', $encrypt = false)
    {
        $value = ($request->get($name) != null) ? clean($request->get($name)) : $default;
        return ($encrypt && $value) ? bcrypt($value) : $value;
    }
}