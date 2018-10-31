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
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if(!$this->isWebAdmin()){
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {        
        $this->global['pageTitle'] = 'Supply from FSL - '.APP_NAME;
        $this->global['pageMenu'] = 'Supply from FSL';
        $this->global['contentHeader'] = 'Supply from FSL';
        $this->global['contentTitle'] = 'Supply from FSL';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $data['list_coverage'] = $this->get_list_warehouse("array");
        $this->loadViews('front/supply-from-fsl/index', $this->global, $data);
    }
    
    /**
     * This function is used to get lists fsl
     */
    public function get_list_warehouse($output = "json"){
        $rs = array();
        $arrWhere = array();
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = $this->common->nohtml($r->fsl_name);
 
            $data[] = $row;
        }
        
        switch ($output){
            case "json":
                return $this->output
                ->set_content_type('application/json')
                ->set_output(
                    json_encode($data)
                );
            break;
            case "array":
                return $data;
            break;
            default:
                return $this->output
                ->set_content_type('application/json')
                ->set_output(
                    json_encode($data)
                );
            break;
        }
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_view_datatable(){ 
        $rs = array();
        $arrWhere = array();
        
        //Parameters for cURL
        $fcode = 'WSPS';
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fpurpose = "S";
        $coverage = !empty($_POST['fcoverage']) ? implode(';',$_POST['fcoverage']) : "";
        
        if (strpos($coverage, 'C000') !== false) {
            $fcoverage = array();
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
            $fpurpose = filter_var($r->incoming_purpose, FILTER_SANITIZE_STRING);
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
            $row['user'] = $user_fullname;
            $row['notes'] = $notes;            
            $row['button'] = $button = '<a href="'.base_url("print-incoming-supply/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
 
            if(empty($e_coverage)){
                $data[] = $row;
            }else{
                if(in_array($fslfrom, $e_coverage)){
                    $data[] = $row;
                }
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to load the add new form
     */
    public function add()
    {
        $this->global['pageTitle'] = 'Add Supply from FSL - '.APP_NAME;
        $this->global['pageMenu'] = 'Add Supply from FSL';
        $this->global['contentHeader'] = 'Add Supply from FSL';
        $this->global['contentTitle'] = 'Add Supply from FSL';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->loadViews('front/supply-from-fsl/create', $this->global, NULL);
    }
    
    /**
     * This function is used to check outgoing transaction
     */
    public function check_outgoing_trf(){
        $rs = array();
        $arrWhere = array();
        $global_response = array();
        $success_response = array();
        $error_response = array();
        
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $arrWhere = array('ftrans_out'=>$ftrans_out);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $total_qty = 0;
            $purpose = "";
            $status = "";
            foreach ($rs as $r){
                $total_qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $purpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
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
                    'purpose' => $purpose,
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
     * This function is used to complete close transfer stock transaction
     */
    public function submit_trans_close_transfer(){
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
            
                    $rs_stock = $this->get_info_part_stock($fcode, $partnum);
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
}