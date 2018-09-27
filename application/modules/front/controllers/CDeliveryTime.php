<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class CDeliveryTime extends BaseController{
 	
 	public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        
    }

    public function get_list_delivery_by(){
    	$rs = array();
        $arrWhere = array();

        $rs_data = send_curl($arrWhere, $this->config->item('api_list_delivery_by'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $code_delivery_by = filter_var($r->delivery_by, FILTER_SANITIZE_STRING);
            $row['user'] = $user;
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }

    public function get_list_delivery_service(){}

    public function get_eta_time(){}


}