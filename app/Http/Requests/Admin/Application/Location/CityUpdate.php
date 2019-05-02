<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin\Application\Location;

use App\Http\Requests\Admin\AdminRequest;

class CityUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:cities,id',
            'country_id' => 'required|integer|exists:countries,id',
            'name' => 'required',
            'lat' => 'numeric',
            'lng' => 'numeric'
        ];
    }
}
