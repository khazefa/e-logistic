<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CPartsub (CPartsubController)
 * CPartsub Class to control Data Part Subtitute.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CPartsub extends BaseController
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
        $this->global['pageTitle'] = 'List Part Subtitute - '.APP_NAME;
        $this->global['pageMenu'] = 'List Part Subtitute';
        $this->global['contentHeader'] = 'List Part Subtitute';
        $this->global['contentTitle'] = 'List Part Subtitute';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/part-sub/index', $this->global, NULL);
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function lists()
    {
        if($this->isSuperAdmin()){
            $this->global['pageTitle'] = 'Manage Part Subtitute - '.APP_NAME;
            $this->global['pageMenu'] = 'Manage Part Subtitute';
            $this->global['contentHeader'] = 'Manage Part Subtitute';
            $this->global['contentTitle'] = 'Manage Part Subtitute';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $this->loadViews('front/part-sub/lists', $this->global, NULL);
        }else{
            redirect('data-spareparts-sub');
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_sub'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_parts = array();
        $names = '';
        foreach ($rs as $r) {
            $id = filter_var($r->partsub_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['partno'] = $partnum;
            $part_sub = filter_var($r->part_number_sub, FILTER_SANITIZE_STRING);
            if(!empty($part_sub)){
                $names = '<ul class="list-unstyled">';
                $e_partsub = explode(';', $part_sub);
                $data_parts = array();
                foreach ($e_partsub as $n){
                    if(!empty($n)){
                        array_push($data_parts, $this->get_list_info_part($n));
                    }
                }
                foreach ($data_parts as $datas){
                    foreach($datas as $d){
//                        $names .= '<li style="display:inline; padding-left:5px;">'.$d["name"].'</li>';
                        $names .= '<li>'.$d["partno"].' -> '.$d["name"].'</li>';
//                        $names .= $d["name"].', ';
                    }
                }
                $names .= '</ul>';
            }else{
                $names = '-';
            }
            $row['partnosub'] = $names;
            
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-spareparts-sub/").$id.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-spareparts-sub/").$id.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_sub'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_parts = array();
        $names = '';
        foreach ($rs as $r) {
            $id = filter_var($r->partsub_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['partno'] = $partnum;
            $part_sub = filter_var($r->part_number_sub, FILTER_SANITIZE_STRING);
            if(!empty($part_sub)){
                $names = '<ul class="list-unstyled">';
                $e_partsub = explode(';', $part_sub);
                foreach ($e_partsub as $n){
                    if(!empty($n)){
                        array_push($data_parts, $this->get_list_info_part($n));
                    }
                }
                
                foreach ($data_parts as $datas){
                    foreach($datas as $d){
//                        $names .= '<li style="display:inline; padding-left:5px;">'.$d["name"].'</li>';
                        $names .= '<li>'.$d["partno"].' -> '.$d["name"].'</li>';
//                        $names .= $d["name"].', ';
                    }
                }
                $names .= '</ul>';
            }else{
                $names = '-';
            }
            $row['partnosub'] = $names;
            
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-spareparts-sub/").$id.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-spareparts-sub/").$id.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
        
        $fpid = $this->input->post('fpid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);

        if ($fid != "") $arrWhere['fid'] = $fid;
        if ($fpartnum != "") $arrWhere['fpartnum'] = $fpartnum;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_sub'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->partsub_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $part_sub = filter_var($r->part_number_sub, FILTER_SANITIZE_STRING);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partnosub'] = $part_sub;
 
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
        
        $fpid = $this->input->post('fpid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);

        if ($fid != "") $arrWhere['fid'] = $fid;
        if ($fpartnum != "") $arrWhere['fpartnum'] = $fpartnum;
//        if ($f_date != ""){
//            $arrWhere['submission_date_1'] = $f_date;
//            $arrWhere['submission_date_2'] = $f_date;
//        }

//        $arrWhere['is_deleted'] = 0;
//        array_push($arrWhere, $arrWhere['is_deleted']);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_sub'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->partsub_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $part_sub = filter_var($r->part_number_sub, FILTER_SANITIZE_STRING);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partnosub'] = $part_sub;
 
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_sub'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->partsub_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $part_sub = filter_var($r->part_number_sub, FILTER_SANITIZE_STRING);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partnosub'] = $part_sub;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get lists for populate data
     */
    public function get_list_data_part(){
        $rs = array();
        $arrWhere = array();
        
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fpartnum != "") $arrWhere['fpartnum'] = $fpartnum;
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
            $pid = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['pid'] = $pid;
            $row['partno'] = $partnum;
            $row['name'] = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
            $row['returncode'] = filter_var($r->part_return_code, FILTER_SANITIZE_STRING);
            $row['machine'] = filter_var($r->part_machine, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get detail information
     */
    public function get_list_info_part($fpartnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fpartnum'=>$fpartnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $pid = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['pid'] = $pid;
            $row['partno'] = $partnum;
            $row['name'] = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
            $row['returncode'] = filter_var($r->part_return_code, FILTER_SANITIZE_STRING);
            $row['machine'] = filter_var($r->part_machine, FILTER_SANITIZE_STRING);
 
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
            $this->global['pageTitle'] = "Add New Part Subtitute - ".APP_NAME;
            $this->global['pageMenu'] = 'Add New Part Subtitute' ;
            $this->global['contentHeader'] = 'Add New Part Subtitute';
            $this->global['contentTitle'] = 'Add New Part Subtitute';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['list_data_part'] = $this->get_list_data_part();
            
            $this->loadViews('front/part-sub/create', $this->global, $data);
        }else{
            redirect('data-spareparts-sub');
        }
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartsub = !empty($_POST['fpartsub']) ? implode(';',$_POST['fpartsub']) : "";

        $dataInfo = array('fpartnum'=>$fpartnum, 'fpartsub'=>$fpartsub);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_part_sub'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-spareparts-sub');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('add-spareparts-sub');
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
                redirect('manage-spareparts-sub');
            }

            $this->global['pageTitle'] = "Edit Data Part Subtitute - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Part Subtitute';
            $this->global['contentHeader'] = 'Edit Data Part Subtitute';
            $this->global['contentTitle'] = 'Edit Data Part Subtitute';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['records'] = $this->get_list_info($fkey);
            $data['list_data_part'] = $this->get_list_data_part();

            $this->loadViews('front/part-sub/edit', $this->global, $data);
        }else{
            redirect('data-spareparts-sub');
        }
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fid = $this->input->post('fid', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartsub = !empty($_POST['fpartsub']) ? implode(';',$_POST['fpartsub']) : "";

        $dataInfo = array('fid'=>$fid, 'fpartnum'=>$fpartnum, 'fpartsub'=>$fpartsub);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_part_sub'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect('manage-spareparts-sub');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect('edit-spareparts-sub/'.$fkey);
        }
    }
    
    /**
     * This function is used to delete the data
     * @return boolean $result : TRUE / FALSE
     */
    function delete($fkey = NULL)
    {
        $arrWhere = array();
        $arrWhere = array('fid'=>$fkey);

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_part_sub'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect('manage-spareparts-sub');
    }
}