<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\Vendor\BaseModel;

class ChatGroupMember extends BaseModel
{
    protected static $tableName = 'chat_group_members';
    protected static $writableColumns = [
        'chat_group_id',
        'member_id', 'added_by_id',
        'is_mute', 'is_active', 'is_seen'
    ];
    protected static $inputBooleans = ['is_mute', 'is_active', 'is_seen'];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }
}
