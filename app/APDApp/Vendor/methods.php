<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

if (!function_exists('list_defined_functions')) {
    /**
     * list all user defined functions
     *
     * @return string
     */
    function list_defined_functions()
    {
        $func = get_defined_functions();

        $view = '';
        foreach ($func['user'] as $row) {
            $view .= $row . '<br>';

            // laravel defined functions remove all
            if ($row == 'with') {
                $view = '';
            }
        }

        return $view;
    }
}

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
        return 'data:image/png;base64,' . base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->generate($data));
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
     * @param string $delimiter
     * @return string
     */
    function app_title($title, $delimiter = ' - ')
    {
        return $delimiter . ucfirst($title);
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

if (!function_exists('str_random_db')) {
    /**
     * check if random string does not exists on database
     *
     * @param $table
     * @param $column_name
     * @param int $length
     * @return null|string
     */
    function str_random_db($table, $column_name, $length = 32)
    {
        $checker = true;
        $rnd = null;

        while ($checker) {
            $rnd = str_random($length);

            $query = Illuminate\Support\Facades\DB::table($table)
                ->where($column_name, $rnd);

            if (!$query->count()) {
                $checker = false;
            }
        }

        return $rnd;
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
        $id = (auth()->check()) ? auth()->user()->id : 0;
        return (int)(request()->header('authenticated_id')) ? request()->header('authenticated_id') : request('authenticated_id', $id);
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
     * @param array $inputs
     * @param array $default_values
     * @return array
     */
    function request_options($inputs = [], $default_values = [])
    {
        $values = [];
        $request = request();

        // if inputs is strings
        if (is_string($inputs)) {
            $inputs = explode('|', $inputs);
        }

        if ($inputs) {
            foreach ($inputs as $key) {
                if ($request->get($key) !== null && $request->get($key) !== '') {
                    $values[$key] = $request->get($key);
                }
            }
        }

        if (count($default_values)) {
            $values = array_merge($values, $default_values);
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
        // make sure the web cache is enabled on environment
        if (env('WEB_CACHE') !== true) {
            return null;
        }

        $path_cache = clean(preg_replace('/\s+/', '_', app_settings('title')->value)) . '.cache';
        $env_mode = env('APP_ENV');

        // check cache exists
        if (!file_exists($path_cache) && $env_mode != 'local' && $env_mode) {
            $cache_create = fopen($path_cache, "w");
            fclose($cache_create);

            $cache_create = fopen($path_cache, "w");
            $txt = "CACHE MANIFEST\n";
            $txt .= "# " . $path_cache . "\n";

            $txt .= "CACHE:\n";
            $txt .= "/assets/js/vendor.js\n";
            $txt .= "/assets/js/lib/helper.js\n";
            $txt .= "/assets/js/main.js\n";
            $txt .= "/assets/js/app.js\n";
            $txt .= "/assets/js/admin/main.js\n";

            $txt .= "\n";
            $txt .= "NETWORK:\n";
            $txt .= "*\n";

            fwrite($cache_create, $txt);
            fclose($cache_create);
        }

        return ($env_mode != 'local' && $env_mode) ? 'manifest="/' . $path_cache . '"' : null;
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

if (!function_exists('is_percent')) {
    /**
     * Check if number passed is percent
     * then convert and return the percentage value
     * force convert the value even the percent sign is not available on string
     *
     * @param $string
     * @param bool $force
     * @return float|int
     */
    function is_percent($string, $force = false)
    {
        if ($string == null) {
            return 0;
        }

        if (strpos($string, '&#37;') !== false || strpos($string, '%') !== false || $force) {
            $string = str_replace('%', '', $string);

            if (!is_numeric($string)) {
                return 0;
            }

            $percent = (float)$string;
            return (float)($percent / 100);
        }

        return 0;
    }
}