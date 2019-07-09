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

    protected $me = NULL;
    protected $request = NULL;
    protected $content = [];

    protected $view = NULL;
    protected $theme = NULL;
    protected $viewType = 'guest';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->request = request();
            $this->view = view();
            $this->me = __me();

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

        if ($this->isJSON()) {
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
     * Is response must be JSON
     *
     * @return bool
     */
    protected function isJSON(): bool
    {
        if ($this->request->ajax() || $this->viewType === 'json' || $this->request->get('response') === 'json') {
            return true;
        }

        return false;
    }

    /**
     * Redirect response
     *
     * @param null $uri
     * @return bool|JsonResponse
     */
    protected function redirect($uri = NULL)
    {
        if ($this->isJSON()) {
            return $this->json(['redirect' => $uri ? $uri : url()->previous()]);
        }

        if ($uri === NULL) {
            return redirect()->back();
        } else {
            return redirect($uri);
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
        $data = count($this->content) > 0 ? $this->content : $data;

        if ($response === 200) {
            return successJSONResponse($data);
        }

        return failedJSONResponse($data, $response, $default_message);
    }

    /**
     * Response errors
     *
     * @param $code
     * @param null $message
     * @return JsonResponse|void
     */
    protected function error($code, $message = NULL)
    {
        if ($this->isJSON()) {
            $message = ($message) ? $message : $code . ' response code.';
            return failedJSONResponse($message, $code);
        }

        abort($code);
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
     * Set new app data view
     *
     * @param $key
     * @param $value
     */
    protected function addAppView($key, $value)
    {
        $data = $this->getAppView($key);
        $data[] = $value;

        return $this->setAppView($key, $data);
    }

    /**
     * Get app view value
     *
     * @param $key
     * @return mixed
     */
    protected function getAppView($key)
    {
        return $this->view->share($key);
    }

    /**
     * Set view variables in app
     *
     * @param $key
     * @param $value
     */
    protected function setAppView($key, $value)
    {
        $this->view->share($key, $value);
    }
}
