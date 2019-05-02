<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class Setting extends BaseModel
{
    // input_type: text, select, checkbox

    protected static $tableName = 'settings';
    protected static $writableColumns = [
        'name', 'key', 'value', 'description', 'input_type', 'input_value', 'attributes', 'is_disabled',
        'category'
    ];

    protected static $inputBooleans = ['is_disabled'];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }

    public static function listColumns()
    {
        return self::$writableColumns;
    }

    /**
     * Custom formatting for storing
     *
     * @param $tableName
     * @param $inputs
     * @return mixed
     */
    public static function formatStore($tableName, $inputs)
    {
        return self::formatInputs($inputs);
    }

    /**
     * Format inputs before saving
     *
     * @param $inputs
     * @return mixed
     */
    private static function formatInputs($inputs)
    {
        $inputs['input_type'] = $inputs['input_type'] ?? 'text';
        $inputs['input_value'] = $inputs['input_value'] ?? null;

        if ($inputs['input_value']) {
            if (in_array($inputs['input_type'], ['select', 'checkbox'])) {
                $inputs['input_value'] = implode(',', $inputs['input_value']);
            }
        }

        return $inputs;
    }

    /**
     * Custom formatting for editing
     *
     * @param $tableName
     * @param $inputs
     * @return mixed
     */
    public static function formatEdit($tableName, $inputs)
    {
        return self::formatInputs($inputs);
    }

    /**
     * Fetch only the key value pair for settings
     *
     * @return array
     */
    public static function keyValuePair()
    {
        $values = [];

        foreach (self::fetchAll() as $row) {
            if (!$row->is_disabled) {
                $values[$row->key] = [
                    'name' => $row->name,
                    'value' => $row->value,
                    'description' => $row->description,
                    'input_type' => $row->input_type
                ];
            }
        }

        return $values;
    }

    public static function categories()
    {
        return self::select('category')->groupBy('category')->get();
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    protected static function dataFormatting($row)
    {
        $row->original_value = $row->value;
        if ($row->input_type == 'checkbox') {
            if ($row->value) {
                $row->value = explode(',', $row->value);
            } else {
                $row->value = [];
            }
        }

        $row->original_input_value = $row->input_value;
        if (in_array($row->input_type, ['select', 'checkbox'])) {
            if ($row->input_value) {
                $row->input_value = explode(',', $row->input_value);
            } else {
                $row->input_value = [];
            }
        }

        return $row;
    }
}
