<?php

namespace App\Models;

use Illuminate\Support\Facades\Mail;

class Subscriber extends AppModel
{
    protected static $writable_columns = [
        'email', 'first_name', 'last_name', 'ip_address'
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
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function get($params = [])
    {
        $table_name = (new self)->getTable();
        $select[] = $table_name . '.*';
        $query = self::select($select);

        // where equal
        $query = self::_whereEqual($query, $params, self::$writable_columns, $table_name);

        // exclude and include
        $query = self::_excInc($query, $params, self::$writable_columns, $table_name);

        // search
        $query = self::_search($query, $params, self::$writable_columns, $table_name);

        $query->orderBy('created_at', 'DESC');

        self::$query_object = $query;
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
     * Send email to subscribers
     */
    public static function sendEmail()
    {
        $subscribers = self::getAll();
        $pages = Page::get([
            'include' => ['page_category_id' => [1]],
            'is_email_to_subscriber' => 1
        ]);

        $num_success = 0;
        $num_error = 0;
        if (count($pages)) {
            foreach ($subscribers as $row) {
                try {
                    // send email for password reset
                    Mail::send('vendor.notifications.subscriber', [
                        'full_name' => $row->full_name,
                        'pages' => $pages
                    ], function ($m) use ($row) {
                        $m->from(env('MAIL_FROM_ADDRESS'), 'Latest news letter at ' . app_header('title'));
                        $m->to($row->email, $row->full_name)->subject('Latest news at ' . app_header('title'));
                    });

                    $num_success++;
                } catch (\Swift_SwiftException $e) {
                    // error sending email
                    error_logger($e->getMessage());
                    $num_error++;
                }
            }
        }

        // update the pages to mark as sent email's
        if ($num_success) {
            foreach ($pages as $row) {
                Page::edit($row->id, [
                    'is_email_to_subscriber' => 0
                ]);
            }
        }

        echo 'Successfully sent ' . $num_success . ' email and ' . $num_error . ' unsuccessful.';
    }

    /**
     * Add formatting on data
     *
     * @param $query
     * @param array $params
     * @return null
     */
    public static function _format($query, $params = [])
    {
        if (isset($params['single'])) {
            if (!$query) {
                return null;
            }

            $query->full_name = $query->first_name . ' ' . $query->last_name;
        } else {
            foreach ($query as $row) {
                $row->full_name = $row->first_name . ' ' . $row->last_name;
            }
        }

        return $query;
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

        // email is required
        if (!isset($inputs['email'])) {
            return false;
        }

        // no duplicates
        if (self::where('email', $inputs['email'])->count()) {
            return false;
        }

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                $store[$key] = $value;
            }
        }

        $store['created_at'] = sql_date();
        return (int)self::insertGetId($store);
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

        // store to activity logs
        ActivityLog::store($id, self::$writable_columns, $query->first(), $inputs, (new self)->getTable());

        return (bool)$query->update($update);
    }
}