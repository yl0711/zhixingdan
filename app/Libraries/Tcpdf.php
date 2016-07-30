<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/7/28
 * Time: 下午9:34
 */

namespace App\Libraries;


class Tcpdf
{
    public function __construct()
    {
        // always load alternative config file for examples
        require_once('TCPDF/config/tcpdf_config_alt.php');
        // Include the main TCPDF library (search the library on the following directories).
        require_once('TCPDF/tcpdf.php');
    }

    public function makePdf($html){
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //$pdf->SetFont('times', 'I', 20);
        $pdf->writeHTML($html);

        return $pdf->Output("test001.pdf");
    }
}