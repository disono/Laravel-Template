<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\CSV\User;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    private $params = [];
    private $exportTemplate = false;
    private $hidden = ['password'];

    public function __construct($params, $isTemplate = false)
    {
        $this->params = $params;
        $this->exportTemplate = $isTemplate;
    }

    public function collection()
    {
        if ($this->exportTemplate) {
            return collect([]);
        }

        return collect((new User())->fetchAll($this->params));
    }

    public function map($data): array
    {
        $columns = [];

        foreach ($this->columns() as $column) {
            $columns[] = $data->$column;
        }

        return $columns;
    }

    public function columns(): array
    {
        $cleanHidden = array_diff((new User())->getWritableColumns(), $this->hidden);
        $cleanColumns = array_diff($this->hidden, (new User())->getWritableColumns());
        return array_merge($cleanHidden, $cleanColumns);
    }

    public function headings(): array
    {
        return $this->columns();
    }
}
