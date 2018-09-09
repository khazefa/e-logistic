<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CReports (CReportsController)
 * CReports Class to control Reports.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */

// Load library phpspreadsheet

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// End load library phpspreadsheet
        
class CReports extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSuperAdmin()){
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect('cl');
    }
    
    public function report_consumed_part(){
        $this->global['pageTitle'] = 'Report Transacted Part - '.APP_NAME;
        $this->global['pageMenu'] = 'Report Transacted Part';
        $this->global['contentHeader'] = 'Report Transacted Part';
        $this->global['contentTitle'] = 'Report Transacted Part';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_wr'] = $this->get_list_wh();
        $this->loadViews('front/reports/consumed-part', $this->global, $data);
    }
    
    public function report_used_part(){
        $this->global['pageTitle'] = 'Report Used Part - '.APP_NAME;
        $this->global['pageMenu'] = 'Report Used Part';
        $this->global['contentHeader'] = 'Report Used Part';
        $this->global['contentTitle'] = 'Report Used Part';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_wr'] = $this->get_list_wh();
        $this->loadViews('front/reports/used-part', $this->global, $data);
    }
    
    public function report_replenish_plan(){
        $this->global['pageTitle'] = 'Report Replenish Plan - '.APP_NAME;
        $this->global['pageMenu'] = 'Report Replenish Plan';
        $this->global['contentHeader'] = 'Report Replenish Plan';
        $this->global['contentTitle'] = 'Report Replenish Plan';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_wr'] = $this->get_list_wh();
        $this->loadViews('front/reports/replenish-plan', $this->global, $data);
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_wh(){
        $rs = array();
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
            $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
            $row['sort'] = stripslashes($r->field_order) ? filter_var($r->field_order, FILTER_SANITIZE_NUMBER_INT) : 0;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get detail information
     */
    private function get_info_warehouse_name($fcode){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $wh_name = "";
        foreach ($rs as $r) {
            $wh_name = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
        }
        
        return $wh_name;
    }
    
    public function print_daily_report(){
        $rs = array();
        $arrWhere = array();

        if($this->isStaff()){
            $code = $this->repo;
        }else{
            $code = $this->input->post('fcode', TRUE);
        }
        
        $fcode = $code;
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $createdBy = $this->name;
        
        if(empty($fcode)){
            redirect('cl');
        }elseif(empty($fdate1) && empty($fdate2)){
            redirect('cl');
        }else{
            //Parameters for cURL
            $arrWhere = array('fcode'=> strtoupper($fcode), 'fdate1'=> $fdate1.' 00:00:00', 'fdate2'=> $fdate2.' 23:59:59');
            $fslname = "";
            $fslname = $this->get_info_warehouse_name($fcode);
            $curdateID = tgl_indo(date('Y-m-d'));
            $curdate = date('dmY');
            $reportdate = date('dmY', strtotime($fdate1));
            $reportdate2 = date('dmY', strtotime($fdate2));
            $reportdateID = tgl_indo($fdate1);
            $reportdateID2 = tgl_indo($fdate2);

            $orientation = "P";
            $paper_size = "A4";
            $width = 0;
            $height = 0;

            switch ($orientation) {
                case "P":
                   switch ($paper_size) {
                        case "A4":
                            $width = 210;
                            $height = 297;
                        break;
                        case "A5":
                            $width = 148;
                            $height = 210;
                        break;
                        default:
                            $width = 210;
                            $height = 297;
                        break;
                    }
                break;

                case "L":
                    switch ($paper_size) {
                        case "A4":
                            $width = 297;
                            $height = 210;
                        break;
                        case "A5":
                            $width = 210;
                            $height = 148;
                        break;
                        default:
                            $width = 297;
                            $height = 210;
                        break;
                    }
                break;

                default:
                    switch ($paper_size) {
                        case "A4":
                            $width = 210;
                            $height = 297;
                        break;
                        case "A5":
                            $width = 148;
                            $height = 210;
                        break;
                        default:
                            $width = 210;
                            $height = 297;
                        break;
                    }
                break;
            }

            // config fpdf options
            $config=array($orientation=>'P','size'=>$paper_size);
            $this->load->library('mypdf',$config);
            $this->mypdf->AliasNbPages();
            $this->mypdf->AddPage();
            $this->mypdf->Image(base_url().'assets/public/images/logo.png',10,8,($width*(15/100)),15);
//            $this->mypdf->ln(20);

    //            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width-($width*0.1)),7,'Report Date: '.$reportdateID.' to '.$reportdateID2,0,1,'R');

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width-($width*0.1)),7,'Generated by: '.$createdBy,0,1,'R');
            
            $this->mypdf->ln(10);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width-20),7,'Consumed Part by '.$fslname,0,1,'C');
            $this->mypdf->ln(2);
            // Garis atas untuk header
    //        $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Requested PN',1,0);
            $this->mypdf->Cell(($width*(23/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(15/100)),6,'Serial No.',1,0);
            $this->mypdf->Cell(($width*(10/100)),6,'Ticket No.',1,0);
            $this->mypdf->Cell(($width*(7.5/100)),6,'FSE ID',1,0);
            $this->mypdf->Cell(($width*(20/100)),6,'Assigned FSE',1,1);

            $this->mypdf->SetFont('Arial','',9);

            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_daily_reports'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            foreach( $rs as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $serialnum = filter_var($row->serial_number, FILTER_SANITIZE_STRING);
                $ticket = filter_var($row->outgoing_ticket, FILTER_SANITIZE_STRING);
                $engineer = filter_var($row->engineer_key, FILTER_SANITIZE_STRING);
                $engineer_name = filter_var($row->engineer_name, FILTER_SANITIZE_STRING);

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(23/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(15/100)),6,$serialnum,1,0);
                $this->mypdf->CellFitScale(($width*(10/100)),6,$ticket,1,0);
                $this->mypdf->CellFitScale(($width*(7.5/100)),6,$engineer,1,0,'C');
                $this->mypdf->CellFitScale(($width*(20/100)),6,$engineer_name,1,1);
            }

            $title = 'Consumed '.$fslname.' ('.$reportdate.'-'.$reportdate2.')';
            $this->mypdf->SetTitle($title);
    //        return $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('I', $title.'.pdf');
        }
    }
    
    public function print_replenish_plan(){
        $rs = array();
        $arrWhere = array();

        if($this->isStaff()){
            $code = $this->repo;
        }else{
            $code = $this->input->post('fcode', TRUE);
        }
        
        $fcode = $code;
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $createdBy = $this->name;
        
        if(empty($fdate1) && empty($fdate2)){
            redirect('cl');
        }else{
            //Parameters for cURL
            $arrWhere = array('fcode'=> strtoupper($fcode), 'fdate1'=> $fdate1.' 00:00:00', 'fdate2'=> $fdate2.' 23:59:59');
            $fslname = "";
            $fslname = $this->get_info_warehouse_name($fcode);
            $curdateID = tgl_indo(date('Y-m-d'));
            $curdate = date('dmY');
            $reportdate = date('dmY', strtotime($fdate1));
            $reportdate2 = date('dmY', strtotime($fdate2));
            $reportdateID = tgl_indo($fdate1);
            $reportdateID2 = tgl_indo($fdate2);

            $orientation = "P";
            $paper_size = "A4";
            $width = 0;
            $height = 0;

            switch ($orientation) {
                case "P":
                   switch ($paper_size) {
                        case "A4":
                            $width = 210;
                            $height = 297;
                        break;
                        case "A5":
                            $width = 148;
                            $height = 210;
                        break;
                        default:
                            $width = 210;
                            $height = 297;
                        break;
                    }
                break;

                case "L":
                    switch ($paper_size) {
                        case "A4":
                            $width = 297;
                            $height = 210;
                        break;
                        case "A5":
                            $width = 210;
                            $height = 148;
                        break;
                        default:
                            $width = 297;
                            $height = 210;
                        break;
                    }
                break;

                default:
                    switch ($paper_size) {
                        case "A4":
                            $width = 210;
                            $height = 297;
                        break;
                        case "A5":
                            $width = 148;
                            $height = 210;
                        break;
                        default:
                            $width = 210;
                            $height = 297;
                        break;
                    }
                break;
            }

            // config fpdf options
            $config=array($orientation=>'P','size'=>$paper_size);
            $this->load->library('mypdf',$config);
            $this->mypdf->AliasNbPages();
            $this->mypdf->AddPage();
            $this->mypdf->Image(base_url().'assets/public/images/logo.png',10,8,($width*(15/100)),15);
//            $this->mypdf->ln(20);

    //            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width-($width*0.1)),7,'Report Date: '.$reportdateID.' to '.$reportdateID2,0,1,'R');

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width-($width*0.1)),7,'Generated by: '.$createdBy,0,1,'R');

            $this->mypdf->ln(10);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width-20),7,'Replenish Plan '.$fslname,0,1,'C');
            $this->mypdf->ln(2);
            // Garis atas untuk header
    //        $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Requested PN',1,0);
            $this->mypdf->Cell(($width*(35/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(5/100)),6,'Qty.',1,0);
            $this->mypdf->Cell(($width*(20/100)),6,'Delivery Notes',1,1);

            $this->mypdf->SetFont('Arial','',9);

            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_replenish_plan'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
            
//            var_dump($rs);exit();

            foreach( $rs as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $qty = filter_var($row->qty, FILTER_SANITIZE_NUMBER_INT);
//                $notes = $row->o_delivery_notes === "" ? "-" : filter_var($row->o_delivery_notes, FILTER_SANITIZE_STRING);
                $notes = "-";

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(35/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(5/100)),6,$qty,1,0);
                $this->mypdf->CellFitScale(($width*(20/100)),6,$notes,1,1);
            }

            $title = 'Replenish '.$fslname.' ('.$reportdate.'-'.$reportdate2.')';
            $this->mypdf->SetTitle($title);
    //        return $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('I', $title.'.pdf');
        }
    }
    
    public function export_replenish_plan(){
        $rs = array();
        $arrWhere = array();
        
        if($this->isStaff()){
            $code = array($this->repo);
        }else{
//            $code = $this->input->post('fcode', TRUE);
            $code = isset($_POST['fcode']) ? $_POST['fcode'] : array();
        }
        (int)$c_arr_code = count($code);

        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $createdBy = $this->name;
        
        if(empty($fdate1) && empty($fdate2)){
            redirect('cl');
        }else{            
            $curdateID = tgl_indo(date('Y-m-d'));
            $curdate = date('dmY');
            
            $parseDate1 = empty($fdate1) ? date("d/m/Y") : substr($fdate1, 0, 10);
            $parseDate2 = empty($fdate2) ? date("d/m/Y") : substr($fdate2, 0, 10);
        
            $reportdate = date('dmY', strtotime($parseDate1));
            $reportdate2 = date('dmY', strtotime($parseDate2));
            
            $sqlDate1 = date( "Y-m-d H:i", strtotime($fdate1));
            $sqlDate2 = date( "Y-m-d H:i", strtotime($fdate2));
            
            $reportdateID = tgl_indo(date('Y-m-d', strtotime($parseDate1)));
            $reportdateID2 = tgl_indo(date('Y-m-d', strtotime($parseDate2)));
            
            $title = 'Replenish Plan ('.$reportdate.'-'.$reportdate2.')';
            
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('E-Logistic')
            ->setLastModifiedBy($createdBy)
            ->setTitle($title)
            ->setSubject('Replenish Plan')
            ->setDescription($title.' generated by '.$createdBy)
            ->setKeywords('replenish plan')
            ->setCategory('Report');
            
//            $activeSheet = $spreadsheet->getActiveSheet();
            
            $styleHeaderArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E5E4E2' ),
                ),
            );
            
            //Start adding next sheets
            $x=0;
            foreach($code as $fcode) {
                $fslname = $this->get_info_warehouse_name($fcode);
            
                $spreadsheet->createSheet($x);
                $spreadsheet->setActiveSheetIndex($x);
                $activeSheet = $spreadsheet->getActiveSheet();
                
                $activeSheet->mergeCells('A1:E1');
                $activeSheet->mergeCells('A2:E2');
                $activeSheet
                ->setCellValue('A1', $fslname)
                ->setCellValue('A2', 'Report Date: '.$reportdateID.' to '.$reportdateID2);
                $activeSheet->getStyle('A1')->getFont()->setBold(true);

                // Header data
                $activeSheet
                ->setCellValue('A4', 'Requested PN')
                ->setCellValue('B4', 'Description')
                ->setCellValue('C4', 'Qty')
                ->setCellValue('D4', 'Last Stock')
                ->setCellValue('E4', 'Delivery Notes')
                ;
                $activeSheet->getStyle('A4:E4')->applyFromArray($styleHeaderArray);

                //Parameters for cURL
//                $arrWhere = array('fcode'=> strtoupper($fcode), 'fdate1'=> $fdate1.' 00:00:00', 'fdate2'=> $fdate2.' 23:59:59');
                $arrWhere = array('fcode'=> strtoupper($fcode), 'fdate1'=> $sqlDate1, 'fdate2'=> $sqlDate2);
                //Parse Data for cURL
                $rs_data = send_curl($arrWhere, $this->config->item('api_replenish_plan'), 'POST', FALSE);
                $rs = $rs_data->status ? $rs_data->result : array();

                $i=5; //initial row for displaying data output
                foreach( $rs as $row )
                {
                    $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                    $qty = filter_var($row->qty, FILTER_SANITIZE_NUMBER_INT);
                    $last_stock = filter_var($row->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
    //                $notes = $row->o_delivery_notes === "" ? "-" : filter_var($row->o_delivery_notes, FILTER_SANITIZE_STRING);
                    $notes = "-";

                    //set column width auto size
                    $activeSheet->getColumnDimension('A')->setAutoSize(true);
                    $activeSheet->getColumnDimension('B')->setAutoSize(true);
                    $activeSheet->getColumnDimension('C')->setAutoSize(true);
                    $activeSheet->getColumnDimension('D')->setAutoSize(true);
                    $activeSheet->getColumnDimension('E')->setAutoSize(true);
                    $activeSheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $activeSheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $activeSheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    //fill data row
                    $activeSheet
                    ->setCellValue('A'.$i, $partnum)
                    ->setCellValue('B'.$i, $partname)
                    ->setCellValue('C'.$i, $qty)
                    ->setCellValue('D'.$i, $last_stock)
                    ->setCellValue('E'.$i, $notes);

                    $i++;
                }
    //            $activeSheet->getHeaderFooter()
    //                    ->setOddHeader('&L&B' . 'Report Date: '.$reportdateID.' to '.$reportdateID2);
                $activeSheet->getHeaderFooter()
                        ->setOddFooter('&L&B' . 'Generated by '.$createdBy . '&RPage &P of &N');
                // Rename worksheet
                $activeSheet->setTitle($fcode);
                $x++;
            }

            /**
            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            */

            ob_start();
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->setOffice2003Compatibility(true);
            // $writer->save('php://output');

            $this->load->library('zip');

//            $path = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']); 
            $path = str_replace('\\', '/', FCPATH);
            $path .= '/tmp/'; // destination dir
            $file_name = $title.'.xlsx'; // destination file
            if (file_exists($path.$file_name)) {
                unlink($path.$file_name);
            } else {
               echo "The file filename does not exist";
            }
            $writer->save($path.$file_name);
			
            $this->zip->read_file($path.$file_name,FALSE);
            ob_end_clean();

            // create zip file on server
//            $this->zip->archive($title.'.zip'); //archive zip file in web directory
            // prompt user to download the zip file
            $this->zip->download($title.'.zip');
            exit;
        }
    }
    
    public function export_consumed_part(){
        $rs = array();
        $arrWhere = array();
        
        if($this->isStaff()){
            $code = array($this->repo);
        }else{
//            $code = $this->input->post('fcode', TRUE);
            $code = isset($_POST['fcode']) ? $_POST['fcode'] : array();
        }
        (int)$c_arr_code = count($code);

        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $createdBy = $this->name;
        
        if(empty($fdate1) && empty($fdate2)){
            redirect('cl');
        }else{
            $curdateID = tgl_indo(date('Y-m-d'));
            $curdate = date('dmY');
            $reportdate = date('dmY', strtotime($fdate1));
            $reportdate2 = date('dmY', strtotime($fdate2));
            $reportdateID = tgl_indo($fdate1);
            $reportdateID2 = tgl_indo($fdate2);
            $title = 'Transacted Part ('.$reportdate.'-'.$reportdate2.')';
            
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('E-Logistic')
            ->setLastModifiedBy($createdBy)
            ->setTitle($title)
            ->setSubject('Transacted Part')
            ->setDescription($title.' generated by '.$createdBy)
            ->setKeywords('transacted part')
            ->setCategory('Report');
            
//            $activeSheet = $spreadsheet->getActiveSheet();
            
            $styleHeaderArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E5E4E2' ),
                ),
            );
            
            //Start adding next sheets
            $x=0;
            foreach($code as $fcode) {
                $fslname = $this->get_info_warehouse_name($fcode);
            
                $spreadsheet->createSheet($x);
                $spreadsheet->setActiveSheetIndex($x);
                $activeSheet = $spreadsheet->getActiveSheet();
                
                $activeSheet->mergeCells('A1:F1');
                $activeSheet->mergeCells('A2:F2');
                $activeSheet
                ->setCellValue('A1', $fslname)
                ->setCellValue('A2', 'Report Date: '.$reportdateID.' to '.$reportdateID2);
                $activeSheet->getStyle('A1')->getFont()->setBold(true);

                // Header data
                $activeSheet
                ->setCellValue('A4', 'Requested PN')
                ->setCellValue('B4', 'Description')
                ->setCellValue('C4', 'Serial No.')
                ->setCellValue('D4', 'Ticket No.')
                ->setCellValue('E4', 'FSE ID')
                ->setCellValue('F4', 'Assigned FSE')
                ;
                $activeSheet->getStyle('A4:F4')->applyFromArray($styleHeaderArray);

                //Parameters for cURL
                $arrWhere = array('fcode'=> strtoupper($fcode), 'fdate1'=> $fdate1.' 00:00:00', 'fdate2'=> $fdate2.' 23:59:59');
                //Parse Data for cURL
                $rs_data = send_curl($arrWhere, $this->config->item('api_daily_reports'), 'POST', FALSE);
                $rs = $rs_data->status ? $rs_data->result : array();

                $i=5; //initial row for displaying data output
                foreach( $rs as $row )
                {
                    $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                    $serialnum = filter_var($row->serial_number, FILTER_SANITIZE_STRING);
                    $ticket = filter_var($row->outgoing_ticket, FILTER_SANITIZE_STRING);
                    $engineer = filter_var($row->engineer_key, FILTER_SANITIZE_STRING);
                    $engineer_name = filter_var($row->engineer_name, FILTER_SANITIZE_STRING);

                    //set column width auto size
                    $activeSheet->getColumnDimension('A')->setAutoSize(true);
                    $activeSheet->getColumnDimension('B')->setAutoSize(true);
                    $activeSheet->getColumnDimension('C')->setAutoSize(true);
                    $activeSheet->getColumnDimension('D')->setAutoSize(true);
                    $activeSheet->getColumnDimension('E')->setAutoSize(true);
                    $activeSheet->getColumnDimension('F')->setAutoSize(true);
//                    $activeSheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $activeSheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    $activeSheet->getStyle('D')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    $activeSheet->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                    //fill data row
                    $activeSheet
                    ->setCellValue('A'.$i, $partnum)
                    ->setCellValue('B'.$i, $partname)
                    ->setCellValue('C'.$i, $serialnum.' ')
                    ->setCellValue('D'.$i, $ticket)
                    ->setCellValue('E'.$i, $engineer)
                    ->setCellValue('F'.$i, $engineer_name);

                    $i++;
                }
    //            $activeSheet->getHeaderFooter()
    //                    ->setOddHeader('&L&B' . 'Report Date: '.$reportdateID.' to '.$reportdateID2);
                $activeSheet->getHeaderFooter()
                        ->setOddFooter('&L&B' . 'Generated by '.$createdBy . '&RPage &P of &N');
                // Rename worksheet
                $activeSheet->setTitle($fcode);
                $x++;
            }

            /**
            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            */

            ob_start();
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->setOffice2003Compatibility(true);
            // $writer->save('php://output');

            $this->load->library('zip');

