<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\CSV;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class BaseReport
{
    private $_params = [
        'exclude' => [],
        'name' => null,
        'sheetName' => null,
        'tableName' => null,
        'data' => []
    ];

    public function __construct($params = [])
    {
        $this->_params = $params;
    }

    /**
     * Download master list
     *
     * @throws \Exception
     */
    public function masterList()
    {
        $exclude = $this->_params['exclude'] ?? [];
        $csvName = $this->_params['name'] ?? str_random(4);
        $sheetName = $this->_params['sheetName'] ?? $csvName;
        $tableName = $this->_params['tableName'] ?? null;
        $masterData = $this->_params['data'] ?? null;

        if (!$tableName) {
            throwError('DB_INVALID_TABLE');
        }

        Excel::create($csvName . '-' . time(), function ($excel) use ($exclude, $sheetName, $tableName, $masterData) {
            $excel->sheet($sheetName, function ($sheet) use ($exclude, $tableName, $masterData) {
                $columns = Schema::getColumnListing($tableName);
                $data = ($masterData) ? $masterData : DB::table($tableName)->get();

                $rows = [];
                $i = 1;

                // formatting font and background
                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri'
                    )
                ));

                // header
                foreach ($columns as $column) {
                    if (!in_array($column, $exclude)) {
                        $rows[$column] = $column;
                        $sheet->row($i, $rows);

                        $sheet->row($i, function ($row) {
                            $row->setBackground('#489EE7');
                            $row->setFontColor('#ffffff');
                        });
                    }
                }
                $i++;

                // data
                foreach ($data as $item) {
                    foreach ($columns as $column) {
                        if (!in_array($column, $exclude)) {
                            $rows[$column] = $item->$column;
                        }
                    }

                    $sheet->row($i, $rows);
                    $i++;
                }
            });
        })->download('xls');
    }
}