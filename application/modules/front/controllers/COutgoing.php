<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : COutgoing (TicketsController)
 * COutgoing Class to control Tickets.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class COutgoing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSpv() || $this->isStaff()){
            //
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {        
        if($this->isSpv()){
            redirect('view-outgoing-trans');
        }elseif($this->isStaff()){
            
            $this->global['pageTitle'] = 'Outgoing Transaction - '.APP_NAME;
            $this->global['pageMenu'] = 'Outgoing Transaction';
            $this->global['contentHeader'] = 'Outgoing Transaction';
            $this->global['contentTitle'] = 'Outgoing Transaction';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            
            $cartid = $this->session->userdata ( 'cart_session' )."ot";
            $data['cartid'] = $cartid;
            
            $this->loadViews('front/outgoing-trans/index', $this->global, $data);
            
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function views()
    {
        $this->global['pageTitle'] = 'Outgoing Transaction - '.APP_NAME;
        $this->global['pageMenu'] = 'Outgoing Transaction';
        $this->global['contentHeader'] = 'Outgoing Transaction';
        $this->global['contentTitle'] = 'Outgoing Transaction';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $this->loadViews('front/outgoing-trans/lists', $this->global, NULL);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->outgoing_date, FILTER_SANITIZE_STRING);
            $transticket = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
            $engineer = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $purpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $user = filter_var($r->user_key, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
            
            $row['transnum'] = $transnum;
            $row['transdate'] = $transdate;
            $row['transticket'] = $transticket;
            $row['engineer'] = $engineer;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user;
            $row['notes'] = $notes;
 
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
    public function get_list_view_datatable(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->outgoing_date, FILTER_SANITIZE_STRING);
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
            $purpose = "";
            
            if(empty($engineer2) || $engineer2 == ""){
                $requestby = $engineer_name;
            }else{
                $requestby = $engineer2_name;
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
            
            $row['transnum'] = $transnum;
            $row['transdate'] = tgl_indo($transdate);
            $row['transticket'] = $transticket;
            $row['reqby'] = $requestby;
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user_fullname;
//            $row['notes'] = "-";
            $row['status'] = strtoupper($status);
            
            if($this->isSpv()){
                $row['button'] = '<div class="btn-group dropdown">';
                $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-outgoing-trans/").$transnum.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-outgoing-trans/").$transnum.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
                $row['button'] .= '</div>';
                $row['button'] .= '</div>';
            }elseif($this->isStaff()){
                $row['button'] = '<a href="'.base_url("print-outgoing-trans/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
            }
 
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
    public function add()
    {        
        if($this->isSpv()){
            redirect('view-outgoing-trans');
        }elseif($this->isStaff()){
            
            $this->global['pageTitle'] = 'New Outgoing Transaction - '.APP_NAME;
            $this->global['pageMenu'] = 'New Outgoing Transaction';
            $this->global['contentHeader'] = 'New Outgoing Transaction';
            $this->global['contentTitle'] = 'New Outgoing Transaction';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            
            $data['list_eg'] = $this->get_list_engineers();
            $data['list_fsl'] = $this->get_list_warehouse('array');
            
            $this->loadViews('front/outgoing-trans/create', $this->global, $data);
            
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function is used to get lists engineers
     */
    private function get_list_engineers(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $arrWhere = array('fcode'=>$fcode);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $fullname = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $partner = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
            
            $row['feid'] = $key;
            $row['fullname'] = $fullname;
            $row['partner'] = $partner;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get lists fsl
     */
    public function get_list_warehouse($output = "json"){
        $rs = array();
        $arrWhere = array();
        
//        $fcode = $this->repo;
//        $arrWhere = array('fcode'=>$fcode);
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
     * This function is used to get list information described by function name
     */
    public function info_eg_json(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $fkey = $this->input->post('fkey', TRUE);
        $arrWhere = array('fkey'=>$fkey);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_engineers'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(empty($rs)){
            $error_response = array(
                'status' => 0,
                'message'=> 'Engineer not found'
            );
            $response = $error_response;
        }else{
            foreach ($rs as $r) {
                $partner_key = filter_var($r->partner_uniqid, FILTER_SANITIZE_STRING);
                $partner = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
                $key = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
                $name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
                $email = filter_var($r->engineer_email, FILTER_SANITIZE_EMAIL);
                $code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
                
                $success_response = array(
                    'status' => 1,
                    'pr_name'=> $partner,
                    'eg_name'=> $name
                );
            }
            $response = $success_response;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * This function is used to check part
     */
    public function check_part(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $stock = 0;
            $minstock = 0;
            $initstock = 0;
            $laststock = 0;
            $initflag = "";
            foreach ($rs as $r) {
                $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                $laststock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            }
            if($initflag === "Y"){
                $stock = $initstock;
            }else{
                $stock = $laststock;
            }

            if($stock < 1){
                $success_response = array(
                    'status' => 0,
                    'message'=> 'Out of stock, please choose part number subtitution!'
                );
            }else{
                $success_response = array(
                    'status' => 1,
                    'stock'=> $stock,
                    'message'=> 'Stock available'
                );
            }
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 2,
                'message'=> 'Stock not available'
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
     * This function is used to get detail information
     */
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
     * This function is used to get lists for populate data
     */
    public function get_list_part_sub(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_partsub_part_sub'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : null;
        
        if(!empty($rs)){
            $val_partsub = $rs;
            
            $exp_partsub = explode(";", $val_partsub);
            $arrData = array();
            foreach ($exp_partsub as $partnum){
                $row['partno'] = $partnum;
                
                $result = $this->get_info_part_stock($fcode, $partnum);

                $arrData[] = array('detail_data'=>$result);
            }
            $success_response = array(
                'status' => 1,
                'data'=> $arrData
            );
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 0,
                'message'=> 'Sparepart is out of stock and do not have subtitution',
                'data'=> array()
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
     * This function is used to get detail information
     */
    private function get_info_warehouse($fcode){
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
            $row['spv'] = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
 
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
     * This function is used to get list for datatables
     */
    public function get_part_nearby(){
        $rs = array();
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $partnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if($rs){
            $wh_nearby = "";
            foreach ($rs as $r){
                $wh_nearby = $r->fsl_nearby;
            }
            $exp_wh_nearby = explode(";", $wh_nearby);
            $arrData = array();
            foreach ($exp_wh_nearby as $code){
                $result = $this->get_info_part_stock($code, $partnum);

                $arrData[] = array('detail_data'=>$result);
            }
            $success_response = array(
                'status' => 1,
                'data'=> $arrData
            );
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 0,
                'message'=> 'no nearby warehouse'
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
     * This function is used to get cart info where part stock is already low
     */
    private function get_info_cart($partnum){
        
        $fcode = $this->repo;
        $arrWhere = array('fpartnum'=>$partnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partname = "";
        $partstock = "";
        
        if(!empty($rs)){
            foreach ($rs as $r) {
                $id = filter_var($r->tmp_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
                $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                $rs_part = $this->get_info_part($partnum);
                foreach ($rs_part as $p){
                    $partname = $p["name"];
                }
                $rs_stock = $this->get_info_part_stock($fcode, $partnum);
                foreach ($rs_stock as $s){
                    $partstock = (int)$s["stock"];
                }
                $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $user = filter_var($r->user, FILTER_SANITIZE_STRING);
                $fullname = filter_var($r->fullname, FILTER_SANITIZE_STRING);

                $row['id'] = $id;
                $row['partno'] = $partnum;
                $row['serialno'] = $serialnum;
                $row['name'] = $partname;
                $row['stock'] = $partstock;
                $row['qty'] = (int)$qty;
                $row['user'] = $user;
                $row['fullname'] = $fullname;

                $data[] = $row;
            }

        }
        return $data;
    }
    
    /**
     * This function is used to add cart
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
        $cartid = $this->session->userdata ( 'cart_session' )."ot";
        $fqty = 1;
        
        $partstock = 0;
        $rs_stock = $this->get_info_part_stock($fcode, $fpartnum);
        foreach ($rs_stock as $s){
            $partstock = (int)$s["stock"];
        }
        
        if($partstock === $fqty){
            $cstock = 0;
            $cqty = 0;
            $cuser = "";
            $cname = "";
            
            $rs_cart_info = $this->get_info_cart($fpartnum);
            
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
                $dataInfo = array('fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum, 'fcartid'=>$cartid, 'fqty'=>$fqty, 
                    'fuser'=>$fuser, 'fname'=>$fname);
                $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_outgoings_cart'), 'POST', FALSE);

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
            $dataInfo = array('fpartnum'=>$fpartnum, 'fserialnum'=>$fserialnum, 'fcartid'=>$cartid, 'fqty'=>$fqty, 'fuser'=>$fuser, 'fname'=>$fname);
            $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_outgoings_cart'), 'POST', FALSE);

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
     * This function is used to get list for datatables
     */
    public function get_list_cart_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' )."ot";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partname = "";
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $rs_part = $this->get_info_part($partnum);
            foreach ($rs_part as $p){
                $partname = $p["name"];
            }
            $rs_stock = $this->get_info_part_stock($fcode, $partnum);
            foreach ($rs_stock as $s){
                $partstock = (int)$s["stock"];
            }
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_outgoing_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['serialno'] = $serialnum;
            $row['name'] = $partname;
            $row['stock'] = $partstock;
            $row['qty'] = (int)$qty;
 
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
    private function get_list_cart(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' )."ot";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_outgoings_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partname = "";
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_outgoing_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $rs_part = $this->get_info_part($partnum);
            foreach ($rs_part as $p){
                $partname = $p["name"];
            }
            $rs_stock = $this->get_info_part_stock($fcode, $partnum);
            foreach ($rs_stock as $s){
                $partstock = (int)$s["stock"];
            }
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_outgoing_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['serialno'] = $serialnum;
            $row['name'] = $partname;
            $row['stock'] = $partstock;
            $row['qty'] = (int)$qty;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get total cart
     */
    public function get_total_cart(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $cartid = $this->session->userdata ( 'cart_session' )."ot";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_total_outgoings_cart'), 'POST', FALSE);
//        $rs_data = send_curl($arrWhere, $this->config->item('api_total_outgoings_cart').'?funiqid='.$cartid, 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $total = 0;
            foreach ($rs as $r){
                $total = $r->total;
            }
            $success_response = array(
                'status' => 1,
                'ttl_cart'=> $total
            );
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 0,
                'ttl_cart'=> 0
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
     * This function is used to delete cart
     */
    public function update_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to update cart'
        );
        
        $fid = $this->input->post('fid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);

        $arrWhere = array('fid'=>$fid, 'fqty'=>$fqty);
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_outgoings_cart'), 'POST', FALSE);

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
     * This function is used to delete cart
     */
    public function delete_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to delete cart'
        );
        
        $fid = $this->input->post('fid', TRUE);

        $arrWhere = array('fid'=>$fid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_delete_outgoings_cart'), 'POST', FALSE);

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
    
    public function get_trans_num(){
        $arrWhere = array('fparam'=>"OT");
        $transnum = send_curl($arrWhere, $this->config->item('api_get_trans_num'), 'POST', FALSE);
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($transnum)
        );
    }
    
    /**
     * This function is used to complete transaction
     */
    public function submit_trans(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to submit transaction'
        );
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' )."ot";
               
        $fsl = $this->input->post('fcode', TRUE);
        
        $fcode = "";
        if(empty($fsl) || $fsl == ""){
            $fcode = $this->repo;
        }else{
            $fcode = $fsl;
        }
        
        $date = date('Y-m-d'); 
        $fticket = $this->input->post('fticket', TRUE);
        $fengineer_id = $this->input->post('fengineer_id', TRUE);
        $fengineer2_id = $this->input->post('fengineer2_id', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $fnotes = $this->input->post('fnotes', TRUE);
        $createdby = $this->session->userdata ( 'vendorUR' );
        
        $arrParam = array('fparam'=>"OT");
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_outgoing_num'), 'POST', FALSE);
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        if($transnum === ""){
            $response = $error_response;
        }else{
            //get cart list by retnum
            $data_tmp = $this->get_list_cart();

            $dataDetail = array();
            if(!empty($data_tmp)){
                foreach ($data_tmp as $d){
                    $dataDetail = array('ftransno'=>$transnum, 'fpartnum'=>$d['partno'], 'fserialnum'=>$d['serialno'], 
                        'fqty'=>$d['qty']);
                    $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$d['partno'], 'fqty'=>(int)$d['stock']-(int)$d['qty'], 'fflag'=>'N');
                    $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_outgoings_trans_detail'), 
                            'POST', FALSE);
                    //update stock by fsl code and part number
                    $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                            'POST', FALSE);
                }
            }

            $dataTrans = array('ftransno'=>$transnum, 'fdate'=>$date, 'fticket'=>$fticket, 'fengineer_id'=>$fengineer_id, 
                'fengineer2_id'=>$fengineer2_id, 'fpurpose'=>$fpurpose, 'fqty'=>$fqty, 'fuser'=>$createdby, 'fcode'=>$fcode, 'fnotes'=>$fnotes);
            $main_res = send_curl($this->security->xss_clean($dataTrans), $this->config->item('api_add_outgoings_trans'), 'POST', FALSE);
            if($main_res->status)
            {
                //clear cart list data
                $arrWhere = array('fcartid'=>$cartid);
                $rem_res = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_clear_outgoings_cart'), 'POST', FALSE);
                if($rem_res->status){
                    $success_response = array(
                        'status' => 1,
                        'message' => $transnum
                    );
                    $response = $success_response;
                }else{
                    $response = $error_response;
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'Failed to submit transaction data');
                $response = $error_response;
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    public function print_transaction($ftrans_out){
        if(empty($ftrans_out) || $ftrans_out = ""){
            redirect('cl');
        }else{
            $orientation = "P";
            $paper_size = "A4";
            $width = 0;
            $height = 0;

            switch ($orientation) {
                case "P":
                   switch ($paper_size) {
                        case "A4":
                            $width = 210;
                            $height = 297;
                        break;
                        case "A5":
                            $width = 148;
                            $height = 210;
                        break;
                        default:
                            $width = 210;
                            $height = 297;
                        break;
                    }
                break;

                case "L":
                    switch ($paper_size) {
                        case "A4":
                            $width = 297;
                            $height = 210;
                        break;
                        case "A5":
                            $width = 210;
                            $height = 148;
                        break;
                        default:
                            $width = 297;
                            $height = 210;
                        break;
                    }
                break;

                default:
                    switch ($paper_size) {
                        case "A4":
                            $width = 210;
                            $height = 297;
                        break;
                        case "A5":
                            $width = 148;
                            $height = 210;
                        break;
                        default:
                            $width = 210;
                            $height = 297;
                        break;
                    }
                break;
            }

            // config fpdf options
            $config=array($orientation=>'P','size'=>$paper_size);
            $this->load->library('mypdf',$config);
            $this->mypdf->AliasNbPages();
            $this->mypdf->AddPage();
            $this->mypdf->Image(base_url().'assets/public/images/logo.png',10,8,($width*(15/100)),15);

            //get query data
            $arrWhere = array('ftrans_out'=>$ftrans_out);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings'), 'POST', FALSE);
            $results = $rs_data->status ? $rs_data->result : array();
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
            foreach ($results as $r){
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
                $ticket = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
                $transdate = date("d/m/Y", strtotime(filter_var($r->outgoing_date, FILTER_SANITIZE_STRING)));
                $partner = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
                $engineer_id = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
                $engineer2_id = filter_var($r->engineer_2_key, FILTER_SANITIZE_STRING);
                $engineer_name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
                if(!empty($engineer2_id)){
                    $engineer_mess = filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
                    $engineer_sign = filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
                }else{
                    $engineer_sign = $engineer_name;
                }
                $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            }

            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print
            $this->mypdf->SetFont('Arial','B',11);
            $this->mypdf->Code39(($width*(65/100)),10,$transnum,1,10);
            $this->mypdf->ln(20);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Purpose',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$purpose,1,1, 'L');

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Stock Location',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$fslname,1,0, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(20/100)),7,'        Ticket Number',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(30/100)),7,$ticket,1,1, 'L');

            $this->mypdf->ln(0);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Service Partner',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$partner,1,0, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(20/100)),7,'        Customer',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(30/100)),7,'',1,1, 'L');

            $this->mypdf->ln(0);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Assigned FSE',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$engineer_name,1,0, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(20/100)),7,'        Location',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(30/100)),7,'',1,1, 'L');

            $this->mypdf->ln(0);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'FSE ID Number',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$engineer_id,1,0, 'L');
            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(20/100)),7,'        SSB/ID',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(30/100)),7,'',1,1, 'L');

            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width*(60/100)),7,'STOCK REQUEST FORM',0,0,'R');
            $this->mypdf->ln(5);
            // Garis atas untuk header
            $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

            $this->mypdf->ln(5);

            $this->mypdf->SetFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),6,'Requested PN',1,0);
            $this->mypdf->Cell(($width*(20/100)),6,'Description',1,0);
            $this->mypdf->Cell(($width*(7.5/100)),6,'Qty',1,0);
            $this->mypdf->Cell(($width*(15/100)),6,'Issued PN',1,0);
            $this->mypdf->Cell(($width*(18/100)),6,'Serial No.',1,0);
            $this->mypdf->Cell(($width*(5/100)),6,'Bin',1,0);
            $this->mypdf->Cell(($width*(10/100)),6,'Status',1,1);

            $this->mypdf->SetFont('Arial','',9);        
            //Parameters for cURL
            $arrWhere = array('ftrans_out'=>$ftrans_out);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
            $rs = $rs_data->status ? $rs_data->result : array();

            foreach( $rs as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $qty = filter_var($row->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $serialnum = filter_var($row->serial_number, FILTER_SANITIZE_STRING);

                $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                $this->mypdf->CellFitScale(($width*(20/100)),6,$partname,1,0);
                $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,0,'C');
                $this->mypdf->CellFitScale(($width*(15/100)),6,' ',1,0);
                $this->mypdf->CellFitScale(($width*(18/100)),6,$serialnum,1,0);
                $this->mypdf->CellFitScale(($width*(5/100)),6,' ',1,0);
                $this->mypdf->CellFitScale(($width*(10/100)),6,' ',1,1);
            }

            $this->mypdf->ln(1);
            $this->mypdf->SetFont('Arial','',10);
            $this->mypdf->drawTextBox('Notes:', $width-20, 12, 'L', 'T');
            $this->mypdf->ln(10);
            $this->mypdf->Cell(($width*(25/100)),6,'Requested by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Approved by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Processed by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Received by:',0,1,'L');
            $this->mypdf->ln(12);
            $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$engineer_sign,0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,1,'L');
            $this->mypdf->ln(0.1);
            $this->mypdf->Cell(($width*(25/100)),6,'Date:'.$transdate,0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,1,'L');

            $title = 'Request #'.$transnum;
            $this->mypdf->SetTitle($title);
    //        $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('I', $title.'.pdf');
        }
    }
    
    public function print_pdf(){
        $orientation = "P";
        $paper_size = "A4";
        $width = 0;
        $height = 0;

        switch ($orientation) {
            case "P":
               switch ($paper_size) {
                    case "A4":
                        $width = 210;
                        $height = 297;
                    break;
                    case "A5":
                        $width = 148;
                        $height = 210;
                    break;
                    default:
                        $width = 210;
                        $height = 297;
                    break;
                }
            break;

            case "L":
                switch ($paper_size) {
                    case "A4":
                        $width = 297;
                        $height = 210;
                    break;
                    case "A5":
                        $width = 210;
                        $height = 148;
                    break;
                    default:
                        $width = 297;
                        $height = 210;
                    break;
                }
            break;

            default:
                switch ($paper_size) {
                    case "A4":
                        $width = 210;
                        $height = 297;
                    break;
                    case "A5":
                        $width = 148;
                        $height = 210;
                    break;
                    default:
                        $width = 210;
                        $height = 297;
                    break;
                }
            break;
        }
        
        // config fpdf options
        $config=array($orientation=>'P','size'=>$paper_size);
        $this->load->library('mypdf',$config);
        $this->mypdf->AliasNbPages();
        $this->mypdf->AddPage();
        $this->mypdf->Image(base_url().'assets/public/images/logo.png',10,8,($width*(15/100)),15);
        
        //get query data
        $ftrans_out = "OT18070001";
        $arrWhere = array('ftrans_out'=>$ftrans_out);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings'), 'POST', FALSE);
        $results = $rs_data->status ? $rs_data->result : array();
        $purpose = "";
        $transdate = "";
        $ticket = "";
        $partner = "";
        $engineer_id = "";
        $engineer2_id = "";
        $engineer_name = "";
        $engineer_mess = "";
        $engineer_sign = "";
        foreach ($results as $r){
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
            $ticket = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
            $transdate = date("d/m/Y", strtotime(filter_var($r->outgoing_date, FILTER_SANITIZE_STRING)));
            $partner = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $engineer_id = filter_var($r->engineer_key, FILTER_SANITIZE_STRING);
            $engineer2_id = filter_var($r->engineer_2_key, FILTER_SANITIZE_STRING);
            $engineer_name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            if(!empty($engineer2_id)){
                $engineer_mess = filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
                $engineer_sign = filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
            }else{
                $engineer_sign = $engineer_name;
            }
        }
        
        $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print
        $this->mypdf->SetFont('Arial','B',11);
        $this->mypdf->Code39(($width*(65/100)),10,$ftrans_out,1,10);
        $this->mypdf->ln(20);

        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(15/100)),7,'Purpose',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(25/100)),7,$purpose,1,1, 'L');
        
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(15/100)),7,'Stock Location',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(25/100)),7,'Gudang A',1,0, 'L');
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(20/100)),7,'        Ticket Number',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(30/100)),7,$ticket,1,1, 'L');
        
        $this->mypdf->ln(0);
        
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(15/100)),7,'Service Partner',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(25/100)),7,$partner,1,0, 'L');
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(20/100)),7,'        Customer',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(30/100)),7,'',1,1, 'L');
        
        $this->mypdf->ln(0);
        
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(15/100)),7,'Assigned FSE',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(25/100)),7,$engineer_name,1,0, 'L');
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(20/100)),7,'        Location',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(30/100)),7,'',1,1, 'L');
        
        $this->mypdf->ln(0);
        
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(15/100)),7,'FSE ID Number',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(25/100)),7,$engineer_id,1,0, 'L');
        $this->mypdf->setFont('Arial','B',10);
        $this->mypdf->Cell(($width*(20/100)),7,'        SSB/ID',0,0,'L');
        $this->mypdf->setFont('Arial','',10);
        $this->mypdf->Cell(($width*(30/100)),7,'',1,1, 'L');
        
        $this->mypdf->ln(5);
        $this->mypdf->setFont('Arial','B',13);
        $this->mypdf->Cell(($width*(60/100)),7,'STOCK REQUEST FORM',0,0,'R');
        $this->mypdf->ln(5);
        // Garis atas untuk header
        $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);
        
        $this->mypdf->ln(5);
        
        $this->mypdf->SetFont('Arial','B',10);
        $this->mypdf->Cell(($width*(15/100)),6,'Requested PN',1,0);
        $this->mypdf->Cell(($width*(20/100)),6,'Description',1,0);
        $this->mypdf->Cell(($width*(7.5/100)),6,'Qty',1,0);
        $this->mypdf->Cell(($width*(15/100)),6,'Issued PN',1,0);
        $this->mypdf->Cell(($width*(18/100)),6,'Serial No.',1,0);
        $this->mypdf->Cell(($width*(5/100)),6,'Bin',1,0);
        $this->mypdf->Cell(($width*(10/100)),6,'Status',1,1);
        
        $this->mypdf->SetFont('Arial','',9);        
        //Parameters for cURL
        $arrWhere = array('ftrans_out'=>$ftrans_out);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        foreach( $rs as $row )
        {
            $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
            $qty = filter_var($row->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $serialnum = filter_var($row->serial_number, FILTER_SANITIZE_STRING);
            
            $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
            $this->mypdf->CellFitScale(($width*(20/100)),6,$partname,1,0);
            $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,0,'C');
            $this->mypdf->CellFitScale(($width*(15/100)),6,' ',1,0);
            $this->mypdf->CellFitScale(($width*(18/100)),6,$serialnum,1,0);
            $this->mypdf->CellFitScale(($width*(5/100)),6,' ',1,0);
            $this->mypdf->CellFitScale(($width*(10/100)),6,' ',1,1);
        }
        
        $this->mypdf->ln(1);
        $this->mypdf->SetFont('Arial','',10);
        $this->mypdf->drawTextBox('Notes:', $width-20, 12, 'L', 'T');
        $this->mypdf->ln(10);
        $this->mypdf->Cell(($width*(25/100)),6,'Requested by:',0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Approved by:',0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Processed by:',0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Received by:',0,1,'L');
        $this->mypdf->ln(12);
        $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$engineer_sign,0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,1,'L');
        $this->mypdf->ln(0.1);
        $this->mypdf->Cell(($width*(25/100)),6,'Date:'.$transdate,0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
        $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,1,'L');
        
        $title = 'Request #'.$ftrans_out;
        $this->mypdf->SetTitle($title);
        $this->mypdf->Output('D', $title.'.pdf');
    }
}