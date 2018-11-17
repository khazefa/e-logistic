<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Cart (CartController)
 * Cart Class to control Data Carts.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class Cart extends BaseController
{
    private $cname = 'cart';
    private $readonly = TRUE;
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isWebAdmin() || $this->isSpv() || $this->isStaff()){

        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function outgoing($postfix){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' ).$postfix;
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $stock = filter_var($r->tmp_stock, FILTER_SANITIZE_NUMBER_INT);
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_outgoing_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
            $row['stock'] = $stock;
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
     * This function is used to get list stucked cart
     */
    public function stucked_outgoing(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_stucked_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_outgoing_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $cart_date = date("d/m/Y H:i:s", strtotime(filter_var($r->createdAt, FILTER_SANITIZE_STRING)));
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
            $row['qty'] = $qty;
            $row['cart_date'] = $cart_date;
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }

    public function get_total_stucked_cart(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_stucked_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('total'=>count($rs)))
        );
        // return count($rs);
    }
    
    /**
     * This function is used to add cart
     */
    public function create_incoming($postfix){
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
        $fpartname = $this->get_part_name($fpartnum);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' ).$postfix.$ftransout;
        
        $dataInfo = array('fpartnum'=>$fpartnum, 'fpartname'=>$fpartname, 'fserialnum'=>$fserialnum, 'fcartid'=>$cartid, 
            'fqty'=>$fqty, 'fstatus'=>$fstatus, 'fnotes'=>$fnotes, 'fuser'=>$fuser, 'fname'=>$fname);
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
     * This function is used to delete cart
     */
    public function delete_incoming(){
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
     * This function is used to delete all cart
     */
    public function delete_all_incoming($postfix){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to delete all cart'
        );
        
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' ).$postfix.$ftrans_out;
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
    public function update_incoming($postfix){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to update cart'
        );
        
        $ftransout = $this->input->post('ftransout', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' ).$postfix.$ftransout;
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
     * This function is used to get list for datatables
     */
    public function incoming($postfix){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $ftrans_out = $this->input->get('ftrans_out', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' ).$postfix.$ftrans_out;
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
    private function get_info_part($fpartnum){
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
    
    /**
     * This function is used to get cart info where part stock is already low
     */
    private function get_info_cart($partnum, $fcode){
        
        $fcode = $this->repo;
        $arrWhere = array('fpartnum'=>$partnum, 'fcode'=>$fcode);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partname = "";
        $partstock = "";
        
        if(!empty($rs)){
            foreach ($rs as $r) {
                $id = filter_var($r->tmp_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
                $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                $rs_part = $this->get_info_part($partnum);
                foreach ($rs_part as $p){
                    $partname = $p["name"];
                }
                $partstock = $this->get_stock($fcode, $partnum);
                $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
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
     * This function is used to get cart stock
     */
    private function get_cart_qty($partnum, $serialnum, $fcode){
        
        $fcode = $this->repo;
        $arrWhere = array('fpartnum'=>$partnum, 'fserialnum'=>$serialnum, 'fcode'=>$fcode);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $tmp_qty = "";
        
        if(!empty($rs)){
            foreach ($rs as $r) {
                $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $tmp_qty = $qty;
            }

        }
        return $tmp_qty;
    }
    
    /**
     * This function is used to add cart
     */
    public function create_outgoing_old($postfix){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed add data to cart'
        );
        
        $fcode = $this->repo;
        $fuser = $this->vendorUR;
        $fname = $this->name;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' ).$postfix;
        $fqty = 1;
        
        $partname = "";
        $partstock = 0;
        $partstock = $this->get_stock($fcode, $fpartnum);
        $partname = $this->get_part_name($fpartnum);
        if($partstock === $fqty){
            $cstock = 0;
            $cqty = 0;
            $cuser = "";
            $cname = "";
            
            $rs_cart_info = $this->get_info_cart($fpartnum, $fcode);
            
            if(!empty($rs_cart_info)){
                foreach($rs_cart_info as $c){
                    $cstock = (int)$c['stock'];
                    $cqty = (int)$c['qty'];
                    $cuser = $c['user'];
                    $cname = $c['fullname'];
                }
                if($cuser === $fuser){
                    if($cstock < $fqty){
                        $error_response = array(
                            'status' => 2,
                            'message'=> 'Part stock has run out!'
                        );
                    }else{
                        $dataInfo = array('fpartnum'=>$fpartnum, 'fpartname'=>$partname, 'fserialnum'=>$fserialnum, 
                            'fcartid'=>$cartid, 'fqty'=>$fqty, 'fuser'=>$fuser, 'fname'=>$fname, 'fcode'=>$fcode);
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
                }else{
                    $error_response = array(
                        'status' => 2,
                        'message'=> 'Stock is limited and part is already assigned to '.$cname
                    );
                }
                $response = $error_response;
            }else{
                $dataInfo = array('fpartnum'=>$fpartnum, 'fpartname'=>$partname, 'fserialnum'=>$fserialnum, 
                    'fcartid'=>$cartid, 'fqty'=>$fqty, 'fuser'=>$fuser, 'fname'=>$fname, 'fcode'=>$fcode);
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
        }else{
            $dataInfo = array('fpartnum'=>$fpartnum, 'fpartname'=>$partname, 'fserialnum'=>$fserialnum, 
                'fcartid'=>$cartid, 'fqty'=>$fqty, 'fuser'=>$fuser, 'fname'=>$fname, 'fcode'=>$fcode);
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
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }

    /**
     * This function is used to add cart
     */
    public function create_outgoing($postfix){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed add data to cart'
        );
        
        $fcode = $this->repo;
        $fuser = $this->vendorUR;
        $fname = $this->name;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' ).$postfix;
        $fqty = $this->input->post('fqty', TRUE);
        
        $partname = "";
        $partstock = 0;
        $curstock = 0;
        $partstock = (int)$this->get_stock($fcode, $fpartnum);
        $partname = $this->get_part_name($fpartnum);
        if($partstock >= $fqty){
            $curstock = $partstock - $fqty;
            $dataInfo = array('fpartnum'=>$fpartnum, 'fpartname'=>$partname, 'fserialnum'=>$fserialnum, 
                'fcartid'=>$cartid, 'fqty'=>$fqty, 'fstock'=>$curstock, 'fuser'=>$fuser, 'fname'=>$fname, 'fcode'=>$fcode);
            $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_outgoings_cart'), 'POST', FALSE);
            if($rs_data->status)
            {
                $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum, 'fqty'=>$curstock, 'fflag'=>'N');
                //update stock by fsl code and part number
                $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                        'POST', FALSE);
                $response = $success_response;
            }
            else
            {
                $response = $error_response;
            }
        }else{
            $error_response = array(
                'status' => 0,
                'message'=> 'Stock of spare parts has run out'
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
     * This function is used to update cart
     */
    public function update_outgoing(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to update cart'
        );
        
        $fcode = $this->repo;
        $fid = $this->input->post('fid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $partstock = 0;
        $curstock = 0;
        $curcart_qty = 0;
        $partstock = (int)$this->get_stock($fcode, $fpartnum);
        $curcart_qty = (int)$this->get_cart_qty($fpartnum, $fserialnum, $fcode);
        $curstock = ($partstock + $curcart_qty) - $fqty;

        $arrWhere = array('fid'=>$fid, 'fserialnum'=>$fserialnum, 'fqty'=>$fqty, 'fstock'=>$curstock);
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_outgoings_cart'), 'POST', FALSE);

        if($rs_data->status)
        {
            $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum, 'fqty'=>$curstock, 'fflag'=>'N');
            //update stock by fsl code and part number
            $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                    'POST', FALSE);
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
    public function delete_outgoing(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to delete cart'
        );
        
        $fcode = $this->repo;
        $fid = $this->input->post('fid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $partstock = 0;
        $curstock = 0;
        $partstock = (int)$this->get_stock($fcode, $fpartnum);
        $curstock = $partstock + $fqty;

        $arrWhere = array('fid'=>$fid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_delete_outgoings_cart'), 'POST', FALSE);

        if($rs_data->status)
        {
            $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum, 'fqty'=>$curstock, 'fflag'=>'N');
            //update stock by fsl code and part number
            $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                    'POST', FALSE);
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