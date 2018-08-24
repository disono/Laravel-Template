<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class Token extends BaseModel
{
    protected static $tableName = 'tokens';
    protected static $writableColumns = [
        'user_id', 'token', 'key', 'secret', 'source', 'expired_at'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }

    /**
     * Remove any related data from user
     *
     * @param $query
     * @return bool
     */
    public static function actionRemove($query)
    {
        foreach ($query as $row) {
            FirebaseToken::where('token_id', $row->id)->delete();
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
