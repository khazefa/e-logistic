<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CSupplyFromCWH (CSupplyFromCWHController)
 * CSupplyFromCWH Class to control supplied parts from FSL to Central Warehouse(CWH).
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CSupplyFromCWH extends BaseController
{
    private $field_modal = array(
        'trans_num' => 'Trans Num',
        'trans_date' => 'Trans Date',
        'fsl_name' => 'To FSL',
        'trans_notes' => 'Notes',
        'trans_purpose' => 'Purpose',
        'airwaybill' => 'Airway Bill',
        'delivery_by' => 'Delivered By',
        'delivery_type' => 'Service',
        'eta' => 'ETA'
    );

    private $field_value = array(
        'trans_num' => 'delivery_note_num',
        'trans_date' => 'delivery_note_date',
        'fsl_name' => 'fsl_name',
        'trans_notes' => 'delivery_note_notes',
        'trans_purpose' => 'delivery_note_purpose',
        'airwaybill' => 'delivery_note_airwaybill',
        'delivery_by' => 'delivery_by',
        'delivery_type' => 'delivery_time_type',
        'eta' => 'delivery_note_eta'
    );
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if(!$this->isStaff()){
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {        
        $this->global['pageTitle'] = 'Supply from Central Warehouse - '.APP_NAME;
        $this->global['pageMenu'] = 'Supply from Central Warehouse';
        $this->global['contentHeader'] = 'Supply from Central Warehouse';
        $this->global['contentTitle'] = 'Supply from Central Warehouse';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $data['field_modal_popup'] = $this->field_modal;
        $data['field_modal_js'] = $this->field_value;
        $this->loadViews('front/supply-from-cwh/index', $this->global, $data);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_view_datatable(){ 
        $rs = array();
        $arrWhere = array();
        
        //Parameters for cURL
        $fcode = $this->repo;
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode, 'fdate1'=>$fdate1, 'fdate2'=>$fdate2, 'fstatus'=>$fstatus);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_delivery_note'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->delivery_note_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->date, FILTER_SANITIZE_STRING);
            $purpose = filter_var($r->delivery_note_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->delivery_note_qty, FILTER_SANITIZE_NUMBER_INT);
            $user = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->delivery_note_notes, FILTER_SANITIZE_STRING);
            $status = filter_var($r->delivery_note_status, FILTER_SANITIZE_STRING);
            $fsl_code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $curdatetime = new DateTime();
            $datetime2 = new DateTime($transdate);
            $interval = $curdatetime->diff($datetime2);
//            $elapsed = $interval->format('%a days %h hours');
            $elapsed = $interval->format('%a days');
            switch ($purpose){
                case "SPL";
                    $purpose = "Supply";
                break;
                
                default;
                    $purpose = "-";
                break;
            }

            $row['transnum'] = $transnum;
            $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
            
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user;
            $row['notes'] = $notes;
            $row['status'] = $status === "open" ? strtoupper($status)."<br> (".$elapsed.")" : strtoupper($status);
            $row['button'] = '
            <a href="'.base_url("print-delivery-note-trans/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>
            <a href="javascript:viewdetail(\''.$transnum.'\');"><i class="mdi mdi-information mr-2 text-muted font-18 vertical-middle"></i></a>
            ';
            $data[] = $row;
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
    public function add() {
        $this->global['pageTitle'] = 'Add Supply from Central Warehouse - '.APP_NAME;
        $this->global['pageMenu'] = 'Add Supply from Central Warehouse';
        $this->global['contentHeader'] = 'Add Supply from Central Warehouse';
        $this->global['contentTitle'] = 'Add Supply from Central Warehouse';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;

        $this->loadViews('front/supply-from-cwh/create', $this->global, NULL);
    }
    
    /**
     * This function is used to get view information
     */
    public function get_view_delivery_note(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $ftrans_out = $this->input->get('ftrans_out', TRUE);
        
        if(!empty($ftrans_out)){
            //Parameters for cURL
            $arrWhere = array('ftransnum'=>$ftrans_out);
        
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_get_delivery_note_get_trans'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
        }else{
            $rs = array();
            $arrWhere = array();
        }
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->delivery_note_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->date, FILTER_SANITIZE_STRING);
            $transticket = filter_var($r->delivery_note_airwaybill, FILTER_SANITIZE_STRING);
            $total_qty = filter_var($r->delivery_note_qty, FILTER_SANITIZE_NUMBER_INT);
            $fpurpose = filter_var($r->delivery_note_purpose, FILTER_SANITIZE_STRING);
            $status = filter_var($r->delivery_note_status, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->delivery_note_notes, FILTER_SANITIZE_STRING);
            $requestby = "";
            $takeby = "";
            $purpose = "";
            $curdatetime = new DateTime();
            $datetime2 = new DateTime($transdate);
            $interval = $curdatetime->diff($datetime2);
//            $elapsed = $interval->format('%a days %h hours');
            $elapsed = $interval->format('%a days');
            
            switch ($fpurpose){
                case "SPL";
                    $purpose = "Supply";
                break;
                default;
                    $purpose = "-";
                break;
            }
            
            $row['transnum'] = $transnum;
            $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
            $row['transticket'] = $transticket;
            $row['purpose'] = $purpose;
            $row['qty'] = $total_qty;
            $row['status'] = $status === "open" ? strtoupper($status)." (".$elapsed.")" : strtoupper($status);
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get list detail for datatables
     */
    public function get_view_delivery_note_detail(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $ftrans_out = $this->input->get('ftrans_out', TRUE);
        
        if(!empty($ftrans_out)){
            //Parameters for cURL
            $arrWhere = array('ftransnum'=>$ftrans_out);
        
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_get_delivery_note_get_trans_detail'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();
        }else{
            $rs = array();
            $arrWhere = array();
        }
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->delivery_note_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->date, FILTER_SANITIZE_STRING);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->dt_delivery_note_qty, FILTER_SANITIZE_NUMBER_INT);
            $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
            $isdeleted = $deleted < 1 ? "N" : "Y";
            
            if($isdeleted === "N"){
                $row['transnum'] = $transnum;
                $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
                $row['partnum'] = $partnum;
                $row['partname'] = $partname;
                $row['serialnum'] = $serialnum;
                $row['qty'] = $qty;
                $row['deleted'] = $isdeleted;
 
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
     * This function is used to check outgoing transaction
     */
    public function check_supply_from_cwh(){
        $rs = array();
        $arrWhere = array();
        $global_response = array();
        $success_response = array();
        $error_response = array();
        
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $arrWhere = array('ftransnum'=>$ftrans_out);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_get_delivery_note_get_trans'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $total_qty = 0;
            $purpose = "";
            $status = "";
            foreach ($rs as $r){
                $total_qty = filter_var($r->delivery_note_qty, FILTER_SANITIZE_NUMBER_INT);
                $purpose = filter_var($r->delivery_note_purpose, FILTER_SANITIZE_STRING);
                $status = filter_var($r->delivery_note_status, FILTER_SANITIZE_STRING);
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
    public function submit_trans_close_transfer(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to submit transaction'
        );
                    
        $date = date('Y-m-d');
        $fcode = $this->repo;
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);

        //update delivery note status by trans number
        $updateTrans = array('ftrans_out'=>$ftrans_out, 'fnotes'=>$fnotes, 'fstatus'=>'complete');
        $update_status_trans = send_curl($this->security->xss_clean($updateTrans), 
                $this->config->item('api_update_delivery_note_trans'), 'POST', FALSE);

        if($update_status_trans->status){
            //Parameters for cURL
            $arrWhere = array('ftransnum'=>$ftrans_out);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_get_delivery_note_get_trans_detail'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            $dataDetail = array();
            $total_qty = 0;
            $partstock = 0;
            if(!empty($rs)){
                foreach ($rs as $r) {
                    $transnum = filter_var($r->delivery_note_num, FILTER_SANITIZE_STRING);
                    $transdate = filter_var($r->date, FILTER_SANITIZE_STRING);
                    $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                    $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->dt_delivery_note_qty, FILTER_SANITIZE_NUMBER_INT);
                    $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
                    $isdeleted = $deleted < 1 ? "N" : "Y";

                    $partstock = $this->get_stock($fcode, $partnum);
                    $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$partnum, 'fqty'=>(int)$partstock+(int)$qty, 'fflag'=>'N');
                    //update stock by fsl code and part number
                    $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                            'POST', FALSE);
                }
            }
            $response = $success_response;
        }else{
            $response = $error_response;
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
    private function get_stock($fcode, $partnum){
        $rs = array();
        $arrWhere = array();
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $stock = 0;
        foreach ($rs as $r) {
            $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
            $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
            $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
            $minval = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            if($initflag === "Y"){
                $stock = $initstock;
            }else{
                $stock = $stock;
            }
        }
        
        return $stock;
    }
}