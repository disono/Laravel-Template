<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\CSV;

use App\Models\User;

class UserCSV extends CSVBase
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
        parent::__construct();
    }

    public function query()
    {
        return User::fetchAll($this->_params);
    }

    public function insert($row)
    {
        User::store($row);
    }

    public function headings(): array
    {
        return [
            '#',
            'Full Name',
            'Role',
            'Email',
            'Username',
            'Phone',
            'Address',
            'Date',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->full_name,
            $row->role,
            $row->email,
            $row->username,
            $row->phone,
            $row->address,
            $row->created_at,
        ];
    }
}