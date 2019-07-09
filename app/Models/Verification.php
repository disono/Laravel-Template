<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class Verification extends BaseModel
{
    protected $tableName = 'verifications';
    protected $writableColumns = [
        'user_id', 'value', 'type', 'token', 'tries', 'expired_at'
    ];

    protected $columnHasRelations = ['user_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function isExpired($type)
    {
        if (!__me()) {
            return FALSE;
        }

        $verification = Verification::where('user_id', __me()->id)->where('type', $type)->first();
        if (!$verification) {
            return TRUE;
        }

        if (strtotime($verification->expired_at) <= time()) {
            return TRUE;
        }

        return FALSE;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
