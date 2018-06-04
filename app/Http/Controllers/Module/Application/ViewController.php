<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Application;

use App\Http\Controllers\Controller;

class ViewController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function viewAction($type)
    {
        if ($type == 'delete') {
            return theme('modals.delete');
        } else if ($type == 'fileSelector') {
            return theme('modals.mediaSelector');
        }

        return abort(404);
    }
}
