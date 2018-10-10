<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CSupplyFromVendor (TicketsController)
 * CSupplyFromVendor Class to control Tickets.
 * @author : Sigit Pray & Abas
 * @version : 2.0
 * @since : sept 2018
 */
class CSupplyFromVendor extends BaseController
{

    private $alias_controller_name = 'supply-from-vendor';
    private $stock_want_to_update = 'wsps';
    private $api_role = 'supplyfromvendor';

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isStaff()){
            redirect('cl');
        }
    }

    public function index()
    {        
        $data = array();
        if($this->isStaff()){
            redirect('cl');
        }
        $this->global['pageTitle'] = 'Supply From Vendor - '.APP_NAME;
        $this->global['pageMenu'] = 'Supply From Vendor';
        $this->global['contentHeader'] = 'Supply From Vendor';
        $this->global['contentTitle'] = 'Supply From Vendor';
        $this->global['role'] = $this->role;
        $this->global['name'] = $this->name;
        $data['link_new'] = base_url('new-'.$this->alias_controller_name.'-trans');
        $data['link_get_data'] = base_url('api-'.$this->alias_controller_name.'-get-datatable');
        $this->loadViews('front/'.$this->alias_controller_name.'/index', $this->global, $data);
    }

    public function add(){
        $data = array();
        if($this->isStaff()){
            redirect('cl');
            
        }else{
            $this->global['pageTitle'] = 'Supply From Vendor - '.APP_NAME;
            $this->global['pageMenu'] = 'Supply From Vendor';
            $this->global['contentHeader'] = 'Supply From Vendor';
            $this->global['contentTitle'] = 'Supply From Vendor';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $data['alias_controller_name']=$this->alias_controller_name;
            $data['list_part'] = $this->get_list_part();
            $this->loadViews('front/'.$this->alias_controller_name.'/create', $this->global, $data);
        }
    }
    

