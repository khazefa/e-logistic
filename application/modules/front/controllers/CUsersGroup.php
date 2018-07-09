<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CUsersGroup (CUsersGroupController)
 * CUsersGroup Class to control Data User Group.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CUsersGroup extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSuperAdmin()){
            //load page
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Manage User Group - '.APP_NAME;
        $this->global['pageMenu'] = 'Manage User Group';
        $this->global['contentHeader'] = 'Manage User Group';
        $this->global['contentTitle'] = 'Manage User Group';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/accounts-group/index', $this->global, NULL);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_user_group'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $id = filter_var($r->group_id, FILTER_SANITIZE_NUMBER_INT);
            $row['name'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
            
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-groups/").$id.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-groups/").$id.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
        $fname = $this->input->post('fname', TRUE);
        $fenc = $this->input->post('fenc', TRUE);

        if ($fid != "") $arrWhere['fid'] = $fid;
        if ($fname != "") $arrWhere['fname'] = $fname;
        if ($fenc != "") $arrWhere['fenc'] = $fenc;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_user_group'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $row['id'] = filter_var($r->group_id, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->group_name, FILTER_SANITIZE_STRING);
            $row['display'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
            $row['enc'] = filter_var($r->group_enc, FILTER_SANITIZE_STRING);
 
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
        $fname = $this->input->post('fname', TRUE);
        $fenc = $this->input->post('fenc', TRUE);

        if ($fid != "") $arrWhere['fid'] = $fid;
        if ($fname != "") $arrWhere['fname'] = $fname;
        if ($fenc != "") $arrWhere['fenc'] = $fenc;
//        if ($f_date != ""){
//            $arrWhere['submission_date_1'] = $f_date;
//            $arrWhere['submission_date_2'] = $f_date;
//        }

//        $arrWhere['is_deleted'] = 0;
//        array_push($arrWhere, $arrWhere['is_deleted']);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_user_group'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
        foreach ($rs as $r) {
            $row['id'] = filter_var($r->group_id, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->group_name, FILTER_SANITIZE_STRING);
            $row['display'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
            $row['enc'] = filter_var($r->group_enc, FILTER_SANITIZE_STRING);
 
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
        
        $arrWhere = array('fid'=>$fkey);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_user_group'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['id'] = filter_var($r->group_id, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->group_name, FILTER_SANITIZE_STRING);
            $row['display'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
            $row['enc'] = filter_var($r->group_enc, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        $this->global['pageTitle'] = "Add New Group - ".APP_NAME;
        $this->global['pageMenu'] = 'Add New Group';
        $this->global['contentHeader'] = 'Add New Group';
        $this->global['contentTitle'] = 'Add New Group';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/accounts-group/create', $this->global, NULL);
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {
        $fname = $this->input->post('fname', TRUE);
        $fdisplay = $this->input->post('fdisplay', TRUE);

        $dataInfo = array('fname'=>$fname, 'fdisplay'=>$fdisplay);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_user_group'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-groups');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('add-groups');
        }
    }
    
    /**
     * This function is used load edit information
     * @param $fkey : Optional : This is data unique key
     */
    function edit($fkey = NULL)
    {
        if($fkey == NULL)
        {
            redirect('manage-groups');
        }
        
        $this->global['pageTitle'] = "Edit Data Group - ".APP_NAME;
        $this->global['pageMenu'] = 'Edit Data Group';
        $this->global['contentHeader'] = 'Edit Data Group';
        $this->global['contentTitle'] = 'Edit Data Group';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['records'] = $this->get_list_info($fkey);
        
        $this->loadViews('front/accounts-group/edit', $this->global, $data);
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fid = $this->input->post('fid', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fdisplay = $this->input->post('fdisplay', TRUE);

        $dataInfo = array('fid'=>$fid, 'fname'=>$fname, 'fdisplay'=>$fdisplay);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_user_group'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-groups');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('edit-groups/'.$fkey);
        }
    }
    
    /**
     * This function is used to delete the data
     * @return boolean $result : TRUE / FALSE
     */
    function delete($fid = NULL)
    {
        $arrWhere = array();
        $arrWhere = array('fid'=>$fid);

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_user_group'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect('manage-groups');
    }
}