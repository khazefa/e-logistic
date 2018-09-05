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
        $this->isLoggedIn();
        if($this->isSpv() || $this->isStaff()){
            //
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {        
        if($this->isSpv()){
            redirect('view-incoming-trans');
        }elseif($this->isStaff()){
            
            $this->global['pageTitle'] = 'Incoming Transaction - '.APP_NAME;
            $this->global['pageMenu'] = 'Incoming Transaction';
            $this->global['contentHeader'] = 'Incoming Transaction';
            $this->global['contentTitle'] = 'Incoming Transaction';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            
            $this->loadViews('front/incoming-trans/index', $this->global, NULL);
            
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function views()
    {
        $this->global['pageTitle'] = 'Incoming Transaction - '.APP_NAME;
        $this->global['pageMenu'] = 'Incoming Transaction';
        $this->global['contentHeader'] = 'Incoming Transaction';
        $this->global['contentTitle'] = 'Incoming Transaction';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $this->loadViews('front/incoming-trans/lists', $this->global, NULL);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_incomings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->incoming_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->incoming_date, FILTER_SANITIZE_STRING);
//            $transticket = filter_var($r->incoming_ticket, FILTER_SANITIZE_STRING);
//            $engineer = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $purpose = filter_var($r->incoming_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->incoming_qty, FILTER_SANITIZE_NUMBER_INT);
            $user = filter_var($r->user_key, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->incoming_notes, FILTER_SANITIZE_STRING);
            
            $row['transnum'] = $transnum;
            $row['transdate'] = $transdate;
//            $row['transticket'] = $transticket;
//            $row['engineer'] = $engineer;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user;
            $row['notes'] = $notes;
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
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
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode, 'fdate1'=>$fdate1, 'fdate2'=>$fdate2, 'fpurpose'=>$fpurpose);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_incomings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
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
            $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->incoming_notes, FILTER_SANITIZE_STRING);
            $purpose = "";
            
            switch ($fpurpose){
                case "S";
                    $purpose = 'Supply';
                    $button = '<a href="'.base_url("print-incoming-supply/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                break;
                case "RG";
                    $purpose = 'Return Good';
                    $button = '<a href="'.base_url("print-incoming-return/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                break;
                default;
                    $purpose = '-';
                    $button = '-';
                break;
            }
            
            $row['transnum'] = $transnum;
            $row['transout'] = $transout == "" ? "-" : $transout." ".$outstatus;
            $row['transdate'] = tgl_indo($transdate);
//            $row['transticket'] = $transticket;
//            $row['engineer'] = $engineer_name;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user_fullname;
            $row['notes'] = $notes;
            
            if($this->isSpv()){
                $row['button'] = '<div class="btn-group dropdown">';
                $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-incoming-trans/").$transnum.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-incoming-trans/").$transnum.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
                $row['button'] .= '</div>';
                $row['button'] .= '</div>';
            }elseif($this->isStaff()){
                $row['button'] = $button;
//                $row['button'] = '-';
            }
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get lists for json or populate data
     */
    public function get_list_part_json(){
        $rs = array();
        $arrWhere = array();
        
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fpartnum != "") $arrWhere['fpartnum'] = $fpartnum;
        if ($fname != "") $arrWhere['fname'] = $fname;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $pid = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['partno'] = $partnum;
            $row['name'] = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
            $row['returncode'] = filter_var($r->part_return_code, FILTER_SANITIZE_STRING);
            $row['machine'] = filter_var($r->part_machine, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($data)
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
            redirect('cl');
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
            redirect('cl');
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
    
    /**
     * This function is used to load the add new form
     */
    public function add()
    {        
        if($this->isSpv()){
            redirect('view-incoming-trans');
        }elseif($this->isStaff()){
            
            $this->global['pageTitle'] = 'New Incoming Transaction - '.APP_NAME;
            $this->global['pageMenu'] = 'New Incoming Transaction';
            $this->global['contentHeader'] = 'New Incoming Transaction';
            $this->global['contentTitle'] = 'New Incoming Transaction';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->loadViews('front/incoming-trans/create', $this->global, NULL);
            
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function is used to check part
     */
    public function check_part(){
        $rs = array();
        $rs2 = array();
        $arrWhere = array();
        $arrWhere2 = array();
        $success_response = array();
        $error_response = array();
        $response = array();
        
        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $partname = null;
        if(!empty($rs)){
            foreach($rs AS $r1){
                $partname = filter_var($r1->part_name, FILTER_SANITIZE_STRING);
            }
            $arrWhere2 = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum);
            //Parse Data for cURL
            $rs_data2 = send_curl($arrWhere2, $this->config->item('api_info_part_stock'), 'POST', FALSE);
            $rs2 = $rs_data2->status ? $rs_data2->result : array();

            if(!empty($rs2)){
                $success_response = array(
                    'status' => 1,
                    'message'=> 'Sparepart <strong>'.$partname.'</strong> is available'
                );
                $response = $success_response;
            }else{
                $dataInfo = array('fcode'=> $fcode, 'fpartnum'=> $fpartnum, 'fminval'=> 3, 'finitval'=> 0, 
                    'flastval'=> 0, 'fflag'=> 'Y');
                $rs_data2 = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_part_stock'), 'POST', FALSE);

                if($rs_data2->status)
                {
                    $success_response = array(
                        'status' => 1,
                        'message'=> 'Sparepart <strong>'.$partname.'</strong> is available'
                    );
                    $response = $success_response;
                }
                else
                {
                    $error_response = array(
                        'status' => 0,
                        'message'=> 'There is an error while searching the data'
                    );
                    $response = $error_response;
                }
            }
        }else{
            $error_response = array(
                'status' => 0,
                'message'=> 'Sparepart data is not available'
            );
            $response = $error_response;
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to get list information described by function name
     */
    private function get_info_part_stock($fcode, $partnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
            $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
            $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
            $minval = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            $row['code'] = $code;
            $row['partno'] = $partno;
            $row['warehouse'] = $this->get_info_warehouse_name($code);
            $row['part'] = $this->get_info_part_name($partno);
            
            if($initflag === "Y"){
                $row['stock'] = $initstock;
            }else{
                $row['stock'] = $stock;
            }
 
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
    
    /**
     * This function is used to get detail information
     */
    private function get_info_part_name($fpartnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fpartnum'=>$fpartnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $partname = "";
        foreach ($rs as $r) {
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
        }
        
        return $partname;
    }
    
    /**
     * This function is used to get detail information
     */
    public function get_info_part($fpartnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fpartnum'=>$fpartnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $pid = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['pid'] = $pid;
            $row['partno'] = $partnum;
            $row['name'] = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
            $row['returncode'] = filter_var($r->part_return_code, FILTER_SANITIZE_STRING);
            $row['machine'] = filter_var($r->part_machine, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get cart info where part stock is already low
     */
    private function get_info_cart($partnum){
        
        $fcode = $this->repo;
        $arrWhere = array('fpartnum'=>$partnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_incomings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partname = "";
        $partstock = "";
        
        if(!empty($rs)){
            foreach ($rs as $r) {
                $id = filter_var($r->tmp_incoming_id, FILTER_SANITIZE_NUMBER_INT);
                $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                $rs_part = $this->get_info_part($partnum);
                foreach ($rs_part as $p){
                    $partname = $p["name"];
                }
                $rs_stock = $this->get_info_part_stock($fcode, $partnum);
                foreach ($rs_stock as $s){
                    $partstock = (int)$s["stock"];
                }
                $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                $qty = filter_var($r->tmp_incoming_qty, FILTER_SANITIZE_NUMBER_INT);
                $user = filter_var($r->user, FILTER_SANITIZE_STRING);
                $fullname = filter_var($r->fullname, FILTER_SANITIZE_STRING);

                $row['id'] = $id;
                $row['partno'] = $partnum;
                $row['serialno'] = $serialnum;
                $row['name'] = $partname;
                $row['stock'] = $partstock;
                $row['qty'] = (int)$qty;
                $row['user'] = $user;
                $row['fullname'] = $fullname;

                $data[] = $row;
            }

        }
        return $data;
    }
    
    /**
     * This function is used to add cart
     */
    public function add_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to add data'
        );
        
        $fcode = $this->repo;
        $fuser = $this->vendorUR;
        $fname = $this->name;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartname = $this->get_info_part_name($fpartnum);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        
        $dataInfo = array('fpartnum'=>$fpartnum, 'fpartname'=>$fpartname, 'fserialnum'=>$fserialnum, 'fcartid'=>$cartid, 'fqty'=>$fqty, 'fuser'=>$fuser, 'fname'=>$fname);
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_incomings_cart'), 'POST', FALSE);

        if($rs_data->status)
        {
            $response = $success_response;
        }
        else
        {
            $response = $error_response;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_cart_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_incomings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();

        $data = array();
        $partname = "";
        $partstock = "";
        if(!empty($rs)){
            foreach ($rs as $r) {
                $id = filter_var($r->tmp_incoming_id, FILTER_SANITIZE_NUMBER_INT);
                $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                $rs_stock = $this->get_info_part_stock($fcode, $partnum);
                foreach ($rs_stock as $s){
                    $partstock = (int)$s["stock"];
                }
                $cartid = filter_var($r->tmp_incoming_uniqid, FILTER_SANITIZE_STRING);
                $qty = filter_var($r->tmp_incoming_qty, FILTER_SANITIZE_NUMBER_INT);

                $row['id'] = $id;
                $row['partno'] = $partnum;
                $row['partname'] = $partname;
                $row['stock'] = $partstock;
                $row['qty'] = (int)$qty;

                $data[] = $row;
            }
        }else{
            $data = array();
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get list for datatables return
     */
    public function get_list_cart_datatable_r(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        
        if(empty($ftrans_out) || $ftrans_out == ""){
            $data = array();
        }else{
            $cartid = $this->session->userdata ( 'cart_session' )."inr".$ftrans_out;
            $arrWhere = array('funiqid'=>$cartid);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_list_incomings_cart'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            $data = array();
            $partname = "";
            $partstock = "";
            if(!empty($rs)){
                foreach ($rs as $r) {
                    $id = filter_var($r->tmp_incoming_id, FILTER_SANITIZE_NUMBER_INT);
                    $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                    $partname = $this->get_info_part_name($partnum);
                    $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                    $cartid = filter_var($r->tmp_incoming_uniqid, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->tmp_incoming_qty, FILTER_SANITIZE_NUMBER_INT);
                    $status = filter_var($r->return_status, FILTER_SANITIZE_STRING);

                    $row['id'] = $id;
                    $row['partno'] = $partnum;
                    $row['partname'] = $partname;
                    $row['serialno'] = $serialnum;
                    $row['qty'] = (int)$qty;
                    $row['status'] = $status;

                    $data[] = $row;
                }
            }else{
                $data = array();
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get list for datatables
     */
    private function get_list_cart_supply(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_incomings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partname = "";
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_incoming_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
//            $rs_stock = $this->get_info_part_stock($fcode, $partnum);
//            foreach ($rs_stock as $s){
//                $partstock = (int)$s["stock"];
//            }
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_incoming_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_incoming_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['serialno'] = $serialnum;
            $row['name'] = $partname;
//            $row['stock'] = $partstock;
            $row['qty'] = (int)$qty;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get total cart
     */
    public function get_total_cart(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_total_incomings_cart'), 'POST', FALSE);
//        $rs_data = send_curl($arrWhere, $this->config->item('api_total_incomings_cart').'?funiqid='.$cartid, 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $total = 0;
            foreach ($rs as $r){
                $total = $r->total > 0 ? (int)$r->total : 0;
            }
            $success_response = array(
                'status' => 1,
                'ttl_cart'=> $total
            );
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 0,
                'ttl_cart'=> 0
            );
            $response = $error_response;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to delete cart
     */
    public function update_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to update cart'
        );
        
        $fid = $this->input->post('fid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);

        $arrWhere = array('fid'=>$fid, 'fqty'=>$fqty);
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_incomings_cart'), 'POST', FALSE);

        if($rs_data->status)
        {
            $response = $success_response;
        }
        else
        {
            $response = $error_response;
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to delete cart
     */
    public function delete_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to delete cart'
        );
        
        $fid = $this->input->post('fid', TRUE);

        $arrWhere = array('fid'=>$fid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_delete_incomings_cart'), 'POST', FALSE);

        if($rs_data->status)
        {
            $response = $success_response;
        }
        else
        {
            $response = $error_response;
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    public function get_trans_num(){
        $arrWhere = array('fparam'=>"IN");
        $transnum = send_curl($arrWhere, $this->config->item('api_get_trans_num'), 'POST', FALSE);
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($transnum)
        );
    }
    
    /**
     * This function is used to complete supply transaction
     */
    public function submit_trans_supply(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to submit transaction'
        );
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' )."in";
               
        $date = date('Y-m-d'); 
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
//        $fticket = $this->input->post('fticket', TRUE);
//        $fengineer_id = $this->input->post('fengineer_id', TRUE);
//        $fengineer2_id = $this->input->post('fengineer2_id', TRUE);
        $fuser = $this->input->post('fuser', TRUE);
		
        $fpurpose = "S";
        $fqty = $this->input->post('fqty', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $createdby = $this->session->userdata ( 'vendorUR' );
        
        $arrParam = array('fparam'=>'IN', 'fcode'=>$fcode, 'fdigits'=>4);
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_incoming_num_ext'), 'POST', FALSE);
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        if($transnum === ""){
            $response = $error_response;
        }else{
            //get cart list by retnum
            $data_tmp = $this->get_list_cart_supply();

            $dataDetail = array();
            $total_qty = 0;
            if(!empty($data_tmp)){
                foreach ($data_tmp as $d){
                    $rs_stock = $this->get_info_part_stock($fcode, $d['partno']);
                    foreach ($rs_stock as $s){
                        $partstock = (int)$s["stock"];
                    }
                    $dataDetail = array('ftransno'=>$transnum, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno'], 
                        'fqty'=>$d['qty']);
                    $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_incomings_trans_detail'), 
                            'POST', FALSE);
                    $total_qty += (int)$d['qty'];
                    
                    $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$d['partno'], 'fqty'=>(int)$partstock+(int)$d['qty'], 'fflag'=>'N');
                    //update stock by fsl code and part number
                    $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                            'POST', FALSE);
                }
            }
			
            $dataTrans = array('ftransno'=>$transnum, 'ftrans_out'=>$ftrans_out, 'fdate'=>$date, 'fpurpose'=>$fpurpose, 'fqty'=>$total_qty, 'fuser'=>$createdby, 'fcode'=>$fcode, 'fnotes'=>$fnotes);
            $main_res = send_curl($this->security->xss_clean($dataTrans), $this->config->item('api_add_incomings_trans'), 'POST', FALSE);
            if($main_res->status)
            {
                //clear cart list data
                $arrWhere = array('fcartid'=>$cartid);
                $rem_res = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_clear_incomings_cart'), 'POST', FALSE);
                if($rem_res->status){
                    $success_response = array(
                        'status' => 1,
                        'message' => $transnum
                    );
                    $response = $success_response;
                }else{
                    $response = $error_response;
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'Failed to submit transaction data');
                $response = $error_response;
            }
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to check outgoing transaction
     */
    public function check_outgoing(){
        $rs = array();
        $arrWhere = array();
        $global_response = array();
        $success_response = array();
        $error_response = array();
        
        $fcode = $this->repo;
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $arrWhere = array('ftrans_out'=>$ftrans_out, 'fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $total_qty = 0;
            $status = "";
            foreach ($rs as $r){
                $total_qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $status = filter_var($r->outgoing_status, FILTER_SANITIZE_STRING);
            }
            if($status == "complete"){
                $global_response = array(
                    'status' => 0,
                    'total_qty' => 0,
                    'message'=> 'Transaction is already complete, you cannot close this transaction twice.'
                );
            }else{
                $global_response = array(
                    'status' => 1,
                    'total_qty' => $total_qty,
                    'message'=> 'Transaction still open'
                );
            }
            $response = $global_response;
        }else{
            $error_response = array(
                'status' => 0,
                'total_qty'=> 0,
                'message'=> 'Transaction is not available'
            );
            $response = $error_response;
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to get list for datatables
     */
    private function get_list_cart_return($cartid){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_incomings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partname = "";
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_incoming_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
//            $rs_stock = $this->get_info_part_stock($fcode, $partnum);
//            foreach ($rs_stock as $s){
//                $partstock = (int)$s["stock"];
//            }
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_incoming_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_incoming_qty, FILTER_SANITIZE_NUMBER_INT);
            $status = filter_var($r->return_status, FILTER_SANITIZE_STRING);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
            $row['qty'] = (int)$qty;
            $row['status'] = $status;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to check outgoing transaction
     */
    public function verify_outgoing(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $fuser = $this->vendorUR;
        $fname = $this->name;
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' )."inr".$ftrans_out;
        
        $arrWhere = array('ftrans_out'=>$ftrans_out, 'fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_detail_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $arrData = array();
            $partname = "";
            $partname = $this->get_info_part_name($fpartnum);
            foreach ($rs as $r){
                $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                $qty = filter_var($r->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                
                $row['partno'] = $partnum;
                $row['serialno'] = $serialnum;
                $row['partname'] = $partname;
                $row['qty'] = (int)$qty;
                $row['status'] = "RG";
                
                $arrData[] = $row;
                $dataInfo = array('fpartnum'=>$partnum, 'fpartname'=>$partname, 'fserialnum'=>$serialnum, 'fcartid'=>$cartid, 'fqty'=>$qty, 
                        'fuser'=>$fuser, 'fname'=>$fname);
                $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_incomings_r_cart'), 
                        'POST', FALSE);
            }

            $success_response = array(
                'status' => 1,
                'data'=> $arrData
            );
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 0,
                'message'=> 'Data not available'
            );
            $response = $error_response;
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to get total cart return
     */
    public function get_total_cart_return(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        
        if(empty($ftrans_out) || $ftrans_out == ""){
            $error_response = array(
                'status' => 0,
                'ttl_cart'=> 0
            );
            $response = $error_response;
        }else{
            $cartid = $this->session->userdata ( 'cart_session' )."inr".$ftrans_out;
            $arrWhere = array('funiqid'=>$cartid);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_total_incomings_cart'), 'POST', FALSE);
    //        $rs_data = send_curl($arrWhere, $this->config->item('api_total_incomings_cart').'?funiqid='.$cartid, 'GET', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            if(!empty($rs)){
                $total = 0;
                foreach ($rs as $r){
                    $total = $r->total > 0 ? (int)$r->total : 0;
                }
                $success_response = array(
                    'status' => 1,
                    'ttl_cart'=> $total
                );
                $response = $success_response;
            }else{
                $error_response = array(
                    'status' => 0,
                    'ttl_cart'=> 0
                );
                $response = $error_response;
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to complete return transaction
     */
    public function submit_trans_return(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to submit transaction'
        );
        
        $fcode = $this->repo;               
        $date = date('Y-m-d'); 
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $ffe_report = $this->input->post('ffe_report', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fpurpose = "RG";
        $fqty = $this->input->post('fqty', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $createdby = $this->session->userdata ( 'vendorUR' );
        
        $cartid = $this->session->userdata ( 'cart_session' )."inr".$ftrans_out;
        $arrParam = array('fparam'=>'IN', 'fcode'=>$fcode, 'fdigits'=>4);
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_incoming_num_ext'), 'POST', FALSE);
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        if($transnum === ""){
            $response = $error_response;
        }else{
            //get cart list by retnum
            $data_tmp = $this->get_list_cart_return($cartid);

            $dataDetail = array();
            $total_qty = 0;
            if(!empty($data_tmp)){
                foreach ($data_tmp as $d){
                    $rs_stock = $this->get_info_part_stock($fcode, $d['partno']);
                    foreach ($rs_stock as $s){
                        $partstock = (int)$s["stock"];
                    }
                    
                    $dataDetail = array('ftransno'=>$transnum, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno'], 
                        'fqty'=>$d['qty']);
                    $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_incomings_trans_detail'), 
                            'POST', FALSE);
                    $total_qty += (int)$d['qty'];
                    
                    $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$d['partno'], 'fqty'=>(int)$partstock+(int)$d['qty'], 
                        'fflag'=>'N');
                    //update stock by fsl code and part number
                    $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                            'POST', FALSE);
                    
                    $updateDetailOutgoing = array('ftrans_out'=>$ftrans_out, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno']);
                    //update detail outgoing status by outgoing number, part number and serial number
                    $update_status_detail_outgoing = send_curl($this->security->xss_clean($updateDetailOutgoing), $this->config->item('api_update_outgoings_trans_detail'), 
                            'POST', FALSE);
                }
            }

            $dataTrans = array('ftransno'=>$transnum, 'ftrans_out'=>$ftrans_out, 'fdate'=>$date, 'fpurpose'=>$fpurpose, 'fqty'=>$total_qty, 'fuser'=>$createdby, 'fcode'=>$fcode, 'fnotes'=>$fnotes);
            $main_res = send_curl($this->security->xss_clean($dataTrans), $this->config->item('api_add_incomings_trans'), 'POST', FALSE);
            if($main_res->status)
            {
                //update outgoing status by outgoing number
                $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'ffe_report'=>$ffe_report, 'fstatus'=>'complete');
                $update_status_outgoing = send_curl($this->security->xss_clean($updateOutgoing), $this->config->item('api_update_outgoings_trans'), 
                        'POST', FALSE);
                //clear cart list data
                $arrWhere = array('fcartid'=>$cartid);
                $rem_res = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_clear_incomings_cart'), 'POST', FALSE);
                if($rem_res->status){
                    $success_response = array(
                        'status' => 1,
                        'message' => $transnum
                    );
                    $response = $success_response;
                }else{
                    $response = $error_response;
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'Failed to submit transaction data');
                $response = $error_response;
            }
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to complete close transaction
     */
    public function submit_trans_close(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to submit transaction'
        );
        
        $fcode = $this->repo;
        $date = date('Y-m-d'); 
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $ffe_report = $this->input->post('ffe_report', TRUE);
        
        //update outgoing status by outgoing number
        $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'ffe_report'=>$ffe_report, 'fstatus'=>'complete');
        $update_status_outgoing = send_curl($this->security->xss_clean($updateOutgoing), $this->config->item('api_update_outgoings_trans'), 
                'POST', FALSE);

        if($update_status_outgoing->status){
            $success_response = array(
                'status' => 1,
                'message' => $ftrans_out
            );
            $response = $success_response;
        }else{
            $response = $error_response;
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
}