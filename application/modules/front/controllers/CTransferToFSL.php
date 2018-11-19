<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CTransferToFSL (CTransferToFSLController)
 * CTransferToFSL Class to control Transfer Stock to FSL
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CTransferToFSL extends BaseController
{
    private $cname = 'transfer-stock-to-fsl';
    private $cname_atm = 'atm';
    private $cname_warehouse = 'warehouse';
    private $cname_cart = 'cart';
    private $view_dir = 'front/transfer-stock-to-fsl/';
    private $readonly = TRUE;
    private $hasCoverage = FALSE;
    private $hasHub = FALSE;
    private $cart_postfix = 'ott';
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
        $this->global['pageTitle'] = 'List Transfer Stock to FSL - '.APP_NAME;
        $this->global['pageMenu'] = 'List Transfer Stock to FSL';
        $this->global['contentHeader'] = 'List Transfer Stock to FSL';
        $this->global['contentTitle'] = 'List Transfer Stock to FSL';
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
    public function add()
    {
        if(!$this->readonly){
            $this->global['pageTitle'] = 'Transfer Stock to FSL - '.APP_NAME;
            $this->global['pageMenu'] = 'Transfer Stock to FSL';
            $this->global['contentHeader'] = 'Transfer Stock to FSL';
            $this->global['contentTitle'] = 'Transfer Stock to FSL';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            
            $data['classname'] = $this->cname;
            $data['cart_postfix'] = $this->cart_postfix;
            $data['list_part'] = $this->get_list_part();
            $data['list_warehouse'] = $this->get_list_warehouse();
            $data['url_list_cart'] = base_url($this->cname_cart.'/outgoing/list/'.$this->cart_postfix);
            $data['url_part_sub'] = base_url('spareparts-sub/list-sub');
            $data['url_part_nearby'] = base_url('spareparts-stock/list-nearby');
            $data['url_check_part'] = base_url('spareparts-stock/check-part');
            $this->loadViews($this->view_dir.'create', $this->global, $data);   
        }else{
            redirect($this->cname.'/view');
        }
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
        $fticket = empty($this->input->get('fticket', TRUE)) ? "" : $this->input->get('fticket', TRUE);
        $fpurpose = "RWH";
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
            
            $fcode = "";
        }else{
            $fcode = $this->repo;
        }
        
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode, 'fdate1'=>$fdate1, 'fdate2'=>$fdate2, 
            'fticket'=>$fticket, 'fpurpose'=>$fpurpose, 'fstatus'=>$fstatus);

        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        // var_dump($rs);exit();
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
                    $closingdate = filter_var($r->closing_date, FILTER_SANITIZE_STRING);
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
                    $row['closingdate'] = empty($closingdate) ? "-" : date('d/m/Y H:i', strtotime($closingdate));
                    $row['transfer_from'] = $transfer_from;
                    $row['transfer_to'] = $transfer_to;
                    $row['qty'] = $qty;
                    $row['status'] = $status === "open" ? strtoupper($status)."<br> (".$elapsed.")" : strtoupper($status);
                    $row['button'] = '<a href="'.base_url($this->cname."/print/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';

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
     * This function is used to check transaction
     */
    public function check_transaction(){
        $rs = array();
        $arrWhere = array();
        $global_response = array();
        $success_response = array();
        $error_response = array();
        
        $fcode_dest = $this->repo;
        $ftrans_out = $this->input->get('ftrans_out', TRUE);
        $arrWhere = array('fcode_dest'=>$fcode_dest, 'ftrans_out'=>$ftrans_out);
        
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
            if($status === "open" || $status === "pending"){
                $global_response = array(
                    'status' => 1,
                    'purpose' => $purpose,
                    'total_qty' => $total_qty,
                    'message'=> 'Transaction still open'
                );
            }else{
                $global_response = array(
                    'status' => 0,
                    'total_qty' => 0,
                    'message'=> 'Transaction is already complete, you cannot close this transaction twice.'
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
     * This function is used to get detail request
     */
    public function get_transfer(){
        $rs = array();
        $arrWhere = array();
        
        $ftrans_out = $this->input->get('ftrans_out', TRUE);        
        if(!empty($ftrans_out)){
            //Parameters for cURL
            $arrWhere = array('ftrans_out'=>$ftrans_out);
        
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_view_outgoings'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
        }else{
            $rs = array();
            $arrWhere = array();
        }
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
            $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
            $total_qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $fsl = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $fsl_name = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
            $status = filter_var($r->outgoing_status, FILTER_SANITIZE_STRING);
            $purpose = "";
            $curdatetime = new DateTime();
            $datetime2 = new DateTime($transdate);
            $interval = $curdatetime->diff($datetime2);
//            $elapsed = $interval->format('%a days %h hours');
            $elapsed = $interval->format('%a days');
            
            $get_purpose = $this->config->config['purpose']['out'];
            $purpose = isset($get_purpose[$fpurpose]) ? $get_purpose[$fpurpose] : "-";
            
            $row['transnum'] = $transnum;
            $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
            $row['purpose'] = $purpose;
            $row['qty'] = $total_qty;
            $row['fsl'] = $fsl;
            $row['fslname'] = $fsl_name;
            $row['status'] = $status === "open" ? strtoupper($status)."<br> (".$elapsed.")" : strtoupper($status);
 
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
    public function get_transfer_detail(){
        $rs = array();
        $arrWhere = array();
        
        $ftrans_out = $this->input->get('ftrans_out', TRUE);
        if(!empty($ftrans_out)){
            //Parameters for cURL
            $arrWhere = array('ftrans_out'=>$ftrans_out);
        
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
        }else{
            $rs = array();
            $arrWhere = array();
        }
        
        $data = array();
        foreach ($rs as $r) {
            $dtid = filter_var($r->dt_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $return = filter_var($r->return_status, FILTER_SANITIZE_STRING);
            $get_status = $this->config->config['status']['in_transfer_fsl'];
            $status = isset($get_status[$return]) ? $get_status[$return] : "-";
            $notes = empty($r->dt_notes) ? "-" : filter_var($r->dt_notes, FILTER_SANITIZE_STRING);
            $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
            $isdeleted = $deleted < 1 ? "N" : "Y";
            
            if($isdeleted === "N"){
                $row['id'] = $dtid;
                $row['transnum'] = $transnum;
                $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
                $row['partnum'] = $partnum;
                $row['partname'] = $partname;
                $row['serialnum'] = $serialnum;
                $row['qty'] = $qty;
                $row['return'] = $return;
                $row['status'] = $status;
                $row['notes'] = $notes;
                $row['deleted'] = $isdeleted;
 
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
     * This function is used to get lists for populate data
     */
    private function get_list_part(){
        $rs = array();
        $arrWhere = array();
        
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
    private function get_list_cart(){
        $rs = array();
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->cart_sess;
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_outgoing_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
//            $row['stock'] = $partstock;
            $row['qty'] = (int)$qty;
 
            $data[] = $row;
        }
        
        return $data;
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
     * This function is used to update detail outgoing status
     */
    public function update_detail_id(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to update detail'
        );
        
        $fid = $this->input->post('fid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);

        $arrWhere = array('fid'=>$fid, 'fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum, 'fnotes'=>$fnotes, 'fstatus'=>$fstatus);
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_outgoings_trans_detail_id'), 'POST', FALSE);

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
     * This function is used to update detail outgoing status
     */
    public function update_detail_status(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to update detail'
        );
        
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);

        $arrWhere = array('ftrans_out'=>$ftrans_out, 'fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum, 
            'fstatus'=>$fstatus, 'fnotes'=>$fnotes);
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_outgoings_trans_detail'), 'POST', FALSE);

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
     * This function is used to update detail outgoing status
     */
    public function update_detail_status_all(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to update detail'
        );
        
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $arrWhere = array('ftrans_out'=>$ftrans_out, 'fstatus'=>$fstatus);
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_outgoings_trans_detail_all'), 'POST', FALSE);

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
     * This function is used to complete transaction
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
        $cartid = $this->cart_sess;
        
        $date = date('Y-m-d'); 
        $fticket = "";
        $fengineer_id = "";
        $fengineer2_id = "";
        $fpurpose = "RWH";
        $fdest_fsl = $this->input->post('fdest_fsl', TRUE);
        $fdelivery = $this->input->post('fdelivery', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fcust = "";
        $floc = "";
        $fssb_id = "";
        $createdby = $this->session->userdata ( 'vendorUR' );
        
        if(($fqty < 1) || (empty($fqty))){
            $this->session->set_flashdata('error', 'Failed to submit transaction data');
            $response = $error_response;
        }else{
            $arrParam = array('fparam'=>"OG");
            $rs_transnum = send_curl($arrParam, $this->config->item('api_get_outgoing_num'), 'POST', FALSE);
            $transnum = $rs_transnum->status ? $rs_transnum->result : "";
//            var_dump($transnum);
            if($transnum === ""){
                $response = $error_response;
            }else{
                $data_tmp = array();
                //get cart list by retnum
                $data_tmp = $this->get_list_cart();
//                var_dump($data_tmp);
                $dataDetail = array();
                if(!empty($data_tmp)){
                    if($fqty < 1){
                        $this->session->set_flashdata('error', 'Skip looped submitted data');
                        $response = $error_response;
                    }else{
                        $dataTrans = array('ftransno'=>$transnum, 'fdate'=>$date, 'fticket'=>$fticket, 'fengineer_id'=>$fengineer_id, 
                            'fengineer2_id'=>$fengineer2_id, 'fpurpose'=>$fpurpose, 'fdelivery'=>$fdelivery, 'fqty'=>$fqty, 
                            'fuser'=>$createdby, 'fcode'=>$fcode, 'fdest_code'=>$fdest_fsl, 'fnotes'=>$fnotes, 'fcust'=>$fcust, 
                            'floc'=>$floc, 'fssb_id'=>$fssb_id);
                        $main_res = send_curl($this->security->xss_clean($dataTrans), $this->config->item('api_add_outgoings_trans'), 'POST', FALSE);
                        if($main_res->status)
                        {
                            $partstock = 0;
                            $listdetail = array();
                            $listupdatestock = array();
                            foreach ($data_tmp as $d){
                                $partstock = $this->get_stock($fcode, $d['partno']);
//                                var_dump($partstock);
                                if($partstock < (int)$d['qty']){
                                    //skip this insert detail for this row
                                }else{
                                    $dataDetail = array('ftransno'=>$transnum, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno'], 
                                        'fqty'=>$d['qty']);
//                                    $listdetail[] = $dataDetail;
                                    $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_outgoings_trans_detail'), 
                                            'POST', FALSE);
                                }
                            }
//                            var_dump($listdetail);
//                            var_dump($listupdatestock);exit();
                            //clear cart list data
                            $arrWhere = array('fcartid'=>$cartid);
                            $rem_res = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_clear_outgoings_cart'), 'POST', FALSE);
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

                }else{
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
                $notes = $r->outgoing_notes == "" ? "-" : filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
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
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,1,'L');
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
}