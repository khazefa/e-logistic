<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CIncoming (TicketsController)
 * CIncoming Class to control Tickets.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CIncoming extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isSignin();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Incoming Report - '.APP_NAME;
        $this->global['pageMenu'] = 'Incoming Report';
        $this->global['contentHeader'] = 'Incoming Report';
        $this->global['contentTitle'] = 'Incoming Report';
        $this->global ['ovRole'] = $this->ovRole;
        $this->global ['ovName'] = $this->ovName;
        $this->global ['ovRepo'] = $this->ovRepo;
        
        $data['list_coverage'] = $this->get_list_wh();

        $this->loadViews2('superintend/incoming-trans/index', $this->global, $data);
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_wh(){
        $rs = array();
        $arrWhere = array();
        
        $fcoverage = $this->session->userdata ( 'ovCoverage' );
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
 
            if(in_array($row['code'], $e_coverage)){
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    public function get_list_coverage()
    {
        $rs = array();
        $arrWhere = array();
        
        $fcoverage = $this->session->userdata ( 'ovCoverage' );
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        array_push($data, array('code'=>'C000','name'=>'FSL All'));
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
 
            if(in_array($row['code'], $e_coverage)){
                $data[] = $row;
            }
        }

//        echo json_encode( $data);
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($data)
        );
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_view_datatable(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode);
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
//        $coverage = $this->input->post('fcoverage', TRUE);
        $coverage = !empty($_POST['fcoverage']) ? implode(';',$_POST['fcoverage']) : "";
        
        if(empty($coverage)){
            $fcoverage = $this->session->userdata ( 'ovCoverage' );
        }else{
            if (strpos($coverage, 'C000') !== false) {
                $fcoverage = $this->session->userdata ( 'ovCoverage' );;
            }else{
                if (strpos($coverage, ',') !== false) {
                    $fcoverage = str_replace(',', ';', $coverage);
                }else{
                    $fcoverage = $coverage;
                }
            }
        }
        
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode, 'fdate1'=>$fdate1, 'fdate2'=>$fdate2, 'fpurpose'=>$fpurpose);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_incomings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
//        var_dump($rs);exit();
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->incoming_num, FILTER_SANITIZE_STRING);
            $transout = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $outstatus = $r->outgoing_status == "" ? "" : "(". strtoupper($r->outgoing_status).")";
            $transdate = filter_var($r->incoming_date, FILTER_SANITIZE_STRING);
