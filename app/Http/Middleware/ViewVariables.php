<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Closure;

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
        initialize_settings();
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
        $view->share('page_title', $_var['title']);
        $view->share('page_description', $_var['description']);
        $view->share('page_keywords', $_var['keywords']);
        $view->share('page_author', $_var['author']);
        $view->share('token', csrf_token());

        // others
        $view->share('view_title', null);
    }
}
