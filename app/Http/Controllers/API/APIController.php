<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class APIController extends Controller
{
    protected $viewType = 'json';

    public function __construct()
    {
        parent::__construct();
    }
}
