<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\APDApp\Helpers;

class WBDate
{
    /**
     * Sql date formatter
     *
     * @param null $date
     * @param bool $date_only
     * @return bool|string
     */
    public static function sqlDate($date = null, $date_only = false)
    {
        if ($date == null) {
            return date('Y-m-d' . (($date_only == true) ? '' : ' H:i:s'), time());
        } else {
            return date('Y-m-d' . (($date_only == true) ? '' : ' H:i:s'), strtotime($date));
        }
    }

    /**
     * Sql time formatter
     *
     * @param null $date
     * @return bool|string
     */
    public static function sqlTime($date = null)
    {
        if ($date == null) {
            return date('H:i:s', time());
        } else {
            return date('H:i:s', strtotime($date));
        }
    }

    /**
     * Human readable date and time
     *
     * @param null $date
     * @param bool|false $date_only
     * @return bool|string
     */
    public static function humanDate($date = null, $date_only = false)
    {
        if ($date == '0000-00-00') {
            return null;
        }

        if ($date == null) {
            return date('F d Y' . (($date_only == true) ? '' : ' h:i A'), time());
        } else {
            return date('F d Y' . (($date_only == true) ? '' : ' h:i A'), strtotime($date));
        }
    }

    /**
     * Human readable time
     *
     * @param null $time
     * @return false|string
     */
    public static function humanTime($time = null)
    {
        if ($time == null) {
            return date('h:i A', time());
        } else {
            return date('h:i A', strtotime($time));
        }
    }

    /**
     * Format date and time
     *
     * @param $date
     * @return array
     */
    public static function formatDate($date)
    {
        $formatted_date = [
            'date' => null,
            'date_month' => 0,
            'date_month_num' => 0,
            'date_month_small' => null,
            'date_month_full' => null,
            'date_day' => 0,
            'date_year' => 0,
            'year_month' => null,
            'year_month_day' => null,
            'time' => null,
            'time_hour' => 0,
            'time_minute' => 0,
            'time_am' => 'AM',
        ];

        $ed_format = explode(' ', $date);

        // if date only
        if (count($ed_format) == 1) {
            $ed_format = [];

            $ed_format[0] = $date;
            $ed_format[1] = '00:00:00';
        }

        if (count($ed_format) == 2) {
            // date
            $formatted_date['date'] = date('F d Y', strtotime($ed_format[0]));
            $formatted_date['date_month_small'] = date('M', strtotime($ed_format[0]));
            $formatted_date['date_month_full'] = date('F', strtotime($ed_format[0]));
            $formatted_date['date_month_num'] = (int)date('m', strtotime($ed_format[0]));
            $formatted_date['date_day'] = (int)date('d', strtotime($ed_format[0]));
            $formatted_date['date_year'] = (int)date('Y', strtotime($ed_format[0]));

            // others
            $formatted_date['year_month'] = date('Y-m', strtotime($ed_format[0]));
            $formatted_date['year_month_day'] = date('Y-m-d', strtotime($ed_format[0]));

            // time
            if ($ed_format[1] != '00:00:00') {
                $formatted_date['time'] = date('h:i A', strtotime($ed_format[1]));
                $formatted_date['time_hour'] = (int)date('h', strtotime($ed_format[1]));
                $formatted_date['time_minute'] = (int)date('i', strtotime($ed_format[1]));
                $formatted_date['time_am'] = date('A', strtotime($ed_format[1]));
            }
        }

        return $formatted_date;
    }

    /**
     * Expired at
     *
     * @param int $minute
     * @return bool|string
     */
    public static function expiredAt($minute = 120)
    {
        return date('Y-m-d H:i:s', strtotime('+' . $minute . ' minute'));
    }

    /**
     * Count years
     *
     * @param $then
     * @param null $current
     * @return bool|string
     */
    public static function countYears($then, $current = null)
    {
        $current = (!$current) ? time() : $current;
        $then_ts = strtotime($then);
        $then_year = date('Y', $then_ts);
        $year = date('Y') - $then_year;

        if (strtotime('+' . $year . ' years', $then_ts) > $current) $year--;

        return $year;
    }

    /**
     * Count hours
     *
     * @param $start
     * @param $end
     * @return float|int
     */
    public static function countHours($start, $end)
    {
        $start_time = StrToTime(sql_date($start));
        $end_time = StrToTime(sql_date($end));
        $diff = $end_time - $start_time;
        return $diff / (60 * 60);
    }
}