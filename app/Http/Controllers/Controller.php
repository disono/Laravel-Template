<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;
    protected $content = [];
    protected $title = null;

    protected $view_type = 'guest';
    protected $view = null;

    // type of response default is web/view
    // json if ajax/mobile api
    protected $response_type = 'web';

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Response
     *
     * @param null $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function response($view = null)
    {
        if ((request()->ajax() || $this->response_type == 'json') && $this->response_type != 'no_ajax') {
            return success_json_response($this->content);
        }

        // view
        if ($view) {
            $this->view .= $view;
        }

        return $this->_view();
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
            return failed_json_response($message);
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
        } else {
            return theme($this->view, $this->content);
        }
    }
}
