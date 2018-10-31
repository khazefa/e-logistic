<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CSearch (CSearchController)
 * CSearch Class to control Data Parts.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CSearch extends BaseController
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
     * This function is used to get lists for populate data
     */
    public function get_list_data_wh_stock(){
        $rs = array();
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $tbl = filter_var($r->table_name, FILTER_SANITIZE_STRING);
            $s_tbl = explode("_", $tbl);
            
            $row['fsl'] = strtoupper($s_tbl[3]);
 
            if($row['fsl'] !== "CID4" AND $row['fsl'] !== "WSPS"){
                $data[] = strtoupper($s_tbl[3]);
            }
        }
        
        return $data;
    }
    
    /**
     * This function is used to get detail information
     */
    private function get_info_warehouse_name($fcode){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $wh_name = "";
        foreach ($rs as $r) {
            $wh_name = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
        }
        
        return $wh_name;
    }
    
    /**
     * This function is used to get lists for populate data
     */
    public function get_list_part(){
        $rs = array();
        $arrWhere = array();
        
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartname = $this->input->post('fpartname', TRUE);

        if ($fpartnum != "") $arrWhere['fpartnum'] = $fpartnum;
        if ($fpartname != "") $arrWhere['fname'] = $fpartname;
        
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
    private function get_info_part_name($fpartnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fpartnum'=>$fpartnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $partname = "";
        foreach ($rs as $r) {
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
        }
        
        return $partname;
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function search_part_number_f()
    {
        $this->global['pageTitle'] = 'Search Part Number in FSL - '.APP_NAME;
        $this->global['pageMenu'] = 'Search Part Number in FSL';
        $this->global['contentHeader'] = 'Search Part Number in FSL';
        $this->global['contentTitle'] = 'Search Part Number in FSL';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_part'] = $this->get_list_part();
        $data['list_coverage'] = $this->get_list_warehouse();        
        $this->loadViews('front/search-data/v_partnum', $this->global, $data);
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function search_part_number_e()
    {
        $this->global['pageTitle'] = 'Search Part Number in Engineer - '.APP_NAME;
        $this->global['pageMenu'] = 'Search Part Number in Engineer';
        $this->global['contentHeader'] = 'Search Part Number in Engineer';
        $this->global['contentTitle'] = 'Search Part Number in Engineer';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['list_part'] = $this->get_list_part();
        $data['list_coverage'] = $this->get_list_warehouse();        
        $this->loadViews('front/search-data/v_partnum_e', $this->global, $data);
    }
    
    /**
     * This function is used to get lists for populate data
     */
    public function get_list_warehouse(){
        $rs = array();
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_nearby = array();
        $names = '';
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
 
            if($row['code'] !== "CID4" AND $row['code'] !== "WSPS"){
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    public function get_list_datatable_fsl(){
        $rs = array();
        $arrWhere = array();
        
        $fcoverage = !empty($_POST['fcoverage']) ? implode(';',$_POST['fcoverage']) : "";
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $active_fsl = $this->get_list_data_wh_stock();
        
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        $data = array();
        foreach($e_coverage AS $e){
            if(in_array(trim(strtoupper($e)), $active_fsl)){
                $arrWhere = array('fcode'=> trim(strtoupper($e)), 'fpartnum'=>$fpartnum);
                //Parse Data for cURL
    //            $rs_data = send_curl($arrWhere, $this->config->item('api_list_fsl_stock'), 'POST', FALSE);
                $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
                $rs = $rs_data->status ? $rs_data->result : array();

                foreach ($rs as $r) {
                    $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
                    $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
                    $fslname = $this->get_info_warehouse_name($code);
                    $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
                    $partname = $this->get_info_part_name($partno);
                    $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                    $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                    $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                    $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);

                    $row['code'] = $code;
                    $row['name'] = $fslname;
                    $row['partno'] = $partno;
                    $row['partname'] = $partname;
                    $row['minstock'] = $minstock;
                    $row['initstock'] = $initstock;
                    if($initflag === "Y"){
                        $row['stock'] = $initstock;
                    }else{
                        $row['stock'] = $stock;
                    }
                    $row['initflag'] = $initflag;

                    $data[] = $row;
                }
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    public function get_list_datatable_eg(){
        $rs = array();
        $arrWhere = array();
        
        $fcoverage = !empty($_POST['fcoverage']) ? implode(';',$_POST['fcoverage']) : "";
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $active_fsl = $this->get_list_data_wh_stock();
        
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        $data = array();
        foreach($e_coverage AS $e){
            if(in_array(trim(strtoupper($e)), $active_fsl)){
                $arrWhere = array('fcode'=> trim(strtoupper($e)), 'fpartnum'=>$fpartnum);
                //Parse Data for cURL
                $rs_data = send_curl($arrWhere, $this->config->item('api_list_history_part_e'), 'POST', FALSE);
                $rs = $rs_data->status ? $rs_data->result : array();

                foreach ($rs as $r) {                    
                    $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
                    $ticketnum = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
                    $eg_name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
                    $partner = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
                    $eg_name_2 = filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
//                    $purpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
//                    $fe_report = filter_var($r->fe_report, FILTER_SANITIZE_STRING);
                    $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                    $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
//                    $return_status = filter_var($r->return_status, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);

                    $row['partnum'] = $partnum;
                    $row['serialnum'] = $serialnum;
                    $row['fullname'] = $eg_name;
                    $row['partner'] = $partner;
                    $row['fullname_2'] = $eg_name_2;
                    $row['transdate'] = $transdate;
                    $row['ticket'] = $ticketnum;
                    $row['fsl'] = $fslname;
                    $row['transnum'] = $transnum;
                    $row['qty'] = $qty;

                    $data[] = $row;
                }
            }
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
}