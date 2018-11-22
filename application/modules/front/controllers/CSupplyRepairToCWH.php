<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CSupplyRepairToCWH (supply from repair to cwh)
 * CSupplyRepairToCWH Class to control.
 * @author : Aris Baskoro
 * @version : 2.0
 * @since : NOVEMBER 2018
 */
class CSupplyRepairToCWH extends BaseController
{
    private $apirole = 'supplyrepairtocwh';
    private $alias_controller_name = 'supply-repair-to-cwh';
    private $app_title = 'Supply Repair To CWH';
    private $prefixCodeTrans = 'RD-';
    private $db_table_name = 'repairdelivery';

    private $field_modal = array(
        'trans_num'     => 'Trans Num',
        'trans_date'    => 'Trans Date',
        'trans_notes'   => 'Notes',
        'trans_purpose' => 'Purpose'
    );

    private $field_purpose = array(
        'RGP' => 'Return Good',
        'SCR' => 'Scrapt',
        'SBK' => 'Send Back'
    );

    private $field_status = array(
        'open' => 'OPEN',
        'pending' => 'PENDING',
        'complete' => 'COMPLETE'
    );

    private $field_value = array();
    
    public function __construct(){
        parent::__construct();
        $this->isLoggedIn();
        $this->field_value = array(
            'trans_num'     => $this->db_table_name.'_num',
            'trans_date'    => $this->db_table_name.'_date',
            'trans_notes'   => $this->db_table_name.'_notes',
            'trans_purpose' => $this->db_table_name.'_purpose',
        );
    }

    //                          VIEW FUNCTION
    ///////////////////////////////////////////////////////////////////////
    public function index(){        
        $this->global['pageTitle']      = $this->app_title.' - '.APP_NAME;
        $this->global['pageMenu']       = $this->app_title;
        $this->global['contentHeader']  = $this->app_title;
        $this->global['contentTitle']   = $this->app_title;
        $this->global ['role']          = $this->role;
        $this->global ['name']          = $this->name;

        $data['link_add']               = base_url($this->alias_controller_name.'/new');
        $data['link_get_data']          = base_url('api-'.$this->alias_controller_name.'/datatable');
        $data['link_modal_detail']      = base_url('api-'.$this->alias_controller_name.'/get-detail');
        $data['link_modal']             = base_url('api-'.$this->alias_controller_name.'/get-trans');

        $data['field_modal_popup']      = $this->field_modal;
        $data['field_modal_js']         = $this->field_value;
        $data['field_purpose']          = $this->field_purpose;
        $data['field_status']           = $this->field_status;
        
        $this->loadViews('front/'.$this->alias_controller_name.'/index', $this->global, $data);
    }
    
    public function add() {        
        $this->global['pageTitle']      = $this->app_title.' - '.APP_NAME;
        $this->global['pageMenu']       = $this->app_title;
        $this->global['contentHeader']  = $this->app_title;
        $this->global['contentTitle']   = $this->app_title;
        $this->global ['role']          = $this->role;
        $this->global ['name']          = $this->name;
        
        $data['list_fsl']               = $this->get_list_warehouse('array');
        $data['list_part']              = $this->get_list_part();
        $data['field_purpose']          = $this->field_purpose;

        $data['field_column']           = array(
            'id'        => 'Action', //must be on first column
            'partno'    => 'Part Number',
            'serialno'  => 'Serial Number',
            'partname'  => 'Part Name',
            'transout'  => 'No Trans Incoming',
            'qty'       => 'QTY',
        );

        $data['link_submit']            = base_url('api-'.$this->alias_controller_name.'/submit-trans');
        $data['link_check_part']        = base_url('api-'.$this->alias_controller_name.'/check-partnum');
        $data['link_cart_add']          = base_url('api-'.$this->alias_controller_name.'/cart/add');
        $data['link_cart_delete']       = base_url('api-'.$this->alias_controller_name.'/cart/delete');
        $data['link_cart_update']       = base_url('api-'.$this->alias_controller_name.'/cart/update');
        $data['link_datatable_cart']    = base_url('api-'.$this->alias_controller_name.'/cart/datatable');
        
        $this->loadViews('front/'.$this->alias_controller_name.'/create', $this->global, $data);
    }


    //                          INDEX FUNCTION
    ///////////////////////////////////////////////////////////////////////


