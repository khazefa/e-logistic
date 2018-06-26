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
     * This function is used to get data list
     */
    public function get_list(){
        $rs = array();
        
        //Parameters for cURL
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
            $row['phone'] = filter_var($r->fsl_phone, FILTER_SANITIZE_STRING);
            
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("front/cwarehouse/delete/").$r->fsl_code.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit Ticket</a>';
            $row['button'] .= '<a class="dropdown-item" href="#"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
     * This function is used to check data already exist
     */
    public function check_exist($str)
    {
        $count = $this->MUser->check_username_exists($str);

        if ($count > 0)
        {
            $this->form_validation->set_message('username_check', 'The {field} is already exists');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
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

        $this->loadViews('client/users/create', $this->global, NULL);
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {
        $this->form_validation->set_rules('fusername','Username','trim|required|max_length[50]|callback_username_check');
        $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('femail','Email','trim|valid_email|max_length[128]');
        $this->form_validation->set_rules('fpassword','Password','required|max_length[20]');
        // $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
        $this->form_validation->set_rules('frole','Role','trim|required');
        $this->form_validation->set_rules('fdept','Departments','trim|required');
        $this->form_validation->set_rules('fmobile','Mobile Number','max_length[32]');
            
        if($this->form_validation->run() == FALSE)
        {
            $this->add();
        }
        else
        {            
            $username = strtolower($this->input->post('fusername', TRUE));
            $password = $this->input->post('fpassword', TRUE);
            $email = $this->input->post('femail', TRUE);
            $name = ucwords(strtolower($this->input->post('fname', TRUE)));
            $mobile = $this->input->post('fmobile', TRUE);
            $roleId = $this->input->post('frole');
            $deptId = $this->input->post('fdept');
            $createdby = $this->vendorId;
            
            $userInfo = array('cl_username'=>$username, 'cl_email'=>$email, 'cl_password'=>getHashedPassword($password), 
            'cl_role_enc'=>$roleId, 'dept_id'=>$deptId,  'cl_name'=> $name, 'cl_mobile'=>$mobile, 'cl_createdBy'=>$createdby, 
            'cl_createdDtm'=>date('Y-m-d H:i:sa'), 'cl_pict'=> $fpict);

            $result = $this->MUser->insert_data($this->security->xss_clean($userInfo));
            
            if($result > 0)
            {
                if($this->send_registration_email($email, $username, $password, $name, $mobile)){
                    $this->session->set_flashdata('success', 'Registration email has been sent.');
                }else{
                    $this->session->set_flashdata('error', 'Registration email has not been sent.');
                }
                $sessionArray = array('fvPict'=>$fpict);
                $this->session->set_userdata($sessionArray);
            }
            else
            {
                $this->session->set_flashdata('error', 'Failed');
            }
            
            redirect('client/users');
        }
    }
}