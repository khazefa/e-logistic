<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : COutgoing (TicketsController)
 * COutgoing Class to control Tickets.
 * @author : Aris Baskoro
 * @version : 1.0
 * @since : August 2018
 */
class CSupplyToRepair extends BaseController
{
    /**
     * ////////////////////////////////////////////////
     * PRIVATE VARIABLE
     * ////////////////////////////////////////////////
     */
    private $apirole = 'repairorder';
    private $alias_controller_name = 'supply-to-repair';
    private $field_modal = array(
        'trans_num' => 'Trans Num',
        'trans_date' => 'Trans Date',
        'fsl_name' => 'From FSL',
        'trans_notes' => 'Notes',
        'trans_purpose' => 'Purpose',
    );
    private $field_purpose = array(
        'RBP' => 'Return Bad Part',
        'RBS' => 'Return Bad Stock' 
    );
    private $field_value = array();
    private $diff_option = array(
        '' => 'OK',
        'diff_partnumber' => 'Diff PN',
        'diff_serialnumber' => 'Diff SN',
        'no_physic' => 'No Physic',
    );  

    /**
     * ////////////////////////////////////////////////
     * PRIVATE FUNCTION
     * ////////////////////////////////////////////////
     */

    private function get_list_warehouse($output = "json"){
        $rs = array();
        $arrWhere = array();
        $data = array();
        
        $arrWhere = array('fdeleted'=>0, 'flimit'=>0);
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
                
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = $this->common->nohtml($r->fsl_name);
 
            $data[] = $row;
        }
        switch ($output){
            case "json":
                return $this->output
                ->set_content_type('application/json')
                ->set_output(
                    json_encode($data)
                );
            break;
            case "array":
                return $data;
            break;
            default:
                return $this->output
                ->set_content_type('application/json')
                ->set_output(
                    json_encode($data)
                );
            break;
        }
    }

    private function get_list_part(){
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
     * ////////////////////////////////////////////////
     * PUBLIC FUNCTION FORM
     * ////////////////////////////////////////////////
     */

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->field_value = array(
            'trans_num' => $this->apirole.'_num',
            'trans_date' => $this->apirole.'_date',
            'fsl_name' => 'fsl_name',
            'trans_notes' => $this->apirole.'_notes',
            'trans_purpose' => $this->apirole.'_purpose',
        );
    }

    public function index(){
        $this->global['pageTitle'] = 'Repair Order - '.APP_NAME;
        $this->global['pageMenu'] = 'Repair Order';
        $this->global['contentHeader'] = 'Repair Order';
        $this->global['contentTitle'] = 'Repair Order';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $data['link_add'] = base_url('new-'.$this->alias_controller_name.'-trans');
        $data['list_coverage'] = $this->get_list_warehouse("array");
        $data['link_get_data'] = base_url('api-'.$this->alias_controller_name.'-get-datatable');
        $data['link_modal_detail'] = base_url('api-'.$this->alias_controller_name.'-get-trans-detail');
        $data['link_modal'] = base_url('api-'.$this->alias_controller_name.'-get-trans');
        $data['field_modal_popup'] = $this->field_modal;
        $data['field_modal_js'] = $this->field_value;
        $data['field_purpose'] = $this->field_purpose;
        $this->loadViews('front/'.$this->alias_controller_name.'/index', $this->global, $data);
    }

    public function views() {
        $this->global['pageTitle'] = 'Repair Order - '.APP_NAME;
        $this->global['pageMenu'] = 'Repair Order';
        $this->global['contentHeader'] = 'Repair Order';
        $this->global['contentTitle'] = 'Repair Order';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $data['list_coverage'] = $this->get_list_warehouse("array");
        $data['link_get_data'] = base_url('api-'.$this->alias_controller_name.'-get-view-datatable');
        $data['link_modal_detail'] = base_url('api-'.$this->alias_controller_name.'-get-trans-detail');
        $data['link_modal'] = base_url('api-'.$this->alias_controller_name.'-get-trans');
        $data['field_modal_popup'] = $this->field_modal;
        $data['field_modal_js'] = $this->field_value;
        $data['field_purpose'] = $this->field_purpose;
        $this->loadViews('front/'.$this->alias_controller_name.'/view', $this->global, $data);
    }

    public function add() {        
        $this->global['pageTitle'] = 'Repair Order - '.APP_NAME;
        $this->global['pageMenu'] = 'Repair Order';
        $this->global['contentHeader'] = 'Repair Order';
        $this->global['contentTitle'] = 'Repair Order';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $data['link_datatable'] = base_url('api-'.$this->alias_controller_name.'-get-detail-trans');
        $data['link_verify'] = base_url('api-verify-'.$this->alias_controller_name);
        $data['link_submit'] = base_url('api-'.$this->alias_controller_name.'-submit-trans');
        $data['link_trans'] = base_url($this->alias_controller_name.'-trans');
        
        //$data['list_fsl'] = $this->get_list_warehouse('array');
        $data['list_part'] = $this->get_list_part();
        $this->loadViews('front/'.$this->alias_controller_name.'/create', $this->global, $data);
    }


    /**
     * ////////////////////////////////////////////////
     * PUBLIC FUNCTION API
     * ////////////////////////////////////////////////
     */

    public function get_list_datatable(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        $coverage = !empty($_POST['fcoverage']) ? implode(';',$_POST['fcoverage']) : "";

        $arrWhere = array('fdate1'=>$fdate1,'fdate2'=>$fdate2,'fstatus'=>$fstatus);

        $fpurpose = !empty($_POST['fpurpose']) ? implode(';',$_POST['fpurpose']) : "";
        
        
        if (strpos($coverage, 'C000') !== false) {
            $fcoverage = array();
        }else{
            if (strpos($coverage, ',') !== false) {
                $fcoverage = str_replace(',', ';', $coverage);
            }else{
                $fcoverage = $coverage;
            }
        }
        
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        

        if(empty($fpurpose)){
            $e_purpose = array();
        }else{
            $e_purpose = explode(';', $fpurpose);
        }
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->apirole), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->repairorder_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
            $status = filter_var($r->repairorder_status, FILTER_SANITIZE_STRING);
            $lpurpose = filter_var($r->repairorder_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->repairorder_qty, FILTER_SANITIZE_NUMBER_INT);
            //$user = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->repairorder_notes, FILTER_SANITIZE_STRING);
            $fsl_code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            switch ($lpurpose){
                case "RBP";
                    $purpose = "RETURN BAD PART";
                break;
                case "RBS";
                    $purpose = "RETURN BAD STOCK";
                break;
                
            }

            $row['transnum'] = $transnum;
            $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
            $row['status'] = $status;
            $row['fsl_code'] =$fsl_code;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            //$row['user'] = $user;
            $row['notes'] = $notes;
            $row['button'] = 
            //'<a href="'.base_url("print-fsltocwh-trans/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>'.
            '<a href="javascript:viewdetail(\''.$transnum.'\');"><i class="mdi mdi-information mr-2 text-muted font-18 vertical-middle"></i></a>';
            
            if((count($e_coverage)!=0) AND (count($e_purpose)!=0)){
                if(in_array($fsl_code, $e_coverage) AND in_array($lpurpose, $e_purpose)){
                    $data[] = $row;
                }
            }else if((count($e_coverage)!=0) || (count($e_purpose)!=0)){
                if(in_array($fsl_code, $e_coverage)){
                    $data[] = $row;
                }else if(in_array($lpurpose, $e_purpose)){
                    $data[] = $row;
                }
            }else{
                $data[] = $row;
            }

        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }

    public function check_part(){
        
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }

    public function get_info_part($fpartnum){
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

    private function get_info_part_stock($fcode, $partnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock_wh'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
            $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
            $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
            $minval = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            $row['code'] = $code;
            $row['partno'] = $partno;
            $row['warehouse'] = $this->get_info_warehouse_name($code);
            $row['part'] = $this->get_info_part_name($partno);
            $row['stock'] = $stock;
            $data[] = $row;
        }
        
        return $data;
    }

    public function get_trans_detail(){
        $rs = array();
        $arrWhere = array();
        
        $ftransnum = $this->input->post('ftransnum', TRUE);
        $arrWhere = array('ftransnum'=>$ftransnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_get_'.$this->apirole.'_get_trans_detail'), 'POST', FALSE);
        //var_dump($rs_data ); 
        $rs = $rs_data->status ? $rs_data->result : array();
        $rdata = (array)$rs;
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$rdata))
        );
    }

    /**
     * ////////////////////////////////////////////////
     * PUBLIC FUNCTION API CART
     * ////////////////////////////////////////////////
     */

    public function add_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed add data to cart'
        );
        
        $fcode = $this->repo;
        $fuser = $this->vendorUR;
        $fname = $this->name;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' )."ctor";
        $fqty = 1;
        
        $partname = "";
        $partstock = 0;
        //$rs_stock = $this->get_info_part_stock($fcode, $fpartnum);
        
        //foreach ($rs_stock as $s){
            //$partstock = (int)$s["stock"];
        //}
        $partname = $this->get_info_part_name($fpartnum);
        if(1 === $fqty){
            $cstock = 0;
            $cqty = 0;
            $cuser = "";
            $cname = "";
            
            $rs_cart_info = $this->get_info_cart($fpartnum, $fcode);
            
            if(!empty($rs_cart_info)){
                foreach($rs_cart_info as $c){
                    $cstock = (int)$c['stock'];
                    $cqty = (int)$c['qty'];
                    $cuser = $c['user'];
                    $cname = $c['fullname'];
                }
                if($cuser === $fuser){
                    $error_response = array(
                        'status' => 2,
                        'message'=> 'Part stock is limited!'
                    );
                }else{
                    $error_response = array(
                        'status' => 2,
                        'message'=> 'Stock is limited and part is already assigned to '.$cname
                    );
                }
                $response = $error_response;
            }else{
                $dataInfo = array(
                    'fpartnum'=>$fpartnum, 
                    'fpartname'=>$partname, 
                    'fserialnum'=>$fserialnum, 
                    'fcartid'=>$cartid, 
                    'fqty'=>$fqty, 
                    'fuser'=>$fuser, 
                    'fname'=>$fname, 
                    'fcode'=>$fcode
                );
                $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_'.$this->apirole.'_cart'), 'POST', FALSE);
                if($rs_data->status)
                {
                    $response = $success_response;
                }
                else
                {
                    $response = $error_response;
                }
            }
        }else{
            $dataInfo = array(
                'fpartnum'=>$fpartnum, 
                'fpartname'=>$partname, 
                'fserialnum'=>$fserialnum, 
                'fcartid'=>$cartid, 
                'fqty'=>$fqty, 
                'fuser'=>$fuser, 
                'fname'=>$fname, 
                'fcode'=>$fcode
            );
            $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_'.$this->apirole.'_cart'), 'POST', FALSE);
            if($rs_data->status)
            {
                $response = $success_response;
            }
            else
            {
                $response = $error_response;
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }

    /**
     * ////////////////////////////////////////////////
     * PUBLIC FUNCTION API SUBMIT
     * ////////////////////////////////////////////////
     */

    public function get_trans_detail_verify(){
        $rs = array();
        $arrWhere = array();
        $data = array();

        $ftransnum = $this->input->post('ftransnum',TRUE);
        $arrWhere = array('ftransnum' => $ftransnum);
        $rs_data = send_curl($arrWhere, $this->config->item('api_get_'.$this->apirole.'_get_trans_detail'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();

        $rdata = json_decode(json_encode($rs),TRUE);
        foreach($rdata as $d){
            $row = array();
            $row['partnum']     = filter_var($d['part_number'], FILTER_SANITIZE_STRING);
            $row['partname']    = filter_var($d['part_name'], FILTER_SANITIZE_STRING);
            $row['serialnum']   = filter_var($d['serial_number'], FILTER_SANITIZE_STRING);
            $row['qty']         = filter_var($d['dt_'.$this->apirole.'_qty'], FILTER_SANITIZE_STRING);
            $row['btn_diff']    = form_dropdown('act_notes['.$d['id'].']', $this->diff_option, $d['dt_notes'], 'id="act_notes_'.$d['id'].'" class="selectpicker"');
            $row['status']      = ($d['flag_process']=='DONE'?'<span class="badge badge-success">':'<span class="badge badge-warning">').$d['flag_process'].'</span>';
            $data[] = $row;
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }

    public function verify_data(){
        $response = array(
            'status' => FALSE,
            'message' => 'Unknown error, can\'t load api'
        );

        $ftransnum = $this->input->post('ftransnum',TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);

        if($ftransnum !== '' && $fpartnum !== '' && $fserialnum !== ''){ //parameter is mandatory
            $arrWhere = array(
                'ftransnum' => $ftransnum,
                'fpartnum'  => $fpartnum,
                'fserialnum'=> $fserialnum
            );
    
            $check_data = send_curl($arrWhere, $this->config->item('api_verify_'.$this->apirole), 'POST', FALSE);
            if($check_data->status){ //if check is return true
                $response = array(
                    'status' => TRUE,
                    'message' => 'Berhasil verifikasi'
                );
            }else{
                $response = array(
                    'status' => FALSE,
                    'message' => $check_data->message
                );
            }
    
        }else{
            $response = array(
                'status' => FALSE,
                'message' => 'Parameter can\'t be null'
            );
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
}