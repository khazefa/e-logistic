<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CSupplyFromFSL (CSupplyFromFSLController)
 * CSupplyFromFSL Class to control supplied parts from FSL to Central Warehouse(CWH).
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */

class CSupplyFromFSLBad extends BaseController{
    private $apirole = 'fsltocwh';
    private $alias_controller_name = 'fsltocwh';
    private $field_modal = array(
        'trans_num' => 'Trans Num',
        'trans_date' => 'Trans Date',
        'fsl_name' => 'From FSL',
        'trans_notes' => 'Notes',
        'trans_purpose' => 'Purpose',
        'airwaybill' => 'Airway Bill',
        'delivery_by' => 'Delivered By',
        'delivery_type' => 'Service',
        'eta' => 'ETA'
    );
    private $field_purpose = array(
        'RBP' => 'Return Bad Part',
        'RBS' => 'Return Bad Stock' 
    );
    private $field_value = array();

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        // if(!$this->isWebAdmin()){
        //     redirect('cl');
        // }
        $this->field_value = array(
            'trans_num' => $this->apirole.'_num',
            'trans_date' => $this->apirole.'_date',
            'fsl_name' => 'fsl_name',
            'trans_notes' => $this->apirole.'_notes',
            'trans_purpose' => $this->apirole.'_purpose',
            'airwaybill' => $this->apirole.'_airwaybill',
            'delivery_by' => 'delivery_by',
            'delivery_type' => 'delivery_time_type',
            'eta' => $this->apirole.'_eta'
        );
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {        
        $this->global['pageTitle'] = 'Supply from FSL BAD - '.APP_NAME;
        $this->global['pageMenu'] = 'Supply from FSL BAD';
        $this->global['contentHeader'] = 'Supply from FSL BAD';
        $this->global['contentTitle'] = 'Supply from FSL BAD';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $data['field_modal_popup'] = $this->field_modal;
        $data['field_modal_js'] = $this->field_value;
        $data['field_purpose'] = $this->field_purpose;
        $data['link_modal_detail'] = base_url('api-'.$this->alias_controller_name.'-get-trans-detail');
        $data['link_modal'] = base_url('api-'.$this->alias_controller_name.'-get-trans');
        $data['list_coverage'] = $this->get_list_warehouse("array");
        $this->loadViews('front/supply-from-fsl-bad/index', $this->global, $data);
    }
    
