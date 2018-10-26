<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CAtm (CAtmController)
 * CAtm Class to control Data Parts.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CAtm extends BaseController
{
    private $cname = 'atm';
    private $view_dir = 'front/atm_places/';
    private $readonly = TRUE;
    
    private $field_modal = array(
        'fssbid' => 'SSB ID',
        'fmachid' => 'Machine ID',
        'fname' => 'Bank Name',
        'floc' => 'Location',
        'faddress' => 'Address',
        'fpostcode' => 'Postal Code',
        'fcity' => 'City',
        'fprovince' => 'Province',
        'fisland' => 'Island'
    );
    
    private $field_value = array(
        'fssbid' => 'serial_no',
        'fmachid' => 'machine',
        'fname' => 'bank',
        'floc' => 'location',
        'faddress' => 'address',
        'fpostcode' => 'postcode',
        'fcity' => 'city',
        'fprovince' => 'province',
        'fisland' => 'island'
    );

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
        $this->global['pageTitle'] = 'List ATM Location - '.APP_NAME;
        $this->global['pageMenu'] = 'List ATM Location';
        $this->global['contentHeader'] = 'List ATM Location';
        $this->global['contentTitle'] = 'List ATM Location';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['readonly'] = $this->readonly;
        $data['arr_data'] = base_url($this->cname.'/list/json');
//        $data['arr_data_u'] = base_url($this->cname.'/get_distinct/json');
        $data['arr_data_u'] = $this->get_unique('array');
        $data['url_modal'] = base_url($this->cname.'/list_detail/json');
        $data['field_modal_popup'] = $this->field_modal;
        $data['field_modal_js'] = $this->field_value;
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
        
//        $fssbid = $this->input->post('fssbid', TRUE);
//        $fmachid = $this->input->post('fmachid', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fcity = $this->input->post('fcity', TRUE);

//        if ($fssbid != "") { $arrWhere['fssbid'] = $fssbid; $isParam = TRUE; }
//        if ($fmachid != "") { $arrWhere['fmachid'] = $fmachid; $isParam = TRUE; }
        if ($fname != "") { $arrWhere['fname'] = $fname; $isParam = TRUE; }
        if ($fcity != "") { $arrWhere['fcity'] = $fcity; $isParam = TRUE; }
        
        //if you have some parameters to get data, please set fdeleted and flimit depend on your needs 
        //default flimit = 0 to retrieve All data
        if($isParam){
//            $arrWhere = array('fssbid'=>$fssbid, 'fmachid'=>$fmachid, 'fname'=>$fname, 'fcity'=>$fcity, 
//                'fdeleted'=>0, 'flimit'=>0);
            $arrWhere = array('fname'=>$fname, 'fcity'=>$fcity, 
                'fdeleted'=>0, 'flimit'=>0);
        }else{
            $arrWhere = array('fdeleted'=>0, 'flimit'=>100);
        }
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->cname), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $pid = filter_var($r->atmp_id, FILTER_SANITIZE_NUMBER_INT);
                    $row['serial_no'] = filter_var($r->atmp_ssbid, FILTER_SANITIZE_STRING);
                    $row['machine'] = filter_var($r->atmp_machid, FILTER_SANITIZE_STRING);
                    $row['bank'] = filter_var($r->atmp_cust, FILTER_SANITIZE_STRING);
                    $row['location'] = filter_var($r->atmp_loc, FILTER_SANITIZE_STRING);
                    $row['address'] = filter_var($r->atmp_address, FILTER_SANITIZE_STRING);
                    $row['postcode'] = filter_var($r->atmp_postcode, FILTER_SANITIZE_STRING);
                    $row['city'] = filter_var($r->atmp_city, FILTER_SANITIZE_STRING);
                    $row['province'] = filter_var($r->atmp_province, FILTER_SANITIZE_STRING);
                    $row['island'] = filter_var($r->atmp_island, FILTER_SANITIZE_STRING);

                    if($this->readonly){
                        $row['button'] = '<a type="btn" href="javascript:viewdetail(\''.$pid.'\');" title="View Detail"><i class="mdi mdi-information-outline text-primary font-18 vertical-middle"></i></a>';
                    }else{
                        $row['button'] = '<div class="btn-group dropdown">';
                        $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                        $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/edit/".$pid).'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                        $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/remove/".$pid).'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
                foreach ($rs as $r) {
                    $pid = filter_var($r->atmp_id, FILTER_SANITIZE_NUMBER_INT);
                    $row['serial_no'] = filter_var($r->atmp_ssbid, FILTER_SANITIZE_STRING);
                    $row['machine'] = filter_var($r->atmp_machid, FILTER_SANITIZE_STRING);
                    $row['bank'] = filter_var($r->atmp_cust, FILTER_SANITIZE_STRING);
                    $row['location'] = filter_var($r->atmp_loc, FILTER_SANITIZE_STRING);
                    $row['address'] = filter_var($r->atmp_address, FILTER_SANITIZE_STRING);
                    $row['postcode'] = filter_var($r->atmp_postcode, FILTER_SANITIZE_STRING);
                    $row['city'] = filter_var($r->atmp_city, FILTER_SANITIZE_STRING);
                    $row['province'] = filter_var($r->atmp_province, FILTER_SANITIZE_STRING);
                    $row['island'] = filter_var($r->atmp_island, FILTER_SANITIZE_STRING);

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
    public function get_unique($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
        $isParam = FALSE;
        
        $fname = $this->input->get('fname', TRUE);
        $fcity = $this->input->get('fcity', TRUE);

        if ($fname != "") { $arrWhere['fname'] = $fname; $isParam = TRUE; }
        if ($fcity != "") { $arrWhere['fcity'] = $fcity; $isParam = TRUE; }
        
        //if you have some parameters to get data, please set fdeleted and flimit depend on your needs 
        //default flimit = 0 to retrieve All data
        if($isParam){
            $arrWhere = "fname=".$fname."&fcity=".$fcity."&fdeleted=0&flimit=0";
        }else{
            $arrWhere = "fdeleted=0&flimit=100";
        }
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_u_'.$this->cname)."?".$arrWhere, 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $row['bank'] = filter_var($r->atmp_cust, FILTER_SANITIZE_STRING);
                    $row['city'] = filter_var($r->atmp_city, FILTER_SANITIZE_STRING);

                    $data[] = $row;
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r) {
                    $row['bank'] = filter_var($r->atmp_cust, FILTER_SANITIZE_STRING);
                    $row['city'] = filter_var($r->atmp_city, FILTER_SANITIZE_STRING);

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
        
        $fid = $this->input->get('fid', TRUE);
        if($fid == null)
        {
           $rs = array();
        }else{
            //Parameters for cURL
            $arrWhere = array('fid'=>$fid);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_'.$this->cname), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            switch($type) {
                case "json":
                    foreach ($rs as $r) {
                        $pid = filter_var($r->atmp_id, FILTER_SANITIZE_NUMBER_INT);
                        $row['serial_no'] = filter_var($r->atmp_ssbid, FILTER_SANITIZE_STRING);
                        $row['machine'] = filter_var($r->atmp_machid, FILTER_SANITIZE_STRING);
                        $row['bank'] = filter_var($r->atmp_cust, FILTER_SANITIZE_STRING);
                        $row['location'] = filter_var($r->atmp_loc, FILTER_SANITIZE_STRING);
                        $row['address'] = filter_var($r->atmp_address, FILTER_SANITIZE_STRING);
                        $row['postcode'] = filter_var($r->atmp_postcode, FILTER_SANITIZE_STRING);
                        $row['city'] = filter_var($r->atmp_city, FILTER_SANITIZE_STRING);
                        $row['province'] = filter_var($r->atmp_province, FILTER_SANITIZE_STRING);
                        $row['island'] = filter_var($r->atmp_island, FILTER_SANITIZE_STRING);

                        $data[] = $row;
                    }
                    $output = $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode(array('data'=>$data)));
                break;
                case "array":
                    foreach ($rs as $r) {
                        $pid = filter_var($r->atmp_id, FILTER_SANITIZE_NUMBER_INT);
                        $row['serial_no'] = filter_var($r->atmp_ssbid, FILTER_SANITIZE_STRING);
                        $row['machine'] = filter_var($r->atmp_machid, FILTER_SANITIZE_STRING);
                        $row['bank'] = filter_var($r->atmp_cust, FILTER_SANITIZE_STRING);
                        $row['location'] = filter_var($r->atmp_loc, FILTER_SANITIZE_STRING);
                        $row['address'] = filter_var($r->atmp_address, FILTER_SANITIZE_STRING);
                        $row['postcode'] = filter_var($r->atmp_postcode, FILTER_SANITIZE_STRING);
                        $row['city'] = filter_var($r->atmp_city, FILTER_SANITIZE_STRING);
                        $row['province'] = filter_var($r->atmp_province, FILTER_SANITIZE_STRING);
                        $row['island'] = filter_var($r->atmp_island, FILTER_SANITIZE_STRING);

                        $data[] = $row;
                    }
                    $output = $data;
                break;

            }
        }
        return $output;
    }
    
    /**
     * This function is used load detail data for edit
     */
    public function get_edit($fid){
        $rs = array();
        $arrWhere = array();
        $data = array();
        
        if($fid == null)
        {
           $rs = array();
        }else{
            //Parameters for cURL
            $arrWhere = array('fid'=>$fid);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_'.$this->cname), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
            foreach ($rs as $r) {
                $pid = filter_var($r->atmp_id, FILTER_SANITIZE_NUMBER_INT);
                $row['pid'] = $pid;
                $row['serial_no'] = filter_var($r->atmp_ssbid, FILTER_SANITIZE_STRING);
                $row['machine'] = filter_var($r->atmp_machid, FILTER_SANITIZE_STRING);
                $row['bank'] = filter_var($r->atmp_cust, FILTER_SANITIZE_STRING);
                $row['location'] = filter_var($r->atmp_loc, FILTER_SANITIZE_STRING);
                $row['address'] = filter_var($r->atmp_address, FILTER_SANITIZE_STRING);
                $row['postcode'] = filter_var($r->atmp_postcode, FILTER_SANITIZE_STRING);
                $row['city'] = filter_var($r->atmp_city, FILTER_SANITIZE_STRING);
                $row['province'] = filter_var($r->atmp_province, FILTER_SANITIZE_STRING);
                $row['island'] = filter_var($r->atmp_island, FILTER_SANITIZE_STRING);

                $data[] = $row;
            }
        }
        return $data;
    }
    
    protected function get_test(){
        $rs = $this->get_list('array');
        $output = $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('data'=>$rs)));
        return $output;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if($this->isWebAdmin()){
            $this->global['pageTitle'] = "Add New ATM Location - ".APP_NAME;
            $this->global['pageMenu'] = 'Add New ATM Location';
            $this->global['contentHeader'] = 'Add New ATM Location';
            $this->global['contentTitle'] = 'Add New ATM Location';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $this->loadViews($this->view_dir.'create', $this->global, NULL);
        }else{
            redirect($this->cname.'/view');
        }
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {
        $fssbid = $this->input->post('fssbid', TRUE);
        $fmachid = $this->input->post('fmachid', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $floc = $this->input->post('floc', TRUE);
        $faddress = $this->input->post('faddress', TRUE);
        $fpostcode = $this->input->post('fpostcode', TRUE);
        $fcity = $this->input->post('fcity', TRUE);
        $fprovince = $this->input->post('fprovince', TRUE);
        $fisland = $this->input->post('fisland', TRUE);

        $dataInfo = array('fssbid'=>$fssbid, 'fmachid'=>$fmachid, 'fname'=>$fname, 'floc'=>$floc, 
            'faddress'=>$faddress, 'fpostcode'=>$fpostcode, 'fcity'=>$fcity, 'fprovince'=>$fprovince, 'fisland'=>$fisland);
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
        if($this->isWebAdmin()){
            if($fkey == NULL)
            {
                redirect($this->cname.'/view');
            }

            $this->global['pageTitle'] = "Edit Data ATM Location - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data ATM Location';
            $this->global['contentHeader'] = 'Edit Data ATM Location';
            $this->global['contentTitle'] = 'Edit Data ATM Location';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['records'] = $this->get_edit($fkey);
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
        $fmachid = $this->input->post('fmachid', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $floc = $this->input->post('floc', TRUE);
        $faddress = $this->input->post('faddress', TRUE);
        $fpostcode = $this->input->post('fpostcode', TRUE);
        $fcity = $this->input->post('fcity', TRUE);
        $fprovince = $this->input->post('fprovince', TRUE);
        $fisland = $this->input->post('fisland', TRUE);

        $dataInfo = array('fid'=>$fid, 'fmachid'=>$fmachid, 'fname'=>$fname, 'floc'=>$floc, 
            'faddress'=>$faddress, 'fpostcode'=>$fpostcode, 'fcity'=>$fcity, 'fprovince'=>$fprovince, 'fisland'=>$fisland);        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_'.$this->cname), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect($this->cname.'/view');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect($this->cname.'/edit/'.$fid);
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