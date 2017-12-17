<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->response_type = 'json';
        parent::__construct();
    }

    /**
     * Application Settings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->content = [
            'settings' => Setting::getAll()
        ];

        return $this->response();
    }
}
