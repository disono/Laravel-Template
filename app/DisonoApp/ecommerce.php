<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

if (!function_exists('order_status')) {
    function order_status()
    {
        return [
            'Pending Payment',
            'Processing',
            'On Hold',
            'Completed',
            'Cancelled',
            'Refunded',
            'Failed',
            'Refund Requested'
        ];
    }
}