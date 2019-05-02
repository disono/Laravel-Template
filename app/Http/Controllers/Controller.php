<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $content = [];
    protected $request = null;
    protected $theme = null;
    protected $me = null;
    protected $view = null;
    protected $viewType = 'guest';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->request = request();
            $this->me = __me();
            $this->view = view();

            return $next($request);
        });
    }

    /**
     * Default theme
     *
     * @param $path
     * @param array $data
     * @param int $response
     * @return JsonResponse|Response
     */
    protected function view($path, $data = [], $response = 200)
    {
        // override the data for content
        $data = (count($this->content) > 0) ? $this->content : $data;

        if ($this->request->ajax() || $this->request->get('response') === 'json' || $this->viewType === 'json') {
            if ($response == 200) {
                return successJSONResponse($data);
            }

            return failedJSONResponse($data, $response);
        }

        $data['request'] = $this->request;

        if ($this->viewType === 'admin') {
            if ($this->theme) {
                return adminTheme($this->theme . '.' . $path, $data, $response);
            }

            return adminTheme($path, $data, $response);
        } else {
            if ($this->theme) {
                return theme($this->theme . '.' . $path, $data, $response);
            }

            return theme($path, $data, $response);
        }
    }

    /**
     * JSON response
     *
     * @param $data
     * @param int $response
     * @param bool $default_message
     * @return JsonResponse
     */
    protected function json($data, $response = 200, $default_message = true)
    {
        // override the data for content
        $data = (count($this->content) > 0) ? $this->content : $data;

        if ($response == 200) {
            return successJSONResponse($data);
        }

        return failedJSONResponse($data, $response, $default_message);
    }

    /**
     * Redirect response
     *
     * @param null $uri
     * @return bool
     */
    protected function redirect($uri = null)
    {
        if ($uri === null) {
            return redirect()->back();
        } else {
            return redirect($uri);
        }
    }

    /**
     * Set headers
     *
     * @param $key
     * @param $value
     */
    protected function setHeader($key, $value)
    {
        switch ($key) {
            case 'title':
                $this->view->share('page_title', ucfirst($value) . ' - ' . __settings('title')->value);
                $this->view->share('view_title', ucfirst($value));
                break;
            case 'description':
                $this->view->share('page_description', $value);
                break;
            case 'keywords':
                $this->view->share('page_keywords', $value);
                break;
            case 'author':
                $this->view->share('page_author', $value);
                break;
        }
    }

    /**
     * Set view variables in app
     *
     * @param $key
     * @param $value
     */
    protected function setApp($key, $value)
    {
        $this->view->share($key, $value);
    }

    /**
     * Response errors
     *
     * @param $code
     * @param null $message
     * @return JsonResponse|void
     */
    protected function error($code, $message = null)
    {
        if ($this->request->ajax() || $this->viewType === 'json' || $this->request->get('response') === 'json') {
            $message = ($message) ? $message : $code . ' response code.';
            return failedJSONResponse($message, $code);
        }

        abort($code);
    }
}
