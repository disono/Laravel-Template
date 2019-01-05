<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\Vendor\BaseModel;

class ChatGroup extends BaseModel
{
    protected static $tableName = 'chat_groups';
    protected static $writableColumns = [
        'created_by_id', 'name',
        'is_deleted', 'is_archived', 'is_spam'
    ];
    protected static $inputBooleans = ['is_deleted', 'is_archived', 'is_spam'];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }
}
