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
        $this->global['pageTitle'] = 'List Warehouse :: '.APP_NAME;
        $this->global['pageMenu'] = 'List Warehouse';
        $this->global['contentHeader'] = 'List Warehouse';
        $this->global['contentTitle'] = 'List Warehouse';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/warehouse/index', $this->global, NULL);
    }
    
    public function get_list(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";
        
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
     * This function is used to check username already exist
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
        $this->global['pageTitle'] = "Add New :: ".APP_NAME;
        $this->global['contentHeader'] = 'User Management';
        $this->global['tableTitle'] = 'Detail Data';
        $data['roles'] = $this->MUser->get_user_roles();
        $data['departments'] = $this->MUser->get_departments();

        $this->loadViews('client/users/create', $this->global, $data);
    }
}