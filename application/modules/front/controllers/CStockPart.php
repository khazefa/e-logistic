<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CStockPart (CStockPartController)
 * CStockPart Class to control Data Stock Parts.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CStockPart extends BaseController
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
        $this->global['pageTitle'] = 'List Stock Parts - '.APP_NAME;
        $this->global['pageMenu'] = 'List Stock Parts';
        $this->global['contentHeader'] = 'List Stock Parts';
        $this->global['contentTitle'] = 'List Stock Parts';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_data_wh'] = $this->get_list_data_wh();
            
        $this->loadViews('front/stock-part/index', $this->global, $data);
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function lists()
    {
        if($this->isSuperAdmin()){
            $this->global['pageTitle'] = 'Manage Stock Parts - '.APP_NAME;
            $this->global['pageMenu'] = 'Manage Stock Parts';
            $this->global['contentHeader'] = 'Manage Stock Parts';
            $this->global['contentTitle'] = 'Manage Stock Parts';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['list_data_wh'] = $this->get_list_data_wh();
            
            $this->loadViews('front/stock-part/lists', $this->global, $data);
        }else{
            redirect('data-spareparts-stock');
        }
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_view_datatable(){
        
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
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
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-spareparts-stock/").$key.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-spareparts-stock/").$key.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
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
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-spareparts-stock/").$key.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-spareparts-stock/").$key.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
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
     * This function is used to get lists for populate data
     */
    public function get_list_data_wh(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fname != "") $arrWhere['fname'] = $fname;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_nearby = array();
        $names = '';
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
            $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
            $row['spv'] = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
 
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
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
            $this->global['pageTitle'] = "Add New Stock Parts - ".APP_NAME;
            $this->global['pageMenu'] = 'Add New Stock Parts';
            $this->global['contentHeader'] = 'Add New Stock Parts';
            $this->global['contentTitle'] = 'Add New Stock Parts';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;
            
            $data['list_data_wh'] = $this->get_list_data_wh();

            $this->loadViews('front/stock-part/create', $this->global, $data);
        }else{
            redirect('data-spareparts-stock');
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
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_part_stock'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-spareparts-stock');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('add-spareparts-stock');
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
                redirect('manage-spareparts-stock');
            }

            $this->global['pageTitle'] = "Edit Data Stock Parts - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Stock Parts';
            $this->global['contentHeader'] = 'Edit Data Stock Parts';
            $this->global['contentTitle'] = 'Edit Data Stock Parts';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['records'] = $this->get_list_info($fkey);

            $this->loadViews('front/stock-part/edit', $this->global, $data);
        }else{
            redirect('data-spareparts-stock');
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
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_part_stock'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-spareparts-stock');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('edit-spareparts-stock/'.$fkey);
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

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_part_stock'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect('manage-spareparts-stock');
    }
}