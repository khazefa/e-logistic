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
    private $cname_request = 'request-parts';
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
        $this->global['pageTitle'] = 'Supply FSL to FSL - '.APP_NAME;
        $this->global['pageMenu'] = 'Supply FSL to FSL';
        $this->global['contentHeader'] = 'Supply FSL to FSL';
        $this->global['contentTitle'] = 'Supply FSL to FSL';
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
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fpurpose = "S";
        $coverage = !empty($_POST['fcoverage']) ? implode(';',$_POST['fcoverage']) : "";
        
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
        
        //Parameters for cURL
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
                        
                    $row['transnum'] = $transnum;
                    $row['transout'] = $transout === "" ? "-" : $transout." ".$outstatus;
                    $row['transdate'] = tgl_indo($transdate);
                    $row['qty'] = $qty;
                    $row['from_fsl'] = $fslfromname === "" ? "-" : $fslfromname;
                    $row['to_fsl'] = $fslname === "" ? "-" : $fslname;
                    $row['button'] = $button = '<a href="'.base_url($this->cname."/print/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';

                    if($rpurpose === "S"){
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
                        
                    $row['transnum'] = $transnum;
                    $row['transout'] = $transout === "" ? "-" : $transout." ".$outstatus;
                    $row['transdate'] = tgl_indo($transdate);
                    $row['qty'] = $qty;
                    $row['from_fsl'] = $fslfromname === "" ? "-" : $fslfromname;
                    $row['to_fsl'] = $fslname === "" ? "-" : $fslname;

                    if($rpurpose === "S"){
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
    public function add()
    {
        $this->global['pageTitle'] = 'Add Supply FSL to FSL - '.APP_NAME;
        $this->global['pageMenu'] = 'Add Supply FSL to FSL';
        $this->global['contentHeader'] = 'Input Reff No';
        $this->global['contentTitle'] = 'Add Supply FSL to FSL';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $data['classname'] = $this->cname;
        $data['classname_request'] = $this->cname_request;
        $data['cart_postfix'] = $this->cart_postfix;
        $this->loadViews($this->view_dir.'create', $this->global, $data);
    }
    
    /**
     * This function is used to complete close transfer stock transaction
     */
    public function submit_trans(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to submit transaction'
        );
                    
        $date = date('Y-m-d');
        $fcode = 'WSPS';
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fpurpose = "S";
        $fqty = $this->input->post('fqty', TRUE);
        $fcode_from = $this->input->post('fcode_from', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $createdby = $this->session->userdata ( 'vendorUR' );
        
//        $arrParam = array('fparam'=>'IN', 'fcode'=>$fcode, 'fdigits'=>5);
//        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_incoming_num_ext'), 'POST', FALSE);
        $arrParam = array('fparam'=>'IC');
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_incoming_num'), 'POST', FALSE);
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        if($transnum === ""){
            $response = $error_response;
        }else{
            //Parameters for cURL
            $arrWhere = array('ftrans_out'=>$ftrans_out);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            $dataDetail = array();
            $total_qty = 0;
            if(!empty($rs)){
                foreach ($rs as $r) {
                    $outgoingnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
                    $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                    $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                    $return = filter_var($r->return_status, FILTER_SANITIZE_STRING);
                    $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
            
                    $rs_stock = $this->get_stock($fcode, $partnum);
                    $partstock = 0;
                    foreach ($rs_stock as $s){
                        $partstock = (int)$s["stock"];
                    }
                    $dataDetail = array('ftransno'=>$transnum, 'fpartnum'=>$partnum, 'fserialnum'=>$serialnum, 
                        'fqty'=>$qty);
                    $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_incomings_trans_detail'), 
                            'POST', FALSE);
                    $total_qty += (int)$qty;
                    
                    $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$partnum, 'fqty'=>(int)$partstock+(int)$qty, 'fflag'=>'N');
                    //update stock by fsl code and part number
                    $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                            'POST', FALSE);
                }
            }
			
            $dataTrans = array('ftransno'=>$transnum, 'ftrans_out'=>$ftrans_out, 'fdate'=>$date, 'fpurpose'=>$fpurpose, 
                'fqty'=>$total_qty, 'fuser'=>$createdby, 'fcode'=>$fcode, 'fcode_from'=>$fcode_from, 'fnotes'=>$fnotes);
            $main_res = send_curl($this->security->xss_clean($dataTrans), $this->config->item('api_add_incomings_trans'), 'POST', FALSE);
            if($main_res->status)
            {
                //update outgoing status by outgoing number
                $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'fstatus'=>'complete');
                $update_status_outgoing = send_curl($this->security->xss_clean($updateOutgoing), $this->config->item('api_update_outgoings_trans'), 
                        'POST', FALSE);

                if($update_status_outgoing->status){
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
    
    /**
     * This function is used to get list information described by function name
     */
    private function get_stock($fcode, $partnum){
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
            $row['warehouse'] = $this->get_warehouse_name($code);
            $row['part'] = $this->get_part_name($partno);
            
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