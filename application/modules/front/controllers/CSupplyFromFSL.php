<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CSupplyFromFSL (CSupplyFromFSLController)
 * CSupplyFromFSL Class to control supplied parts from FSL to Central Warehouse(CWH).
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CSupplyFromFSL extends BaseController
{
    private $cname = 'supply-fsl-to-fsl';
    private $cname_transfer = 'transfer-stock-to-fsl';
    private $cname_atm = 'atm';
    private $cname_warehouse = 'warehouse';
    private $cname_cart = 'cart';
    private $view_dir = 'front/supply-from-fsl/';
    private $readonly = TRUE;
    private $hasCoverage = FALSE;
    private $hasHub = FALSE;
    private $cart_postfix = 'ins';
    private $cart_sess = '';
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSpv() || $this->isStaff()){
            if($this->isStaff()){
                $this->readonly = FALSE;
                $this->cart_sess = $this->session->userdata ( 'cart_session' ).$this->cart_postfix;
            }elseif($this->isSpv()){
                $this->readonly = TRUE;
                $this->hasHub = TRUE;
                $this->hasCoverage = TRUE;
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
        $this->global['pageTitle'] = 'List Received Transfer - '.APP_NAME;
        $this->global['pageMenu'] = 'List Received Transfer';
        $this->global['contentHeader'] = 'List Received Transfer';
        $this->global['contentTitle'] = 'List Received Transfer';
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

        //Parameters for cURL
        // $fcode = $this->repo;
        $fdate1 = $this->input->get('fdate1', TRUE);
        $fdate2 = $this->input->get('fdate2', TRUE);
        $fpurpose = "RWH";
        // $fstatus = "open";
        $fstatus = $this->input->get('fstatus', TRUE);
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
            
            $fcode = $e_coverage;
        }else{
            $fcode = $this->repo;
        }
        
        //Parameters for cURL
        $arrWhere = array('fdest_code'=>$fcode, 'fdate1'=>$fdate1, 'fdate2'=>$fdate2, 'fpurpose'=>$fpurpose, 'fstatus'=>$fstatus);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                    $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $transfer_from = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                    $fsldest = filter_var($r->fsl_dest, FILTER_SANITIZE_STRING);
                    $transfer_to = filter_var($r->fsl_dest_name, FILTER_SANITIZE_STRING);
                    $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
                    $status = filter_var($r->outgoing_status, FILTER_SANITIZE_STRING);
                    $button = "";
                    $curdatetime = new DateTime();
                    $datetime2 = new DateTime($transdate);
                    $interval = $curdatetime->diff($datetime2);
        //            $elapsed = $interval->format('%a days %h hours');
                    $elapsed = $interval->format('%a days');

                    $row['transnum'] = $transnum;
                    $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
                    $row['transfer_from'] = $transfer_from;
                    $row['transfer_to'] = $transfer_to;
                    $row['qty'] = $qty;
                    $row['status'] = $status === "open" ? strtoupper($status)."<br> (".$elapsed.")" : strtoupper($status);

                    if($this->readonly){
                        $button = ' <a href="'.base_url($this->cname."/print/").$transnum.'" title="Print Transaction" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                    }else{
                        if($status === "open" || $status === "pending"){
                            $button = '<a href="'.base_url($this->cname."/add/").$transnum.'" title="Receive Parts"><i class="mdi mdi mdi-archive mr-2 text-muted font-18 vertical-middle"></i></a>';
                            $button .= ' <a href="'.base_url($this->cname."/print/").$transnum.'" title="Print Transaction" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                        }else{
                            $button = ' <a href="'.base_url($this->cname."/print/").$transnum.'" title="Print Transaction" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
                        }
                    }
                    $row['button'] = $button;

                    if($fpurpose === "RWH"){
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
            case "array":
                foreach ($rs as $r) {
                    $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                    $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $transfer_from = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                    $fsldest = filter_var($r->fsl_dest, FILTER_SANITIZE_STRING);
                    $transfer_to = filter_var($r->fsl_dest_name, FILTER_SANITIZE_STRING);
                    $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
                    $status = filter_var($r->outgoing_status, FILTER_SANITIZE_STRING);
                    $curdatetime = new DateTime();
                    $datetime2 = new DateTime($transdate);
                    $interval = $curdatetime->diff($datetime2);
        //            $elapsed = $interval->format('%a days %h hours');
                    $elapsed = $interval->format('%a days');

                    $row['transnum'] = $transnum;
                    $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
                    $row['transfer_from'] = $transfer_from;
                    $row['transfer_to'] = $transfer_to;
                    $row['qty'] = $qty;
                    $row['status'] = $status === "open" ? strtoupper($status)."<br> (".$elapsed.")" : strtoupper($status);

                    if($fpurpose === "RWH"){
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
     * This function is used to load the add new form
     */
    public function add($transnum = "")
    {
        $this->global['pageTitle'] = 'Add Transfered Parts from FSL - '.APP_NAME;
        $this->global['pageMenu'] = 'Add Transfered Parts from FSL';
        $this->global['contentHeader'] = 'Input Reff No';
        $this->global['contentTitle'] = 'Add Transfered Parts from FSL';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $data['classname'] = $this->cname;
        $data['classname_transfer'] = $this->cname_transfer;
        $data['cart_postfix'] = $this->cart_postfix;
        $data['status_option'] = $this->config->config['status']['in_transfer_fsl'];
        if(empty($transnum)){
            $data['transnum'] = "";
        }else{
            $data['transnum'] = $transnum;
        }
        // $this->loadViews($this->view_dir.'create', $this->global, $data);
        $this->loadViews($this->view_dir.'create_verify', $this->global, $data);
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
        $fcode_from = $this->input->post('fcode_from', TRUE);
        $date = date('Y-m-d'); 
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fpurpose = "S";
        $fnotes = $this->input->post('fnotes', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $createdby = $this->session->userdata ( 'vendorUR' );
        
        if($fstatus === "pending"){
            //update outgoing status by outgoing number
            $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'ffe_report'=>'', 'fstatus'=>$fstatus);
            $update_status_outgoing = send_curl($this->security->xss_clean($updateOutgoing), $this->config->item('api_update_outgoings_trans'), 
                    'POST', FALSE);
            $response = $success_response;
        }else{
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
                        'fqty'=>$fqty, 'fuser'=>$createdby, 'fcode'=>$fcode, 'fcode_from'=>$fcode_from, 'fnotes'=>$fnotes);
                    $main_res = send_curl($this->security->xss_clean($dataTrans), $this->config->item('api_add_incomings_trans'), 'POST', FALSE);
                    if($main_res->status)
                    {
                        //update outgoing status by outgoing number
                        $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'ffe_report'=>'', 'fstatus'=>$fstatus);
                        $update_status_outgoing = send_curl($this->security->xss_clean($updateOutgoing), $this->config->item('api_update_outgoings_trans'), 
                                'POST', FALSE);
                        
                        foreach ($data_tmp as $d){
                            $partstock = $this->get_stock($fcode, $d['partno']);
                            $dataDetail = array('ftransno'=>$transnum, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno'], 
                                'fqty'=>$d['qty'], 'fstatus'=>$d['status'], 'fnotes'=>$d['notes']);
                            $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_incomings_trans_detail'), 
                                    'POST', FALSE);

                            //Return stock for Return Good Part (RGP)
                            if($this->check_part($fcode, $d['partno'])){
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
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }

    /**
     * This function is used to check part
     */
    private function check_part($fcode, $fpartnum){
        $rs = array();
        $rs2 = array();
        $arrWhere = array();
        $arrWhere2 = array();
        $success_response = array();
        $error_response = array();
        $response = array();
        $isAvailable = false;

        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();

        if(empty($rs)){
            $rs_data2 = send_curl(array('fpartnum'=>$fpartnum), $this->config->item('api_info_parts'), 'POST', FALSE);
            $rs2 = $rs_data2->status ? $rs_data2->result : array();

            if(empty($rs2)){
                $isAvailable = false;
            }else{
                $dataInfo = array('fcode'=> $fcode, 'fpartnum'=> $fpartnum, 'fminval'=> 3, 'finitval'=> 0, 
                    'flastval'=> 0, 'fflag'=> 'Y');
                $rs_data3 = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_part_stock'), 'POST', FALSE);

                if($rs_data3->status)
                {
                    $isAvailable = true;
                }
            }

        }else{
            $isAvailable = true;
        }

        return $isAvailable;
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
        $fstatus = $this->input->post('fstatus', TRUE);
        
        //update outgoing status by outgoing number
        $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'ffe_report'=>$ffe_report, 'fstatus'=>$fstatus);
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

    public function print_transaction($ftrans_out){
        $rs = array();
        $arrWhere = array();
        $arrWhere2 = array();

        //Parameters for cURL
        $arrWhere = array('ftrans_out'=>$ftrans_out);
            
        if(empty($ftrans_out) || $ftrans_out = ""){
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
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_view_outgoings'), 'POST', FALSE);
            $results = $rs_data->status ? $rs_data->result : array();
            
            $transnum = "";
            $purpose = "";
            $transdate = "";
            $fpurpose = "";
            $fslname = "";
            $fsldest = "";
            $fsldestname = "";
            $notes = "";
            foreach ($results as $r){
                $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
                $get_purpose = $this->config->config['purpose']['out'];
                $purpose = isset($get_purpose[$fpurpose]) ? $get_purpose[$fpurpose] : "-";
                $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                $transdate = date("d/m/Y H:i:s", strtotime(filter_var($r->created_at, FILTER_SANITIZE_STRING)));
                $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                $fsldestcode = filter_var($r->fsl_dest, FILTER_SANITIZE_STRING);
                $fsldestname = filter_var($r->fsl_dest_name, FILTER_SANITIZE_STRING);
                $notes = empty($r->outgoing_notes) ? "-" : filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
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
            $this->mypdf->Cell(($width*(15/100)),7,'Delivery Notes',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->CellFitScale(($width*(35/100)),7,$notes,1,1, 'L');

            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width*(60/100)),7,'STOCK TRANSFER FORM',0,0,'R');
            $this->mypdf->ln(5);
            // Garis atas untuk header
//            $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

            $this->mypdf->ln(5);

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Requested PN',1,0);
            $this->mypdf->Cell(($width*(20/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(7.5/100)),6,'Qty',1,0);
            $this->mypdf->Cell(($width*(15/100)),6,'Issued PN',1,0);
            $this->mypdf->Cell(($width*(18/100)),6,'Serial No.',1,0);
            $this->mypdf->Cell(($width*(5/100)),6,'Bin',1,0);
            $this->mypdf->Cell(($width*(10/100)),6,'Status',1,1);

            $this->mypdf->SetFont('Arial','',9);
            
            //Parse Data for cURL
            $rs_data2 = send_curl($arrWhere, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
            $rslist = $rs_data2->status ? $rs_data2->result : array();
            
            foreach( $rslist as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $qty = filter_var($row->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $serialnum = filter_var($row->serial_number, FILTER_SANITIZE_STRING);
                $deleted = filter_var($row->is_deleted, FILTER_SANITIZE_NUMBER_INT);

                if($deleted < 1){
                    $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                    $this->mypdf->CellFitScale(($width*(20/100)),6,$partname,1,0);
                    $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,0,'C');
                    $this->mypdf->CellFitScale(($width*(15/100)),6,' ',1,0);
                    $this->mypdf->CellFitScale(($width*(18/100)),6,$serialnum,1,0);
                    $this->mypdf->CellFitScale(($width*(5/100)),6,' ',1,0);
                    $this->mypdf->CellFitScale(($width*(10/100)),6,' ',1,1);
                }
            }

            $this->mypdf->ln(1);
            $this->mypdf->SetFont('Arial','',8.5);
            $this->mypdf->drawTextBox('Notes:', $width-20, 12, 'L', 'T');
            $this->mypdf->ln(10);
            $this->mypdf->Cell(($width*(25/100)),6,'Transfered by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Approved by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Processed by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Received by:',0,1,'L');
            $this->mypdf->ln(12);
            $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$fslname,0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$fsldestname,0,1,'L');
            $this->mypdf->ln(0.1);
            $this->mypdf->Cell(($width*(25/100)),6,'Date:'.$transdate,0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,1,'L');

            $title = 'Transfered Stock #'.$transnum;
            $this->mypdf->SetTitle($title);
    //        $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('D', $title.'.pdf');
        }
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
     * This function is used to get detail information
     */
    private function get_warehouse_name($fcode){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
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
    private function get_part_name($fpartnum){
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
}