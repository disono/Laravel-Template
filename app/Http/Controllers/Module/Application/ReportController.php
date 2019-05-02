<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Application;

use App\Http\Requests\Module\Application\ReportStore;
use App\Http\Controllers\Controller;
use App\Models\App\PageReport;

class ReportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function storeAction(ReportStore $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = __me()->id;
        return $this->json(PageReport::store($inputs));
    }
}
