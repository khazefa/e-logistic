<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CParts (CPartsController)
 * CParts Class to control Data Parts.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CParts extends BaseController
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
        $this->global['pageTitle'] = 'List Sparepart - '.APP_NAME;
        $this->global['pageMenu'] = 'List Sparepart';
        $this->global['contentHeader'] = 'List Sparepart';
        $this->global['contentTitle'] = 'List Sparepart';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/parts/index', $this->global, NULL);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $wh_name = '';
        $names = '';
        foreach ($rs as $r) {
            $pid = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['partno'] = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['serialno'] = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            
            $parentid = filter_var($r->parent_part_id, FILTER_SANITIZE_NUMBER_INT);
            $partidsub = filter_var($r->part_id_sub, FILTER_SANITIZE_NUMBER_INT);
            $typeid = filter_var($r->part_type_id, FILTER_SANITIZE_NUMBER_INT);
            $row['type'] = $typeid;
            $partsupplier = filter_var($r->supplier_id, FILTER_SANITIZE_NUMBER_INT);
            
            $row['name'] = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
            $row['stock'] = filter_var($r->part_stock, FILTER_SANITIZE_NUMBER_INT);
            
            if(empty($row['code'])){
                $wh_name = "-";
            }else{
                if($row['code'] == "00"){
                    $wh_name = "HQ";
                }else{
                    $data_warehouse = $this->get_list_info_wh($row['code']);
                    foreach ($data_warehouse as $d){
                        $wh_name = $d["name"];
                    }
                }
            }
            
            $row['warehouse'] = $wh_name;
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-spareparts/").$pid.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-spareparts/").$pid.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fpartnum != "") $arrWhere['fpartnum'] = $fpartnum;
        if ($fserialnum != "") $arrWhere['fserialnum'] = $fserialnum;
        if ($fname != "") $arrWhere['fname'] = $fname;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_nearby = array();
        $names = '';
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['partno'] = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['serialno'] = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            
            $parentid = filter_var($r->parent_part_id, FILTER_SANITIZE_NUMBER_INT);
            $partidsub = filter_var($r->part_id_sub, FILTER_SANITIZE_NUMBER_INT);
            $typeid = filter_var($r->part_type_id, FILTER_SANITIZE_NUMBER_INT);
            $row['type'] = $typeid;
            $partsupplier = filter_var($r->supplier_id, FILTER_SANITIZE_NUMBER_INT);
            
            $row['name'] = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
            $row['stock'] = filter_var($r->part_stock, FILTER_SANITIZE_NUMBER_INT);
            
            if(empty($row['code'])){
                $wh_name = "-";
            }else{
                if($row['code'] == "00"){
                    $wh_name = "HQ";
                }else{
                    $data_warehouse = $this->get_list_info_wh($row['code']);
                    foreach ($data_warehouse as $d){
                        $wh_name = $d["name"];
                    }
                }
            }
            
            $row['warehouse'] = $wh_name;
 
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
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fpartnum != "") $arrWhere['fpartnum'] = $fpartnum;
        if ($fserialnum != "") $arrWhere['fserialnum'] = $fserialnum;
        if ($fname != "") $arrWhere['fname'] = $fname;
//        if ($f_date != ""){
//            $arrWhere['submission_date_1'] = $f_date;
//            $arrWhere['submission_date_2'] = $f_date;
//        }

