<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

$GLOBALS['_app_settings'] = [];
$GLOBALS['_me'] = null;

function initialize_settings()
{
    $set_app_settings = \App\Models\Setting::all();
    $clean = [];

    foreach ($set_app_settings as $row) {
        $clean[$row->key] = $row;
    }

    $GLOBALS['_app_settings'] = $clean;
    $GLOBALS['_me'] = me();
}

function __settings($key = null)
{
    if ($key === null) {
        return $GLOBALS['_app_settings'];
    }

    if (isset($GLOBALS['_app_settings'][$key])) {
        return $GLOBALS['_app_settings'][$key];
    }

    return (object)[
        'name' => null, 'key' => null, 'value' => null, 'description' => null, 'input_type' => 'text', 'input_value' => null,
        'attributes' => null, 'is_disabled' => 0
    ];
}

function __me()
{
    return $GLOBALS['_me'];
}