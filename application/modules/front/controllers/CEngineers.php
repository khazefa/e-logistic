<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CEngineers (CEngineersController)
 * CEngineers Class to control Data Engineer.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CEngineers extends BaseController
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
        $this->global['pageTitle'] = 'List Engineers - '.APP_NAME;
        $this->global['pageMenu'] = 'List Engineers';
        $this->global['contentHeader'] = 'List Engineers';
        $this->global['contentTitle'] = 'List Engineers';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/engineers/index', $this->global, NULL);
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function lists()
    {
        if($this->isWebAdmin()){
            $this->global['pageTitle'] = 'Manage Engineers - '.APP_NAME;
            $this->global['pageMenu'] = 'Manage Engineers';
            $this->global['contentHeader'] = 'Manage Engineers';
            $this->global['contentTitle'] = 'Manage Engineers';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $this->loadViews('front/engineers/lists', $this->global, NULL);
        }else{
            redirect('data-engineers');
        }
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $row['feid'] = $key;
            $row['fullname'] = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $row['partner'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            if($code == "00"){
                $names = "WH";
            }else{
                $data_warehouse = $this->get_list_info_wh($code);
                foreach ($data_warehouse as $d){
                    $names = $d["name"];
                }
            }
            $row['warehouse'] = $names;
 
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
    public function get_m_list_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $row['feid'] = $key;
            $row['fullname'] = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $row['partner'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            if($code == "00"){
                $names = "WH";
            }else{
                $data_warehouse = $this->get_list_info_wh($code);
                foreach ($data_warehouse as $d){
                    $names = $d["name"];
                }
            }
            $row['warehouse'] = $names;
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-engineers/").$key.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-engineers/").$key.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
            $row['button'] .= '</div>';
            $row['button'] .= '</div>';
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get lists for json or populate data
     */
    public function get_list_json(){
        $rs = array();
        $arrWhere = array();
        
        $fkey = $this->input->post('fkey', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $femail = $this->input->post('femail', TRUE);

        if ($fkey != "") $arrWhere['fkey'] = $fkey;
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fpartner != "") $arrWhere['fpartner'] = $fpartner;
        if ($femail != "") $arrWhere['femail'] = $femail;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $row['feid'] = $key;
            $row['fullname'] = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $row['partner'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            if($code == "00"){
                $names = "WH";
            }else{
                $data_warehouse = $this->get_list_info_wh($code);
                foreach ($data_warehouse as $d){
                    $names = $d["name"];
                }
            }
            $row['warehouse'] = $names;
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($data)
        );
    }
    
    /**
     * This function is used to get lists for populate data
     */
    public function get_list_data(){
        $rs = array();
        $arrWhere = array();
        
        $fkey = $this->input->post('fkey', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $femail = $this->input->post('femail', TRUE);

        if ($fkey != "") $arrWhere['fkey'] = $fkey;
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fpartner != "") $arrWhere['fpartner'] = $fpartner;
        if ($femail != "") $arrWhere['femail'] = $femail;
//        if ($f_date != ""){
//            $arrWhere['submission_date_1'] = $f_date;
//            $arrWhere['submission_date_2'] = $f_date;
//        }

//        $arrWhere['is_deleted'] = 0;
//        array_push($arrWhere, $arrWhere['is_deleted']);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $row['feid'] = $key;
            $row['fullname'] = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $row['partner'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            if($code == "00"){
                $names = "WH";
            }else{
                $data_warehouse = $this->get_list_info_wh($code);
                foreach ($data_warehouse as $d){
                    $names = $d["name"];
                }
            }
            $row['warehouse'] = $names;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_partners(){
        $rs = array();
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_partners'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->partner_id, FILTER_SANITIZE_NUMBER_INT);
            $key = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
            $row['id'] = $id;
            $row['code'] = $key;
            $row['name'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->partner_location, FILTER_SANITIZE_STRING);
            $row['contact'] = filter_var($r->partner_contact, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_wh(){
        $rs = array();
        $arrWhere = array();
        
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
     * This function is used to get list information described by function name
     */
    public function get_list_info($fkey){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fkey'=>$fkey);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $fid = filter_var($r->engineer_id, FILTER_SANITIZE_STRING);
            $partner = filter_var($r->partner_id, FILTER_SANITIZE_STRING);
            $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $email = filter_var($r->engineer_email, FILTER_SANITIZE_EMAIL);
            $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
            
            $row['feid'] = $key;
            $row['partner'] = $partner;
            $row['name'] = $name;
            $row['email'] = $email;
            $row['code'] = $code;
            $row['deleted'] = $deleted;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if($this->isWebAdmin()){
            $this->global['pageTitle'] = "Add New Engineer - ".APP_NAME;
            $this->global['pageMenu'] = 'Add New Engineer';
            $this->global['contentHeader'] = 'Add New Engineer';
            $this->global['contentTitle'] = 'Add New Engineer';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['list_partner'] = $this->get_list_partners();
            $data['list_wr'] = $this->get_list_wh();
            $data['default_pass'] = strtoupper(generateRandomString());

            $this->loadViews('front/engineers/create', $this->global, $data);
        }else{
            redirect('data-engineers');
        }
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {        
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $fcode = $this->input->post('fcode', TRUE);

        $dataInfo = array('fpartner'=>$fpartner, 'fkey'=>$fkey, 'fpass'=>$fpass, 
            'fname'=>$fname, 'femail'=>$femail, 'fcode'=>$fcode);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_engineers'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-engineers');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('add-engineers');
        }
    }
    
    /**
     * This function is used load edit information
     * @param $fkey : Optional : This is data unique key
     */
    function edit($fkey = NULL)
    {
        if($this->isWebAdmin()){
            if($fkey == NULL)
            {
                redirect('manage-engineers');
            }

            $this->global['pageTitle'] = "Edit Data Engineer - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Engineer';
            $this->global['contentHeader'] = 'Edit Data Engineer';
            $this->global['contentTitle'] = 'Edit Data Engineer';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['records'] = $this->get_list_info($fkey);
            $data['list_partner'] = $this->get_list_partners();
            $data['list_wr'] = $this->get_list_wh();

            $this->loadViews('front/engineers/edit', $this->global, $data);
        }else{
            redirect('data-engineers');
        }
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $fcode = $this->input->post('fcode', TRUE);

        $dataInfo = array('fpartner'=>$fpartner, 'fkey'=>$fkey, 'fpass'=>$fpass, 
            'fname'=>$fname, 'femail'=>$femail, 'fcode'=>$fcode);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_engineers'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-engineers');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('edit-engineers/'.$fkey);
        }
    }
    
    /**
     * This function is used to delete the data
     * @return boolean $result : TRUE / FALSE
     */
    function delete($fkey = NULL)
    {
        $arrWhere = array();
        $arrWhere = array('fkey'=>$fkey);

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_engineers'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect('manage-engineers');
    }
}