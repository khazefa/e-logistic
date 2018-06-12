<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package     PDF Generator
 * @author      Sigit Prayitno
 * @copyright   Copyright(c) 2016
 * @version     1.0.0
 **/
 require_once(APPPATH ."third_party/dompdf/autoload.inc.php");
 use Dompdf\Dompdf;
 
class PdfGenerator
{
    private $CI;

    function __construct()
    {
        $this->CI = &get_instance();
    }

    public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait")
    {
        define('DOMPDF_ENABLE_AUTOLOAD', true);

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            $dompdf->stream($filename.".pdf", array("Attachment" => false));
        } else {
            return $dompdf->output();
        }
        // $dompdf->stream($filename.'.pdf',array("Attachment"=>1));
    }
}