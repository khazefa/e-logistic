<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Fpdf_Generator
{
    public function __construct() {
        require_once APPPATH.'third_party/fpdf/fpdf.php';

        $pdf = new FPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $CI =& get_instance();
        $CI->fpdf = $pdf;
    }
}