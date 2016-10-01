<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
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

if (!function_exists('is_checked')) {
    /**
     * Is item checked
     *
     * @param $item
     * @param $data
     * @return null|string
     */
    function is_checked($item, $data)
    {
        return ($item == $data) ? 'checked' : null;
    }
}

if (!function_exists('filter_id_number')) {
    /**
     * Search for values then return the search value
     *
     * @param $array
     * @param $search
     * @return int
     */
    function array_search_value($array, $search)
    {
        if (!is_array($array)) {
            return 0;
        }

        foreach ($array as $value) {
            if ($value == $search) {
                return $value;
            }
        }

        return 0;
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

if (!function_exists('rand_numbers')) {
    /**
     * Create random numbers
     *
     * @param $digits
     * @return int
     */
    function rand_numbers($digits = 4)
    {
        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
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

if (!function_exists('request_options')) {
    /**
     * Check for request values
     *
     * @param $request
     * @param array $inputs
     * @param array $options
     * @return array
     */
    function request_options($request, $inputs = [], $options = [])
    {
        $values = [];
        foreach ($inputs as $key) {
            if ($request->get($key) !== null && $request->get($key) !== '') {
                $values[$key] = $request->get($key);
            }
        }

        if (count($options)) {
            $values = array_merge($values, $options);
        }

        return $values;
    }
}

if (!function_exists('number_shorten')) {
    /**
     * Shorten number
     *
     * @param $number
     * @param int $precision
     * @param null $divisors
     * @return string
     */
    function number_shorten($number, $precision = 3, $divisors = null)
    {
        // Setup default $divisors if not provided
        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => '',     // 1000^0 == 1
                pow(1000, 1) => 'K',    // Thousand
                pow(1000, 2) => 'M',    // Million
                pow(1000, 3) => 'B',    // Billion
                pow(1000, 4) => 'T',    // Trillion
                pow(1000, 5) => 'Qa',   // Quadrillion
                pow(1000, 6) => 'Qi',   // Quintillion
            );
        }

        // Loop through each $divisor and find the
        // lowest amount that matches
        foreach ($divisors as $divisor => $shorthand) {
            if ($number < ($divisor * 1000)) {
                // We found a match!
                break;
            }
        }

        // We found our match, or there were no matches.
        // Either way, use the last defined value for $divisor.
        return (int)number_format($number / $divisor, $precision) . $shorthand;
    }
}

if (!function_exists('html_app_cache')) {
    /**
     * Application Cache
     *
     * @return string
     */
    function html_app_cache()
    {
        return (env('APP_ENV') === 'local') ? null : 'manifest="' .
            clean(preg_replace('/\s+/', '_', app_settings('title')->value)) . '.cache"';
    }
}

if (!function_exists('access_routes')) {
    /**
     * Access routes
     *
     * @return array
     */
    function access_routes()
    {
        $routes = [];
        $route_names = Illuminate\Support\Facades\Route::getRoutes();

        foreach ($route_names as $value) {
            if ($value->getName()) {
                $routes[$value->getName()] = ucwords(str_replace('_', ' ', str_replace('-', ' ', $value->getName())));
            }
        }

        return $routes;
    }
}

/*
 * --------------------------------------------------------------------------
 * Random DATA for DEMO use ONLY
 * --------------------------------------------------------------------------
 */

if (!function_exists('random_first_names')) {

    function random_first_names()
    {
        $first_names = [
            'Abigail', 'Caroline', 'Dorothy', 'Elizabeth', 'Ella', 'Jasmine', 'Jennifer', 'Julia'
        ];

        $k = array_rand($first_names);
        return $first_names[$k];
    }
}

if (!function_exists('random_last_names')) {

    function random_last_names()
    {
        $last_names = [
            'Abraham', 'Ball', 'Cornish', 'Dowd', 'Hamilton', 'Hardacre', 'Johnston', 'McDonald'
        ];

        $k = array_rand($last_names);
        return $last_names[$k];
    }
}

if (!function_exists('random_middle_names')) {

    function random_middle_names()
    {
        $last_names = [
            'Abraham', 'Ball', 'Cornish', 'Dowd', 'Hamilton', 'Hardacre', 'Johnston', 'McDonald'
        ];

        $k = array_rand($last_names);
        return $last_names[$k];
    }
}