///////////////////////////////////////////////////////////////////////////
//FUNCTION API
///////////////////////////////////////////////////////////////////////////

    public function get_list_datatable(){
        $rs = array();
        $arrWhere = array();
        
        $fcode ='WSPS';
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $arrWhere = array('fcode'=>$fcode,'fdate1'=>$fdate1,'fdate2'=>$fdate2);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_'.$this->api_role), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->sfvendor_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->sfvendor_date, FILTER_SANITIZE_STRING);
            $purpose = filter_var($r->sfvendor_purpose, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->sfvendor_qty, FILTER_SANITIZE_NUMBER_INT);
            $user = filter_var($r->user_key, FILTER_SANITIZE_STRING);
            $notes = filter_var($r->sfvendor_notes, FILTER_SANITIZE_STRING);
            //$button = '<a href="'.base_url("print-incoming-supply/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';

            $row['transnum'] = $transnum;
            $row['transdate'] = $transdate;
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

    public function add_cart(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to add data'
        );
        
        $fcode = $this->repo;
        $fuser = $this->vendorUR;
        $fname = $this->name;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fpartname = $this->get_info_part_name($fpartnum);
        $fqty = $this->input->post('fqty', TRUE);
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        
        $dataInfo = array(
            'fpartnum'=>$fpartnum, 
            'fpartname'=>$fpartname, 
            'fcartid'=>$cartid, 
            'fqty'=>$fqty, 
            'fuser'=>$fuser, 
            'fname'=>$fname,
            'fcode'=>$fcode
        );
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_'.$this->api_role.'_cart'), 'POST', FALSE);

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

    public function get_list_cart_datatable(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        $arrWhere = array('funiqid'=>$cartid);
        
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->api_role.'_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();

        $data = array();
        $partname = "";
        $partstock = "";
        if(!empty($rs)){
            foreach ($rs as $r) {
                $id = filter_var($r->tmp_sfvendor_id, FILTER_SANITIZE_NUMBER_INT);
                $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                $rs_stock = $this->get_info_part_stock($this->stock_want_to_update, $partnum);
                foreach ($rs_stock as $s){
                    $partstock = (int)$s["stock"];
                }
                $cartid = filter_var($r->tmp_sfvendor_uniqid, FILTER_SANITIZE_STRING);
                $qty = filter_var($r->tmp_sfvendor_qty, FILTER_SANITIZE_NUMBER_INT);

                $row['id'] = $id;
                $row['partno'] = $partnum;
                $row['partname'] = $partname;
                $row['stock'] = $partstock;
                $row['qty'] = (int)$qty;

                $data[] = $row;
            }
        }else{
            $data = array();
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }

    public function get_total_cart(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        $arrWhere = array('funiqid'=>$cartid);
        
        $rs_data = send_curl($arrWhere, $this->config->item('api_total_'.$this->api_role.'_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if(!empty($rs)){
            $total = 0;
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
        $rs_data = send_curl($arrWhere, $this->config->item('api_delete_'.$this->api_role.'_cart'), 'POST', FALSE);

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

    public function submit_trans(){
        $response = array();
        $success_response = array('status' => 1);
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to submit transaction, Please check your data and try again1.'
        );

        //var form session
        $fcode = 'WSPS';
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        $createdby = $this->session->userdata ('vendorUR');

        //var form
        $date = date('Y-m-d');
        $fqty = $this->input->post('fqty', TRUE);
        $ftransnotes = $this->input->post('fnotes', TRUE);
        $fponum = $this->input->post('fponum', TRUE);
        
        //var form static
        $kode = 'IN-VDR';
        $fpurpose = 'VDR';
        
        //post doc
        $arrParam = array('fparam'=>$kode, 'fcode'=>$fcode);
        $rs_transnum = send_curl($arrParam, $this->config->item('api_get_'.$this->api_role.'_num'), 'POST', FALSE);
        $transnum = $rs_transnum->status ? $rs_transnum->result : "";
        
        //result if no qty
        if(($fqty < 1) || (empty($fqty))){
            $this->session->set_flashdata('error', 'Please insert minimum 1 part number.');
            $response = $error_response;
        }elseif(!$transnum || $transnum === ''){ 
            $response = $error_response;
        }else{
           
            $data_tmp = $this->get_list_cart();
            $dataDetail = array();
            if(!empty($data_tmp)){
                //insert transaction
                $dataTrans = array(
                    'ftransno'=>$transnum, 
                    'fponum' => $fponum,
                    'fdate'=>$date, 
                    'fpurpose'=>$fpurpose, 
                    'ftransnotes'=>$ftransnotes, 
                    'fcode'=>$fcode,
                    'fqty'=>$fqty, 
                    'fuser'=>$createdby
                );
                $total_qty = 0;
                $main_res = send_curl(
                        $this->security->xss_clean($dataTrans), 
                        $this->config->item('api_add_'.$this->api_role.'_trans'), 
                        'POST', FALSE);
                //if transaction is success        
                if($main_res->status)
                {
                    $id_trans = $main_res->result;
                    foreach ($data_tmp as $d){
                        //get info Stock
                        $rs_stock = $this->get_info_part_stock($this->stock_want_to_update, $d['partno']);
                        $partstock = 1;
                        foreach ($rs_stock as $s){
                            $partstock = (int)$s["stock"];
                        }

                        //insert detail
                        $dataDetail = array(
                            'ftransno'=>$transnum, 
                            'fpartnum'=>$d['partno'],
                            'fqty'=>$d['qty'],
                            'fid_trans'=>$id_trans
                        );
                        $sec_res = send_curl(
                            $this->security->xss_clean($dataDetail), 
                            $this->config->item('api_add_'.$this->api_role.'_trans_detail'), 
                            'POST', FALSE
                        );
                        
                        if(!$sec_res->status){
                            $rts = $this->cancel_trans($id_trans);
                            $error_response = array(
                                'status' => 0,
                                'message'=> 'Failed to submit transaction, Please check your data and try again.'
                            );
                            $response = $error_response;
                            return $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($response));
                        }

                        //update stock by fsl code and part number
                        $total_qty += (int)$d['qty'];
                        $dataUpdateStock = array(
                            'fcode'=>$this->stock_want_to_update, 
                            'fpartnum'=>$d['partno'], 
                            'fqty'=>$partstock+$d['qty'], 
                            'fflag'=>'N');
                        $update_stock_res = send_curl(
                            $this->security->xss_clean($dataUpdateStock), 
                            $this->config->item('api_edit_stock_part_stock'), 
                            'POST', FALSE);
                        if(!$sec_res->status){
                            $rts = $this->cancel_trans($id_trans);
                            $error_response = array(
                                'status' => 0,
                                'message'=> 'Failed to Update Stock, Please check your data and try again.'
                            );
                            $response = $error_response;
                            return $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($response));
                        }
                        
                    }

                    //clear cart list data
                    $arrWhere = array('fcartid'=>$cartid);
                    $rem_res = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_clear_'.$this->api_role.'_cart'), 'POST', FALSE);
                    if($rem_res->status){
                        $success_response = array(
                            'status' => 1,
                            'message' => $transnum
                        );
                        $response = $success_response;
                    }else{
                        $response = $error_response;
                    }
                }else{
                    //$rts = $this->cancel_trans($id_trans);
                    $response = $error_response;
                }
            }else{
                $response = $error_response;
            }
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }

    public function checkpartnum(){
        $rs = array();
        $success_response = array();
        $error_response = array();
        $response = array();
        $fcode = 'wsps';
        $fpartname = null;

        $fpartnum = $this->input->post('fpartnum', TRUE);
        $arrWhere = array('fpartnum'=>$fpartnum);

        $rs_data = send_curl($arrWhere, $this->config->item('api_info_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();

        //apakah part num terdaftar
        if(!empty($rs)){
            foreach($rs AS $r1){
                $partname = filter_var($r1->part_name, FILTER_SANITIZE_STRING);
                $arrWhere2 = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum);
            
                $rs_data2 = send_curl($arrWhere2, $this->config->item('api_info_part_stock'), 'POST', FALSE);
                $rs2 = $rs_data2->status ? $rs_data2->result : array();

                //apakah no part ada di wsps
                if(!empty($rs2)){
                    $success_response = array(
                        'status' => 1,
                        'message'=> 'Sparepart <strong>'.$partname.'</strong> is available'
                    );
                    $response = $success_response;
                }else{
                    //jika tidak ada maka dilakukan penginisiasi data
                    $dataInfo = array('fcode'=> $fcode, 'fpartnum'=> $fpartnum, 'fminval'=> 3, 'finitval'=> 0, 
                    'flastval'=> 0, 'fflag'=> 'Y');
                    $rs_data2 = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_part_stock'), 'POST', FALSE);

                    if($rs_data2->status){
                        $success_response = array(
                            'status' => 1,
                            'message'=> 'Sparepart <strong>'.$partname.'</strong> is available'
                        );
                        $response = $success_response;
                    }else{
                        $error_response = array(
                            'status' => 0,
                            'message'=> 'There is an error while searching the data'
                        );
                        $response = $error_response;
                    }
                }
            }
        }else{
            $error_response = array(
                'status' => 0,
                'message'=> 'Sparepart data is not available'
            );
            $response = $error_response;
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode($response)
        );
    }

    

///////////////////////////////////////////////////////////////////////////
// BUILT IN FUNCTION API
///////////////////////////////////////////////////////////////////////////

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
            //$row['warehouse'] = $this->get_info_warehouse_name($code);
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

    private function get_list_cart(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $cartid = $this->session->userdata ( 'cart_session' )."in";
        $arrWhere = array('funiqid'=>$cartid);
        
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_'.$this->api_role.'_cart'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
       
        $data = array();
        $partstock = "";
        foreach ($rs as $r) {
            $id = filter_var($r->tmp_sfvendor_id, FILTER_SANITIZE_NUMBER_INT);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $cartid = filter_var($r->tmp_sfvendor_uniqid, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->tmp_sfvendor_qty, FILTER_SANITIZE_NUMBER_INT);
            
            $row['id'] = $id;
            $row['partno'] = $partnum;
            $row['partname'] = $partname;
            $row['qty'] = (int)$qty;
 
            $data[] = $row;
        }
        
        return $data;
    }

    private function cancel_trans($id){
        $arrWHere = array('fid' => $id);
        $rs_data = send_curl($arrWhere, $this->config->item('api_cancel_trans_'.$this->api_role.''), 'POST', FALSE);
        $rs = $rs_data->status;
        return $rs;
    }

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
}