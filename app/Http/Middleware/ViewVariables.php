<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ViewVariables
{
    private $vueApp = 'WBApp';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        __initializeSettings();
        $this->_setViewVariables();

        return $next($request);
    }

    private function _setViewVariables()
    {
        $_var = [
            'title' => '',
            'description' => '',
            'keywords' => '',
            'author' => ''
        ];

        foreach (__settings() as $key => $value) {
            if (in_array($key, ['title', 'description', 'keywords', 'author'])) {
                $_var[$key] = $value->value;
            }
        }

        $view = view();

        // SEO
        $view->share('view_title', NULL);
        $view->share('page_title', $_var['title']);
        $view->share('page_description', $_var['description']);
        $view->share('page_keywords', $_var['keywords']);
        $view->share('page_author', $_var['author']);
        $view->share('seo_robots', NULL);

        // Meta og
        $view->share('og_url', NULL);
        $view->share('og_type', NULL);
        $view->share('og_title', NULL);
        $view->share('og_description', NULL);
        $view->share('og_image', NULL);
        $view->share('og_image_width', NULL);
        $view->share('og_image_height', NULL);

        // others
        $view->share('token', csrf_token());
        $view->share('vue_app', $this->vueApp);
        $view->share('app_styles', []);
        $view->share('app_scripts', $this->_setDefaultAppScripts());
        $view->share('app_libraries', $this->_setDefaultJavascriptLibraries());
    }

    private function _setDefaultAppScripts()
    {
        return [
            'assets/js/vue/application.js'
        ];
    }

    private function _setDefaultJavascriptLibraries()
    {
        // Google Map
        $libraries = [];
        if (env('GOOGLE_API_KEY')) {
            $libraries[] = 'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&libraries=places';
        }
        return $libraries;
    }
}
