<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\CSV;

class CSVSupport
{
    public function cleanParams($inputs)
    {
        $params = [];

        foreach ($inputs as $key => $value) {
            if ($value !== '' && $value !== null) {
                $params[$key] = $value;
            }
        }

        return $params;
    }
}
