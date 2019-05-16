<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Application;

use App\Http\Controllers\API\APIController;
use App\Models\Country;
use App\Models\Setting;

class ApplicationController extends APIController
{
    public function settingsAction()
    {
        return $this->json([
            'settings' => (new Setting())->keyValuePair(),
            'countries' => (new Country())->fetchAll(),
        ]);
    }
}
