<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class UserTracker extends BaseModel
{
    protected $tableName = 'user_trackers';
    protected $writableColumns = [
        'user_id',
        'lat', 'lng',
        'device_id', 'http_referrer', 'current_url', 'ip_address', 'platform', 'browser',
    ];

    protected $inputDates = ['birthday'];
    protected $columnHasRelations = ['user_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function rawFilters($query): void
    {
        $query->join('users', 'user_trackers.user_id', '=', 'users.id');
    }

    public function log()
    {
        if (!request('lat') || !request('lng') || !authId()) {
            return;
        }

        try {
            $userAgent = userAgent();
            (new UserTracker())->store([
                'user_id' => (authId()) ? authId() : 0,
                'lat' => request('lat'),
                'lng' => request('lng'),
                'device_id' => request()->header('device_id'),
                'http_referrer' => (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null,
                'current_url' => (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : null,
                'ip_address' => ipAddress(),
                'platform' => $userAgent->platform,
                'browser' => $userAgent->browserName
            ]);
        } catch (\Exception $e) {
            logErrors($e->getMessage());
        }
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function page()
    {
        return $this->belongsTo('App\Models\Page');
    }

    protected function rawQuerySelectList()
    {
        return [
            'full_name' => 'CONCAT(users.first_name, " ", users.last_name)',
            'location' => 'CONCAT("http://maps.google.com/maps?q=", user_trackers.lat, ",", user_trackers.lng)',
        ];
    }
}
