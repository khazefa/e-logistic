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
    private $cname = 'spareparts-sub';
    private $view_dir = 'front/part-sub/';
    private $readonly = TRUE;
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSuperAdmin()){
            $this->readonly = FALSE;
        }else{
            $this->readonly = TRUE;
        }
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
        
        $data['readonly'] = $this->readonly;
        $data['classname'] = $this->cname;
        $data['url_list'] = base_url($this->cname.'/list/json');
        $this->loadViews($this->view_dir.'index', $this->global, $data);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
        $isParam = FALSE;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_sub'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
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
                                array_push($data_parts, $this->get_detail_part($n));
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
                    
                    if($this->readonly){
                        $row['button'] = '-';
                    }else{
                        $row['button'] = '<div class="btn-group dropdown">';
                        $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                        $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/edit/").$id.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/remove/").$id.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
                        $row['button'] .= '</div>';
                        $row['button'] .= '</div>';
                    }

                    $data[] = $row;
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r){
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
                $output = $data;
            break;
        }
        return $output;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_detail($fkey){
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
    public function get_detail_part($fpartnum){
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

            $data['classname'] = $this->cname;
            $data['list_data_part'] = $this->get_list_data_part();            
            $this->loadViews($this->view_dir.'create', $this->global, $data);
        }else{
            redirect($this->cname.'/view');
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
            redirect($this->cname.'/view');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect($this->cname.'/add');
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
                redirect($this->cname.'/view');
            }

            $this->global['pageTitle'] = "Edit Data Part Subtitute - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Part Subtitute';
            $this->global['contentHeader'] = 'Edit Data Part Subtitute';
            $this->global['contentTitle'] = 'Edit Data Part Subtitute';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['classname'] = $this->cname;
            $data['records'] = $this->get_detail($fkey);
            $data['list_data_part'] = $this->get_list_data_part();
            $this->loadViews($this->view_dir.'edit', $this->global, $data);
        }else{
            redirect($this->cname.'/view');
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
            redirect($this->cname.'/view');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect($this->cname.'/edit/'.$fkey);
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

        redirect($this->cname.'/view');
    }
}