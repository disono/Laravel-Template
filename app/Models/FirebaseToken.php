<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class FirebaseToken extends BaseModel
{
    protected $tableName = 'firebase_tokens';
    protected $writableColumns = [
        'token_id', 'fcm_token'
    ];

    protected $columnHasRelations = ['token_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function token()
    {
        return $this->belongsTo('App\Models\Token');
    }

    protected function customQueries($query): void
    {
        $query->join('tokens', 'firebase_tokens.token_id', '=', 'tokens.id');
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
}