    /**
     * FUNCTION TO GET datatable from table
     * @var fdate1 Date Awal
     * @var fdate2 Date Akhir
     * @var fpurpose Purpose yang digunakan
     */
    public function get_list_view_datatable(){
        $fcode      = $this->repo;
        $fdate1     = $this->input->post('fdate1', TRUE);
        $fdate2     = $this->input->post('fdate2', TRUE);
        
        $data       = array();
        $fstatus   = !empty($_POST['fstatus']) ? implode(';',$_POST['fstatus']) : "";
        $fpurpose   = !empty($_POST['fpurpose']) ? implode(';',$_POST['fpurpose']) : "";
        

        if(empty($fstatus)){
            $e_status = array();
        }else{
            $e_status = explode(';', $fstatus);
        }

        if(empty($fpurpose)){
            $e_purpose = array();
        }else{
            $e_purpose = explode(';', $fpurpose);
        }
        
        $arrWhere   = array('fdate1'=>$fdate1,'fdate2'=>$fdate2);
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->apirole), 'POST', FALSE);
        
        $rs = (is_object($rs_data) AND $rs_data->status) ? $rs_data->result : array();
    
        foreach ($rs as $p) {
            $r = (array)$p;
            $transnum   = filter_var($r[$this->db_table_name.'_num'], FILTER_SANITIZE_STRING);
            $transdate  = filter_var($r[$this->db_table_name.'_date'], FILTER_SANITIZE_STRING);
            $lpurpose   = filter_var($r[$this->db_table_name.'_purpose'], FILTER_SANITIZE_STRING);
            $qty        = filter_var($r[$this->db_table_name.'_qty'], FILTER_SANITIZE_NUMBER_INT);
            $user       = filter_var($r['user_fullname'], FILTER_SANITIZE_STRING);
            $notes      = filter_var($r[$this->db_table_name.'_notes'], FILTER_SANITIZE_STRING);
            $status     = filter_var($r[$this->db_table_name.'_status'], FILTER_SANITIZE_STRING);
            $purpose    = $this->field_purpose[$lpurpose];

            $row['transnum']    = $transnum;
            $row['transdate']   = date('d/m/Y H:i', strtotime($transdate));
            $row['purpose']     = $purpose;
            $row['status']      = $status;
            $row['qty']         = $qty;
            $row['user']        = $user;
            $row['notes']       = $notes;
            $row['button']      = '
            <a href="'.base_url($this->alias_controller_name.'\/print\/').$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>
            <a href="javascript:viewdetail(\''.$transnum.'\');"><i class="mdi mdi-information mr-2 text-muted font-18 vertical-middle"></i></a>
            ';

            if((count($e_status)!=0) AND (count($e_purpose)!=0)){
                if(in_array($status, $e_status) AND in_array($lpurpose, $e_purpose)){
                    $data[] = $row;
                }
            }else if((count($e_status)!=0) || (count($e_purpose)!=0)){
                if(in_array($status, $e_status)){
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
     * get list detail of table delivery note
     * @param String post fdate1
     * @param String post fdate2
     * @param String post fcoverage
     * 
     */
    public function get_list_view_datatable2(){
        $rs = array();
        $arrWhere = array();
        
        //$fcode ='';
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        
        $coverage = !empty($_POST['fcoverage']) ? implode(';',$_POST['fcoverage']) : "";
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
        
        $arrWhere = array('fdate1'=>$fdate1,'fdate2'=>$fdate2);
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->apirole), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->fsltocwh_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->date, FILTER_SANITIZE_STRING);
            $lpurpose = filter_var($r->fsltocwh_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->fsltocwh_qty, FILTER_SANITIZE_NUMBER_INT);
            $user = filter_var($r->user_fullname, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->fsltocwh_notes, FILTER_SANITIZE_STRING);
            $fsl_code = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $purpose = $this->field_purpose[$lpurpose];

            $row['transnum'] = $transnum;
            $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
            
            $row['purpose'] = $purpose;
            $row['qty'] = $qty;
            $row['user'] = $user;
            $row['notes'] = $notes;
            $row['button'] = '
            <a href="'.base_url("print-fsltocwh-trans/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>
            <a href="javascript:viewdetail(\''.$transnum.'\');"><i class="mdi mdi-information mr-2 text-muted font-18 vertical-middle"></i></a>
            ';
 
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
     * FUNCTION get Transaction
     * @var ftransnum Transaction Number
     */
    public function get_trans(){
        $rs = array();
        $arrWhere = array();
        $res = array('status'=>FALSE);
        $ftransnum = $this->input->post('ftransnum', TRUE);
        $arrWhere = array('ftransnum'=>$ftransnum);
        $rs_data = send_curl($arrWhere, $this->config->item('api_get_'.$this->apirole.'_get_trans'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        $rdata = (array)$rs;
        foreach($rdata as $v){
            foreach($v as $rk => $rv){
                $res[$rk] = filter_var($rv,FILTER_SANITIZE_STRING);
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($res)
        );
    }

    /**
     * FUNCTION get detail Transaction
     * @var ftransnum Transaction Number
     */
    public function get_trans_detail(){
        $rs = array();
        $arrWhere = array();
        $ftransnum = $this->input->post('ftransnum', TRUE);
        $arrWhere = array('ftransnum'=>$ftransnum);
        $rs_data = send_curl($arrWhere, $this->config->item('api_get_'.$this->apirole.'_get_trans_detail'), 'POST', FALSE);
        $rs = (is_object($rs_data) AND $rs_data->status) ? $rs_data->result : array();
        $rdata = (array)$rs;
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$rdata))
        );
    }
    

    //                          CART FUNCTION
    ///////////////////////////////////////////////////////////////////////
    
    /**
     * FUNCTION to check part is available or stock of part is available
     * @var fpartnum
     * @var fserialnum
     */
    public function check_part(){
        $rs = array();
        $arrWhere = array();
        $response = array(
            'status' => FALSE,
            'message'=> 'Unknown Error, Parts can\'t be find.'
        );

        $cartid         = $this->session->userdata ( 'cart_session' ).$this->apirole;
        $fuser          = $this->vendorUR;
        $fname          = $this->name;
        $fpartnum       = $this->input->post('fpartnum', TRUE);
        $fserialnum     = $this->input->post('fserialnum', TRUE);
        $transout       = $this->get_info_transout_num($fpartnum,$fserialnum);
        $transout_num   = !empty($transout)?$transout['transout_num']:'';
        $transout_purpose = !empty($transout)?$transout['transout_purpose']:'';
        $transout_qty   = !empty($transout)?$transout['transout_qty']:'';
        $purpose        = $this->get_info_purpose($transout_purpose);
        $fcode          = 'dnrc_'.$purpose;
        $warehouse      = '';
        $partname       = $this->get_info_part_name($fpartnum);
        $stock          = 0;

        if($fserialnum == 'nosn')$fserialnum = 'NOSN'; //untuk NOSN di perbesar

        if(!empty($transout_num)){ // Jika no serial number dengan nomor part sudah memiliki no trans out.
            $rs_stock = $this->get_info_part_stock($fcode,$fpartnum);
            
            if(!empty($rs_stock)){ // jika part sudah terdaftar pada table stok
                foreach($rs_stock as $r_stock){
                    $warehouse = $r_stock['warehouse'];
                    $partname = $r_stock['part'];
                    $stock = $r_stock['stock'];
                }
                if($stock > 0){ //jika stocknya tidak kosong
                    //START ADD CART
                    $dataInfo = array(
                        'ftransoutnum'  =>$transout_num,
                        'fpartnum'      =>$fpartnum, 
                        'fpartname'     =>$partname, 
                        'fserialnum'    =>$fserialnum, 
                        'fcartid'       =>$cartid, 
                        'fqty'          =>$transout_qty, 
                        'fuser'         =>$fuser, 
                        'fname'         =>$fname, 
                        'fcode'         =>$fcode
                    );
        
                    $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_incoming_cwh_add_cart'), 'POST', FALSE);
                    
                    if($rs_data->status){ // Jika add data cart berhasil
                        $response = array(
                            'status' => TRUE,
                            'message'=> 'Insert data Success'
                        );
                    }else{ //Jika tidak bisa add data cart
                        $response = array(
                            'status' => FALSE,
                            'message'=> 'Failed add data to cart'
                        );
                    }
                    //END ADD CART
                }else{ //Jika Stock Kosong
                    $response = array(
                        'status' => FALSE,
                        'message'=> 'Stock Not Available'
                    );
                }
            }else{ //jika part belum terdaftar pada stock
                if(!empty($partname)){ //jika tidak ada di data master part
                    $initval = 0;
                    if($this->insert_part_stock($fpartnum,$initval)){ //jika berhasil melakukan insert data stok
                        $partstock = $initval;
                        $response = array(
                            'status' => FALSE,
                            'message'=> 'Stock Not Available'
                        );
                    }else{ //jika gagal insert
                        $response = array(
                            'status' => FALSE,
                            'message'=> 'Adding master parts failed, Stock Not Available, please check again.'
                        );
                    }
                }else{ //jika part belum terdaftar pada master part
                    $response = array(
                        'status' => FALSE,
                        'message'=> 'Part Number can\'t identified, please check again.'
                    );
                }
                
            }
        }else{ // jika part dan serial belum terdaftar pada incoming
            $response = array(
                'status' => FALSE,
                'message'=> 'SN and PN can\'t found in incoming transaction'
            );
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode($response)
            );
    }

    /**
     * FUNCTION ADD to cart
     * Response Status AND Message.
     * @var fpartname
     * @var fserialnum
     */
    public function add_cart(){
        $response = array(
            'status' => 0,
            'message'=> 'Failed add cart, Unknown error'
        );
        
        $fcode      = 'dnrc';
        $fuser      = $this->vendorUR;
        $fname      = $this->name;
        $fpartnum   = $this->input->post('fpartnum', TRUE);
        $fserialnum = $this->input->post('fserialnum', TRUE);
        $cartid     = $this->session->userdata ( 'cart_session' ).$this->apirole;
        $partstock  = 0;
        $partname   = $this->get_info_part_name($fpartnum); // get info partname
        $rs_stock   = $this->get_info_part_stock($fcode, $fpartnum); //get info stock of part
        
        

        if(!empty($rs_stock)){ // check if result from data is not empty
            foreach ($rs_stock as $s){
                $partstock = (int)$s["stock"];
            }
        }
        
        if($partstock > 0){ //check if part stock is not zero
            $dataInfo = array(
                'fpartnum'  =>$fpartnum, 
                'fpartname' =>$partname, 
                'fserialnum'=>$fserialnum, 
                'fcartid'   =>$cartid, 
                'fqty'      =>$fqty, 
                'fuser'     =>$fuser, 
                'fname'     =>$fname, 
                'fcode'     =>$fcode
            );

            $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_'.$this->apirole.'_cart'), 'POST', FALSE);
            if($rs_data->status){
                $response = array(
                    'status' => TRUE,
                    'message'=> 'Insert data Success'
                );
            }else{
                $response = array(
                    'status' => FALSE,
                    'message'=> 'Failed add data to cart'
                );
            }
        }else{
            $response = array(
                'status' => FALSE,
                'message'=> 'Stock is empty.'
            );
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * FUNCTION to get list of datatable cart
     * @var cartid
     */
    public function get_list_cart_datatable(){
        $rs = array();  
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata( 'cart_session' ).$this->apirole;
        $arrWhere = array('funiqid'=>$cartid);
        
        $rs_data = send_curl($arrWhere, $this->config->item('api_incoming_cwh_list_cart'), 'POST', FALSE);
        //var_dump($rs_data);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            //$rs_stock = $this->get_info_part_stock($fcode, $partnum);
            //foreach ($rs_stock as $s){
                //$partstock = (int)$s["stock"];
            //}
            $transoutnum = filter_var($r->tmp_transout_num, FILTER_SANITIZE_STRING);
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
            $row['transout'] = $transoutnum;
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
     * FUNCTION to get list of cart (data only)
     * @var cartid
     */
    private function get_list_cart(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' ).$this->apirole;

        $arrWhere = array('funiqid'=>$cartid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_incoming_cwh_list_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
       
        $data = array();
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
        //    $rs_stock = $this->get_info_part_stock($fcode, $partnum);
        //    foreach ($rs_stock as $s){
        //        $partstock = (int)$s["stock"];
        //    }
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_qty, FILTER_SANITIZE_NUMBER_INT);
            $transoutnum = filter_var($r->tmp_transout_num, FILTER_SANITIZE_STRING);

            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['serialno'] = $serialnum;
            // $row['stock'] = $partstock;
            $row['fsl_code'] = $r->fsl_code;
            $row['transout'] = $transoutnum;
            $row['qty'] = (int)$qty;
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * FUNCTION to get total parts on cart
     * @var cartid
     */
    public function get_total_cart(){
        $rs = array();
        $arrWhere = array();
        
        $cartid = $this->session->userdata ( 'cart_session' ).$this->apirole;

        $arrWhere = array('funiqid'=>$cartid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_incoming_cwh_total_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $total = 0;
        if(!empty($rs)){
            foreach ($rs as $r){
                $total = $r->total > 0 ? (int)$r->total : 0;
            }    
        }
        
        return $total;
    }
    
    /**
     * FUNCTION to update quantity of cart or else
     * @var fid ID cart
     * @var fqty QTY of cart was update
     */
    public function update_cart(){        
        $response = array(
            'status' => FALSE,
            'message'=> 'Unknown Error, Failed to update cart'
        );
        
        $fid = $this->input->post('fid', TRUE);
        $fqty = $this->input->post('fqty', TRUE);

        $arrWhere = array('fid'=>$fid, 'fqty'=>$fqty);
        $rs_data = send_curl($arrWhere, $this->config->item('api_update_'.$this->apirole.'_cart'), 'POST', FALSE);

        if($rs_data->status)
        {
            $response = array(
                'status' => TRUE,
                'message' => 'Success Update.'
            );
        }
        else
        {
            $response = array(
                'status' => FALSE,
                'message'=> 'Failed to update cart'
            );
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    /**
     * FUNCTION to delete cart
     * @var fid ID of cart
     */
    public function delete_cart(){
        $response = array(
            'status' => FALSE,
            'message'=> 'Unknown Error, Failed to delete cart'
        );
        
        $fid = $this->input->post('fid', TRUE);

        $arrWhere = array('fid'=>$fid);
        $rs_data = send_curl($arrWhere, $this->config->item('api_incoming_cwh_delete_cart'), 'POST', FALSE);
        if($rs_data->status)
        {
            $response = array(
                'status' => TRUE,
                'message' => 'Success Delete'
            );
        }
        else
        {
            $response = array(
                'status' => FALSE,
                'message'=> 'Unknown Error, Failed to delete cart'
            );
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    
    //                          TRANS FUNCTION
    ///////////////////////////////////////////////////////////////////////
    
    /**
     * FUNCTION to check transaction
     * @var ftransnum Transaction Number
     */
    public function check_trans(){
        $rs = array();
        $arrWhere = array();
        $response = array('status'=>FALSE);
        
        $ftransnum = $this->input->post('ftransnum');
        
        $arrWhere['ftransnum'] = ($ftransnum!='' || !is_null($ftransnum))?$ftransnum:'';
        $rs = send_curl($arrWhere, $this->config->item('api_get_fsltocwh_get_trans'), 'POST', FALSE);
        $result = $rs->status ? $rs->result : array();
        
        if(!empty($result)){
            foreach($result as $v){
                $purpose = filter_var($v->fsltocwh_purpose, FILTER_SANITIZE_STRING);
            }
            
            $count = count((array)$result);
            if($count>0){
                $response = array(
                    'status' => TRUE,
                    'purpose' => $purpose
                );
            }else{
                $response = array(
                    'status' => FALSE,
                    'message' => 'Transaction Cann\'t found'
                );
            }
            
        }else{
            $response = array(
                'status' => FALSE,
                'message' => 'Transaction Cann\'t found'
            );
        }
        
        return $this->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode($response)
            );
       
    }
    
    /**
     * This function is used to complete transaction
     */
    public function submit_trans(){
        $response = array(
            'status' => FALSE,
            'message'=> 'Unknown Error, Failed to submit transaction.'
        );
        
        $fcode          = $this->repo;
        $cartid         = $this->session->userdata ( 'cart_session' ).$this->apirole;
        $date           = date('Y-m-d'); 
        $ftransnotes    = $this->input->post('ftransnote', TRUE);
        $fpurpose       = $this->input->post('fpurpose', TRUE);
        $fqty           = $this->get_total_cart();
        $createdby      = $this->session->userdata ( 'vendorUR' );
        $kodeNum        = '';
        $kode           = $this->prefixCodeTrans.$fpurpose; // parameter code
        $purpose_tbl    = 'dnrc';
        
        $arrParam = array(
            'fparam'=>$kode, 
            'fcode'=>$fcode
        );
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_'.$this->apirole.'_num'), 'POST', FALSE);// Get trans number
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        if(($fqty < 1) || (empty($fqty))){ // Jika total cart kosong atau kurang dari 1
            $this->session->set_flashdata('error', 'Failed to submit transaction data');
            $response = array(
                'status' => FALSE, 
                'message'=> 'Failed to submit, cart is empty. please input to cart minimal 1 of parts'
            );
        }else{  // Jika total cart tidak kosong
            $data_tmp = array();
            if($transnum === ""){ // Jika generasi transaksi no tidak berhasil
                $response = array(
                    'status' => FALSE, 
                    'message'=> 'Failed to create transaction number, please try again.'
                );
            }else{ // Jika generasi transaksi no berhasil
                $data_tmp = $this->get_list_cart(); // ambil semua data cart berdasarkan uniqid
                $dataDetail = array();
                $total_qty = 0;
                $err_detail = array();
                $err_stock = array();
                if(!empty($data_tmp)){ // Jika data cart tidak kosong
                    foreach ($data_tmp as $d){ //melakukan loop per data cart
                        $rs_stock = $this->get_info_part_stock($d['fsl_code'], $d['partno']);
                        $partstock = 1;
                        foreach ($rs_stock as $s){
                            $partstock = (int)$s["stock"];
                        }
                        
                        $dataDetail = array( //parameter to detail transaction
                            'ftransno'=>$transnum, 
                            'ftransoutnum'=>$d['transout'],
                            'ftransoutpurpose' => $d['fsl_code'],
                            'fpartnum'=>$d['partno'], 
                            'fserialnum'=>$d['serialno'], 
                            'fqty'=>$d['qty']);
                        $sec_res = send_curl($this->security->xss_clean($dataDetail), $this->config->item('api_add_'.$this->apirole.'_trans_detail'),'POST', FALSE);
                        if(!$sec_res->status) {$err_detail[] = $d['partno'];}
                        $total_qty += (int)$d['qty'];
                        
                        
                        $dataUpdateStock = array(
                            'fcode'=>$d['fsl_code'], 
                            'fpartnum'=>$d['partno'], 
                            'fqty'=>$partstock-$d['qty'], 
                            'fflag'=>'N');
                        
                        $update_stock_res = send_curl( //update stock by fsl code and part number
                            $this->security->xss_clean($dataUpdateStock), 
                            $this->config->item('api_edit_stock_part_stock'), 
                            'POST', FALSE);
                        if(!$update_stock_res->status) {$err_stock[] = $d['partno'];}    
                        
                    }

                    if($total_qty < 1){ // jika qty kurang dari 1
                        $response = array(
                            'status' => FALSE, 
                            'message'=> 'Failed to submit, qty to send is 0.'
                        );
                    }else{ // Jika qty lebih dari 1
                        $dataTrans = array(
                            'ftransno'=>$transnum, 
                            'fdate'=>$date, 
                            'fpurpose'=>$fpurpose, 
                            'ftransnotes'=>$ftransnotes, 
                            'fqty'=>$total_qty, 
                            'fuser'=>$createdby
                        );
                        $main_res = send_curl(
                                $this->security->xss_clean($dataTrans), 
                                $this->config->item('api_add_'.$this->apirole.'_trans'), 
                                'POST', FALSE);
                        if($main_res->status){ // Jika Sukses menambahkan data transaksi
                            $arrWhere = array('fcartid'=>$cartid);
                            $rem_res = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_incoming_cwh_clear_cart'), 'POST', FALSE); // Clear Cart
                            if($rem_res->status){ // Jika sukses menghapus cart
                                $response = array(
                                    'status' => TRUE,
                                    'message' => 'Sucess Submit Transaction : '.$transnum
                                );
                                
                            }else{ // Jika Gagal Hapus cart
                                $response = array(
                                    'status' => FALSE, 
                                    'message'=> 'Success Create Transaction, But can\'t clear the cart, please clear manually.'
                                );
                            }
                        }else{ //Jika gagal create Transaksi
                            $this->session->set_flashdata('error', 'Failed to submit transaction data');
                            $response = array(
                                'status' => FALSE, 
                                'message'=> $main_res->message
                            );
                        }
                    }
                }else{ // Jika isi cart kosong
                    $response = array(
                        'status' => FALSE, 
                        'message'=> 'Transaction cart is empty, please input part data minimal ( 1 ) parts.'
                    );
                }
            }
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }
    

    //                          PRINT FUNCTION
    ///////////////////////////////////////////////////////////////////////

    private function snt($input, $type){
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
            'ftransnum' => $transnum
        );
        
        //Ambil Database
        // $rs_data = json_decode(json_encode(send_curl($params, $this->config->item('api_get_data_detail_'.$this->apirole), 'POST', FALSE)),TRUE);
        // $rs = $rs_data['status'] ? $rs_data['result'] : array();
        
        $rdata = send_curl($params, $this->config->item('api_get_'.$this->apirole.'_get_trans'), 'POST', FALSE);
        $rs_data = $rdata->status ? $rdata->result : array();
        $rst = (array)json_decode(json_encode($rs_data));
        $rs = (array)$rst[0];

        $rs_detail = send_curl($params, $this->config->item('api_get_'.$this->apirole.'_get_trans_detail'), 'POST', FALSE);
        $rsdetailt = json_decode(json_encode($rs_detail->status ? $rs_detail->result : array()));
        $rsdetail = (array)$rsdetailt;
        //var_dump((array)$rsdetail);

        //HARDCODE Untuk Kode Purpose
        $purpose = $this->field_purpose[$rs[$this->db_table_name.'_purpose']];
        
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
        $pdf->Cell(($width*(15/100)),7,'Purpose '.': '.$purpose.' SPAREPART Repair TO CWH',0,0,'L');
        $pdf->setFont('Arial','',10);
        $pdf->Cell(($width*(25/100)),7,'',0,1, 'L');
        
        //row 1
        //$pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->setFont('Arial','BU',11); //ARIAL BOLD 10
        $pdf->Cell(($width*(45/100)),7,'Warehouse Pusat',0,0,'L');
        $pdf->setFont('Arial','',10); //ARIAL NORMAL 10
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'D-D: '.$this->snt($rs[$this->db_table_name.'_date'], 'string'),1,1,'C');
            
        //row 2
        //col 1 Wrap
        $py = $pdf->GetY();
        $px = $pdf->GetX();
        $pdf->MultiCell(($width*(45/100)),7,'PT. Wincor Nixdorf Indonesia ( DIEBOLD NIXDORF )     Komplek Infinia Park NO. 10E                                          Jakarta 12940',0,'L');
        //col 2 Wrap
        $px += ($width*(67.5/100));
        $pdf->SetXY($px,$py);
        $pdf->MultiCell(($width*(22.5/100)),7,$this->snt($rs[$this->db_table_name.'_num'],'string'),1,'C');
        
        //row 3
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        //$pdf->Cell(($width*(22.5/100)),7,$this->snt($rs['fsltocwh_airwaybill'], 'string'),1,1,'C');
        $pdf->Cell(($width*(22.5/100)),7,'',0,1,'L');
        
        //row 4
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        //$pdf->Cell(($width*(22.5/100)),7,$this->snt($rs['delivery_by'], 'string').' - '.$this->snt($rs['delivery_time_type'], 'string'),1,1,'C');
        $pdf->Cell(($width*(22.5/100)),7,'',0,1,'L');
        
        //row 5
        $pdf->Cell(($width*(22.5/100)),7,'Attn : '.'Ibu Siti Aisah'.' :',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        //$pdf->Cell(($width*(22.5/100)),7,'ETA: '.$this->snt($rs['fsltocwh_eta'], 'string'),1,1,'C');
        $pdf->Cell(($width*(22.5/100)),7,'',0,1,'L');
        
        //row 6
        $pdf->Cell(($width*(22.5/100)),7,'         '.'021-25527763',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,0,'L');
        $pdf->Cell(($width*(22.5/100)),7,'',0,1,'C');
        
        
        
        //row 9
        $pdf->setFont('Arial','B',11); //ARIAL BOLD 11
        $pdf->Cell(($width*(90/100)),7,'REPAIR DELIVERY',0,1,'C');
        
        //row 10
        $pdf->SetFont('Arial','B',10); //ARIAL BOLD 10
        $pdf->Cell(($width*(7.5/100)),6,'Item',1,0,'C');
        $pdf->Cell(($width*(15/100)),6,'Part #',1,0,'C');
        $pdf->Cell(($width*(30/100)),6,'Description',1,0,'C');
        $pdf->Cell(($width*(15/100)),6,'Serial No.',1,0,'C');
        $pdf->Cell(($width*(15/100)),6,'Incoming',1,0,'C');
        $pdf->Cell(($width*(7.5/100)),6,'Qty',1,1,'C');
        
        //row Data
        $start_awal=$pdf->GetX(); 
        $get_xxx = $pdf->GetX();
        $get_yyy = $pdf->GetY();
        
        $height = 7;
        
        $i = 1;
        $j = 1;
        $batas_page = 20;
        $pdf->SetFont('Arial','',8); //ARIAL NORMAL 10
        if(count($rs)>0 AND count($rsdetail)>0){
            
            foreach($rsdetail as $dtt){
                $dt = (array)$dtt;
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
                $get_xxx+=($width*(30/100));
                
                $pdf->SetXY($get_xxx,$get_yyy);
                $pdf->MultiCell(($width*(15/100)),$height,$this->snt($dt['serial_number'], 'string'),1,'L');
                $get_xxx+=($width*(15/100));
                
                $pdf->SetXY($get_xxx,$get_yyy);
                $pdf->MultiCell(($width*(15/100)),$height,$this->snt($dt['repairorder_num'], 'string'),1,'L');
                $get_xxx+=($width*(15/100));
                
                $pdf->SetXY($get_xxx,$get_yyy);
                $pdf->MultiCell(($width*(7.5/100)),$height,$this->snt($dt['dt_'.$this->db_table_name.'_qty'], 'string'),1,'C');
                $get_xxx+=($width*(7.5/100));
        
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
        $pdf->Cell(($width*(30/100)),7,'Sent By,',0,0,'C');
        $pdf->Cell(($width*(30/100)),7,'',0,0,'C');
        $pdf->Cell(($width*(30/100)),7,'Received By,',0,1,'C');
        
        //row 15 TTD
        $pdf->SetFont('Arial','',9); //ARIAL NORMAL 10
        //$pdf->Cell(($width*(30/100)),6,$rs['fsl_name'],0,0,'L');
        $pdf->Cell(($width*(30/100)),6,'',0,0,'C');
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
        $pdf->Cell(($width*(30/100)),7,'______________',0,0,'C');
        $pdf->Cell(($width*(30/100)),7,'',0,0,'C');
        $pdf->Cell(($width*(30/100)),7,'______________',0,1,'C');
        
        //Output
        $title = 'Repair_Delivery_'.$transnum;
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
    
    public function get_eta(){
        $rs = array();
        $arrPOST = array();
        $eta = '';
        
        $ffsl_code = $this->repo;
        $fdelivery_type = $this->input->post('fdelivery_type', TRUE);
        $fdelivery_by = $this->input->post('fdelivery_by', TRUE);
        if($fdelivery_type == 'SAMEDAY'){
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

        if($fdelivery_by == 'INTCOURIER'){
            $date_now = date("Y-m-d", time() + 86400);
            $response = array(
                'status' => 1,
                'ETA'=> $date_now
            );
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode($response)
            );
        
    }
    

    //                          PRIVATE FUNCTION
    ///////////////////////////////////////////////////////////////////////

    /**
     * GET THE TRANSACTION NUMBER 
     * @var param as parameter eg: 'DN-CWH'
     */
    private function get_trans_num($param){
        $arrWhere = array('fparam'=>$param);
        $transnum = send_curl($arrWhere, $this->config->item('api_get_trans_num'), 'POST', FALSE);
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($transnum)
        );
    }
    
    /**
     * GET LISTING OF WAREHOUSE
     * @var output default is 'json' you can put 'array' if result as array
     */
    private function get_list_warehouse($output = "json"){
        $rs = array();
        $arrWhere = array();

        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
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
     * Function to get list of part as array
     * @var fpartnum
     * @var fpartname
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
    
    /**
     * Function to get of part info
     * @var fpartnum
     */
    private function get_info_part($fpartnum){
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
     * Function to get of part stock information
     * @var fcode as table name prefix code
     * @var fpartnum
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
     * Function to get info name of part
     * @var fpartnum
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
     * Function to get info name of warehouse
     * @var fcode
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
     * Function to get info part and serial num on progress
     * @var partnum 
     * @var serialnumber
     */
    private function get_info_transout_num($partnum, $serialnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere_trans = array(
            'fpartnum'      => $partnum,
            'fserialnum'    => $serialnum,
        );
        $transout = array();
        $sc_detail = send_curl($arrWhere_trans,$this->config->item('api_check_part_'.$this->apirole),'POST',FALSE);//ketika verifikasi ro sudah jadi, harusnya di arahkan ke sana.
        //var_dump($sc_detail);
        $rs_detail = (is_object($sc_detail) AND $sc_detail->status) ? $sc_detail->result : array();
        if(!empty($rs_detail)){
            //var_dump($rs_detail)
            foreach ($rs_detail as $r) {
                $transout['transout_num'] = filter_var($r->repairorder_num, FILTER_SANITIZE_STRING);
                $transout['transout_purpose'] = filter_var($r->repairorder_purpose,FILTER_SANITIZE_STRING);
                $transout['transout_qty'] = filter_var($r->dt_repairorder_qty,FILTER_SANITIZE_STRING);
            }
        }
        return $transout;
    }
    
    /**
     * Function to get info purpose to database stock
     * @var purpose_code 
     */
    private function get_info_purpose($purpose_code){
        $result = '';
        switch($purpose_code){
            case 'RBP' : $result = 'badpart'; break;
            case 'RBS' : $result = 'badstock'; break;
        }
        return $result;
    }

    /**
     * Function to get info cart
     * @var fcode as table name prefix code
     * @var fpartnum
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

    /**
     * Function to get info cart
     * @var fcode as table name prefix code
     * @var fpartnum
     * @var initval is optional
     */
    private function insert_part_stock($fcode,$fpartnum, $initval = '3'){
        $ret = FALSE;
        $dataInfo = array(
            'fcode'      => strtoupper($fcode),
            'fpartnum'   => $fpartnum,
            'fminval'    => 3,
            'finitval'   => $initval,
            'flastval'   => 0,
            'fflag'      => 'Y',
        );
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_part_stock'), 'POST', FALSE);
        if(is_object($rs_data)){
            if($rs_data->status){
                $ret = TRUE;
            }
        }
        
        return $ret;
    }
    
    
}