//            $path = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']); 
            $path = str_replace('\\', '/', FCPATH);
            $path .= '/tmp/'; // destination dir
            $file_name = $title.'.xlsx'; // destination file
            if (file_exists($path.$file_name)) {
                unlink($path.$file_name);
            } else {
               echo "The file filename does not exist";
            }
            $writer->save($path.$file_name);
			
            $this->zip->read_file($path.$file_name,FALSE);
            ob_end_clean();

            // create zip file on server
//            $this->zip->archive($title.'.zip'); //archive zip file in web directory
            // prompt user to download the zip file
            $this->zip->download($title.'.zip');
            exit;
        }
    }
    
    public function export_used_part(){
        $rs = array();
        $arrWhere = array();
        
        if($this->isStaff()){
            $code = array($this->repo);
        }else{
//            $code = $this->input->post('fcode', TRUE);
            $code = isset($_POST['fcode']) ? $_POST['fcode'] : array();
        }
        (int)$c_arr_code = count($code);

        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $createdBy = $this->name;
        
        if(empty($fdate1) && empty($fdate2)){
            redirect('cl');
        }else{
            $curdateID = tgl_indo(date('Y-m-d'));
            $curdate = date('dmY');
            $reportdate = date('dmY', strtotime($fdate1));
            $reportdate2 = date('dmY', strtotime($fdate2));
            $reportdateID = tgl_indo($fdate1);
            $reportdateID2 = tgl_indo($fdate2);
            $title = 'Used Part ('.$reportdate.'-'.$reportdate2.')';
            
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('E-Logistic')
            ->setLastModifiedBy($createdBy)
            ->setTitle($title)
            ->setSubject('Used Part')
            ->setDescription($title.' generated by '.$createdBy)
            ->setKeywords('used part')
            ->setCategory('Report');
            
//            $activeSheet = $spreadsheet->getActiveSheet();
            
            $styleHeaderArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E5E4E2' ),
                ),
            );
            
            //Start adding next sheets
            $x=0;
            foreach($code as $fcode) {
                $fslname = $this->get_info_warehouse_name($fcode);
            
                $spreadsheet->createSheet($x);
                $spreadsheet->setActiveSheetIndex($x);
                $activeSheet = $spreadsheet->getActiveSheet();
                
                $activeSheet->mergeCells('A1:K1');
                $activeSheet->mergeCells('A2:K2');
                $activeSheet
                ->setCellValue('A1', $fslname)
                ->setCellValue('A2', 'Report Date: '.$reportdateID.' to '.$reportdateID2);
                $activeSheet->getStyle('A1')->getFont()->setBold(true);

                // Header data
                $activeSheet
                ->setCellValue('A4', 'Requested PN')
                ->setCellValue('B4', 'Description')
                ->setCellValue('C4', 'Serial No.')
                ->setCellValue('D4', 'Outgoing No.')
                ->setCellValue('E4', 'Ticket No.')
                ->setCellValue('F4', 'Purpose')
                ->setCellValue('G4', 'FE Report')
                ->setCellValue('H4', 'Status')
                ->setCellValue('I4', 'FSE ID')
                ->setCellValue('J4', 'Assigned FSE')
                ->setCellValue('K4', 'Partner')
                ;
                $activeSheet->getStyle('A4:K4')->applyFromArray($styleHeaderArray);

                //Parameters for cURL
                $arrWhere = array('fcode'=> strtoupper($fcode), 'fdate1'=> $fdate1.' 00:00:00', 'fdate2'=> $fdate2.' 23:59:59');
                //Parse Data for cURL
                $rs_data = send_curl($arrWhere, $this->config->item('api_used_reports'), 'POST', FALSE);
                $rs = $rs_data->status ? $rs_data->result : array();

                $i=5; //initial row for displaying data output
                foreach( $rs as $row )
                {
                    $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                    $serialnum = filter_var($row->serial_number, FILTER_SANITIZE_STRING);
                    $transnum = filter_var($row->outgoing_num, FILTER_SANITIZE_STRING);
                    $ticket = filter_var($row->outgoing_ticket, FILTER_SANITIZE_STRING);
                    $purpose = filter_var($row->outgoing_purpose, FILTER_SANITIZE_STRING);
                    $fereport = filter_var($row->fe_report, FILTER_SANITIZE_STRING);
                    $status = filter_var($row->status, FILTER_SANITIZE_STRING);
                    $engineer = filter_var($row->engineer_key, FILTER_SANITIZE_STRING);
                    $engineer_name = filter_var($row->engineer_name, FILTER_SANITIZE_STRING);
                    $partner_name = filter_var($row->partner_name, FILTER_SANITIZE_STRING);

                    //set column width auto size
                    $activeSheet->getColumnDimension('A')->setAutoSize(true);
                    $activeSheet->getColumnDimension('B')->setAutoSize(true);
                    $activeSheet->getColumnDimension('C')->setAutoSize(true);
                    $activeSheet->getColumnDimension('D')->setAutoSize(true);
                    $activeSheet->getColumnDimension('E')->setAutoSize(true);
                    $activeSheet->getColumnDimension('F')->setAutoSize(true);
                    $activeSheet->getColumnDimension('G')->setAutoSize(true);
                    $activeSheet->getColumnDimension('H')->setAutoSize(true);
                    $activeSheet->getColumnDimension('I')->setAutoSize(true);
                    $activeSheet->getColumnDimension('J')->setAutoSize(true);
                    $activeSheet->getColumnDimension('K')->setAutoSize(true);
                    $activeSheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    $activeSheet->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    $activeSheet->getStyle('H')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    $activeSheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $activeSheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $activeSheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $activeSheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    //fill data row
                    $activeSheet
                    ->setCellValue('A'.$i, $partnum)
                    ->setCellValue('B'.$i, $partname)
                    ->setCellValue('C'.$i, $serialnum.' ')
                    ->setCellValue('D'.$i, $transnum)
                    ->setCellValue('E'.$i, $ticket)
                    ->setCellValue('F'.$i, $purpose)
                    ->setCellValue('G'.$i, $fereport)
                    ->setCellValue('H'.$i, strtoupper($status))
                    ->setCellValue('I'.$i, $engineer)
                    ->setCellValue('J'.$i, $engineer_name)
                    ->setCellValue('K'.$i, $partner_name);

                    $i++;
                }
    //            $activeSheet->getHeaderFooter()
    //                    ->setOddHeader('&L&B' . 'Report Date: '.$reportdateID.' to '.$reportdateID2);
                $activeSheet->getHeaderFooter()
                        ->setOddFooter('&L&B' . 'Generated by '.$createdBy . '&RPage &P of &N');
                // Rename worksheet
                $activeSheet->setTitle($fcode);
                $x++;
            }

            ob_start();
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->setOffice2003Compatibility(true);
            // $writer->save('php://output');

            $this->load->library('zip');

//            $path = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']); 
            $path = str_replace('\\', '/', FCPATH);
            $path .= '/tmp/'; // destination dir
            $file_name = $title.'.xlsx'; // destination file
            if (file_exists($path.$file_name)) {
                unlink($path.$file_name);
            } else {
               echo "The file filename does not exist";
            }
            $writer->save($path.$file_name);
			
            $this->zip->read_file($path.$file_name,FALSE);
            ob_end_clean();

            // prompt user to download the zip file
            $this->zip->download($title.'.zip');
            exit;
        }
    }
}