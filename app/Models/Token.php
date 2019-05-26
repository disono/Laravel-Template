<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class Token extends BaseModel
{
    protected $tableName = 'tokens';
    protected $writableColumns = [
        'user_id', 'token', 'key', 'secret', 'source', 'expired_at'
    ];

    protected $columnHasRelations = ['user_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    /**
     * Remove any related data from user
     *
     * @param $query
     * @return bool
     */
    public function actionRemoveBefore($query)
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
