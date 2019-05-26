<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Application;

use App\Http\Controllers\Controller;
use App\Models\City;

class LocationController extends Controller
{
    public function citiesAction($country_id)
    {
        return $this->json((new City())->fetchAll(['country_id' => $country_id]));
    }
}
