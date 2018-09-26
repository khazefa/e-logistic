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
class CFsltocwh extends BaseController
{
    var $apirole = 'fsltocwh';
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
    public function index(){        
        $this->global['pageTitle'] = 'Outgoing FSL to CWH - '.APP_NAME;
        $this->global['pageMenu'] = 'Outgoing FSL to CWH';
        $this->global['contentHeader'] = 'Outgoing FSL to CWH';
        $this->global['contentTitle'] = 'Outgoing FSL to CWH';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;

        $this->loadViews('front/fsltocwh/index', $this->global, NULL);
    }
    
    /**
     * This function is used to load the add new form
     */
    public function add() {        
            $this->global['pageTitle'] = 'Outgoing FSL to CWH - '.APP_NAME;
            $this->global['pageMenu'] = 'Outgoing FSL to CWH';
            $this->global['contentHeader'] = 'Outgoing FSL to CWH';
            $this->global['contentTitle'] = 'Outgoing FSL to CWH';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            
            
            $data['list_fsl'] = $this->get_list_warehouse('array');
            $data['list_part'] = $this->get_list_part();
            
            $this->loadViews('front/fsltocwh/create', $this->global, $data);
    }


/////////////////////////// VIEW DATA //////////////////////////////////////////

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
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_fsltocwh'), 'POST', FALSE);
        //var_dump($rs_data);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->fsltocwh_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
            $fpurpose = filter_var($r->fsltocwh_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->fsltocwh_qty, FILTER_SANITIZE_NUMBER_INT);
            $user_fullname = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->fsltocwh_notes, FILTER_SANITIZE_STRING);
            $status = filter_var($r->fsltocwh_status, FILTER_SANITIZE_STRING);
            $requestby = "";
            $takeby = "";
            $purpose = "";
            
            
            
            switch ($fpurpose){
                case "RBP";
                    $purpose = "RETURN BAD PART";
                break;
                case "RBS";
                    $purpose = "RETURN BAD STOCK";
                break;
                
            }
            
            $row['fsltocwh_num'] = $transnum;
            $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
           
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user_fullname;
            $row['fsltocwh_notes'] = $notes;
            $row['fsltocwh_status']= $status;
            
//            $row['notes'] = "-";
            $row['fsltocwh_status'] = strtoupper($status);
            
            $row['button'] = '<a href="'.base_url('print-'.$this->apirole.'-trans/').$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
            
            
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    
    
////////////////////////// FORM CREATE /////////////////////////////////////////

// LIST COMBO BOX
//==============================================================================
    /**
     * This function is used to get lists engineers
   
    private function get_list_engineers(){
        $rs = array();
        $arrWhere = array();
        
//        $fcode = $this->repo;
//        $arrWhere = array('fcode'=>$fcode);
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
      */
     
    /**
     * This function is used to get lists fsl
    */
    public function get_list_warehouse($output = "json"){
        $rs = array();
        $arrWhere = array();

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
     * This function is used to get lists for populate data
     
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
    */
   
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
     * This function is used to get lists for json or populate data
     */
    public function get_list_part_like(){
        $rs = array();
        $arrLike = array();
        
//        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->get('query', TRUE);

//        if ($fpartnum != "") $arrLike['fpartnum'] = $fpartnum;
        if ($fname != "") $arrLike['fname'] = $fname;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrLike, $this->config->item('api_list_parts_like'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $pid = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);  
            
            array_push($data, $partname);
            
//            $row['partno'] = $partnum;
//            $row['name'] = $partname;
//            $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
//            $row['returncode'] = filter_var($r->part_return_code, FILTER_SANITIZE_STRING);
//            $row['machine'] = filter_var($r->part_machine, FILTER_SANITIZE_STRING);
 
//            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($data)
        );
        
//        if($output == "json"){
//            return $this->output
//            ->set_content_type('application/json')
//            ->set_output(
//                json_encode($data)
//            );
//        }elseif($output == "array"){
//            
//        }
    }
    
