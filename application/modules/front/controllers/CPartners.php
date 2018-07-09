<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CPartners (CPartnersController)
 * CPartners Class to control Data Partners.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CPartners extends BaseController
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
        $this->global['pageTitle'] = 'List Partners - '.APP_NAME;
        $this->global['pageMenu'] = 'List Partners';
        $this->global['contentHeader'] = 'List Partners';
        $this->global['contentTitle'] = 'List Partners';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/partners/index', $this->global, NULL);
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function lists()
    {
        if($this->isSuperAdmin()){
            $this->global['pageTitle'] = 'Manage Partners - '.APP_NAME;
            $this->global['pageMenu'] = 'Manage Partners';
            $this->global['contentHeader'] = 'Manage Partners';
            $this->global['contentTitle'] = 'Manage Partners';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $this->loadViews('front/partners/lists', $this->global, NULL);
        }else{
            redirect('data-partners');
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_partners'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->partner_id, FILTER_SANITIZE_NUMBER_INT);
            $key = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
            $row['code'] = $key;
            $row['name'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->partner_location, FILTER_SANITIZE_STRING);
            $row['contact'] = filter_var($r->partner_contact, FILTER_SANITIZE_STRING);
            
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-partners/").$key.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-partners/").$key.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
     * This function is used to get list for datatables
     */
    public function get_m_list_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_partners'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->partner_id, FILTER_SANITIZE_NUMBER_INT);
            $key = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
            $row['code'] = $key;
            $row['name'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->partner_location, FILTER_SANITIZE_STRING);
            $row['contact'] = filter_var($r->partner_contact, FILTER_SANITIZE_STRING);
            
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-partners/").$key.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-partners/").$key.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
        
        $fid = $this->input->post('fid', TRUE);
        $fkey = $this->input->post('fkey', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fid != "") $arrWhere['fid'] = $fid;
        if ($fkey != "") $arrWhere['fkey'] = $fkey;
        if ($fname != "") $arrWhere['fname'] = $fname;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_partners'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $id = filter_var($r->partner_id, FILTER_SANITIZE_NUMBER_INT);
            $key = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
            $row['code'] = $key;
            $row['name'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->partner_location, FILTER_SANITIZE_STRING);
            $row['contact'] = filter_var($r->partner_contact, FILTER_SANITIZE_STRING);
 
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
        
        $fid = $this->input->post('fid', TRUE);
        $fkey = $this->input->post('fkey', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fid != "") $arrWhere['fid'] = $fid;
        if ($fkey != "") $arrWhere['fkey'] = $fkey;
        if ($fname != "") $arrWhere['fname'] = $fname;
//        if ($f_date != ""){
//            $arrWhere['submission_date_1'] = $f_date;
//            $arrWhere['submission_date_2'] = $f_date;
//        }

//        $arrWhere['is_deleted'] = 0;
//        array_push($arrWhere, $arrWhere['is_deleted']);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_partners'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $id = filter_var($r->partner_id, FILTER_SANITIZE_NUMBER_INT);
            $key = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
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
    public function get_list_info($fkey){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fkey'=>$fkey);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_partners'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->partner_id, FILTER_SANITIZE_NUMBER_INT);
            $key = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
            $row['code'] = $key;
            $row['name'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->partner_location, FILTER_SANITIZE_STRING);
            $row['contact'] = filter_var($r->partner_contact, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if($this->isSuperAdmin()){
            $this->global['pageTitle'] = "Add New Partner - ".APP_NAME;
            $this->global['pageMenu'] = 'Add New Partner';
            $this->global['contentHeader'] = 'Add New Partner';
            $this->global['contentTitle'] = 'Add New Partner';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $this->loadViews('front/partners/create', $this->global, NULL);
        }else{
            redirect('data-partners');
        }
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {
        $fkey = $this->input->post('fkey', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fcontact = $this->input->post('fcontact', TRUE);

        $dataInfo = array('fkey'=>$fkey, 'fname'=>$fname, 'flocation'=>$flocation, 'fcontact'=>$fcontact);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_partners'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-partners');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('add-partners');
        }
    }
    
    /**
     * This function is used load edit information
     * @param $fkey : Optional : This is data unique key
     */
    function edit($fkey = NULL)
    {
        if($this->isSuperAdmin()){
            if($fkey == NULL)
            {
                redirect('manage-partners');
            }

            $this->global['pageTitle'] = "Edit Data Partner - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Partner';
            $this->global['contentHeader'] = 'Edit Data Partner';
            $this->global['contentTitle'] = 'Edit Data Partner';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['records'] = $this->get_list_info($fkey);

            $this->loadViews('front/partners/edit', $this->global, $data);
        }else{
            redirect('data-partners');
        }
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fkey = $this->input->post('fkey', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fcontact = $this->input->post('fcontact', TRUE);

        $dataInfo = array('fkey'=>$fkey, 'fname'=>$fname, 'flocation'=>$flocation, 'fcontact'=>$fcontact);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_partners'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-partners');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('edit-partners/'.$fkey);
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

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_partners'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect('manage-partners');
    }
}