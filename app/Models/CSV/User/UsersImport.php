<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\CSV\User;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    private $hidden = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User($this->headingValues($row));
    }

    public function headingValues($row)
    {
        $data = [];
        foreach ($this->columns() as $column) {
            if (isset($row[$column])) {
                $data[$column] = $row[$column];
            }
        }

        // create a default password
        if (!isset($data['password'])) {
            $data['password'] = bcrypt(str_random());
        }

        return $data;
    }

    public function columns(): array
    {
        $cleanHidden = array_diff(User::getColumns(), $this->hidden);
        $cleanColumns = array_diff($this->hidden, User::getColumns());
        $final_output = array_merge($cleanHidden, $cleanColumns);

        return $final_output;
    }
}
