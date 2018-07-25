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
class CReports extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isWebAdmin()){
            
        }else{
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
        $this->global['pageTitle'] = 'Report Consumed Part - '.APP_NAME;
        $this->global['pageMenu'] = 'Report Consumed Part';
        $this->global['contentHeader'] = 'Report Consumed Part';
        $this->global['contentTitle'] = 'Report Consumed Part';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_wr'] = $this->get_list_wh();
        $this->loadViews('front/reports/consumed-part', $this->global, $data);
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
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function load_daily_reports(){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>'CID3', 'fdate1'=> '2018-07-23 00:00:00', 'fdate2'=> '2018-07-23 23:59:59');
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_daily_reports'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            
        }
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
    
    public function generate_consumed_part(){
        $rs = array();
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        
        $data = array();
        foreach ($rs as $r) {
            $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $this->print_consumed_part($code, $fdate1, $fdate2);
        }
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
            $reportdateID = tgl_indo($fdate1);

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
            $this->mypdf->Cell(($width*(75/100)),7,'Report Date:',1,0,'R');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->CellFitScale(($width*(35/100)),7,$reportdateID,0,1, 'R');

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(12/100)),7,'FSL Location:',0,0,'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->CellFitScale(($width*(35/100)),7,$fslname,0,1, 'L');

            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width*(60/100)),7,'Daily Report Consumed Part',0,0,'R');
            $this->mypdf->ln(5);
            // Garis atas untuk header
    //        $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

            $this->mypdf->ln(5);

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

            $this->mypdf->ln(1);
//            $this->mypdf->SetFont('Arial','',10);
//            $this->mypdf->drawTextBox('Notes:', $width-20, 12, 'L', 'T');
//            $this->mypdf->ln(10);
            $this->mypdf->Cell(($width*(50/100)),6,'Generated by:',0,1,'L');
            $this->mypdf->ln(12);
            $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$createdBy,0,1,'L');
            $this->mypdf->ln(0.1);
            $this->mypdf->Cell(($width*(25/100)),6,'Print Date: '.$curdateID,0,1,'L');

            $title = 'Daily Consumed Part '.$fcode.' - '.$reportdate;
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
            $reportdateID = tgl_indo($fdate1);

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
            $this->mypdf->ln(20);

    //            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(11/100)),7,'Report Date:',0,0,'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->CellFitScale(($width*(35/100)),7,$reportdateID,0,1, 'L');

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(12/100)),7,'FSL Location:',0,0,'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->CellFitScale(($width*(35/100)),7,$fslname,0,1, 'L');

            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width*(60/100)),7,'Report Replenish Plan',0,0,'R');
            $this->mypdf->ln(5);
            // Garis atas untuk header
    //        $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

            $this->mypdf->ln(5);

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Requested PN',1,0);
            $this->mypdf->Cell(($width*(35/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(5/100)),6,'Qty.',1,0);
            $this->mypdf->Cell(($width*(20/100)),6,'Notes',1,1);

            $this->mypdf->SetFont('Arial','',9);

            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_replenish_plan'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
            
//            var_dump($rs);exit();

            foreach( $rs as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $qty = filter_var($row->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $notes = $row->o_delivery_notes === "" ? "-" : filter_var($row->o_delivery_notes, FILTER_SANITIZE_STRING);

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(35/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(5/100)),6,$qty,1,0);
                $this->mypdf->CellFitScale(($width*(20/100)),6,$notes,1,1);
            }

            $this->mypdf->ln(1);
            $this->mypdf->Cell(($width*(50/100)),6,'Generated by:',0,1,'L');
            $this->mypdf->ln(12);
            $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$createdBy,0,1,'L');
            $this->mypdf->ln(0.1);
            $this->mypdf->Cell(($width*(25/100)),6,'Print Date: '.$curdateID,0,1,'L');

            $title = 'Replenish Plan '.$fcode.' - '.$reportdate;
            $this->mypdf->SetTitle($title);
    //        return $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('I', $title.'.pdf');
        }
    }
    
    public function print_consumed_part($code, $date1, $date2){
        $rs = array();
        $arrWhere = array();

//        if($this->isStaff()){
//            $fcode = $this->repo;
//        }else{
//            $fcode = $this->input->post('fcode', TRUE);
//        }
        
        $fcode = $code;
        $fdate1 = $date1;
        $fdate2 = $date2;
        $createdBy = $this->name;
        
        //Parameters for cURL
        $arrWhere = array('fcode'=> strtoupper($fcode), 'fdate1'=> $fdate1.' 00:00:00', 'fdate2'=> $fdate2.' 23:59:59');
        $curdateID = tgl_indo(date('Y-m-d'));
        $curdate = date('dmY');
        $reportdate = date('dmY', strtotime($fdate1));
        $reportdateID = tgl_indo($fdate1);
            
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
        $this->mypdf->ln(20);

//            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print

        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(11/100)),7,'Report Date:',0,0,'L');
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->CellFitScale(($width*(35/100)),7,$reportdateID,0,1, 'L');

        $this->mypdf->ln(5);
        $this->mypdf->setFont('Arial','B',13);
        $this->mypdf->Cell(($width*(60/100)),7,'Daily Report Consumed Part',0,0,'R');
        $this->mypdf->ln(5);
        // Garis atas untuk header
//        $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

        $this->mypdf->ln(5);

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

//        var_dump($rs);exit();
        
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

        $this->mypdf->ln(1);
        $this->mypdf->SetFont('Arial','',10);
        $this->mypdf->drawTextBox('Notes:', $width-20, 12, 'L', 'T');
        $this->mypdf->ln(10);
        $this->mypdf->Cell(($width*(50/100)),6,'Generated by:',0,1,'L');
        $this->mypdf->ln(12);
        $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$createdBy,0,1,'L');
        $this->mypdf->ln(0.1);
        $this->mypdf->Cell(($width*(25/100)),6,'Print Date: '.$curdateID,0,1,'L');

        $title = 'Daily Consumed Part '.$fcode.' - '.$reportdate;
        $this->mypdf->SetTitle($title);
//        return $this->mypdf->Output('D', $title.'.pdf');
        return $this->mypdf->Output('I', $title.'.pdf');
    }
    
}