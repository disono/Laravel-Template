<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */
namespace App\APDApp\Helpers;

use GrahamCampbell\HTMLMin\Facades\HTMLMin;
use Jenssegers\Agent\Agent;

class WBView
{
    private static $js = 'wb_javascript_loader';

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

        return 'theme.main.' .
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
        $wb_js = self::$js;

        if (!session()->exists($wb_js)) {
            session()->put($wb_js, json_encode($js_list));
        } else {
            $javascript = json_decode(session()->get($wb_js));

            // delete the old data scripts
            session()->forget($wb_js);

            // let's combine the javascript
            $scripts_list = (is_array($javascript)) ? array_merge($js_list, $javascript) : $js_list;

            // let's add a new data
            session()->put($wb_js, json_encode($scripts_list));
        }
    }

    /**
     * Javascript runner
     *
     * @return array|mixed
     */
    public static function js_view_runner()
    {
        $wb_js = self::$js;

        if (!session()->exists($wb_js)) {
            return [];
        } else {
            $js = json_decode(session()->get($wb_js));
            session()->forget($wb_js);

            return $js;
        }
    }
}