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
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_incomings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->incoming_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->incoming_date, FILTER_SANITIZE_STRING);
            $transticket = filter_var($r->incoming_ticket, FILTER_SANITIZE_STRING);
            $engineer = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $purpose = filter_var($r->incoming_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->incoming_qty, FILTER_SANITIZE_NUMBER_INT);
            $user = filter_var($r->user_key, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->incoming_notes, FILTER_SANITIZE_STRING);
            
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_incomings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->incoming_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->incoming_date, FILTER_SANITIZE_STRING);
            $transticket = filter_var($r->incoming_ticket, FILTER_SANITIZE_STRING);
            $engineer = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $engineer_name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $purpose = filter_var($r->incoming_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->incoming_qty, FILTER_SANITIZE_NUMBER_INT);
            $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->incoming_notes, FILTER_SANITIZE_STRING);
            
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
                'message'=> 'Data cannot be found'
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
        
        // filters that will be loaded in the multiselect dropdown
        
        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(empty($rs)){
            $error_response = array(
                'status' => 0,
                'message'=> 'Data cannot be found'
            );
            $response = $error_response;
        }else{
            
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
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $cartid = $this->session->userdata ( 'cart_session' ).md5('Incoming');
        
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        
    }
}