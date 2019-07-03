<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class UserPhone extends BaseModel
{
    protected $tableName = 'user_phones';
    protected $writableColumns = [
        'user_id', 'ext', 'phone', 'is_verified',
        'is_verified', 'verification_code', 'verification_expired_at', 'verification_tries'
    ];

    protected $columnHasRelations = ['user_id'];

    protected $inputDates = ['verification_expired_at'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
