<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    private static $params;

    private static $username;
    private static $full_name;

    protected static $writable_columns = [
        'first_name', 'middle_name', 'last_name', 'image_id', 'gender', 'address', 'country_id', 'phone', 'birthday', 'username',
        'email', 'password', 'enabled', 'email_confirmed', 'role'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writable_columns);
        parent::__construct($attributes);
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function get($params = [])
    {
        $select[] = 'users.*';

        self::$full_name = 'CONCAT(first_name, " ", last_name)';
        $select[] = DB::raw(self::$full_name . ' as full_name');

        self::$username = '(' . self::_username() . ')';
        $select[] = DB::raw(self::$username . ' as username');

        $query = self::select($select);

        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }

        if (isset($params['username'])) {
            $query->where(DB::raw(self::$username), '=', $params['username']);
        }

        if (isset($params['email'])) {
            $query->where('email', $params['email']);
        }

        if (isset($params['role'])) {
            $query->where('role', $params['role']);
        }

        if (isset($params['gender'])) {
            $query->where('gender', $params['gender']);
        }

        if (isset($params['search'])) {
            self::$params = $params;

            $query->where(function ($query) {
                $query->where('first_name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('middle_name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('last_name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('email', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere(DB::raw(self::$username), 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere(DB::raw(self::$full_name), 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        if (isset($params['country_id'])) {
            $query->where('country_id', (int)$params['country_id']);
        }

        if (isset($params['enabled'])) {
            $query->where('enabled', (int)$params['enabled']);
        }

        if (isset($params['email_confirmed'])) {
            $query->where('email_confirmed', (int)$params['email_confirmed']);
        }

        $query->orderBy('created_at', 'DESC');

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
     * Get single data
     *
     * @param $id
     * @param string $column
     * @return null
     */
    public static function single($id, $column = 'id')
    {
        if (!$id) {
            return null;
        }
        return self::get([
            'single' => true,
            $column => $id
        ]);
    }

    /**
     * Username query string
     *
     * @return string
     */
    private static function _username()
    {
        return 'SELECT name FROM slugs WHERE source_id = users.id AND source_type = "user"';
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
        } else if (is_array($column_name)) {
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
        } else {
            return false;
        }

        if (!$query) {
            return false;
        }

        if (!$query->count()) {
            return false;
        }

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                if ($key === 'password') {
                    if ($value) {
                        $update[$key] = bcrypt($value);
                    }
                } else if ($key === 'image') {
                    $user = $query->first();

                    // image
                    $upload_image = upload_image($value, [
                        'user_id' => $user->id,
                        'source_id' => $user->id,
                        'title' => $user->first_name . ' ' . $user->last_name,
                        'type' => 'user',
                        'crop_auto' => true
                    ], $user->image_id);

                    if ($upload_image) {
                        $update['image_id'] = $upload_image;
                    }
                } else if ($key === 'birthday') {
                    if ($value) {
                        $update[$key] = sql_date($value, true);
                    }
                } else if ($key === 'country_id') {
                    if ($value && is_numeric($value) && $value > 0) {
                        $update[$key] = $value;
                    }
                } else if ($key === 'username') {
                    if ($value) {
                        $slug = Slug::get([
                            'source_id' => $id,
                            'source_type' => 'user',
                            'single' => true
                        ]);

                        if ($slug) {
                            Slug::edit($slug->id, [
                                'name' => $value
                            ]);
                        }
                    }
                } else {
                    $update[$key] = clean($value);
                }
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
        // delete all related images to user
        Image::destroySource($id, 'user');

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
                return $query;
            }

            // country
            $query->country = Country::find($query->country_id);

            // birthday
            $query->birthday = date('F j, Y', strtotime($query->birthday));

            // age
            $query->age = count_years($query->birthday);

            // avatar image
            $query->avatar = get_image($query->image_id, 'avatar');
        } else {
            foreach ($query as $row) {
                // country
                $row->country = Country::find($row->country_id);

                // birthday
                $row->birthday = date('F j, Y', strtotime($row->birthday));

                // age
                $row->age = count_years($row->birthday);

                // avatar image
                $row->avatar = get_image($row->image_id, 'avatar');
            }
        }

        return $query;
    }
}