//        $arrWhere['is_deleted'] = 0;
//        array_push($arrWhere, $arrWhere['is_deleted']);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['pid'] = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['partno'] = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['serialno'] = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            
            $row['parentid'] = filter_var($r->parent_part_id, FILTER_SANITIZE_NUMBER_INT);
            $row['partidsub'] = filter_var($r->part_id_sub, FILTER_SANITIZE_NUMBER_INT);
            $row['typeid'] = filter_var($r->part_type_id, FILTER_SANITIZE_NUMBER_INT);
            $row['supplyid'] = filter_var($r->supplier_id, FILTER_SANITIZE_NUMBER_INT);
            
            $row['name'] = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
            $row['stock'] = filter_var($r->part_stock, FILTER_SANITIZE_NUMBER_INT);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get detail information
     */
    public function get_list_info($fserialnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fpid'=>$fserialnum);
//        $arrWhere = array('fserialnum'=>$fserialnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['pid'] = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['partno'] = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['serialno'] = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            
            $row['parentid'] = filter_var($r->parent_part_id, FILTER_SANITIZE_NUMBER_INT);
            $row['partidsub'] = filter_var($r->part_id_sub, FILTER_SANITIZE_NUMBER_INT);
            $row['typeid'] = filter_var($r->part_type_id, FILTER_SANITIZE_NUMBER_INT);
            $row['supplyid'] = filter_var($r->supplier_id, FILTER_SANITIZE_NUMBER_INT);
            
            $row['name'] = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
            $row['stock'] = filter_var($r->part_stock, FILTER_SANITIZE_NUMBER_INT);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get detail information
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
     * This function is used to get lists for populate data warehouse
     */
    public function get_list_data_wh(){
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
        
        $arrWhere = array('fserialnum'=>$fserialnum);
        if($fserialnum == null)
        {
           $rs = array();
        }else{
            //Parameters for cURL
            $arrWhere = array('fserialnum'=>$fserialnum);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
        }
        return $rs;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        $this->global['pageTitle'] = "Add New Sparepart - ".APP_NAME;
        $this->global['pageMenu'] = 'Add New Sparepart';
        $this->global['contentHeader'] = 'Add New Sparepart';
        $this->global['contentTitle'] = 'Add New Sparepart';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_data'] = $this->get_list_data();
        $data['list_data_wh'] = $this->get_list_data_wh();
        
        $this->loadViews('front/parts/create', $this->global, $data);
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {        
        $fcode = $this->input->post('fcode', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fparentid = $this->input->post('fparentid', TRUE);
        $fpartidsub = !empty($_POST['fpartidsub']) ? implode(';',$_POST['fpartidsub']) : "";
        $fparttype = $this->input->post('fparttype', TRUE);
        $fpartsupply = $this->input->post('fpartsupply', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fdesc = $this->input->post('fdesc', TRUE);
        $fstock = $this->input->post('fstock', TRUE);

        $dataInfo = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum, 'fparentid'=>$fparentid, 
                'fpartidsub'=>$fpartidsub, 'fparttype'=>$fparttype, 'fpartsupply'=>$fpartsupply, 'fname'=>$fname, 
                'fdesc'=>$fdesc, 'fstock'=>$fstock);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_parts'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('data-spareparts');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('add-spareparts');
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
            redirect('data-spareparts');
        }
        
        $this->global['pageTitle'] = "Edit Data Sparepart - ".APP_NAME;
        $this->global['pageMenu'] = 'Edit Data Sparepart';
        $this->global['contentHeader'] = 'Edit Data Sparepart';
        $this->global['contentTitle'] = 'Edit Data Sparepart';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['records'] = $this->get_list_info($fkey);
        $data['list_data'] = $this->get_list_data();
        $data['list_data_wh'] = $this->get_list_data_wh();
        
        $this->loadViews('front/parts/edit', $this->global, $data);
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fcode = $this->input->post('fcode', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $fparentid = $this->input->post('fparentid', TRUE);
        $fpartidsub = !empty($_POST['fpartidsub']) ? implode(';',$_POST['fpartidsub']) : "";
        $fparttype = $this->input->post('fparttype', TRUE);
        $fpartsupply = $this->input->post('fpartsupply', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fdesc = $this->input->post('fdesc', TRUE);
        $fstock = $this->input->post('fstock', TRUE);

        $dataInfo = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum, 'fparentid'=>$fparentid, 
                'fpartidsub'=>$fpartidsub, 'fparttype'=>$fparttype, 'fpartsupply'=>$fpartsupply, 'fname'=>$fname, 
                'fdesc'=>$fdesc, 'fstock'=>$fstock);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_parts'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('data-spareparts');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('edit-spareparts/'.$fserialnum);
        }
    }
    
    /**
     * This function is used to delete the data
     * @return boolean $result : TRUE / FALSE
     */
    function delete($fkey = NULL)
    {
        $arrWhere = array();
        $arrWhere = array('fserialnum'=>$fkey);

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_parts'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect('data-spareparts');
    }
}