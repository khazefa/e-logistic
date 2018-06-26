<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Tickets (TicketsController)
 * Tickets Class to control Tickets.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class Tickets extends BaseController
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
        $this->global['pageTitle'] = 'Tickets - '.APP_NAME;
        $this->global['pageMenu'] = 'Tickets';
        $this->global['contentHeader'] = 'Tickets';
        $this->global['contentTitle'] = 'Input Used Spareparts';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;

        $rsparts = array();
        $rsfsl = array();
        $rsparts = $this->converter->objectToArray($this->get_parts());
        $rsfsl = $this->converter->objectToArray($this->get_warehouses());
        
        $data["ticket_num"] = $this->get_ticket_num();
        $data["list_parts"] = $rsparts;
        $data["list_warehouses"] = $rsfsl;
        
        $this->loadViews('front/tickets/index', $this->global, $data);
    }
    
    private function get_ticket_num(){
        $rs = array();
        
        //Parameter Data for cURL
        $arrWhere = "fparam=R&ffsl_code=".$this->repo;
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_ticket_num')."?".$arrWhere, 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";
        
        return $rs;
    }
    
    private function get_warehouses(){
        $rs = array();
        $arrWhere = array();
        $ffsl_code = $this->input->post('ffsl', TRUE);
        
        //Parameter Data for cURL
        $arrWhere = array('ffsl_code'=>$ffsl_code);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";
        
        return $rs;
    }
    
    public function get_list_warehouse(){
        $rs = array();
        $arrWhere = array();
        $ffsl_code = $this->input->get('ffsl', TRUE);
        
        //Parse Data for cURL
        $arrWhere = array('ffsl_code'=>$ffsl_code);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($data)
        );
    }
    
    private function get_parts(){
        $rs = array();
        $arrWhere = array();
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        //Parse Data for cURL
        $arrWhere = array('fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";
        
        return $rs;
    }
    
    public function get_part_name($fpartnum){
        $rs = array();
        $arrWhere = array();
//        $fpartnum = $this->input->get('fpartnum', TRUE);
        
        //Parse Data for cURL
        $arrWhere = array('fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";
        $partname = "";
        
        foreach ($rs AS $r){
            $partname = $r->part_name;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($partname)
        );
    }
    
    public function get_carts(){
        $rs = array();
        $arrWhere = array();

        $fticket = $this->input->post('fticket', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);

        //Condition
//        if ($fticket != "") $arrWhere['fticket'] = $fticket;
        $arrWhere = array('fticket'=>$fticket, 'fpartnum'=>$fpartnum, 'fqty'=>$fqty);

        $rs_data = send_curl($arrWhere, $this->config->item('api_list_cart'), 'POST', FALSE);
//        var_dump($rs_data);exit();
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
//            $row['button'] = filter_var($r->ptt_id, FILTER_SANITIZE_NUMBER_INT);
            $row['ticketnum'] = filter_var($r->pticket_number, FILTER_SANITIZE_STRING);
            $row['partnum'] = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['qty'] = filter_var($r->ptt_qty, FILTER_SANITIZE_NUMBER_INT);
 
            $data[] = $row;
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    public function get_list_carts($fticket){
        $rs = array();
        $arrWhere = array();

        //Condition
        $arrWhere = array('fticket'=>$fticket);

        $rs_data = send_curl($arrWhere, $this->config->item('api_list_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        return $rs;
    }
    
    /**
     * This function is used to add detail data to the system
     */
     function add_cart()
     {
        $rs = array();
        $arrWhere = array();
        $arrValue = array();
        
        $fticket = $this->input->post('fticket', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        
        $arrWhere = array('fticket'=>$fticket, 'fpartnum'=>$fpartnum);

        $success_response = array(
            'status' => 1,
            'message'=> 'Successfully insert data'
        );
        $update_response = array(
            'status' => 1,
            'message'=> 'Successfully update data'
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed execute data'
        );
        
        $rs_data = send_curl($arrWhere, $this->config->item('api_check_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? TRUE : FALSE;

        if (!$rs)
        {
            $arrValue = array(
                'fticket' => $fticket,
                'fpartnum' => $fpartnum,
                'fqty' => $fqty
            );
            $insert_cart = send_curl($arrValue, $this->config->item('api_add_cart'), 'POST', FALSE);
            $result = $insert_cart->status ? TRUE : FALSE;
            if($result)
            {
                $response = $success_response;
            }
            else
            {
                $this->session->set_flashdata('error', 'Something wrong when inserting data');
                $response = $error_response;
            }
        }
        else
        {
            $arrValue = array(
                'fticket' => $fticket,
                'fpartnum' => $fpartnum,
                'fqty' => $fqty
            );
            $update_cart = send_curl($arrValue, $this->config->item('api_update_cart'), 'POST', FALSE);
            $result = $update_cart->status ? TRUE : FALSE;
            if($result)
            {
                $response = $update_response;
            }
            else
            {
                $this->session->set_flashdata('error', 'Something wrong when inserting data');
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
     * This function is used to delete the data using id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_cart()
    {
        $fid = $this->input->post('fid');

        $success_response = array(
            'status' => 1,
            'message'=> 'Successfully delete data'
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed delete data'
        );

        $arrWhere = array('fid'=>$fid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_delete_cart'), 'POST', FALSE);
        $result = $rs_data->status ? TRUE : FALSE;

        if($result)
        {
            $response = $success_response;
        }
        else
        {
            $this->session->set_flashdata('error', 'Something wrong when deleting data');
            $response = $error_response;
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to save ticket data to the system
     */
     function submit_ticket()
     {
        $rs = array();
        $arrWhere = array();
        $arrValue = array();
        
        $success_response = array(
            'status' => 1,
            'message'=> 'Successfully insert data',
            'redirect' => site_url('front/tickets/')
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed insert data'
        );
        
        $fticket = $this->input->post('fticket', TRUE);
        $fdate = date('Y-m-d');
        $ffsl = $this->input->post('ffsl', TRUE);
        $user = $this->vendorUR;
        
        //get cart list
        $data_tmp = $this->get_list_carts($fticket);
        $cart_total = count($data_tmp);
        $total_qty = 0;
        if ($cart_total > 0){
            // save detail data  
            for ($i = 0; $i < $cart_total; $i++){
                $arrValue = $this->converter->objectToArray($data_tmp[$i]);
                
                $sec_data = array(
                    'fticket'=>$fticket, 'fpartnum'=>$arrValue['part_number'], 'fqty'=>$arrValue['ptt_qty']
                );

                $total_qty = $total_qty + (int)$arrValue['ptt_qty'];
                $insert_detail = send_curl($sec_data, $this->config->item('api_add_ticket_detail'), 'POST', FALSE);
                $resultd = $insert_detail->status ? TRUE : FALSE;
            }
            
            $dataInfo = array('fticket'=>$fticket, 'fdate'=>$fdate, 'fqty'=>$total_qty, 
            'fuser'=> $user,'ffsl'=> $ffsl);
            //insert into trans data
            $insert_trans = send_curl($dataInfo, $this->config->item('api_add_ticket_trans'), 'POST', FALSE);
            $result = $insert_trans->status ? TRUE : FALSE;
            if($result)
            {
                //clear cart list data
                $clear_carts = send_curl($sec_data, $this->config->item('api_clear_cart'), 'POST', FALSE);
                $resultc = $clear_carts->status ? TRUE : FALSE;
                if($resultc){
                    $response = $success_response;
                }else{
                    $response = $error_response;
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'Failed to insert data');
                $response = $error_response;
            }
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
     }
    
    public function get_doctype(){
        $rs = array();
        $arrWhere = array();

        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_get_doctype'), 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";

        return $rs;
    }
}