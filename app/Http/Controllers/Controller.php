<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;
    public $content = [];
    protected $title = null;
    protected $me = null;

    // admin and guest
    protected $view_type = 'guest';
    protected $view = null;

    // type of response default is web/view
    // json if ajax/mobile api
    protected $response_type = 'web';

    public function __construct()
    {
        $this->request = request();
        $this->middleware(function ($request, $next) {
            $this->me = me();

            return $next($request);
        });
    }

    /**
     * Response
     *
     * @param null $view
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    protected function response($view = null)
    {
        $json = $this->jsonResponse($view);
        if ($json !== null) {
            return $json;
        }

        // view
        if ($view) {
            $this->view .= $view;
        }

        $this->_js();
        $this->_seo();

        return $this->_view();
    }

    /**
     * Redirect response
     *
     * @param null $uri
     * @return bool
     */
    protected function redirectResponse($uri = null)
    {
        $json = $this->jsonResponse();
        if ($json !== null) {
            return $json;
        }

        if ($uri === null) {
            return redirect()->back();
        } else {
            return redirect($uri);
        }
    }

    /**
     * JSON response
     *
     * @param null $data
     * @return bool
     */
    protected function jsonResponse($data = null)
    {
        if (request()->ajax() || $this->response_type == 'json') {
            if (!in_array('ob_gzhandler', ob_list_handlers())) {
                ob_start('ob_gzhandler');
            } else {
                ob_start();
            }

            if ($data && !$this->_hasContent()) {
                $this->content = $data;
            }

            return success_json_response($this->content);
        }

        return null;
    }

    /**
     * Failed response
     *
     * @param $message
     * @param int $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    protected function failed_response($message, $view = 404)
    {
        if ((request()->ajax() || $this->response_type == 'json') && $this->response_type != 'no_ajax') {
            return failed_json_response(($message === false) ? exception_messages('UNKNOWN') : $message);
        }

        return view('errors.' . $view, ['message' => $message]);
    }

    /**
     * Get the value from GET and POST request
     *
     * @param $key
     * @return mixed
     */
    protected function get($key)
    {
        return $this->request->get($key);
    }

    /**
     * Get the file from request
     *
     * @param $key
     * @return array|\Illuminate\Http\UploadedFile|null
     */
    protected function file($key)
    {
        return $this->request->file($key);
    }

    /**
     * Load JS
     */
    public function _js()
    {
        $map_api = 'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&libraries=places';

        if (env('APP_ENV') == 'local') {
            $_js = ($this->view_type == 'admin') ? [
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
            $_js = ($this->view_type == 'admin') ? [
                $map_api,
                asset('assets/js/vendor.js')
            ] : [
                $map_api,
                asset('assets/js/vendor.js')
            ];
        }

        js_view_loader($_js);
    }

    /**
     * SEO
     */
    public function _seo()
    {
        $this->_setContent([
            'page_description' => app_header('description'),
            'page_keywords' => app_header('keywords'),
            'page_author' => app_header('author')
        ]);
    }

    /**
     * Set default values for content
     *
     * @param $contents
     */
    private function _setContent($contents)
    {
        if (!is_array($this->content)) {
            return;
        }

        foreach ($contents as $key => $value) {
            $this->content[$key] = (isset($this->content[$key])) ? $this->content[$key] : $value;
        }
    }

    /**
     * Has content
     *
     * @return bool
     */
    private function _hasContent()
    {
        if ($this->content == null) {
            return false;
        }

        if (is_array($this->content)) {
            if (!count($this->content)) {
                return false;
            }
        }

        return true;
    }

    /**
     * View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function _view()
    {
        $this->content['request'] = request();
        $this->content['title'] = ($this->title) ? app_title($this->title) : app_settings('title')->value;

        if (!$this->view) {
            abort(404);
        }

        if ($this->view_type == 'admin') {
            return admin_view($this->view, $this->content);
        } else if ($this->view_type == 'guest') {
            return theme($this->view, $this->content);
        } else {
            return view($this->view, $this->content);
        }
    }
}
