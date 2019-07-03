<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use App\Models\Setting;

$GLOBALS['_app_settings'] = [];
$GLOBALS['_me'] = NULL;

function __initializeSettings()
{
    $set_app_settings = Setting::all();
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
        'category_setting_id' => null,
        'name' => null,
        'key' => null,
        'value' => null,
        'description' => null,
        'input_type' => 'text',
        'input_value' => null,
        'attributes' => null,
        'is_disabled' => 0
    ];
}

function __me()
{
    return $GLOBALS['_me'];
}