//            $transticket = filter_var($r->incoming_ticket, FILTER_SANITIZE_STRING);
//            $engineer = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
//            $engineer_name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $fpurpose = filter_var($r->incoming_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->incoming_qty, FILTER_SANITIZE_NUMBER_INT);
            $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->incoming_notes, FILTER_SANITIZE_STRING);
            $purpose = "";
            
            switch ($fpurpose){
                case "S";
                    $purpose = 'Supply';
                    $button = '<a href="'.base_url("oversee/print-incoming-supply/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                break;
                case "RG";
                    $purpose = 'Return Good';
                    $button = '<a href="'.base_url("oversee/print-incoming-return/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                break;
                default;
                    $purpose = '-';
                    $button = '-';
                break;
            }
            
            $row['transnum'] = $transnum;
            $row['transout'] = $transout == "" ? "-" : $transout." ".$outstatus;
            $row['transdate'] = tgl_indo($transdate);
            $row['fsl'] = $fslname;
//            $row['transticket'] = $transticket;
//            $row['engineer'] = $engineer_name;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user_fullname;
            $row['notes'] = $notes;
            
//            if($this->isSpv()){
//                $row['button'] = '<div class="btn-group dropdown">';
//                $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
//                $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
//                $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-incoming-trans/").$transnum.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
//                $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-incoming-trans/").$transnum.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
//                $row['button'] .= '</div>';
//                $row['button'] .= '</div>';
//            }elseif($this->isStaff()){
                $row['button'] = $button;
//                $row['button'] = '-';
//            }
 
            if(in_array($fslcode, $e_coverage)){
                $data[] = $row;
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to print supply transaction
     */
    public function print_trans_supply($ftrans_in){
        $rs = array();
        $arrWhere = array();
        $arrWhere2 = array();

        //Parameters for cURL
        $arrWhere = array('ftrans_in'=>$ftrans_in);
            
        if(empty($ftrans_in) || $ftrans_in = ""){
            redirect('oversee');
        }else{
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
            
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_view_incomings'), 'POST', FALSE);
            $results = $rs_data->status ? $rs_data->result : array();
            $transnum = "";
            $purpose = "";
            $transdate = "";
            $ticket = "";
            foreach ($results as $r){
                $fpurpose = filter_var($r->incoming_purpose, FILTER_SANITIZE_STRING);
                switch ($fpurpose){
                    case "S";
                        $purpose = "Supply";
                    break;
                    case "RG";
                        $purpose = "Return Good";
                    break;
                    default;
                        $purpose = "Supply";
                    break;
                }
                $transnum = filter_var($r->incoming_num, FILTER_SANITIZE_STRING);
                $transdate = date("d/m/Y H:i:s", strtotime(filter_var($r->created_at, FILTER_SANITIZE_STRING)));
                $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                $notes = $r->incoming_notes == "" ? "-" : filter_var($r->incoming_notes, FILTER_SANITIZE_STRING);
            }

//            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print
            $this->mypdf->SetFont('Arial','B',11);
            $this->mypdf->Code39(($width*(65/100)),10,$transnum,1,10);
            $this->mypdf->ln(20);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Purpose',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$purpose,1,1, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Stock Location',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$fslname,1,1, 'L');
            $this->mypdf->setFont('Arial','B',10);
            
            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width*(60/100)),7,'SPAREPART SUPPLY LIST',0,1,'R');

            $this->mypdf->ln(5);

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Supplied PN',1,0);
            $this->mypdf->Cell(($width*(40/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(7.5/100)),6,'Qty',1,1);

            $this->mypdf->SetFont('Arial','',9);
            
            //Parse Data for cURL
            $rs_data2 = send_curl($arrWhere, $this->config->item('api_list_view_detail_incomings'), 'POST', FALSE);
            $rslist = $rs_data2->status ? $rs_data2->result : array();
            
            foreach( $rslist as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $qty = filter_var($row->dt_incoming_qty, FILTER_SANITIZE_NUMBER_INT);

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(40/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,1,'C');
            }

            $title = 'Supply #'.$transnum;
            $this->mypdf->SetTitle($title);
    //        $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('D', $title.'.pdf');
        }
    }
    
    public function print_trans_return($ftrans_in){
        $rs = array();
        $arrWhere = array();
        $arrWhere2 = array();

        //Parameters for cURL
        $arrWhere = array('ftrans_in'=>$ftrans_in);
            
        if(empty($ftrans_in) || $ftrans_in = ""){
            redirect('oversee');
        }else{
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
            
            //Parse Data for cURL
            $rs_data1 = send_curl($arrWhere, $this->config->item('api_info_view_incomings'), 'POST', FALSE);
            $results1 = $rs_data1->status ? $rs_data1->result : array();
            $ftrans_out = "";
            $ftransin = "";
            foreach ($results1 AS $r1){
                $ftransin = filter_var($r1->incoming_num, FILTER_SANITIZE_STRING);
                $ftrans_out = filter_var($r1->outgoing_num, FILTER_SANITIZE_STRING);
            }
            
            $arrWhere2 = array('ftrans_out'=>$ftrans_out);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere2, $this->config->item('api_info_view_outgoings'), 'POST', FALSE);
            $results = $rs_data->status ? $rs_data->result : array();
            $transnum = "";
            $purpose = "";
            $transdate = "";
            $ticket = "";
            $partner = "";
            $engineer_id = "";
            $engineer2_id = "";
            $engineer_name = "";
            $engineer_mess = "";
            $engineer_sign = "";
            $fpurpose = "";
            $customer = "";
            $location = "";
            $ssb_id = "";
            $fslname = "";
            $fe_report = "";
            foreach ($results as $r){
                $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
                switch ($fpurpose){
                    case "SP";
                        $purpose = "Sales/Project";
                    break;
                    case "W";
                        $purpose = "Warranty";
                    break;
                    case "M";
                        $purpose = "Maintenance";
                    break;
                    case "I";
                        $purpose = "Investments";
                    break;
                    case "B";
                        $purpose = "Borrowing";
                    break;
                    case "RWH";
                        $purpose = "Transfer Stock";
                    break;
                    default;
                        $purpose = "-";
                    break;
                }
                $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                $ticket = $r->outgoing_ticket == "" ? "-" : filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
                $transdate = date("d/m/Y H:i:s", strtotime(filter_var($r->created_at, FILTER_SANITIZE_STRING)));
                $partner = $r->partner_name == "" ? "-" : filter_var($r->partner_name, FILTER_SANITIZE_STRING);
                $engineer_id = $r->engineer_key == "" ? "-" : filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
                $engineer2_id = $r->engineer_2_key == "" ? "-" : filter_var($r->engineer_2_key, FILTER_SANITIZE_STRING);
                $engineer_name = $r->engineer_name == "" ? "-" : filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
                if(!empty($engineer2_id)){
                    $engineer_mess = $r->engineer_2_name == "" ? "-" : filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
                    $engineer_sign = $r->engineer_2_name == "" ? $engineer_name : filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
                }else{
                    $engineer_sign = $engineer_name;
                }
                $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                $notes = $r->outgoing_notes == "" ? "-" : filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
                $customer = $r->outgoing_cust == "" ? "-" : filter_var($r->outgoing_cust, FILTER_SANITIZE_STRING);
                $location = $r->outgoing_loc == "" ? "-" : filter_var($r->outgoing_loc, FILTER_SANITIZE_STRING);
                $ssb_id = $r->outgoing_ssbid == "" ? "-" : filter_var($r->outgoing_ssbid, FILTER_SANITIZE_STRING);
                $fe_report = $r->fe_report == "" ? "-" : filter_var($r->fe_report, FILTER_SANITIZE_STRING);
            }

//            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print
            $this->mypdf->SetFont('Arial','B',11);
            $this->mypdf->Code39(($width*(65/100)),10,$transnum,1,10);
            $this->mypdf->ln(20);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Purpose',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$purpose,1,1, 'L');

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Stock Location',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$fslname,1,0, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(20/100)),7,'        Ticket Number',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(30/100)),7,$ticket,1,1, 'L');

            $this->mypdf->ln(0);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Service Partner',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$partner,1,0, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(20/100)),7,'        Customer',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(30/100)),7,$customer,1,1, 'L');

            $this->mypdf->ln(0);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Assigned FSE',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$engineer_name,1,0, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(20/100)),7,'        Location',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(30/100)),7,$location,1,1, 'L');

            $this->mypdf->ln(0);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'FSE ID Number',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$engineer_id,1,0, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(20/100)),7,'        SSB/ID',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(30/100)),7,$ssb_id,1,1, 'L');

            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width-20),7,'STOCK REQUEST FORM',0,1,'C');
            // Garis atas untuk header
