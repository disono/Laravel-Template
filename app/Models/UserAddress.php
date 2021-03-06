<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class UserAddress extends BaseModel
{
    protected $tableName = 'user_addresses';
    protected $writableColumns = [
        'user_id', 'address', 'postal_code', 'country_id', 'city_id',
        'is_verified', 'verification_code', 'verification_expired_at', 'verification_tries'
    ];

    protected $inputDates = ['verification_expired_at'];
    protected $columnHasRelations = ['user_id', 'country_id', 'city_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    protected function customQueries($query): void
    {
        $query->join('countries', 'user_addresses.country_id', '=', 'countries.id');
        $query->join('cities', 'user_addresses.city_id', '=', 'cities.id');
    }

    protected function customQuerySelectList(): array
    {
        return [
            'country_name' => 'countries.name',
            'city_name' => 'cities.name',
            'is_verification_expired' => 'IF(user_addresses.verification_expired_at IS NOT NULL, IF(user_addresses.verification_expired_at < DATE(NOW()), 1, 0), 0)',
        ];
    }
}
