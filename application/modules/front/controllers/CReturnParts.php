<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CReturnParts (CReturnPartsController)
 * CReturnParts Class to control Tickets.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CReturnParts extends BaseController
{
    private $cname = 'return-parts';
    private $cname_request = 'request-parts';
    private $cname_atm = 'atm';
    private $cname_warehouse = 'warehouse';
    private $cname_cart = 'cart';
    private $view_dir = 'front/return-parts/';
    private $readonly = TRUE;
    private $hasCoverage = FALSE;
    private $hasHub = FALSE;
    private $cart_postfix = 'inr';
    private $cart_sess = '';
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isWebAdmin() || $this->isSpv() || $this->isStaff()){
            if($this->isStaff()){
                $this->readonly = FALSE;
                $this->cart_sess = $this->session->userdata ( 'cart_session' ).$this->cart_postfix;
            }elseif($this->isSpv()){
                $this->readonly = TRUE;
                $this->hasHub = TRUE;
                $this->hasCoverage = TRUE;
            }else{
                $this->readonly = TRUE;
                $this->hasHub = TRUE;
            }
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'List Return Parts - '.APP_NAME;
        $this->global['pageMenu'] = 'List Return Parts';
        $this->global['contentHeader'] = 'List Return Parts';
        $this->global['contentTitle'] = 'List Return Parts';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $data['readonly'] = $this->readonly;
        $data['hashub'] = $this->hasHub;
        $data['classname'] = $this->cname;
        $data['url_list'] = base_url($this->cname.'/list/json');
        if($this->hasHub){
            $data['list_warehouse'] = $this->get_list_warehouse();
        }
        $this->loadViews($this->view_dir.'index', $this->global, $data);
    }
    
    /**
     * This function is used to load the add new form
     */
    public function add($transnum = "")
    {
        $this->global['pageTitle'] = 'Return Parts - '.APP_NAME;
        $this->global['pageMenu'] = 'Return Parts';
        $this->global['contentHeader'] = 'Input Reff No';
        $this->global['contentTitle'] = 'Return Parts';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $data['classname'] = $this->cname;
        $data['classname_request'] = $this->cname_request;
        $data['cart_postfix'] = $this->cart_postfix;
        $data['status_option'] = $this->config->config['status']['in_detail'];
        if(empty($transnum)){
            $data['transnum'] = "";
        }else{
            $data['transnum'] = $transnum;
        }
        $this->loadViews($this->view_dir.'create', $this->global, $data);
    }
    
    /**
     * This function is used to get list information described by function name
     */
    private function get_list_warehouse(){
        $rs = array();
        $arrWhere = array();
        
        $fcoverage = $this->session->userdata ( 'ovCoverage' );
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        $arrWhere = array('fdeleted'=>0, 'flimit'=>0);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
 
            if($this->hasCoverage){
                if(in_array($row['code'], $e_coverage)){
                    if($row['code'] !== "WSPS"){
                        $data[] = $row;
                    }
                }
            }else{
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
        $isParam = FALSE;
        
        $fdate1 = $this->input->get('fdate1', TRUE);
        $fdate2 = $this->input->get('fdate2', TRUE);
        $fpurpose = $this->input->get('fpurpose', TRUE);
        $coverage = !empty($_GET['fcoverage']) ? implode(';',$_GET['fcoverage']) : "";
        
        if($this->hasHub){
            if($this->hasCoverage){
                if(empty($coverage)){
                    $fcoverage = $this->session->userdata ( 'ovCoverage' );
                }else{
                    if (strpos($coverage, ',') !== false) {
                        $fcoverage = str_replace(',', ';', $coverage);
                    }else{
                        $fcoverage = $coverage;
                    }
                }
            }else{
                if (strpos($coverage, ',') !== false) {
                    $fcoverage = str_replace(',', ';', $coverage);
                }else{
                    $fcoverage = $coverage;
                }
            }

            if(empty($fcoverage)){
                $e_coverage = array();
            }else{
                $e_coverage = explode(';', $fcoverage);
            }
            
            $fcode = "";
        }else{
            $fcode = $this->repo;
        }
        
        $arrWhere = array('fcode'=>$fcode, 'fdate1'=>$fdate1, 'fdate2'=>$fdate2, 'fpurpose'=>$fpurpose);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_incomings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $transnum = filter_var($r->incoming_num, FILTER_SANITIZE_STRING);
                    $transout = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $outstatus = $r->outgoing_status == "" ? "" : "(". strtoupper($r->outgoing_status).")";
                    $transdate = filter_var($r->incoming_date, FILTER_SANITIZE_STRING);
                    $rpurpose = filter_var($r->incoming_purpose, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                    $fslfrom = filter_var($r->fsl_from, FILTER_SANITIZE_STRING);
                    $fslfromname = filter_var($r->fsl_from_name, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->incoming_qty, FILTER_SANITIZE_NUMBER_INT);
                    $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
                    $notes = filter_var($r->incoming_notes, FILTER_SANITIZE_STRING);
                    
                    $get_purpose = $this->config->config['purpose']['in'];
                    $purpose = isset($get_purpose[$rpurpose]) ? $get_purpose[$rpurpose] : "-";
                    $button = '<a href="'.base_url($this->cname."/print/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                        
                    $row['transnum'] = $transnum;
                    $row['transout'] = $transout === "" ? "-" : $transout." ".$outstatus;
                    $row['transdate'] = tgl_indo($transdate);
                    $row['fsl_location'] = $fslname;
                    $row['qty'] = $qty;
                    $row['button'] = $button;

                    if($rpurpose === "R" || $rpurpose === "RG"){
                        if($this->hasHub){
                            if(in_array($fslcode, $e_coverage)){
                                $data[] = $row;
                            }
                        }else{
                            $data[] = $row;
                        }
                    }
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array";
                foreach ($rs as $r) {
                    $transnum = filter_var($r->incoming_num, FILTER_SANITIZE_STRING);
                    $transout = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $outstatus = $r->outgoing_status == "" ? "" : "(". strtoupper($r->outgoing_status).")";
                    $transdate = filter_var($r->incoming_date, FILTER_SANITIZE_STRING);
                    $fpurpose = filter_var($r->incoming_purpose, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $fslfrom = filter_var($r->fsl_from, FILTER_SANITIZE_STRING);
                    $fslfromname = filter_var($r->fsl_from_name, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->incoming_qty, FILTER_SANITIZE_NUMBER_INT);
                    $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
                    $notes = filter_var($r->incoming_notes, FILTER_SANITIZE_STRING);
                    
                    $get_purpose = $this->config->config['purpose']['in'];
                    $purpose = isset($get_purpose[$fpurpose]) ? $get_purpose[$fpurpose] : "-";
                    $button = '<a href="'.base_url($this->cname."/print/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                        
                    $row['transnum'] = $transnum;
                    $row['transout'] = $transout === "" ? "-" : $transout." ".$outstatus;
                    $row['transdate'] = tgl_indo($transdate);
                    $row['qty'] = $qty;

                    if($fpurpose === "R" || $fpurpose === "RG"){
                        if($this->hasHub){
                            if(in_array($fslcode, $e_coverage)){
                                $data[] = $row;
                            }
                        }else{
                            $data[] = $row;
                        }
                    }
                }
                $output = $data;
            break;
        }
        return $output;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    private function get_stock($fcode, $partnum){
        $rs = array();
        $arrWhere = array();
        $val_stock = 0;
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $minval = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            if($initflag === "Y"){
                $val_stock = $initstock;
            }else{
                $val_stock = $stock;
            }
        }
        
        return $val_stock;
    }
    
    /**
     * This function is used to get list cart
     */
    private function get_list_cart($cartid){
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
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_incoming_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_incoming_qty, FILTER_SANITIZE_NUMBER_INT);
            $status = filter_var($r->return_status, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->tmp_notes, FILTER_SANITIZE_STRING);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
            $row['qty'] = (int)$qty;
            $row['status'] = $status;
            $row['notes'] = $notes;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to complete return transaction
     */
    public function submit_trans(){
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
        $fpurpose = "R";
        $fnotes = $this->input->post('fnotes', TRUE);
        $createdby = $this->session->userdata ( 'vendorUR' );
        
        $cartid = $this->cart_sess.$ftrans_out;
        $arrParam = array('fparam'=>'IC');
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_incoming_num'), 'POST', FALSE);
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        if($transnum === ""){
            $response = $error_response;
        }else{
            $data_tmp = array();
            //get cart list by retnum
            $data_tmp = $this->get_list_cart($cartid);
            $dataDetail = array();
            if(!empty($data_tmp)){
                $dataTrans = array('ftransno'=>$transnum, 'ftrans_out'=>$ftrans_out, 'fdate'=>$date, 'fpurpose'=>$fpurpose, 
                    'fqty'=>$fqty, 'fuser'=>$createdby, 'fcode'=>$fcode, 'fcode_from'=>'', 'fnotes'=>$fnotes);
                $main_res = send_curl($this->security->xss_clean($dataTrans), $this->config->item('api_add_incomings_trans'), 'POST', FALSE);
                if($main_res->status)
                {
                    //update outgoing status by outgoing number
                    $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'ffe_report'=>$ffe_report, 'fstatus'=>'complete');
                    $update_status_outgoing = send_curl($this->security->xss_clean($updateOutgoing), $this->config->item('api_update_outgoings_trans'), 
                            'POST', FALSE);
                    
                    foreach ($data_tmp as $d){
                        $partstock = $this->get_stock($fcode, $d['partno']);
                        $dataDetail = array('ftransno'=>$transnum, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno'], 
                            'fqty'=>$d['qty'], 'fstatus'=>$d['status'], 'fnotes'=>$d['notes']);
                        $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_incomings_trans_detail'), 
                                'POST', FALSE);

                        if($d['status'] === 'RGP'){
                            $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$d['partno'], 'fqty'=>(int)$partstock+(int)$d['qty'], 
                                'fflag'=>'N');
                            //update stock by fsl code and part number
                            $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                                    'POST', FALSE);
                        }
                    }
                    
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
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    public function print_transaction($ftrans_in){
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
            list($width, $height) = set_pdf_size($orientation, $paper_size);

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
                $get_purpose = $this->config->config['purpose']['out'];
                $purpose = isset($get_purpose[$fpurpose]) ? $get_purpose[$fpurpose] : "-";
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
            $this->mypdf->Code39(($width*(50/100)),10,$transnum,1,10);
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
            $this->mypdf->Cell(($width*(30/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(7.5/100)),6,'Qty',1,0);
            $this->mypdf->Cell(($width*(18/100)),6,'Serial No.',1,0);
            $this->mypdf->Cell(($width*(15/100)),6,'Status',1,1);

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
                $fstatus = $row->return_status == "" ? "" : filter_var($row->return_status, FILTER_SANITIZE_STRING);
                $get_status = $this->config->config['status']['in_detail'];
                $status = isset($get_status[$fstatus]) ? $get_status[$fstatus] : "-";

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(30/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,0,'C');
                $this->mypdf->CellFitScale(($width*(18/100)),6,$serialnum,1,0);
                $this->mypdf->CellFitScale(($width*(15/100)),6,$status,1,1);
            }
            
            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width-20),7,'STOCK RETURN FORM #'.$ftransin,0,1,'C');
            
            $this->mypdf->ln(3);
            
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width-($width*0.1)),7,'FE Report: '.$fe_report,0,1,'L');

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Returned PN',1,0);
            $this->mypdf->Cell(($width*(30/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(7.5/100)),6,'Qty',1,0);
            $this->mypdf->Cell(($width*(18/100)),6,'Serial No.',1,0);
            $this->mypdf->Cell(($width*(15/100)),6,'Notes',1,1);

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
                $notes = empty($row->dt_notes) ? "-" : filter_var($row->dt_notes, FILTER_SANITIZE_STRING);

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(30/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,0,'C');
                $this->mypdf->CellFitScale(($width*(18/100)),6,$serialnum,1,0);
                $this->mypdf->CellFitScale(($width*(15/100)),6,$notes,1,1);
            }
            
            $title = 'Return Parts #'.$ftransin;
            $this->mypdf->SetTitle($title);
    //        $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('D', $title.'.pdf');
        }
    }
}