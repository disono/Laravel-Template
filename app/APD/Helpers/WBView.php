<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use Jenssegers\Agent\Agent;

if (!function_exists('theme')) {
    /**
     * Theme for user
     *
     * @param null $file
     * @param array $data
     * @param int $response
     * @return \Illuminate\Http\Response
     */
    function theme($file = null, $data = [], $response = 200)
    {
        $currentTheme = currentTheme();
        return response()->view($currentTheme . trim($file, '/'), $data, $response);
    }
}

if (!function_exists('currentTheme')) {
    /**
     * Current theme selected from settings
     */
    function currentTheme()
    {
        $agent = new Agent();
        return 'themes.' . __settings('theme')->value . '.' .
            ((($agent->isMobile() === 1 || $agent->isTablet() === 1) && env('VIEW_MOBILE') === true) ? 'mobile.' : 'desktop.');
    }
}

if (!function_exists('adminTheme')) {
    /**
     * Admin theme
     *
     * @param $file
     * @param $data
     * @param int $response
     * @return \Illuminate\Http\Response
     */
    function adminTheme($file, $data, $response = 200)
    {
        return response()->view('admin.' . $file, $data, $response);
    }
}

if (!function_exists('isCurrentRoute')) {
    /**
     * Check if on the current route
     *
     * @param $to
     * @return bool
     */
    function isCurrentRoute($to)
    {
        if (!request()) {
            return false;
        }

        if (!request()->route()) {
            return false;
        }

        if ($to === request()->route()->getName()) {
            return true;
        }

        return false;
    }
}

if (!function_exists('isActiveMenu')) {
    /**
     * Is active menu
     *
     * @param $to
     * @return string
     */
    function isActiveMenu($to)
    {
        if (isCurrentRoute($to)) {
            return 'active';
        }

        return '';
    }
}

if (!function_exists('hasInputError')) {
    /**
     * Form error
     *
     * @param $errors
     * @param $inputName
     * @return string
     */
    function hasInputError($errors, $inputName)
    {
        return $errors->has($inputName) ? ' is-invalid invalid' : '';
    }
}

if (!function_exists('thDelete')) {
    /**
     * Checkbox th delete
     *
     * @return string
     */
    function thDelete()
    {
        return '<th>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" value="" v-on:change="deleteSelectAll">
            </div>
        </th>';
    }
}

if (!function_exists('tdDelete')) {
    /**
     * Checkbox td delete
     *
     * @param $id
     * @return string
     */
    function tdDelete($id)
    {
        return '<td>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" delete-data="deleteListCheckbox" id="del_' . $id . '"
                                           v-model="deleteListCheckbox" value="' . $id . '">
            </div>
        </td>';
    }
}