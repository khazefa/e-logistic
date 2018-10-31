<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CUsers (CUsersController)
 * CUsers Class to control Data User.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CUsers extends BaseController
{
    private $cname = 'users';
    private $view_dir = 'front/accounts/';
    private $readonly = TRUE;
    
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
        $this->global['pageTitle'] = 'List User - '.APP_NAME;
        $this->global['pageMenu'] = 'List User';
        $this->global['contentHeader'] = 'List User';
        $this->global['contentTitle'] = 'List User';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_users'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $username = filter_var($r->user_key, FILTER_SANITIZE_STRING);
                    $row['uname'] = $username;
                    $row['email'] = filter_var($r->user_email, FILTER_SANITIZE_STRING);
                    $row['fullname'] = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
                    $row['group'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
                    $row['warehouse'] = empty($r->fsl_name) ? "HQ" : filter_var($r->fsl_name, FILTER_SANITIZE_STRING); 
                    
                    $row['button'] = '<div class="btn-group dropdown">';
                    $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                    $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                    $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/edit/").$username.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                    $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/remove/").$username.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
                    $row['button'] .= '</div>';
                    $row['button'] .= '</div>';

                    $data[] = $row;
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r) {
                    $username = filter_var($r->user_key, FILTER_SANITIZE_STRING);
                    $row['uname'] = $username;
                    $row['email'] = filter_var($r->user_email, FILTER_SANITIZE_STRING);
                    $row['fullname'] = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
                    $row['group'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
                    $row['warehouse'] = empty($r->fsl_name) ? "HQ" : filter_var($r->fsl_name, FILTER_SANITIZE_STRING); 
                    
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
    private function get_list_group(){
        $rs = array();
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_user_group'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['id'] = filter_var($r->group_id, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->group_name, FILTER_SANITIZE_STRING);
            $row['display'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
            $row['enc'] = filter_var($r->group_enc, FILTER_SANITIZE_STRING);
 
            if($row['enc'] != ROLE_SU){
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    private function get_list_warehouse(){
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
    private function get_edit($fkey){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fkey'=>$fkey);        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_users'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['uname'] = filter_var($r->user_key, FILTER_SANITIZE_STRING);
            $row['email'] = filter_var($r->user_email, FILTER_SANITIZE_STRING);
            $row['fullname'] = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $row['adm'] = filter_var($r->is_admin, FILTER_SANITIZE_NUMBER_INT);
            $row['gid'] = filter_var($r->group_id, FILTER_SANITIZE_STRING);
            $row['group'] = filter_var($r->group_name, FILTER_SANITIZE_STRING);
            $row['group_name'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
            $row['fsl'] = empty($r->fsl_code) ? "00" : filter_var($r->fsl_code, FILTER_SANITIZE_STRING) ;
            $row['coverage'] = filter_var($r->coverage_fsl, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        $this->global['pageTitle'] = "Add New User - ".APP_NAME;
        $this->global['pageMenu'] = 'Add New User';
        $this->global['contentHeader'] = 'Add New User';
        $this->global['contentTitle'] = 'Add New User';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['classname'] = $this->cname;
        $data['list_group'] = $this->get_list_group();
        $data['list_wr'] = $this->get_list_warehouse();
        
        $this->loadViews($this->view_dir.'create', $this->global, $data);
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {        
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fgroup = $this->input->post('fgroup', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $ffsl = $this->input->post('ffsl', TRUE);
        $fcode = !empty($_POST['fcode']) ? implode(';',$_POST['fcode']) : "";
        $fisadm = $this->input->post('fisadm', TRUE);

        $dataInfo = array('fgroup'=>$fgroup, 'fkey'=>$fkey, 'fpass'=>$fpass, 'fname'=>$fname, 'femail'=>$femail, 
        'ffsl'=>$ffsl, 'fcoverage'=>$fcode, 'fisadm'=>$fisadm);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_users'), 'POST', FALSE);

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
        if($fkey == NULL)
        {
            redirect('manage-users');
        }
        
        $this->global['pageTitle'] = "Edit Data User - ".APP_NAME;
        $this->global['pageMenu'] = 'Edit Data User';
        $this->global['contentHeader'] = 'Edit Data User';
        $this->global['contentTitle'] = 'Edit Data User';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['classname'] = $this->cname;
        $data['records'] = $this->get_edit($fkey);
        $data['list_group'] = $this->get_list_group();
        $data['list_wr'] = $this->get_list_warehouse();
        
        $this->loadViews($this->view_dir.'edit', $this->global, $data);
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fkey = $this->input->post('fkey', TRUE);
        $fpass = $this->input->post('fpass', TRUE);
        $fgroup = $this->input->post('fgroup', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $femail = $this->input->post('femail', TRUE);
        $ffsl = $this->input->post('ffsl', TRUE);
        $fcode = !empty($_POST['fcode']) ? implode(';',$_POST['fcode']) : "";
        $fisadm = $this->input->post('fisadm', TRUE);

        $dataInfo = array('fgroup'=>$fgroup, 'fkey'=>$fkey, 'fpass'=>$fpass, 'fname'=>$fname, 'femail'=>$femail, 
            'ffsl'=>$ffsl, 'fcoverage'=>$fcode, 'fisadm'=>$fisadm);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_users'), 'POST', FALSE);

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

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_users'), 'POST', FALSE);

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