<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */
namespace App\Disono\Helpers;

use FPDI;

class WBPDF
{
    public static function modify()
    {
        // initiate FPDI
        $pdf = new FPDI();

        // add a page
        $pdf->AddPage();

        return null;
    }
}