    /**
     * This function is used to get lists fsl
     */
    public function get_list_warehouse($output = "json"){
        $rs = array();
        $arrWhere = array();
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
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
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_view_datatable(){ 
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_fsltocwh_close'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->fsltocwh_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->date, FILTER_SANITIZE_STRING);
            $transclose = filter_var($r->date_close, FILTER_SANITIZE_STRING);
            $status = filter_var($r->fsltocwh_status, FILTER_SANITIZE_STRING);
            $lpurpose = filter_var($r->fsltocwh_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->fsltocwh_qty, FILTER_SANITIZE_NUMBER_INT);
            $user = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->fsltocwh_notes, FILTER_SANITIZE_STRING);
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
            $row['transclose'] = date('d/m/Y H:i', strtotime($transclose));
            $row['status'] = $status;
            $row['fsl_code'] =$fsl_code;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user;
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
    
    /**
     * This function is used to load the add new form
     */
    public function add()
    {
        $this->global['pageTitle'] = 'Add Supply from FSL BAD - '.APP_NAME;
        $this->global['pageMenu'] = 'Add Supply from FSL BAD';
        $this->global['contentHeader'] = 'Add Supply from FSL BAD';
        $this->global['contentTitle'] = 'Add Supply from FSL BAD';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $data['list_part'] = $this->get_list_part();
        $this->loadViews('front/supply-from-fsl-bad/create', $this->global, $data);
    }
    
    /**
     * This function is used to check outgoing transaction
     */
    public function check_outgoing_trf(){
        $rs = array();
        $arrWhere = array();
        $global_response = array();
        $success_response = array();
        $error_response = array();
        
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $arrWhere = array('ftrans_out'=>$ftrans_out);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $total_qty = 0;
            $purpose = "";
            $status = "";
            foreach ($rs as $r){
                $total_qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $purpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
                $status = filter_var($r->outgoing_status, FILTER_SANITIZE_STRING);
            }
            if($status == "complete"){
                $global_response = array(
                    'status' => 0,
                    'total_qty' => 0,
                    'message'=> 'Transaction is already complete, you cannot close this transaction twice.'
                );
            }else{
                $global_response = array(
                    'status' => 1,
                    'purpose' => $purpose,
                    'total_qty' => $total_qty,
                    'message'=> 'Transaction still open'
                );
            }
            $response = $global_response;
        }else{
            $error_response = array(
                'status' => 0,
                'total_qty'=> 0,
                'message'=> 'Transaction is not available'
            );
            $response = $error_response;
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to complete close transfer stock transaction
     */
    public function close_trans(){
        $arrWhere = array();
        $rs_check = array();
        $rs_closing = array();
        $reponse = array(
            'status' => FALSE
        );
        
        $date = date('Y-m-d');
        $ftransnum = $this->input->post('ftransnum', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $fuser = $this->vendorUR;
      
        if($ftransnum != ''){ // check if ftransnum is not defined
            $arrWhere['ftransnum'] = $ftransnum;
            $arrWhere['fnotes'] = $fnotes;
            $arrWhere['fuser'] = $fuser;

            //check
            $rs_check = send_curl($arrWhere, $this->config->item('api_get_fsltocwh_get_trans'), 'POST', FALSE);
            $rs = $rs_check->status ? $rs_check->result : array();
            if($rs){ // check if transaction data has been success to retrive
                if(count($rs)>0){// check if transaction data is not empty
                    $data_check = $rs_check->result[0];
                    if($data_check->fsltocwh_status == 'open'){ //check if transaction data is opened
                        $rs_closing = send_curl($arrWhere, $this->config->item('api_update_fsltocwh_closing_trans'), 'POST', FALSE);
                        $rs_c = $rs_closing->status ? $rs_closing->result : array();
                        if(!empty($rs_c)){ //check if closing data is success
                            $response = array(
                                'status' => TRUE,
                                'message' => 'Closing Transaction '.$ftransnum.' Success.',
                                'result' => $rs_c

                            );
                        }else{
                            $response = array(
                                'status' => FALSE,
                                'message' => 'Failed to close transaction'
                            );
                        }
                    }else{
                        $response = array(
                            'status' => FALSE,
                            'message' => 'Failed to close transaction, Transaction has been closed before.'
                        );
                    }
                }else{
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Failed to close transaction, '.$ftransnum.' not found.'
                    );
                }
            }else{
                $response = array(
                    'status' => FALSE,
                    'message' => 'Failed to close transaction, Error Retreive Data.'
                );
            }
        }else{
            $response = array(
                'status' => FALSE,
                'message' => 'Please insert Transnumber.'
            );
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to get list information described by function name
     */
    private function get_info_part_stock($fcode, $partnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
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
            
            if($initflag === "Y"){
                $row['stock'] = $initstock;
            }else{
                $row['stock'] = $stock;
            }
 
            $data[] = $row;
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $wh_name = "";
        foreach ($rs as $r) {
            $wh_name = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
        }
        
        return $wh_name;
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

    public function get_trans_detail(){
        $rs = array();
        $arrWhere = array();
        
        $ftransnum = $this->input->post('ftransnum', TRUE);
        $arrWhere = array('ftransnum'=>$ftransnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_get_fsltocwh_get_trans_detail'), 'POST', FALSE);
        //var_dump($rs_data ); 
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach($rs as $r){
            $transid = filter_var($r->id, FILTER_SANITIZE_STRING);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->dt_fsltocwh_qty, FILTER_SANITIZE_NUMBER_INT);
            $transnum = filter_var($r->fsltocwh_num, FILTER_SANITIZE_STRING);
            $dtnotes = filter_var($r->dt_notes, FILTER_SANITIZE_STRING);
            $select_option = array(
                '' => 'OK',
                'diff_partnumber' => 'Diff PN',
                'diff_serialnumber' => 'Diff SN',
                'diff_pn_and_sn' => 'Diff PN & SN',
                'doesnt_exist' => 'Not Exists',
            );  
            $select = form_dropdown('act_notes['.$transid.']', $select_option, $dtnotes, 'id="act_notes_'.$transid.'" class="selectpicker"');

            $row['dtnotes'] = $dtnotes;
            $row['partnum'] = $partnum;
            $row['partname'] = $partname;
            $row['serialnum'] = $serialnum;
            $row['qty'] = $qty;
            $row['select_dt_notes'] = $select;
            $row['transid'] = $transid;

            $data[] = $row;
        }
        

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }

    public function update_notexist(){
        $rs = array();
        $arrWhere = array();
        $response = array(
            'status' => false,
            'message' => 'No Action.'
        );
        $fid = $this->input->post('fid', TRUE);
        $faction = $this->input->post('faction', TRUE);
        if($fid!='')$arrWhere['fid']=$fid;
        if($faction!='')$arrWhere['fnotes']=$faction;
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_fsltocwh_different_detail'), 'POST', FALSE);
        $message = $rs_data->status ? $rs_data->message : '';

        if($rs_data->status){
            $response = array(
                'status' => true,
                'message' => $message
            );
        }else{
            $response = array(
                'status' => false,
                'message' => $message
            );
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }

    public function update_diff(){
        $rs = array();
        $arrWhere = array();
        $response = array(
            'status' => false,
            'message' => 'No Action.'
        );

        $fid = $this->input->post('fid', TRUE);
        $fnewpn = $this->input->post('fnewpn', TRUE);
        $fnewsn = $this->input->post('fnewsn', TRUE);
        $faction = $this->input->post('faction', TRUE);

        if($fid!='')$arrWhere['fid']=$fid;
        if($fnewpn!='')$arrWhere['fnewpn']=$fnewpn;
        if($fnewsn!='')$arrWhere['fnewsn']=$fnewsn;
        if($faction!='')$arrWhere['faction']=$faction;

        $rs_data = send_curl($arrWhere, $this->config->item('api_update_fsltocwh_different_detail'), 'POST', FALSE);
        //var_dump($rs_data ); 
        $message = $rs_data->status ? $rs_data->message : '';

        if($rs_data->status){
            $response = array(
                'status' => true,
                'message' => $message
            );
        }else{
            $response = array(
                'status' => false,
                'message' => $message
            );
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    

    /**
     * This function is used to get lists for populate data
     */
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

}