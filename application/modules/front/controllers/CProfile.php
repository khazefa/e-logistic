<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CProfile (CProfileController)
 * CProfile Class to control Reports.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CProfile extends BaseController
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
        $this->global['pageTitle'] = 'My Account - '.APP_NAME;
        $this->global['pageMenu'] = 'My Account';
        $this->global['contentHeader'] = 'My Account';
        $this->global['contentTitle'] = 'My Account';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['records'] = $this->get_data_info();
        $this->loadViews('front/profiles/edit', $this->global, $data);
    }
    
    private function get_data_info(){
        $rs = array();
        $arrWhere = array();
        
        $fkey = $this->vendorUR;
        $arrWhere = array('fkey'=>$fkey);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_users'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $uid = filter_var($r->user_id, FILTER_SANITIZE_NUMBER_INT);
            $group_id = filter_var($r->group_id, FILTER_SANITIZE_NUMBER_INT);
            $ukey = filter_var($r->user_key, FILTER_SANITIZE_STRING);
            $fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $email = filter_var($r->user_email, FILTER_SANITIZE_STRING);
            $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $isdeleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
            
            if($code == "00"){
                $names = "WH";
            }else{
                $data_warehouse = $this->get_list_info_wh($code);
                foreach ($data_warehouse as $d){
                    $names = $d["name"];
                }
            }
            
            $row['group'] = $group_id;
            $row['ukey'] = $ukey;
            $row['fullname'] = $fullname;
            $row['email'] = $email;
            $row['warehouse'] = $names;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_info_wh($fcode){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
            $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $femail = $this->input->post('femail', TRUE);

        $dataInfo = array('fkey'=>$fkey, 'fpass'=>$fpass, 'fname'=>$fname, 'femail'=>$femail);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_account'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('my-account');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('my-account');
        }
    }
}