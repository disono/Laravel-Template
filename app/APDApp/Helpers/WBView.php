<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\APDApp\Helpers;

use HTMLMin\HTMLMin\Facades\HTMLMin;
use Jenssegers\Agent\Agent;

class WBView
{
    /**
     * Theme
     *
     * @param null $file
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function theme($file = null, $data = [])
    {
        $current_them = self::current_theme();

        if (env('APP_ENV') === 'local') {
            return view($current_them . trim($file, '/'), $data);
        }

        return HTMLMin::blade(view($current_them . trim($file, '/'), $data));
    }

    /**
     * Check for current theme
     *
     * @return string
     */
    public static function current_theme()
    {
        $agent = new Agent();
        $theme_name = app_settings('theme')->value;

        return 'theme.' . $theme_name . '.' .
            ((($agent->isMobile() === 1 || $agent->isTablet() === 1) && env('VIEW_MOBILE') === true) ? 'mobile.' : 'desktop.');
    }

    /**
     * Admin view
     *
     * @param null $file
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function admin_view($file, $data)
    {
        if (env('APP_ENV') === 'local') {
            return view('admin.' . $file, $data);
        }

        return HTMLMin::blade(view('admin.' . $file, $data));
    }

    /**
     * Load javascript
     *
     * @param array $js_list
     */
    public static function js_view_loader($js_list = [])
    {
        if (!count($GLOBALS['wb_scripts_loaders'])) {
            $GLOBALS['wb_scripts_loaders'] = $js_list;
        } else {
            $javascript = $GLOBALS['wb_scripts_loaders'];

            // remove duplicate js
            $key = 0;
            foreach ($js_list as $js) {
                if (in_array($js, $javascript)) {
                    unset($js_list[$key]);
                }

                $key++;
            }

            $GLOBALS['wb_scripts_loaders'] = [];
            $GLOBALS['wb_scripts_loaders'] = (is_array($javascript)) ? array_merge($javascript, $js_list) : $js_list;
        }
    }

    /**
     * Javascript runner
     *
     * @return array|mixed
     */
    public static function js_view_runner()
    {
        if (!count($GLOBALS['wb_scripts_loaders'])) {
            return [];
        } else {
            $js = $GLOBALS['wb_scripts_loaders'];
            $GLOBALS['wb_scripts_loaders'] = [];

            return $js;
        }
    }
}