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
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['partno'] = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['serialno'] = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            
            $parentpart = filter_var($r->parent_part_id, FILTER_SANITIZE_NUMBER_INT);
            $subtitutepart = filter_var($r->part_id_sub, FILTER_SANITIZE_NUMBER_INT);
            $parttype = filter_var($r->part_type_id, FILTER_SANITIZE_NUMBER_INT);
            $row['type'] = $parttype;
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
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-spareparts/").$row['serialno'].'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-spareparts/").$row['serialno'].'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
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
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($data)
        );
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
}