//                $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

            $this->mypdf->ln(5);

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Requested PN',1,0);
            $this->mypdf->Cell(($width*(35/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(7.5/100)),6,'Qty',1,0);
            $this->mypdf->Cell(($width*(18/100)),6,'Serial No.',1,0);
            $this->mypdf->Cell(($width*(10/100)),6,'Status',1,1);

            $this->mypdf->SetFont('Arial','',9);
            
            //Parse Data for cURL
            $rs_data2 = send_curl($arrWhere2, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
            $rslist = $rs_data2->status ? $rs_data2->result : array();
            
            foreach( $rslist as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = $row->part_name == "" ? "-" : filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $qty = filter_var($row->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $serialnum = $row->serial_number == "" ? "-" : filter_var($row->serial_number, FILTER_SANITIZE_STRING);
                $status = $row->return_status == "" ? "X" : filter_var($row->return_status, FILTER_SANITIZE_STRING);

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(35/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,0,'C');
                $this->mypdf->CellFitScale(($width*(18/100)),6,$serialnum,1,0);
                $this->mypdf->CellFitScale(($width*(10/100)),6,$status,1,1);
            }

            /**
            $this->mypdf->ln(1);
            $this->mypdf->SetFont('Arial','',10);
            $this->mypdf->drawTextBox('Notes:', $width-20, 12, 'L', 'T');
            $this->mypdf->ln(10);
            $this->mypdf->Cell(($width*(25/100)),6,'Requested by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Approved by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Processed by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Received by:',0,1,'L');
            $this->mypdf->ln(12);
            $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$engineer_sign,0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,1,'L');
            $this->mypdf->ln(0.1);
            $this->mypdf->Cell(($width*(25/100)),6,'Date:'.$transdate,0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,1,'L');
            **/
            
            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width-20),7,'STOCK RETURN FORM #'.$ftransin,0,1,'C');
            
            $this->mypdf->ln(3);
            
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width-($width*0.1)),7,'FE Report: '.$fe_report,0,1,'L');

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Returned PN',1,0);
            $this->mypdf->Cell(($width*(35/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(7.5/100)),6,'Qty',1,0);
            $this->mypdf->Cell(($width*(18/100)),6,'Serial No.',1,1);

            $this->mypdf->SetFont('Arial','',9);
            
            //Parse Data for cURL
            $rs_data3 = send_curl($arrWhere, $this->config->item('api_list_view_detail_incomings'), 'POST', FALSE);
            $rslist1 = $rs_data3->status ? $rs_data3->result : array();
            
            foreach( $rslist1 as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $qty = filter_var($row->dt_incoming_qty, FILTER_SANITIZE_NUMBER_INT);
                $serialnum = filter_var($row->serial_number, FILTER_SANITIZE_STRING);

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(35/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,0,'C');
                $this->mypdf->CellFitScale(($width*(18/100)),6,$serialnum,1,1);
            }
            
            $title = 'Return #'.$ftransin;
            $this->mypdf->SetTitle($title);
    //        $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('D', $title.'.pdf');
        }
    }
}