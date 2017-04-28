<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

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

        ];

        return $this->response();
    }
}