//  FUNCTION
//==============================================================================
  
    
    /**
     * This function is used to check part
     */
    public function check_part(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $fcode = $this->role;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock_wh'), 'POST', FALSE);
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

            if($stock > 0){
                $success_response = array(
                    'status' => 1,
                    'stock'=> $stock,
                    'message'=> 'OK'
                );
                $response = $success_response;
            }else{
                $error_response = array(
                    'status' => 0,
                    'message'=> 'Part Not Found'
                );
                $response = $error_response;
            }
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
            //karena stock hanya bertambah
            // if($initflag === "Y"){
            //     $row['stock'] = $initstock;
            // }else{
                $row['stock'] = $stock;
            // }
 
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
     * This function is used to get cart info where part stock is already low
     */
    private function get_info_cart($partnum, $fcode){
        
        $fcode = $this->repo;
        $arrWhere = array('fpartnum'=>$partnum, 'fcode'=>$fcode);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->apirole.'_cart'), 'POST', FALSE);
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
    
   
//  CART FUNCTION
//==============================================================================    
    
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
        $cartid = $this->session->userdata ( 'cart_session' )."dn";
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
     * This function is used to get list for datatables
     */
    public function get_list_cart_datatable(){
        $rs = array();
        
        //Parameters for cURL
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata( 'cart_session' )."dn";
        //var_dump("SESSION =".$cartid);
        
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->apirole.'_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_fsltocwh_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $rs_stock = $this->get_info_part_stock($fcode, $partnum);
            foreach ($rs_stock as $s){
                $partstock = (int)$s["stock"];
            }
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_fsltocwh_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_fsltocwh_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
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
        $cartid = $this->session->userdata ( 'cart_session' )."dn";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->apirole.'_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
       
        $data = array();
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_fsltocwh_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
//            $rs_stock = $this->get_info_part_stock($fcode, $partnum);
//            foreach ($rs_stock as $s){
//                $partstock = (int)$s["stock"];
//            }
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_fsltocwh_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_fsltocwh_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
//            $row['stock'] = $partstock;
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
        
        $cartid = $this->session->userdata ( 'cart_session' )."dn";
        $arrWhere = array('funiqid'=>$cartid);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_total_'.$this->apirole.'_cart'), 'POST', FALSE);
//        $rs_data = send_curl($arrWhere, $this->config->item('api_total_outgoings_cart').'?funiqid='.$cartid, 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $total = 0;
        if(!empty($rs)){
            foreach ($rs as $r){
                $total = $r->total > 0 ? (int)$r->total : 0;
            }
            $success_response = array(
                'status' => 1,
                'ttl_cart'=> $total
            );
            $response = $success_response;
        }else{
            $error_response = array(
                'status' => 0,
                'ttl_cart'=> $total
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_'.$this->apirole.'_cart'), 'POST', FALSE);

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
        $rs_data = send_curl($arrWhere, $this->config->item('api_delete_'.$this->apirole.'_cart'), 'POST', FALSE);

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
        $arrWhere = array('fparam'=>"DN-CWH");
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
        $cartid = $this->session->userdata ( 'cart_session' )."dn";
               
        $fsl = $this->input->post('fcode', TRUE);
        
//        if(empty($fsl) || $fsl == ""){
//            $fcode = $this->repo;
//        }else{
//            $fcode = $fsl;
//        }
        
        $date = date('Y-m-d'); 
        
        $fairwaybill = $this->input->post('fairwaybill', TRUE);
        $ftransnotes = $this->input->post('ftransnote', TRUE);
        $fservice = $this->input->post('fservice', TRUE);
        $fdeliveryby = $this->input->post('fdeliveryby', TRUE);
        $feta = $this->input->post('feta', TRUE);
        $fdest_fsl = $fcode;
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fqty = $this->input->post('fqty', TRUE);
        $createdby = $this->session->userdata ( 'vendorUR' );
        $kodeNum = '';
        switch ($fpurpose) {
            case 'RBP':
                $kode = 'DN-BAD';
                $purpose_tbl = 'wsps_badpart';
                break;
            case 'RBS':
                $kode = 'DN-DOA';
                $purpose_tbl = 'wsps_badstock';
                break;
            default:
                # code...
                break;
        }
        $arrParam = array('fparam'=>$kode, 'fcode'=>$fcode);
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_'.$this->apirole.'_num'), 'POST', FALSE);
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        if(($fqty < 1) || (empty($fqty))){
            $this->session->set_flashdata('error', 'Failed to submit transaction data');
            $response = $error_response;
        }else{  
            $data_tmp = array();
            if($transnum === ""){
                $response = $error_response;
            }else{
                //get cart list by retnum
                $data_tmp = $this->get_list_cart();
                $dataDetail = array();
                $total_qty = 0;
                if(!empty($data_tmp)){
                    foreach ($data_tmp as $d){
                        $rs_stock = $this->get_info_part_stock($purpose_tbl, $d['partno']);
                        $partstock = 1;
                        foreach ($rs_stock as $s){
                            $partstock = (int)$s["stock"];
                        }
                        if(false){

                        }else{                        
                            $dataDetail = array(
                                'ftransno'=>$transnum, 
                                'fpartnum'=>$d['partno'], 
                                'fserialnum'=>$d['serialno'], 
                                'fqty'=>$d['qty']);
                            $sec_res = send_curl(
                                $this->security->xss_clean($dataDetail), 
                                $this->config->item('api_add_'.$this->apirole.'_trans_detail'), 
                                'POST', FALSE);
                            $total_qty += (int)$d['qty'];
                            echo $partstock+$d['qty'].$purpose_tbl.$d['partno'];

                            $dataUpdateStock = array(
                                'fcode'=>$purpose_tbl, 
                                'fpartnum'=>$d['partno'], 
                                'fqty'=>$partstock+$d['qty'], 
                                'fflag'=>'N');
                            //update stock by fsl code and part number
                            $update_stock_res = send_curl(
                                $this->security->xss_clean($dataUpdateStock), 
                                $this->config->item('api_edit_stock_part_stock'), 
                                'POST', FALSE);
                        }
                    }

                    if($total_qty < 1){
                        $this->session->set_flashdata('error', 'Skip looped submit transaction data');
                        $response = $error_response;
                    }else{
                        $dataTrans = array(
                            'ftransno'=>$transnum, 
                            'fdate'=>$date, 
                            'fpurpose'=>$fpurpose, 
                            'ftransnotes'=>$ftransnotes, 
                            'fdest_fsl'=>$fdest_fsl,
                            'fairwaybill'=>$fairwaybill,
                            'fservice'=>$fservice,
                            'fdeliveryby'=>$fdeliveryby,
                            'feta'=>$feta,
                            'fqty'=>$total_qty, 
                            'fuser'=>$createdby
                        );
                        $main_res = send_curl(
                                $this->security->xss_clean($dataTrans), 
                                $this->config->item('api_add_'.$this->apirole.'_trans'), 
                                'POST', FALSE);
                        if($main_res->status)
                        {
                            //clear cart list data
                            $arrWhere = array('fcartid'=>$cartid);
                            $rem_res = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_clear_'.$this->apirole.'_cart'), 'POST', FALSE);
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
                }else{
                    $this->session->set_flashdata('error', 'Failed to submit transaction data');
                    $response = $error_response;
                }
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
 // PRINT TRANSACTION
 //=============================================================================
    
    public function snt($input, $type){
        $san = FILTER_DEFAULT;
        switch($type){
            case 'string': $san = FILTER_SANITIZE_STRING;break;
            case 'int' : $san = FILTER_SANITIZE_NUMBER_INT;break;
        }
        return filter_var($input,$san);
    }
    
    public function print_trans($transnum){
        //jika Nomor Transaksi Kosong
        if(empty($transnum) || $transnum == '') echo 'error transaction is NULL.';//redirect ('cl');
        
        //Deklarasi Variable yang akan digunakan
        $orientation = 'P';
        $paper_size = 'A4';
        $cfg = $this->init_prints($orientation, $paper_size);
        $width = $cfg['width'];
        $height = $cfg['height'];
        $params = array(
            'transnum' => $transnum
        );
        
        //Ambil Database
        $rs_data = json_decode(json_encode(send_curl($params, $this->config->item('api_get_data_detail'), 'POST', FALSE)),TRUE);
        $rs = $rs_data['status'] ? $rs_data['result'] : array();
        
        
        //HARDCODE Untuk Kode Purpose
        switch ($this->snt($rs[$this->apirole.'_purpose'],'string')){
            case "RBP";
                $purpose = "Return Bad Part";
            break;
            case "RBS";
                $purpose = "Return Bad Stock";
            break;
            
            default;
                $purpose = "-";
            break;
        }
        
        //PDF Generate
        if(count($rs)==0) return false;
        $this->load->library('mypdf',array($orientation,$paper_size));
        $pdf = $this->mypdf;
        
        $pdf->AliasNbPages();
        $pdf->AddPage();
        
        //image file
        $pdf->Image(base_url().'assets/public/images/logo.png',10,8,($width*(15/100)),15);
        //$pdf->Image(base_url().'assets/public/images/DHL-Supply.png',($width*(70/100)),8,($width*(20/100)),20); //IMAGE DHL
        
        //barcode
        $pdf->SetFont('Arial','B',9); //ARIAL BOLD 11
        $pdf->Code39(($width*(50/100)),10,$transnum,1,10);
        $pdf->ln(20);
        
        //row Header
        $pdf->setFont('Arial','',10);
        $pdf->Cell(($width*(15/100)),7,'Purpose '.': '.$purpose.' SPAREPART TO FSL',0,0,'L');
        $pdf->setFont('Arial','',10);
        $pdf->Cell(($width*(25/100)),7,'',0,1, 'L');
        
        //row 1
        //$pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->setFont('Arial','BU',11); //ARIAL BOLD 10
        $pdf->Cell(($width*(45/100)),7,$this->snt($rs['fsl_name'], 'string'),0,0,'L');
        $pdf->setFont('Arial','',10); //ARIAL NORMAL 10
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'D-D: '.$this->snt($rs['delivery_note_date'], 'string'),1,1,'C');
            
        //row 2
        //col 1 Wrap
        $py = $pdf->GetY();
        $px = $pdf->GetX();
        $pdf->MultiCell(($width*(45/100)),7,$this->snt($rs['fsl_location'], 'string'),0,'L');
        //col 2 Wrap
        $px += ($width*(67.5/100));
        $pdf->SetXY($px,$py);
        $pdf->MultiCell(($width*(22.5/100)),7,$this->snt($rs['delivery_note_num'],'string'),1,'C');
        
        //row 3
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,$this->snt($rs['delivery_note_airwaybill'], 'string'),1,1,'C');
        
        //row 4
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,$this->snt($rs['delivery_by'], 'string').' - '.$this->snt($rs['delivery_time_type'], 'string'),1,1,'C');
        
        //row 5
        $pdf->Cell(($width*(22.5/100)),7,'Attn : '.$this->snt($rs['fsl_pic'], 'string').' :',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'ETA: '.$this->snt($rs['delivery_note_eta'], 'string'),1,1,'C');
        
        //row 6
        $pdf->Cell(($width*(22.5/100)),7,'         '.$this->snt($rs['fsl_phone'], 'string'),0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,1,'C');
        
        
        
        //row 9
        $pdf->setFont('Arial','B',11); //ARIAL BOLD 11
        $pdf->Cell(($width*(90/100)),7,'DELIVERY NOTE',0,1,'C');
        
        //row 10
        $pdf->SetFont('Arial','B',10); //ARIAL BOLD 10
        $pdf->Cell(($width*(7.5/100)),6,'Item',1,0,'C');
        $pdf->Cell(($width*(15/100)),6,'Part #',1,0,'C');
        $pdf->Cell(($width*(45/100)),6,'Description',1,0,'C');
        $pdf->Cell(($width*(15/100)),6,'Serial No.',1,0,'C');
        $pdf->Cell(($width*(7.5/100)),6,'Qty',1,1,'C');
        
        //row Data
        $start_awal=$pdf->GetX(); 
        $get_xxx = $pdf->GetX();
        $get_yyy = $pdf->GetY();
        
        $height = 7;
        
        $i = 1;
        $j = 1;
        $batas_page = 20;
        $pdf->SetFont('Arial','',9); //ARIAL NORMAL 10
        if(count($rs)>0 AND count($rs['detail'])>0){
            
            $detail = $rs['detail'];
            foreach($detail as $dt){
                if(count($rs)>$batas_page){
                    if($j > 25){
                        $pdf->AddPage();    
                        $j = 1;
                        $start_awal=$pdf->GetX();
                        $get_xxx = $pdf->GetX();
                        $get_yyy = $pdf->GetY();
                    }
                }
                if($j>1){
                    $get_yyy += $height;
                    $get_xxx = $start_awal;
                }
                $pdf->SetXY($get_xxx,$get_yyy);
                $pdf->MultiCell(($width*(7.5/100)),$height,$i,1,'C');
                $get_xxx+=($width*(7.5/100));
                
                $pdf->SetXY($get_xxx,$get_yyy);
                $pdf->MultiCell(($width*(15/100)),$height,$this->snt($dt['part_number'], 'string'),1,'L');
                $get_xxx+=($width*(15/100));
                
                $pdf->SetXY($get_xxx,$get_yyy);
                $pdf->MultiCell(($width*(45/100)),$height,$this->snt($dt['part_name'], 'string'),1,'L');
                $get_xxx+=($width*(45/100));
                
                $pdf->SetXY($get_xxx,$get_yyy);
                $pdf->MultiCell(($width*(15/100)),$height,$this->snt($dt['serial_number'], 'string'),1,'L');
                $get_xxx+=($width*(15/100));
                
                $pdf->SetXY($get_xxx,$get_yyy);
                $pdf->MultiCell(($width*(7.5/100)),$height,$this->snt($dt['dt_delivery_note_qty'], 'string'),1,'C');
                $get_xxx+=($width*(7.5/100));
                
//                $pdf->Cell(($width*(7.5/100)),6,$i,1,0,'C');
//                $pdf->Cell(($width*(20/100)),6,$this->snt($dt['part_number'], 'string'),1,0,'L');
//                $pdf->Cell(($width*(35/100)),6,$this->snt($dt['part_name'], 'string'),1,0,'C');
//                $pdf->Cell(($width*(20/100)),6,$this->snt($dt['serial_number'], 'string'),1,0,'L');
//                $pdf->Cell(($width*(7.5/100)),6,$this->snt($dt['dt_delivery_note_qty'], 'string'),1,1,'C');
                $i++;
                $j++;
            }
        }
        
        //row 11
        $pdf->SetFont('Arial','',9); //ARIAL NORMAL 10
        $static_note = 'NOTE : Mohon cek kelengkapan barang dan mohon infokan ke Warehouse Pusat, jika ada ketidaksesuaian.';
        $pdf->MultiCell(($width*(90/100)),7,$static_note,1,'C');
        
        //row 12-13
        $pdf->Ln(15);
        
        //row 14 TTD 3 kolom
        $pdf->SetFont('Arial','B',9); //ARIAL BOLD 10
        $pdf->Cell(($width*(30/100)),7,'Sent By,',0,0,'L');
        $pdf->Cell(($width*(30/100)),7,'Courier By,',0,0,'C');
        $pdf->Cell(($width*(30/100)),7,'Received By,',0,1,'C');
        
        //row 15 TTD
        $pdf->SetFont('Arial','',9); //ARIAL NORMAL 10
        $pdf->Cell(($width*(30/100)),6,'PT. Diebold Nixdorf Indonesia',0,0,'L');
        $pdf->Cell(($width*(30/100)),6,'',0,0,'C');
        $pdf->Cell(($width*(30/100)),6,'',0,1,'C');
        
        //row 16 TTD
        $pdf->Cell(($width*(30/100)),6,'',0,0,'L');
        $pdf->Cell(($width*(30/100)),6,'',0,0,'C');
        $pdf->Cell(($width*(30/100)),6,'',0,1,'C');
        
        //row 16-18
        $pdf->Ln(17);
        
        //row 19
        $pdf->SetFont('Arial','B',9); //ARIAL BOLD 10
        $pdf->Cell(($width*(30/100)),7,'______________',0,0,'L');
        $pdf->Cell(($width*(30/100)),7,'______________',0,0,'C');
        $pdf->Cell(($width*(30/100)),7,'______________',0,1,'C');
        
        //Output
        $title = 'Delivery_Note_'.$transnum;
        $pdf->SetTitle($title);
        $pdf->Output('D', $title.'_'. date('Ymd') .'.pdf');
        
        
    }
    
    public function init_prints($orientation, $paper_size){
        $rs = array();
        $width = '210';
        $height = '297';
       
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
        $rs['width'] = $width;
        $rs['height'] = $height;
        return $rs;
    }
    
    
    
////////////////////////////////////////////////////////////////////////////////    
    
    //  get ETA
    public function get_eta(){
        $rs = array();
        $arrPOST = array();
        $eta = '';
        
        $ffsl_code = $this->repo;
        $fdelivery_type = $this->input->post('fdelivery_type', TRUE);
        $fdelivery_by = $this->input->post('fdelivery_by', TRUE);
        
        if($fdelivery_type == 'INTCOURIER'){
            $date_now = date("Y-m-d", time() + 86400);
            $response = array(
                'status' => 1,
                'ETA'=> $date_now
            );
        }else if($fdelivery_type == 'SAMEDAY'){
            $date_now = date("Y-m-d");
            $response = array(
                'status' => 1,
                'ETA'=> $date_now
            );
        }else{
            $arrPOST = array('ffsl_code'=>$ffsl_code,'fdelivery_type'=>$fdelivery_type, 'fdelivery_by' => $fdelivery_by);
            //var_dump($arrPOST);
            //Parse Data for cURL
            $rs = send_curl($arrPOST, $this->config->item('api_get_eta_time'), 'POST', FALSE);
            $result = $rs->status ? $rs->result : array();
            if(!empty($result)){
                foreach ($result as $r){
                    $eta = !empty($r->ETA) ? $r->ETA : '';

                }
                $response = array(
                    'status' => 1,
                    'ETA'=> $eta
                );
            }else{
                $response = array(
                    'status' => 0,
                    'ETA'=> 0
                );
            }
            
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode($response)
            );
        
    }
    
    
}