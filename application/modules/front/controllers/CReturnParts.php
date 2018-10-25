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
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect('cl');
    }
    
    /**
     * This function is used to load the add new form
     */
    public function add()
    {
        $this->global['pageTitle'] = 'Return Parts - '.APP_NAME;
        $this->global['pageMenu'] = 'Return Parts';
        $this->global['contentHeader'] = 'Input Transaction Number';
        $this->global['contentTitle'] = 'Return Parts';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->loadViews('front/incoming-trans/return_parts', $this->global, NULL);
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

        $arrWhere = array('ftrans_out'=>$ftrans_out, 'fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum, 'fstatus'=>$fstatus);
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

        $arrWhere = array('ftrans_out'=>$ftrans_out, 'fstatus'=>'');
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
    
    /**
     * This function is used to delete cart
     */
    public function delete_all_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to delete all cart'
        );
        
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' )."inr".$ftrans_out;
        $arrWhere = array('fcartid'=>$cartid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_clear_incomings_cart'), 'POST', FALSE);

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
     * This function is used to update cart
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
        $fpurpose = "R";
        $fnotes = $this->input->post('fnotes', TRUE);
        $createdby = $this->session->userdata ( 'vendorUR' );
        
        $cartid = $this->session->userdata ( 'cart_session' )."inr".$ftrans_out;
//        $arrParam = array('fparam'=>'IN', 'fcode'=>$fcode, 'fdigits'=>5);
//        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_incoming_num_ext'), 'POST', FALSE);
        $arrParam = array('fparam'=>'IC');
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_incoming_num'), 'POST', FALSE);
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        if($transnum === ""){
            $response = $error_response;
        }else{
            //get cart list by retnum
            $data_tmp = $this->get_list_cart_return($cartid);

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
                        $rs_stock = $this->get_info_part_stock($fcode, $d['partno']);
                        foreach ($rs_stock as $s){
                            $partstock = (int)$s["stock"];
                        }

                        $dataDetail = array('ftransno'=>$transnum, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno'], 
                            'fqty'=>$d['qty']);
                        $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_incomings_trans_detail'), 
                                'POST', FALSE);

                        if($d['status'] === 'RG'){
                            $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$d['partno'], 'fqty'=>(int)$partstock+(int)$d['qty'], 
                                'fflag'=>'N');
                            //update stock by fsl code and part number
                            $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                                    'POST', FALSE);
                        }

//                        $updateDetailOutgoing = array('ftrans_out'=>$ftrans_out, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno'], 'fstatus'=>'RG');
//                        //update detail outgoing status by outgoing number, part number and serial number
//                        $update_status_detail_outgoing = send_curl($this->security->xss_clean($updateDetailOutgoing), $this->config->item('api_update_outgoings_trans_detail'), 
//                                'POST', FALSE);
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
        $ftransout = $this->input->post('ftransout', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartname = $this->get_info_part_name($fpartnum);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fserialnum_old = $this->input->post('fserialnum_old', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' )."inr".$ftransout;
        
        $dataInfo = array('fpartnum'=>$fpartnum, 'fpartname'=>$fpartname, 'fserialnum'=>$fserialnum, 'fcartid'=>$cartid, 
            'fqty'=>$fqty, 'fstatus'=>$fstatus, 'fuser'=>$fuser, 'fname'=>$fname);
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
    public function get_list_cart_return_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $ftrans_out = $this->input->get('ftrans_out', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' )."inr".$ftrans_out;
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
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
            $row['qty'] = (int)$qty;
            $row['status'] = $status;
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get list cart
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
}