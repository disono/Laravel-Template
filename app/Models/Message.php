<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    private static $params;
    private static $query_params;

    protected static $writable_columns = [
        'to_id', 'from_id', 'group_id', 'message', 'type', 'is_viewed'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writable_columns);
        parent::__construct($attributes);
    }

    /**
     * Get single data
     *
     * @param $id
     * @param string $column
     * @param array $params
     * @return null
     */
    public static function single($id, $column = 'id', $params = [])
    {
        if (!$id) {
            return null;
        }

        $required_params = [
            'single' => true,
            $column => $id
        ];

        if (count($params)) {
            $required_params = $required_params + $params;
        }

        return self::get($required_params);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function get($params = [])
    {
        $select[] = 'messages.*';

        // to
        if (!isset($params['group_id'])) {
            self::$query_params['to_full_name'] = 'CONCAT(user_to.first_name, " ", user_to.last_name)';
            self::$query_params['to_username'] = '(' . self::_username('user_to') . ')';
            self::$query_params['to_role_name'] = '(' . self::_role('user_to') . ')';
            $select[] = DB::raw('user_to.first_name AS to_first_name, user_to.last_name AS to_last_name, user_to.email AS to_email, user_to.role AS to_role, ' .
                self::$query_params['to_full_name'] . ' AS to_full_name, ' . self::$query_params['to_username'] . ' AS to_username, ' .
                self::$query_params['to_role_name'] . ' AS to_role_name, user_to.image_id as to_image_id');
        }

        // from
        self::$query_params['from_full_name'] = 'CONCAT(user_from.first_name, " ", user_from.last_name)';
        self::$query_params['from_username'] = '(' . self::_username('user_from') . ')';
        self::$query_params['from_role_name'] = '(' . self::_role('user_from') . ')';
        $select[] = DB::raw('user_from.first_name AS from_first_name, user_from.last_name AS from_last_name, user_from.email AS from_email, user_from.role AS from_role, ' .
            self::$query_params['from_full_name'] . ' AS from_full_name, ' . self::$query_params['from_username'] . ' AS from_username, ' .
            self::$query_params['from_role_name'] . ' AS from_role_name, user_from.image_id as from_image_id');

        // sender type
        if (!isset($params['group_id'])) {
            $select[] = DB::raw('(IF(messages.from_id = ' .
                ((isset($params['to_id'])) ? (int)$params['to_id'] : 0) . ', "you", "sender")) as sender_type');
        } else {
            $select[] = DB::raw('(IF(messages.from_id = ' .
                ((isset($params['from_id'])) ? (int)$params['from_id'] : 0) . ', "you", "sender")) as sender_type');
        }

        // table
        if (isset($params['reading'])) {
            $query = DB::table('messages');
        } else {
            $query = DB::table(DB::raw('(SELECT * FROM messages ORDER BY created_at DESC) as messages'));
        }

        // select
        $query->select($select);

        // joins
        if (!isset($params['group_id'])) {
            $query = $query->join('users as user_to', 'messages.to_id', '=', 'user_to.id');
        }
        $query = $query->join('users as user_from', 'messages.from_id', '=', 'user_from.id');

        // query where filters
        $query = self::_query_filters($query, $params);

        // list reading messages
        if (isset($params['reading'])) {
            // to list
            if (!isset($params['group_id'])) {
                $to_id = (int)$params['to_id'];
                $to = $query->where(function ($q) use ($to_id) {
                    $q->where('messages.from_id', '<>', $to_id);
                });
            } else {
                $to = $query;
            }

            // from list
            $query = DB::table('messages')
                ->select($select);
            if (!isset($params['group_id'])) {
                $query = $query->join('users as user_to', 'messages.to_id', '=', 'user_to.id');
            }
            $query = $query->join('users as user_from', 'messages.from_id', '=', 'user_from.id');

            if (isset($params['from_id']) && !isset($params['group_id'])) {
                $query = $query->where('messages.to_id', '=', (int)$params['from_id']);
            }

            if (!isset($params['group_id'])) {
                $query = $query->where('messages.from_id', '=', (int)$params['to_id'])
                    ->unionAll($to);
            } else {
                $query = $query->unionAll($to);
            }

            // order by
            $query->orderBy('created_at', 'DESC');
        } else {
            // get by id
            if (isset($params['id'])) {
                $query->where('messages.id', (int)$params['id']);
            }

            // group by messages
            if (isset($params['group_by'])) {
                $to_user_selects = (!isset($params['group_id'])) ?
                    'messages.from_id, user_to.first_name, user_to.last_name, user_to.email, user_to.role, user_to.id, user_to.image_id, ' : '';

                $query->groupBy(DB::raw($to_user_selects .
                    'user_from.first_name, user_from.last_name, user_from.email, user_from.role, user_from.id, user_from.image_id'));
            }

            // order by
            $query->orderBy('messages.created_at', 'DESC');
        }

        // ['column_name' => [value, value, value]]
        if (isset($params['exclude'])) {
            $exclude = $params['exclude'];

            foreach ($exclude as $key => $value) {
                if (in_array($key, self::$writable_columns)) {
                    if (is_array($value)) {
                        $query->whereNotIn('messages.' . $key, $value);
                    }
                }
            }
        }

        if (isset($params['object'])) {
            return $query;
        } else {
            if (isset($params['single'])) {
                return self::_format($query->first(), $params);
            } else if (isset($params['all'])) {
                return self::_format($query->get(), $params);
            } else {
                $query = paginate($query);

                return self::_format($query, $params);
            }
        }
    }

    /**
     * Where Filters Query
     *
     * @param $query
     * @param $params
     * @return mixed
     */
    private static function _query_filters($query, $params)
    {
        if (isset($params['id'])) {
            $query->where('messages.id', $params['id']);
        }

        if (isset($params['to_id']) && !isset($params['group_id'])) {
            $query->where('messages.to_id', $params['to_id']);
        }

        if (isset($params['from_id']) && !isset($params['group_id'])) {
            $query->where('messages.from_id', $params['from_id']);
        }

        if (isset($params['group_id'])) {
            $query->where('messages.group_id', $params['group_id']);
        }

        if (isset($params['type'])) {
            $query->where('messages.type', $params['type']);
        }

        if (isset($params['is_viewed'])) {
            $query->where('messages.is_viewed', $params['is_viewed']);
        }

        if (isset($params['from_username'])) {
            $query->where(DB::raw(self::$query_params['from_username']), $params['from_username']);
        }

        if (isset($params['to_username'])) {
            $query->where(DB::raw(self::$query_params['to_username']), $params['to_username']);
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->where(function ($query) {
                $query->where(DB::raw(self::$query_params['from_full_name']), 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere(DB::raw(self::$query_params['from_username']), 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere(DB::raw(self::$query_params['from_role_name']), 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere(DB::raw(self::$query_params['to_full_name']), 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere(DB::raw(self::$query_params['to_username']), 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere(DB::raw(self::$query_params['to_role_name']), 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('messages.message', 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        return $query;
    }

    /**
     * Username query string
     *
     * @param $column_name
     * @return string
     */
    private static function _username($column_name)
    {
        return 'SELECT name FROM slugs WHERE source_id = ' . $column_name . '.id AND source_type = "user"';
    }

    /**
     * Role query string
     *
     * @param $column_name
     * @return string
     */
    private static function _role($column_name)
    {
        return 'SELECT name FROM roles WHERE slug = ' . $column_name . '.role';
    }

    /**
     * Get all data no pagination
     *
     * @param array $params
     * @return null
     */
    public static function getAll($params = [])
    {
        $params['all'] = true;
        return self::get($params);
    }

    /**
     * Store new data
     *
     * @param array $inputs
     * @return bool
     */
    public static function store($inputs = [])
    {
        $store = [];

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                $store[$key] = $value;
            }
        }

        $store['type'] = 'text';
        if (isset($inputs['file'])) {
            $ext = $inputs['file']->getClientOriginalExtension();
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'mp4', '3gp', 'docx', 'doc', 'pdf', 'rar', 'zip'])) {
                return 0;
            }

            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $store['type'] = 'image';
            }

            if (in_array($ext, ['mp4', '3gp'])) {
                $store['type'] = 'video';
            }

            if (in_array($ext, ['docx', 'doc', 'pdf'])) {
                $store['type'] = 'doc';
            }

            if (in_array($ext, ['rar', 'zip'])) {
                $store['type'] = 'file';
            }

            $store['message'] = upload_any_file($inputs['file']);
        } else {
            if (!isset($store['message'])) {
                return 0;
            }

            if ($store['message'] == '') {
                return 0;
            }
        }

        $store['created_at'] = sql_date();
        return (int)self::insertGetId($store);
    }

    /**
     * Update data
     *
     * @param $id
     * @param array $inputs
     * @param null $column_name
     * @return bool
     */
    public static function edit($id, $inputs = [], $column_name = null)
    {
        $update = [];
        $query = null;

        if (!$column_name) {
            $column_name = 'id';
        }

        if ($id && !is_array($column_name)) {
            $query = self::where($column_name, $id);
        } else {
            $i = 0;

            foreach ($column_name as $key => $value) {
                if (!in_array($key, self::$writable_columns)) {
                    return false;
                }

                if (!$i) {
                    $query = self::where($key, $value);
                } else {
                    if ($query) {
                        $query->where($key, $value);
                    }
                }

                $i++;
            }
        }

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                $update[$key] = $value;
            }
        }

        return (bool)$query->update($update);
    }

    /**
     * Delete data
     *
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function remove($id)
    {
        return (bool)self::destroy($id);
    }

    /**
     * Add formatting on data
     *
     * @param $query
     * @param array $params
     * @return null
     */
    private static function _format($query, $params = [])
    {
        if (isset($params['single'])) {
            if (!$query) {
                return null;
            }

            // avatar image
            $query->from_avatar = get_image($query->from_image_id, 'avatar');
            if (!isset($params['group_id'])) {
                $query->to_avatar = get_image($query->to_image_id, 'avatar');
            }

            // file url
            $query->file = null;
            if (in_array($query->type, ['image', 'video', 'file'])) {
                $query->file = url('private/any/' . $query->message);
            }
        } else {
            foreach ($query as $row) {
                // avatar image
                $row->from_avatar = get_image($row->from_image_id, 'avatar');
                if (!isset($params['group_id'])) {
                    $row->to_avatar = get_image($row->to_image_id, 'avatar');
                }

                // file url
                $row->file = null;
                if (in_array($row->type, ['image', 'video', 'file'])) {
                    $row->file = url('private/any/' . $row->message);
                }
            }
        }

        return $query;
    }
}