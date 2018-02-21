<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Support\Facades\Route;

class ViewVariables
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        _init_app_settings();
        $this->_setViewVariables();
        $this->_setJS($request);

        return $next($request);
    }

    private function _setViewVariables()
    {
        $_var = [
            'description' => '',
            'keywords' => '',
            'author' => '',
            'show_sharer' => false
        ];

        foreach (Setting::all() as $obj) {
            if (in_array($obj->key, ['description', 'keywords', 'author'])) {
                $_var[$obj->key] = $obj->value;
            }
        }

        view()->share('page_description', $_var['description']);
        view()->share('page_keywords', $_var['keywords']);
        view()->share('page_author', $_var['author']);
        view()->share('show_sharer', $_var['show_sharer']);
    }

    private function _setJS($request)
    {
        $map_api = 'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&libraries=places';

        if (env('APP_ENV') == 'local') {
            $_js = ($this->_isAdminRoute($request)) ? [
                $map_api,
                asset('assets/js/vendor.js'),
                asset('assets/js/vendor/libraries.js'),
                asset('assets/js/vendor/helper.js'),
                asset('assets/js/vendor/socket.js'),
                asset('assets/js/admin.js'),
                asset('assets/js/application.js')
            ] : [
                $map_api,
                asset('assets/js/vendor.js'),
                asset('assets/js/vendor/libraries.js'),
                asset('assets/js/vendor/helper.js'),
                asset('assets/js/vendor/socket.js'),
                asset('assets/js/application.js')
            ];
        } else {
            $_js = ($this->_isAdminRoute($request)) ? [
                $map_api,
                asset('assets/js/vendor.js')
            ] : [
                $map_api,
                asset('assets/js/vendor.js')
            ];
        }

        js_view_loader($_js);
    }

    private function _isAdminRoute($request)
    {
        // Route::currentRouteName()
        $route = Route::getRoutes()->match($request);
        $_route_name = $route->getName();

        if (!$_route_name) {
            return false;
        }

        return preg_match('/admin/', strtolower($_route_name));
    }
}