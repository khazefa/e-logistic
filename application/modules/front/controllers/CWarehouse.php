<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CWarehouse (CWarehouseController)
 * CWarehouse Class to control Data Warehouse.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CWarehouse extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSuperUser()){
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
        $this->global['pageTitle'] = 'List Warehouse - '.APP_NAME;
        $this->global['pageMenu'] = 'List Warehouse';
        $this->global['contentHeader'] = 'List Warehouse';
        $this->global['contentTitle'] = 'List Warehouse';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/warehouse/index', $this->global, NULL);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_nearby = array();
        $names = '';
        $data_spv = array();
        $spvs = '';
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
            $nearby = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            if(!empty($nearby)){
                $names = '<ul class="list-unstyled">';
                $e_nearby = explode(';', $nearby);
                $data_nearby = array();
                foreach ($e_nearby as $n){
                    array_push($data_nearby, $this->get_list_info($n));
                }
                
                foreach ($data_nearby as $datas){
                    foreach($datas as $d){
//                        $names .= '<li style="display:inline; padding-left:5px;">'.$d["name"].'</li>';
                        $names .= '<li>'.$d["name"].'</li>';
                    }
                }
                $names .= '</ul>';
            }else{
                $names = '-';
            }
            $row['nearby'] = $names;
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
            $listspv = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
            if(!empty($listspv)){
                $spvs = '<ul class="list-unstyled">';
                $e_spv = explode(';', $listspv);
                $data_spv = array();
                foreach ($e_spv as $s){
                    array_push($data_spv, $this->get_list_users($s));
                }
                
                foreach ($data_spv as $datasp){
                    foreach($datasp as $dp){
//                        $names .= '<li style="display:inline; padding-left:5px;">'.$dp["fullname"].'</li>';
                        $spvs .= '<li>'.$dp["fullname"].'</li>';
                    }
                }
                $spvs .= '</ul>';
            }else{
                $spvs = '-';
            }
            $row['spv'] = $spvs;
            
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-warehouses/").$row['code'].'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-warehouses/").$row['code'].'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
            $nearby = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            if(!empty($nearby)){
                $names = '<ul class="list-unstyled">';
                $e_nearby = explode(';', $nearby);
                foreach ($e_nearby as $n){
                    array_push($data_nearby, $this->get_list_info($n));
                }
                
                foreach ($data_nearby as $datas){
                    foreach($datas as $d){
//                        $names .= '<li style="display:inline; padding-left:5px;">'.$d["name"].'</li>';
                        $names .= '<li>'.$d["name"].'</li>';
                    }
                }
                $names .= '</ul>';
            }else{
                $names = '-';
            }
            $row['nearby'] = $names;
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
            $row['spv'] = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
 
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
        
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fname != "") $arrWhere['fname'] = $fname;
//        if ($f_date != ""){
//            $arrWhere['submission_date_1'] = $f_date;
//            $arrWhere['submission_date_2'] = $f_date;
//        }

//        $arrWhere['is_deleted'] = 0;
//        array_push($arrWhere, $arrWhere['is_deleted']);
        
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
     * This function is used to get detail information
     */
    public function get_list_info($fcode){
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
            $row['spv'] = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_users($fkey){
        $rs = array();
        $arrWhere = array();
        
        $fgroup = "spv";
        $arrWhere = array('fgroup'=>$fgroup);

        $arrWhere['fkey'] = $fkey;
        
        if ($fkey != ""){
            $arrWhere = array('fgroup'=>$fgroup, 'fkey'=>$fkey);
        }
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_users'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['uname'] = filter_var($r->user_key, FILTER_SANITIZE_STRING);
            $row['email'] = filter_var($r->user_email, FILTER_SANITIZE_STRING);
            $row['fullname'] = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used load detail data
     */
    public function get_info()
    {
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->input->post('fcode', TRUE);
        if($fcode == null)
        {
           $rs = array();
        }else{
            //Parameters for cURL
            $arrWhere = array('fcode'=>$fcode);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_warehouses'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
        }
        return $rs;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        $this->global['pageTitle'] = "Add New Warehouse - ".APP_NAME;
        $this->global['pageMenu'] = 'Add New Warehouse';
        $this->global['contentHeader'] = 'Add New Warehouse';
        $this->global['contentTitle'] = 'Add New Warehouse';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_wr'] = $this->get_list_data();
        $data['list_spv'] = $this->get_list_users("");
        
        $this->loadViews('front/warehouse/create', $this->global, $data);
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {        
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fnearby = !empty($_POST['fnearby']) ? implode(';',$_POST['fnearby']) : "";
        $fpic = $this->input->post('fpic', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $fspv = !empty($_POST['fspv']) ? implode(';',$_POST['fspv']) : "";

        $dataInfo = array('fcode'=>$fcode, 'fname'=>$fname, 'flocation'=>$flocation, 
        'fnearby'=>$fnearby, 'fpic'=>$fpic, 'fphone'=>$fphone, 'fspv'=>$fspv);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_warehouses'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('data-warehouses');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('add-warehouses');
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
            redirect('data-warehouses');
        }
        
        $this->global['pageTitle'] = "Edit Data Warehouse - ".APP_NAME;
        $this->global['pageMenu'] = 'Edit Data Warehouse';
        $this->global['contentHeader'] = 'Edit Data Warehouse';
        $this->global['contentTitle'] = 'Edit Data Warehouse';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['records'] = $this->get_list_info($fkey);
        $data['list_wr'] = $this->get_list_data();
        $data['list_spv'] = $this->get_list_users("");
        
        $this->loadViews('front/warehouse/edit', $this->global, $data);
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fnearby = !empty($_POST['fnearby']) ? implode(';',$_POST['fnearby']) : "";
        $fpic = $this->input->post('fpic', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $fspv = !empty($_POST['fspv']) ? implode(';',$_POST['fspv']) : "";

        $dataInfo = array('fcode'=>$fcode, 'fname'=>$fname, 'flocation'=>$flocation, 
        'fnearby'=>$fnearby, 'fpic'=>$fpic, 'fphone'=>$fphone, 'fspv'=>$fspv);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_warehouses'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('data-warehouses');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('edit-warehouses/'.$fcode);
        }
    }
    
    /**
     * This function is used to delete the data
     * @return boolean $result : TRUE / FALSE
     */
    function delete($fkey = NULL)
    {
        $arrWhere = array();
        $arrWhere = array('fcode'=>$fkey);

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_warehouses'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect('data-warehouses');
    }
}