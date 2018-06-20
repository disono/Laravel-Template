<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\CSV;

use Maatwebsite\Excel\Facades\Excel;

class CSVBase
{
    public $filename = null;

    public $title = null;
    public $author = null;
    public $company = null;
    public $description = null;
    public $sheetNames = [];

    public $password = null;
    private $_params = [];

    public function __construct($params = [])
    {
        $this->_params = $params;
    }

    public function query()
    {
        return [];
    }

    public function template($data = [])
    {
        $this->_setHeaders();

        return Excel::create($this->filename, function ($excel) use ($data) {

            foreach ($this->sheetNames as $sheetName) {
                $excel->sheet($sheetName, function ($sheet) use ($data) {

                    $sheet->setOrientation('landscape');

                    // Header
                    $rowIndex = 1;
                    $sheet->row($rowIndex, $data);

                });
            }

        })->export('xls');
    }

    public function headings(): array
    {
        return [];
    }

    public function insert($row)
    {

    }

    public function store($results = [])
    {
        foreach ($results as $row) {
            $this->insert($row);
        }
    }

    public function map($row): array
    {
        return [];
    }

    public function download($filename = null)
    {
        $this->filename = $filename;
        $this->_setHeaders();

        return Excel::create($this->filename, function ($excel) {

            // Set the title
            $excel->setTitle($this->title);

            // Chain the setters
            $excel->setCreator($this->author)
                ->setCompany($this->company);

            // Call them separately
            $excel->setDescription($this->description);

            foreach ($this->sheetNames as $sheetName) {
                $excel->sheet($sheetName, function ($sheet) {

                    $sheet->setOrientation('landscape');

                    // protected sheet
                    if ($this->password !== null) {
                        $sheet->protect($this->password);
                    }

                    // Header
                    $rowIndex = 1;
                    $sheet->row($rowIndex, $this->headings());

                    // Body
                    foreach ($this->query() as $row) {
                        $rowIndex++;
                        $sheet->row($rowIndex, $this->map($row));
                    }

                });
            }

        })->export('xls');
    }

    public function import($filename = null)
    {
        $this->_setHeaders();

        Excel::load($filename, function ($reader) {

            $this->store($reader->all());

        });
    }

    private function _setHeaders()
    {
        if ($this->filename === null) {
            $this->filename = time() . '.xls';
        } else {
            $this->filename = $this->filename . '_' . time() . '_' . str_random(8) . '.xls';
        }

        if ($this->title === null) {
            $this->title = time();
        }

        if ($this->author === null) {
            $this->author = __settings('title')->value;
        }

        if ($this->company === null) {
            $this->company = __settings('title')->value;
        }

        if ($this->description === null) {
            $this->description = __settings('title')->value;
        }

        if (count($this->sheetNames) === 0) {
            $this->sheetNames = [time() . '_' . str_random(4)];
        }
    }
}