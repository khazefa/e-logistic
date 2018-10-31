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
    private $cname = 'warehouse';
    private $view_dir = 'front/warehouse/';
    private $readonly = TRUE;
    
    private $field_modal = array(
        'fcode' => 'FSL Code',
        'fname' => 'FSL Name',
        'flocation' => 'Location',
        'fpic' => 'Person In Charge (PIC)',
        'fphone' => 'Phone',
        'fspv' => 'Supervisor'
    );
    
    private $field_value = array(
        'fcode' => 'code',
        'fname' => 'name',
        'flocation' => 'location',
        'fpic' => 'pic',
        'fphone' => 'phone',
        'fspv' => 'spv'
    );
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSuperAdmin()){
            $this->readonly = FALSE;
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
        
        $data['classname'] = $this->cname;
        $data['readonly'] = $this->readonly;
        $data['url_list'] = base_url($this->cname.'/list_nearby/json');
        $data['url_modal'] = base_url($this->cname.'/list_detail/json');
        $data['field_modal_popup'] = $this->field_modal;
        $data['field_modal_js'] = $this->field_value;
        $this->loadViews($this->view_dir.'index', $this->global, $data);
    }
    
    /**
     * This function is used to get list for datatables with nearby warehouse
     */
    public function get_list_nearby($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->cname), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                $data_nearby = array();
                $names = '';
                $data_spv = array();
                $spvs = '';
                foreach ($rs as $r) {
                    $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $row['code'] = $code;
                    $row['name'] = $this->common->nohtml($r->fsl_name);
                    $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
                    $nearby = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
                    if(!empty($nearby)){
                        $names = '<ul class="list-unstyled">';
                        $e_nearby = explode(';', $nearby);
                        $data_nearby = array();
                        foreach ($e_nearby as $n){
                            if(!empty($n)){
                                array_push($data_nearby, $this->get_detail_by($n));
                            }
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
                            if(!empty($s)){
                                array_push($data_spv, $this->get_list_users($s));
                            }
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
                    $row['sort'] = filter_var($r->field_order, FILTER_SANITIZE_NUMBER_INT);
                    
                    if($this->readonly){
                        $row['button'] = '<a type="btn" href="javascript:viewdetail(\''.$code.'\');" title="View Detail"><i class="mdi mdi-information-outline text-primary font-18 vertical-middle"></i></a>';
//                        $row['button'] = '-';
                    }else{
                        $row['button'] = '<div class="btn-group dropdown">';
                        $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                        $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/edit/").$code.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/remove/").$code.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
                $data_nearby = array();
                $names = '';
                $data_spv = array();
                $spvs = '';
                foreach ($rs as $r) {
                    $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $row['code'] = $code;
                    $row['name'] = $this->common->nohtml($r->fsl_name);
                    $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
                    $nearby = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
                    if(!empty($nearby)){
                        $names = '<ul class="list-unstyled">';
                        $e_nearby = explode(';', $nearby);
                        $data_nearby = array();
                        foreach ($e_nearby as $n){
                            if(!empty($n)){
                                array_push($data_nearby, $this->get_detail_by($n));
                            }
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
                            if(!empty($s)){
                                array_push($data_spv, $this->get_list_users($s));
                            }
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
                    $row['sort'] = filter_var($r->field_order, FILTER_SANITIZE_NUMBER_INT);

                    $data[] = $row;
                }
                $output = $data;
            break;
        }
        
        return $output;
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->cname), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                $data_nearby = array();
                $names = '';
                $data_spv = array();
                $spvs = '';
                foreach ($rs as $r) {
                    $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $row['code'] = $code;
                    $row['name'] = $this->common->nohtml($r->fsl_name);
                    $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
                    $nearby = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
                    $row['nearby'] = $nearby;
                    $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
                    $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
                    $listspv = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
                    $row['spv'] = $listspv;
                    $row['sort'] = filter_var($r->field_order, FILTER_SANITIZE_NUMBER_INT);
                    
                    if($this->readonly){
                        $row['button'] = '<a type="btn" href="javascript:viewdetail(\''.$code.'\');" title="View Detail"><i class="mdi mdi-information-outline text-primary font-18 vertical-middle"></i></a>';
//                        $row['button'] = '-';
                    }else{
                        $row['button'] = '<div class="btn-group dropdown">';
                        $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                        $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/edit/").$code.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/remove/").$code.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
                $data_nearby = array();
                $names = '';
                $data_spv = array();
                $spvs = '';
                foreach ($rs as $r) {
                    $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $row['code'] = $code;
                    $row['name'] = $this->common->nohtml($r->fsl_name);
                    $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
                    $nearby = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
                    $row['nearby'] = $nearby;
                    $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
                    $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
                    $listspv = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
                    $row['spv'] = $listspv;
                    $row['sort'] = filter_var($r->field_order, FILTER_SANITIZE_NUMBER_INT);

                    $data[] = $row;
                }
                $output = $data;
            break;
        }
        
        return $output;
    }
    
    /**
     * This function is used load detail data
     */
    public function get_detail($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
        
        $fcode = $this->input->get('fcode', TRUE);
        if($fcode == null)
        {
            $rs = array();
            $output = $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array()));
        }else{
            //Parameters for cURL
            if(!empty($fcode)){
                $arrWhere = array('fcode'=>$fcode);
            }
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_'.$this->cname), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            switch($type) {
                case "json":
                    foreach ($rs as $r) {
                        $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                        $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                        $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
                        $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
                        $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
                        $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
                        $row['spv'] = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
                        $row['sort_order'] = filter_var($r->field_order, FILTER_SANITIZE_NUMBER_INT);

                        $data[] = $row;
                    }
                    $output = $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode(array('data'=>$data)));
                break;
                case "array":
                    foreach ($rs as $r) {
                        $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                        $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                        $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
                        $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
                        $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
                        $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
                        $row['spv'] = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
                        $row['sort_order'] = filter_var($r->field_order, FILTER_SANITIZE_NUMBER_INT);

                        $data[] = $row;
                    }
                    $output = $data;
                break;

            }
        }
        return $output;
    }
    
    /**
     * This function is used to get lists for populate data
     */
    /**
    public function get_list_data(){
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
            $arrWhere = array('fcode'=>$fcode, 'fname'=>$fname, 
                'fdeleted'=>0, 'flimit'=>0);
        }else{
            //set flimit = 0 to retrieve All data, because the data is not too large
            $arrWhere = array('fdeleted'=>0, 'flimit'=>0);
        }
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->cname), 'POST', FALSE);
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
    */
    
    /**
     * This function is used to get detail information
     */
    public function get_detail_by($fcode){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode);        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->cname), 'POST', FALSE);
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
            $row['sort_order'] = filter_var($r->field_order, FILTER_SANITIZE_NUMBER_INT);
 
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
     * This function is used to load the add new form
     */
    function add()
    {
        if($this->isSuperAdmin()){
            $this->global['pageTitle'] = "Add New Warehouse - ".APP_NAME;
            $this->global['pageMenu'] = 'Add New Warehouse';
            $this->global['contentHeader'] = 'Add New Warehouse';
            $this->global['contentTitle'] = 'Add New Warehouse';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['classname'] = $this->cname;
//            $data['list_wr'] = $this->get_list_data();
            $data['list_wr'] = $this->get_list("array");
            $data['list_spv'] = $this->get_list_users("");
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
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fnearby = !empty($_POST['fnearby']) ? implode(';',$_POST['fnearby']) : "";
        $fpic = $this->input->post('fpic', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $fspv = !empty($_POST['fspv']) ? implode(';',$_POST['fspv']) : "";
        $forder = $this->input->post('forder', TRUE);

        $dataInfo = array('fcode'=>$fcode, 'fname'=>$fname, 'flocation'=>$flocation, 
            'fnearby'=>$fnearby, 'fpic'=>$fpic, 'fphone'=>$fphone, 'fspv'=>$fspv, 'forder'=>$forder);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_'.$this->cname), 'POST', FALSE);

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

            $this->global['pageTitle'] = "Edit Data Warehouse - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Warehouse';
            $this->global['contentHeader'] = 'Edit Data Warehouse';
            $this->global['contentTitle'] = 'Edit Data Warehouse';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['classname'] = $this->cname;
            $data['records'] = $this->get_detail_by($fkey);
//            $data['list_wr'] = $this->get_list_data();
            $data['list_wr'] = $this->get_list("array");
            $data['list_spv'] = $this->get_list_users("");
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
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $flocation = $this->input->post('flocation', TRUE);
        $fnearby = !empty($_POST['fnearby']) ? implode(';',$_POST['fnearby']) : "";
        $fpic = $this->input->post('fpic', TRUE);
        $fphone = $this->input->post('fphone', TRUE);
        $fspv = !empty($_POST['fspv']) ? implode(';',$_POST['fspv']) : "";
        $forder = $this->input->post('forder', TRUE);

        $dataInfo = array('fcode'=>$fcode, 'fname'=>$fname, 'flocation'=>$flocation, 
        'fnearby'=>$fnearby, 'fpic'=>$fpic, 'fphone'=>$fphone, 'fspv'=>$fspv, 'forder'=>$forder);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_'.$this->cname), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect($this->cname.'/view');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect($this->cname.'/edit/'.$fcode);
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

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_'.$this->cname), 'POST', FALSE);

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