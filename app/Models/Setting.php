<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;
use App\Models\Vendor\Facades\SettingCategory;

class Setting extends BaseModel
{
    protected $tableName = 'settings';
    protected $writableColumns = [
        // input_type: text, select, checkbox_multiple, checkbox_single
        'category_setting_id', 'name', 'key', 'value', 'description', 'input_type', 'input_value', 'attributes', 'is_disabled',
    ];

    protected $inputBooleans = ['is_disabled'];

    protected $columnHasRelations = ['category_setting_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    protected function customQueries($query): void
    {
        $query->join('setting_categories', 'settings.category_setting_id', '=', 'setting_categories.id');
    }

    protected function customQuerySelectList(): array
    {
        return [
            'category_name' => 'setting_categories.name'
        ];
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        $row->original_value = $row->value;
        if ($row->input_type == 'checkbox_multiple') {
            if ($row->value) {
                $row->value = explode(',', $row->value);
            } else {
                $row->value = [];
            }
        }

        $row->original_input_value = $row->input_value;
        if (in_array($row->input_type, ['select', 'checkbox_multiple'])) {
            if ($row->input_value) {
                $row->input_value = explode(',', $row->input_value);
            } else {
                $row->input_value = [];
            }
        }

        if ($row->input_type == 'checkbox_single') {
            if (!$row->value) {
                $row->value = NULL;
            }
        }

        return $row;
    }

    public function categories()
    {
        return SettingCategory::get();
    }

    /**
     * Fetch only the key value pair for settings
     *
     * @return array
     */
    public function keyValuePair()
    {
        $values = [];

        foreach ((new Setting())->fetchAll() as $row) {
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
}
