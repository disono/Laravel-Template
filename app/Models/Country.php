<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class Country extends BaseModel
{
    protected $tableName = 'countries';
    protected $writableColumns = [
        'code', 'name', 'lat', 'lng'
    ];

    protected $columnWhere = ['code'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->hasMany('App\Models\User');
    }

    public function city()
    {
        return $this->hasMany('App\Models\City');
    }
}
