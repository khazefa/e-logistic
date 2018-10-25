<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CHistoryTrans (HistoryTransController)
 * CHistoryTrans Class to control Transaction History.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CHistoryTrans extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if(!$this->isSuperAdmin()){
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect('cl');
    }
    
    public function history_outgoing(){
        $this->global['pageTitle'] = 'History Outgoing Transaction - '.APP_NAME;
        $this->global['pageMenu'] = 'History Outgoing Transaction';
        $this->global['contentHeader'] = 'History Outgoing Transaction';
        $this->global['contentTitle'] = 'History Outgoing Transaction';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/history-trans/index_outgoing', $this->global, NULL);
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
        $ftransnum = $this->input->post('ftransnum', TRUE);
        $fticket = $this->input->post('fticket', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        //Parameters for cURL
        $arrWhere = array('fdate1'=>$fdate1, 'fdate2'=>$fdate2, 'ftrans_out'=>$ftransnum, 
            'fticket'=>$fticket, 'fpurpose'=>$fpurpose, 'fstatus'=>$fstatus);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings_history'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transid = filter_var($r->outgoing_id, FILTER_SANITIZE_NUMBER_INT);
            $enc_transid = md5($transid);
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
//            $transdate = filter_var($r->outgoing_date, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
            $transticket = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
            $engineer = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $engineer_name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $engineer2 = filter_var($r->engineer_2_key, FILTER_SANITIZE_STRING);
            $engineer2_name = filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
            $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
            $status = filter_var($r->outgoing_status, FILTER_SANITIZE_STRING);
            $requestby = "";
            $takeby = "";
            $purpose = "";
            $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
            
            if(empty($engineer2) || $engineer2 == ""){
                $requestby = $engineer_name;
                $takeby = "-";
            }else{
                $requestby = $engineer_name;
                $takeby = $engineer2_name;
            }
            
            switch ($fpurpose){
                case "SP";
                    $purpose = "Sales/Project";
                break;
                case "W";
                    $purpose = "Warranty";
                break;
                case "M";
                    $purpose = "Maintenance";
                break;
                case "I";
                    $purpose = "Investments";
                break;
                case "B";
                    $purpose = "Borrowing";
                break;
                case "RWH";
                    $purpose = "Transfer Stock";
                break;
                default;
                    $purpose = "-";
                break;
            }
            
            $isdeleted = $deleted < 1 ? "N" : "Y";
            
            $row['transnum'] = $transnum;
            $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
            $row['transticket'] = $transticket;
            $row['reqby'] = $requestby;
            $row['takeby'] = $takeby;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user_fullname;
//            $row['notes'] = "-";
            $row['status'] = strtoupper($status);
            $row['deleted'] = $isdeleted;
            
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-outgoing/").$enc_transid.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-outgoing-trans/").$enc_transid.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
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
     * This function is used to get list for datatables
     */
    public function get_detail_outgoing_datatable($fkey){
        $rs = array();
        //Parameters for cURL
        $arrWhere = array();
        $data = array();
        
        if(empty($fkey)){
            $data = array();
        }else{
            $arrWhere = array('ftrans_out'=>$fkey);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            foreach ($rs as $r) {
                $id = filter_var($r->dt_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
                $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                $qty = filter_var($r->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $returnstatus = filter_var($r->return_status, FILTER_SANITIZE_STRING);  
                $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
                $isdeleted = $deleted < 1 ? "N" : "Y";

                $row['id'] = $id;
                $row['partno'] = $partnum;
                $row['partname'] = $partname;
                $row['serialno'] = $serialnum;
                $row['qty'] = (int)$qty;
                $row['return'] = $returnstatus !== "" ? $returnstatus : "USED";
//                $row['deleted'] = $isdeleted;

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
     * This function is used to delete task
     */
    public function delete_task(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to delete data'
        );
        
        $fid = $this->input->post('fid', TRUE);

        $arrWhere = array('fid'=>$fid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_outgoings_trans_detail'), 'POST', FALSE);

        if($rs_data->status)
        {
            $response = $success_response;
        }
        else
        {
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
    public function get_outgoing($fkey){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fid'=>$fkey);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_view_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        
        $transnum = "";
        $purpose = "";
        $transdate = "";
        $ticket = "";
        $partner = "";
        $engineer_id = "";
        $engineer2_id = "";
        $engineer_name = "";
        $engineer_mess = "";
        $engineer_sign = "";
        $fpurpose = "";
        $customer = "";
        $location = "";
        $ssb_id = "";
        $fslname = "";
        foreach ($rs as $r) {
            $transid = filter_var($r->outgoing_id, FILTER_SANITIZE_NUMBER_INT);
            $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
            switch ($fpurpose){
                case "SP";
                    $purpose = "Sales/Project";
                break;
                case "W";
                    $purpose = "Warranty";
                break;
                case "M";
                    $purpose = "Maintenance";
                break;
                case "I";
                    $purpose = "Investments";
                break;
                case "B";
                    $purpose = "Borrowing";
                break;
                case "RWH";
                    $purpose = "Transfer Stock";
                break;
                default;
                    $purpose = "-";
                break;
            }
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $ticket = $r->outgoing_ticket == "" ? "-" : filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
            $transdate = date("d/m/Y H:i:s", strtotime(filter_var($r->created_at, FILTER_SANITIZE_STRING)));
            $partner = $r->partner_name == "" ? "-" : filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $engineer_id = $r->engineer_key == "" ? "-" : filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $engineer2_id = $r->engineer_2_key == "" ? "-" : filter_var($r->engineer_2_key, FILTER_SANITIZE_STRING);
            $engineer_name = $r->engineer_name == "" ? "-" : filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            if(!empty($engineer2_id)){
                $engineer_mess = $r->engineer_2_name == "" ? "-" : filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
                $engineer_sign = $r->engineer_2_name == "" ? $engineer_name : filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
            }else{
                $engineer_sign = $engineer_name;
            }
            $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $notes = $r->outgoing_notes == "" ? "-" : filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
            $customer = $r->outgoing_cust == "" ? "-" : filter_var($r->outgoing_cust, FILTER_SANITIZE_STRING);
            $location = $r->outgoing_loc == "" ? "-" : filter_var($r->outgoing_loc, FILTER_SANITIZE_STRING);
            $ssb_id = $r->outgoing_ssbid == "" ? "-" : filter_var($r->outgoing_ssbid, FILTER_SANITIZE_STRING);
 
            $row["fid"] = $transid;
            $row["fpurpose"] = $purpose;
            $row["ftransnum"] = $transnum;
            $row["fticket"] = $ticket;
            $row["ftransdate"] = $transdate;
            $row["feg"] = $engineer_id;
            $row["feg_name"] = $engineer_sign;
            $row["feg_mess"] = $engineer_mess;
            $row["fpartner"] = $partner;
            $row["fcustomer"] = $customer;
            $row["flocation"] = $location;
            $row["fssbid"] = $ssb_id;
            $row["ffsl"] = $fslcode;
            $row["ffslname"] = $fslname;
            
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used load edit transaction outgoing
     * @param $fkey : Optional : This is data unique key
     */
    function edit_outgoing($fkey = NULL)
    {
        if($this->isSuperAdmin()){
            if($fkey == NULL)
            {
                redirect('history-outgoing');
            }
            
            $dec_transid = md5($fkey);
            $this->global['pageTitle'] = "Edit Data Outgoing - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Outgoing';
            $this->global['contentHeader'] = 'Edit Data Outgoing';
            $this->global['contentTitle'] = 'Edit Data Outgoing';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['records'] = $this->get_outgoing($dec_transid);

            $this->loadViews('front/history-trans/edit', $this->global, $data);
        }else{
            redirect('cl');
        }
    }
}