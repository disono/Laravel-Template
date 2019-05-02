<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

if (!function_exists('frmIsSelected')) {
    /**
     * Selected option for option form
     *
     * @param $inputName
     * @param $value
     * @param null $default
     * @param string $success
     * @return null|string
     */
    function frmIsSelected($inputName, $value, $default = null, $success = 'selected')
    {
        return (request($inputName) === $value || old($inputName, $default) === $value) ? $success : null;
    }
}

if (!function_exists('frmIsChecked')) {
    /**
     * Check for checkbox
     *
     * @param $inputName
     * @param $value
     * @param null $default
     * @param string $success
     * @return null|string
     */
    function frmIsChecked($inputName, $value, $default = null, $success = 'checked')
    {
        if (request($inputName) === $value) {
            return $success;
        }

        if (is_array($default)) {
            foreach ($default as $val) {
                if (old($inputName, $val) === $value) {
                    return $success;
                }
            }
        }

        return (old($inputName, $default) === $value) ? $success : null;
    }
}

if (!function_exists('hasInput')) {
    /**
     * Get value of input
     *
     * @param $name
     * @param $data
     * @return mixed|null
     */
    function hasInput($name, $data)
    {
        if (is_object($data)) {
            return $data->$name;
        }

        if (is_array($data)) {
            return $data[$name] ?? null;
        }

        return null;
    }
}

if (!function_exists('dbRandomValues')) {
    /**
     * Check for unique values for table
     *
     * @param $table
     * @param $columnName
     * @param int $length
     * @return null|string
     */
    function dbRandomValues($table, $columnName, $length = 32)
    {
        $checker = true;
        $rnd = null;

        while ($checker) {
            $rnd = str_random($length);

            $query = Illuminate\Support\Facades\DB::table($table)
                ->where($columnName, $rnd);

            if (!$query->first()) {
                $checker = false;
            }
        }

        return $rnd;
    }
}

if (!function_exists('shortenNumber')) {
    /**
     * Short numbers
     *
     * @param $number
     * @param int $precision
     * @param null $divisors
     * @return string
     */
    function shortenNumber($number, $precision = 3, $divisors = null)
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
        $_division = 1;
        $_shortHand = null;
        foreach ($divisors as $division => $shortHand) {
            if ($number < ($division * 1000)) {
                // We found a match!
                $_division = $division;
                $_shortHand = $shortHand;
                break;
            }
        }

        // We found our match, or there were no matches.
        // Either way, use the last defined value for $divisor.
        return (int)number_format($number / $_division, $precision) . $_shortHand;
    }
}