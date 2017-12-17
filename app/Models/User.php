<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    protected static $writable_columns = [
        'first_name', 'middle_name', 'last_name',
        'image_id',

        'gender', 'address', 'country_id', 'phone', 'birthday', 'about',
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
    public static function fetch($params = [])
    {
        $table_name = (new self)->getTable();
        $select[] = $table_name . '.*';

        $full_name = 'CONCAT(first_name, " ", last_name)';
        $select[] = DB::raw($full_name . ' as full_name');

        $username = '(' . self::_username() . ')';
        $select[] = DB::raw($username . ' as username');

        $query = self::select($select);

        if (isset($params['username'])) {
            $query->where(DB::raw($username), '=', $params['username']);
        }

        // where equal
        $query = AppModel::_whereEqual($query, $params, self::$writable_columns, $table_name);

        // exclude and include
        $query = AppModel::_excInc($query, $params, self::$writable_columns, $table_name);

        // search
        $query = AppModel::_search($query, $params, self::$writable_columns, $table_name, [
            DB::raw($username), DB::raw($full_name)
        ]);

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
        return self::fetch($params);
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
        return self::fetch([
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
                } else if ($key === 'birthday') {
                    if ($value) {
                        $update[$key] = sql_date($value, true);
                    }
                } else if ($key === 'country_id') {
                    if ($value && is_numeric($value) && $value > 0) {
                        $update[$key] = $value;
                    }
                } else {
                    $update[$key] = clean($value);
                }
            }
        }

        // avatar
        if (isset($inputs['image'])) {
            $user = $query->first();

            if ($user) {
                // image
                $upload_image = upload_image($inputs['image'], [
                    'user_id' => $user->id,
                    'source_id' => $user->id,
                    'title' => $user->first_name . ' ' . $user->last_name,
                    'type' => 'user',
                    'crop_auto' => true
                ], $user->image_id);

                if ($upload_image) {
                    $update['image_id'] = $upload_image;
                }
            }
        }

        // username
        if (isset($inputs['username'])) {
            $slug = Slug::fetch([
                'source_id' => $id,
                'source_type' => 'user',
                'single' => true
            ]);

            if ($slug) {
                Slug::edit($slug->id, [
                    'name' => $inputs['username']
                ]);
            }
        }

        // store to activity logs
        ActivityLog::log($id, self::$writable_columns, $query->first(), $inputs, (new self)->getTable());

        return (bool)$query->update($update);
    }

    /**
     * Download user's avatar
     *
     * @param $user_id
     * @param $social_id
     */
    public static function downloadFBAvatar($user_id, $social_id)
    {
        if (!$social_id) {
            return;
        }

        $user = self::single($user_id);
        if (!$user) {
            return;
        }

        // save the image from auth social (Facebook)
        // we only support facebook
        if ($user && $social_id) {
            $filename = download_image($social_id . '-' . time() . '-' . str_random(), 'http://graph.facebook.com/' . $social_id . '/picture?type=large');

            if ($filename) {
                $image_id = Image::store([
                    'user_id' => $user->id,
                    'source_id' => $user->id,
                    'title' => $user->first_name . ' ' . $user->last_name,
                    'type' => 'user',
                    'filename' => $filename
                ]);

                self::edit($user_id, [
                    'image_id' => $image_id
                ]);
            }
        }
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

        // delete the slug/username
        Slug::where('source_type', 'user')->where('source_id', $id)->delete();

        // delete social auth
        SocialAuth::where('user_id', $id)->delete();

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

            self::_dataFormat($query);
        } else {
            foreach ($query as $row) {
                self::_dataFormat($row);
            }
        }

        return $query;
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    private static function _dataFormat($row)
    {
        // country
        $row->country = Country::find($row->country_id);

        // birthday
        $row->birthday = date('F j, Y', strtotime($row->birthday));

        // age
        $row->age = count_years($row->birthday);

        // avatar image
        $row->avatar = get_image($row->image_id, 'avatar');

        return $row;
    }
}