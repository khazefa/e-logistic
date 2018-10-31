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
    private $cname = 'engineers';
    private $view_dir = 'front/engineers/';
    private $readonly = TRUE;
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isWebAdmin()){
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
        $this->global['pageTitle'] = 'List Engineers - '.APP_NAME;
        $this->global['pageMenu'] = 'List Engineers';
        $this->global['contentHeader'] = 'List Engineers';
        $this->global['contentTitle'] = 'List Engineers';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['classname'] = $this->cname;
        $data['readonly'] = $this->readonly;
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
                    $row['feid'] = $key;
                    $row['fullname'] = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
                    $row['partner'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                    $row['warehouse'] = $fslname;
                    
                    if($this->readonly){
//                        $row['button'] = '<a type="btn" href="javascript:viewdetail(\''.$code.'\');" title="View Detail"><i class="mdi mdi-information-outline text-primary font-18 vertical-middle"></i></a>';
                        $row['button'] = '-';
                    }else{
                        $row['button'] = '<div class="btn-group dropdown">';
                        $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                        $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/edit/").$key.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/remove/").$key.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
                    $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
                    $row['feid'] = $key;
                    $row['fullname'] = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
                    $row['partner'] = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                    $row['warehouse'] = $fslname;

                    $data[] = $row;
                }
                $output = $data;
            break;
        }
        return $output;
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
                $data_warehouse = $this->get_detail_warehouse($code);
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
    public function get_list_warehouse(){
        $rs = array();
        $arrWhere = array();
        $isParam = FALSE;
        
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fcode != "") { $arrWhere['fcode'] = $fcode; $isParam = TRUE; }
        if ($fname != "") { $arrWhere['fname'] = $fname; $isParam = TRUE; }
        
        //if you have some parameters to get data, please set fdeleted and flimit depend on your needs 
        //default flimit = 0 to retrieve All data
        if($isParam){
            $arrWhere["fdeleted"] = 0;
            array_push($arrWhere, $arrWhere["fdeleted"]);
        }else{
            //set flimit = 0 to retrieve All data, because the data is not too large
            $arrWhere = array('fdeleted'=>0, 'flimit'=>0);
        }
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
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
    public function get_detail_warehouse($fcode){
        $rs = array();
        $arrWhere = array();        
        $arrWhere = array('fcode'=>$fcode);
        $arrWhere["fdeleted"] = 0;
        array_push($arrWhere, $arrWhere["fdeleted"]);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
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
    public function get_edit($fkey){
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
            $title = filter_var($r->engineer_title, FILTER_SANITIZE_STRING);
            $email = filter_var($r->engineer_email, FILTER_SANITIZE_EMAIL);
            $phone = filter_var($r->engineer_phone, FILTER_SANITIZE_STRING);
            $area = filter_var($r->engineer_area, FILTER_SANITIZE_STRING);
            $spv = filter_var($r->engineer_spv, FILTER_SANITIZE_STRING);
            $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
            
            $row['feid'] = $key;
            $row['partner'] = $partner;
            $row['name'] = $name;
            $row['title'] = $title;
            $row['email'] = $email;
            $row['phone'] = $phone;
            $row['area'] = $area;
            $row['spv'] = $spv;
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

            $data['classname'] = $this->cname;
            $data['list_partner'] = $this->get_list_partners();
            $data['list_wr'] = $this->get_list_warehouse();
            $data['default_pass'] = strtoupper(generateRandomString());
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
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $ftitle = $this->input->post('ftitle', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $farea = $this->input->post('farea', TRUE);
        $fspv = $this->input->post('fspv', TRUE);
        $fcode = $this->input->post('fcode', TRUE);

        $dataInfo = array('fpartner'=>$fpartner, 'fkey'=>$fkey, 'fpass'=>$fpass, 
            'fname'=>$fname, 'ftitle'=>$ftitle, 'femail'=>$femail, 'fphone'=>$fphone, 
            'farea'=>$farea, 'fspv'=>$fspv, 'fcode'=>$fcode);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_engineers'), 'POST', FALSE);

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
        if($this->isWebAdmin()){
            if($fkey == NULL)
            {
                redirect($this->cname.'/view');
            }

            $this->global['pageTitle'] = "Edit Data Engineer - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Engineer';
            $this->global['contentHeader'] = 'Edit Data Engineer';
            $this->global['contentTitle'] = 'Edit Data Engineer';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['classname'] = $this->cname;
            $data['records'] = $this->get_edit($fkey);
            $data['list_partner'] = $this->get_list_partners();
            $data['list_wr'] = $this->get_list_warehouse();

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
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fpartner = $this->input->post('fpartner', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $ftitle = $this->input->post('ftitle', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $farea = $this->input->post('farea', TRUE);
        $fspv = $this->input->post('fspv', TRUE);
        $fcode = $this->input->post('fcode', TRUE);

        $dataInfo = array('fkey'=>$fkey, 'fpass'=>$fpass, 'fpartner'=>$fpartner, 
            'fname'=>$fname, 'ftitle'=>$ftitle, 'femail'=>$femail, 'fphone'=>$fphone, 
            'farea'=>$farea, 'fspv'=>$fspv, 'fcode'=>$fcode);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_engineers'), 'POST', FALSE);

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

        redirect($this->cname.'/view');
    }
}