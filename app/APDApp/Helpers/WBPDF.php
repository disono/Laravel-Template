<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\APDApp\Helpers;

use Barryvdh\DomPDF\Facade as PDF;
use FPDI;

class WBPDF
{
    /**
     * Modify existing PDF file
     *
     * @param null $file
     * @param array $params
     *
     * file: 'private/doc/file_name.pdf'
     * params: ['content' => [
     *  1 => ['body' => 'body 1', 'position' => [x, y], 'color' => [50, 54, 57], 'size' => 12, 'fount' => 'Helvetica']
     * ]]
     *
     * @return bool
     */
    public static function modify($file = null, $params = [])
    {
        if (!$file) {
            return false;
        }

        // initiate FPDI
        $pdf = new FPDI();

        // remove the line on top
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        try {
            // set the source file
            $pdf->setSourceFile($file);

            for ($page_num = 1; $page_num < self::_count_pages($file) + 1; $page_num++) {
                // add a page
                $pdf->AddPage();

                // import page
                // use the imported page and place it at position 0, 0 with a width of 210 mm (A4 size paper)
                $tplIdx = $pdf->importPage($page_num);
                $s = $pdf->getTemplateSize($tplIdx);
                $pdf->useTemplate($tplIdx, 0, 0, $s['w'], $s['h']);

                // add content
                $pdf = self::_page_content($pdf, $page_num, $params);
            }

            $pdf->Output();
        } catch (\Exception $e) {
            error_logger($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Blade to PDF
     *
     * @param $view
     * @param array $data
     * @return mixed
     */
    public static function blade($view, $data = [])
    {
        $pdf = PDF::loadView($view, $data);

        // rewrite get_computed_border_radius
        // BUG on PHP 7+
        // Style.php
        // line 1315

        return $pdf->download(time() . '_pdf.pdf');
    }

    /**
     * Add content formatting and body
     *
     * @param $pdf
     * @param $page_num
     * @param $params
     * @return mixed
     */
    private static function _page_content($pdf, $page_num, $params)
    {
        if (isset($params['content'])) {
            $content = $params['content'];

            if (isset($content[$page_num])) {
                $page = $content[$page_num];

                // initialize default values
                $font = (isset($page['font'])) ? $page['font'] : 'Helvetica';
                $size = (isset($page['size'])) ? $page['size'] : 12;
                $color = (isset($page['color'])) ? $page['color'] : [50, 54, 57];
                $position = (isset($page['position'])) ? $page['position'] : [30, 30];
                $body = (isset($page['body'])) ? $page['body'] : null;

                // now write some text above the imported page
                $pdf->SetFont($font);
                $pdf->SetFontSize($size);
                $pdf->SetTextColorArray($color);
                $pdf->SetXY($position[0], $position[1]);
                $pdf->Write(0, $body);
            }
        }

        return $pdf;
    }

    /**
     * Count number of pages
     *
     * @param $file
     * @return int
     */
    private static function _count_pages($file)
    {
        $pdftext = file_get_contents($file);
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        return $num;
    }
}