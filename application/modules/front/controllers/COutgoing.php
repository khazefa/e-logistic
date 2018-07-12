<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : COutgoing (TicketsController)
 * COutgoing Class to control Tickets.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class COutgoing extends BaseController
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
            redirect('view-outgoing-trans');
        }elseif($this->isStaff()){
            
            $this->global['pageTitle'] = 'Outgoing Transaction - '.APP_NAME;
            $this->global['pageMenu'] = 'Outgoing Transaction';
            $this->global['contentHeader'] = 'Outgoing Transaction';
            $this->global['contentTitle'] = 'Outgoing Transaction';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            
            $cartid = $this->session->userdata ( 'cart_session' )."ot";
            $data['cartid'] = $cartid;
            
            $this->loadViews('front/outgoing-trans/index', $this->global, $data);
            
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function views()
    {
        $this->global['pageTitle'] = 'Outgoing Transaction - '.APP_NAME;
        $this->global['pageMenu'] = 'Outgoing Transaction';
        $this->global['contentHeader'] = 'Outgoing Transaction';
        $this->global['contentTitle'] = 'Outgoing Transaction';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $this->loadViews('front/outgoing-trans/lists', $this->global, NULL);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->outgoing_date, FILTER_SANITIZE_STRING);
            $transticket = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
            $engineer = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $purpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $user = filter_var($r->user_key, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
            
            $row['transnum'] = $transnum;
            $row['transdate'] = $transdate;
            $row['transticket'] = $transticket;
            $row['engineer'] = $engineer;
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
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->outgoing_date, FILTER_SANITIZE_STRING);
            $transticket = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
            $engineer = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $engineer_name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $purpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
            
            $row['transnum'] = $transnum;
            $row['transdate'] = tgl_indo($transdate);
            $row['transticket'] = $transticket;
            $row['engineer'] = $engineer_name;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user_fullname;
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
     * This function is used to load the add new form
     */
    public function add()
    {        
        if($this->isSpv()){
            redirect('view-outgoing-trans');
        }elseif($this->isStaff()){
            
            $this->global['pageTitle'] = 'New Outgoing Transaction - '.APP_NAME;
            $this->global['pageMenu'] = 'New Outgoing Transaction';
            $this->global['contentHeader'] = 'New Outgoing Transaction';
            $this->global['contentTitle'] = 'New Outgoing Transaction';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->loadViews('front/outgoing-trans/create', $this->global, NULL);
            
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function info_eg(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $fkey = $this->input->post('fkey', TRUE);
        $arrWhere = array('fkey'=>$fkey);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(empty($rs)){
            $error_response = array(
                'status' => 0,
                'message'=> 'Engineer not found'
            );
            $response = $error_response;
        }else{
            foreach ($rs as $r) {
                $partner_key = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
                $partner = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
                $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
                $name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
                $email = filter_var($r->engineer_email, FILTER_SANITIZE_EMAIL);
                $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
                
                $success_response = array(
                    'status' => 1,
                    'pr_name'=> $partner,
                    'eg_name'=> $name
                );
            }
            $response = $success_response;
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
    public function check_part(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if($rs){
            $stock = 0;
            foreach ($rs as $r) {
                $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                $laststock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
                
                if($initflag === "Y"){
                    $stock = $initstock;
                }else{
                    $stock = $laststock;
                }
                
                if($stock < 1){
                    $success_response = array(
                        'status' => 0,
                        'message'=> 'Out of stock, please choose part number subtitution!'
                    );
                    $response = $success_response;
                }else{
                    $success_response = array(
                        'status' => 1,
                        'stock'=> $stock,
                        'message'=> 'Stock available'
                    );
                    $response = $success_response;
                }
            }
        }else{
            $success_response = array(
                'status' => 2,
                'message'=> 'Stock not available'
            );
            $response = $success_response;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
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
     * This function is used to get lists for populate data
     */
    public function get_list_part_sub(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_partsub_part_sub'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : null;
        
        if($rs){
            $val_partsub = $rs;
            
            $exp_partsub = explode(";", $val_partsub);
            $arrData = array();
            foreach ($exp_partsub as $partnum){
                $row['partno'] = $partnum;
                
                $result = $this->get_info_part_stock($fcode, $partnum);

                $arrData[] = array('detail_data'=>$result);
            }
            $success_response = array(
                'status' => 1,
                'data'=> $arrData
            );
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 0,
                'message'=> 'Sparepart is out of stock and do not have subtitution'
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
    public function get_part_nearby(){
        $rs = array();
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $partnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if($rs){
            $wh_nearby = "";
            foreach ($rs as $r){
                $wh_nearby = $r->fsl_nearby;
            }
            $exp_wh_nearby = explode(";", $wh_nearby);
            $arrData = array();
            foreach ($exp_wh_nearby as $code){
                $result = $this->get_info_part_stock($code, $partnum);

                $arrData[] = array('detail_data'=>$result);
            }
            $success_response = array(
                'status' => 1,
                'data'=> $arrData
            );
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 0,
                'message'=> 'no nearby warehouse'
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
     * This function is used to add cart
     */
    public function add_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed add data to cart'
        );
        
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);  
        $cartid = $this->session->userdata ( 'cart_session' )."ot";
        $fqty = 1;

        $dataInfo = array('fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum, 'fcartid'=>$cartid, 'fqty'=>$fqty);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_outgoings_cart'), 'POST', FALSE);

        if($rs_data->status)
        {
            $response = $success_response;
        }
        else
        {
            $response = $error_response;
        }
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_cart_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partname = "";
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
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
            $cartid = filter_var($r->tmp_outgoing_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['serialno'] = $serialnum;
            $row['name'] = $partname;
            $row['stock'] = $partstock;
            $row['qty'] = $qty;
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get total cart
     */
    public function get_total_cart(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $cartid = $this->session->userdata ( 'cart_session' )."ot";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_total_outgoings_cart'), 'POST', FALSE);
//        $rs_data = send_curl($arrWhere, $this->config->item('api_total_outgoings_cart').'?funiqid='.$cartid, 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $total = 0;
            foreach ($rs as $r){
                $total = $r->total;
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_outgoings_cart'), 'POST', FALSE);

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
        $rs_data = send_curl($arrWhere, $this->config->item('api_delete_outgoings_cart'), 'POST', FALSE);

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
}