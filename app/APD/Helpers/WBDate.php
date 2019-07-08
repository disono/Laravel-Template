<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

if (!function_exists('sqlDate')) {
    /**
     * Create SQL date format
     *
     * @param null $date
     * @param bool $dateOnly
     * @return false|string
     */
    function sqlDate($date = null, $dateOnly = false)
    {
        if ($date == null) {
            return date('Y-m-d' . (($dateOnly == true) ? '' : ' H:i:s'), time());
        } else {
            return date('Y-m-d' . (($dateOnly == true) ? '' : ' H:i:s'), strtotime($date));
        }
    }
}

if (!function_exists('expiredAt')) {
    /**
     * Expired at
     *
     * @param int $minute
     * @return bool|string
     */
    function expiredAt($minute = 120)
    {
        return date('Y-m-d H:i:s', strtotime('+' . $minute . ' minute'));
    }
}

if (!function_exists('countYears')) {
    /**
     * Count years
     *
     * @param $then
     * @param null $current
     * @return false|string
     */
    function countYears($then, $current = null)
    {
        $current = (!$current) ? time() : $current;
        $then_ts = strtotime($then);
        $then_year = date('Y', $then_ts);
        $year = date('Y') - $then_year;

        if (strtotime('+' . $year . ' years', $then_ts) > $current) $year--;

        return $year;
    }
}

if (!function_exists('humanDate')) {
    /**
     * Human readable date
     *
     * @param null $date
     * @param bool $date_only
     * @return false|null|string
     */
    function humanDate($date = null, $date_only = false)
    {
        if ($date == '0000-00-00') {
            return null;
        }

        if ($date == null) {
            return date('F d, Y' . (($date_only == true) ? '' : ' h:i A'), time());
        } else {
            return date('F d, Y' . (($date_only == true) ? '' : ' h:i A'), strtotime($date));
        }
    }
}

if (!function_exists('humanTime')) {
    /**
     * Human readable time
     *
     * @param null $time
     * @return false|string
     */
    function humanTime($time = null)
    {
        if ($time == null) {
            return date('h:i A', time());
        } else {
            return date('h:i A', strtotime($time));
        }
    }
}

if (!function_exists('countHours')) {
    /**
     * Count hours
     *
     * @param $start
     * @param $end
     * @return float|int
     */
    function countHours($start, $end)
    {
        $start_time = StrToTime(sqlDate($start));
        $end_time = StrToTime(sqlDate($end));
        $diff = $end_time - $start_time;
        return $diff / (60 * 60);
    }
}

if (!function_exists('setTimezone')) {
    /**
     * Set Timezone
     */
    function setTimezone()
    {
        try {
            if (!fetchRequestValue('device_timezone')) {
                return;
            }

            $collection = collect(timezone_identifiers_list());
            $searchFor = fetchRequestValue('device_timezone');
            $c = $collection->get($collection->search(function ($item, $key) use ($searchFor) {
                return strpos($item, $searchFor) !== false;
            }));

            if (!isset($collection[$c])) {
                return;
            }

            // secret key
            config(['app.timezone' => $collection[$c]]);
        } catch (Exception $e) {
            logErrors('Error (App\APDApp\Helpers\WBAuth - setTimezone()): ' . $e->getMessage());
        }
    }
}