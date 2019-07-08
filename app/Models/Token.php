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

    protected $inputDates = ['expired_at'];
    protected $columnHasRelations = ['user_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function actionRemoveBefore($results)
    {
        foreach ($results as $row) {
            FirebaseToken::where('token_id', $row->id)->delete();
        }

        return true;
    }

    protected function customQueries($query): void
    {
        $query->join('users', 'tokens.user_id', '=', 'users.id');
    }

    protected function customQuerySelectList(): array
    {
        return [
            'full_name' => 'CONCAT(users.first_name, " ", users.last_name)',
            'email' => 'users.email',
            'username' => 'users.username',
            'is_expired' => 'IF(tokens.expired_at >= DATE(NOW()), 0, 1)',
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
