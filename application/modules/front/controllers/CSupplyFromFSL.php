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
class CSupplyFromFSL extends BaseController
{
    private $cname = 'supply-fsl-to-fsl';
    private $cname_transfer = 'transfer-to-fsl';
    private $cname_atm = 'atm';
    private $cname_warehouse = 'warehouse';
    private $cname_cart = 'cart';
    private $view_dir = 'front/supply-from-fsl/';
    private $readonly = TRUE;
    private $hasCoverage = FALSE;
    private $hasHub = FALSE;
    private $cart_postfix = 'ins';
    private $cart_sess = '';
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSpv() || $this->isStaff()){
            if($this->isStaff()){
                $this->readonly = FALSE;
                $this->cart_sess = $this->session->userdata ( 'cart_session' ).$this->cart_postfix;
            }elseif($this->isSpv()){
                $this->readonly = TRUE;
                $this->hasHub = TRUE;
                $this->hasCoverage = TRUE;
            }
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {        
        $this->global['pageTitle'] = 'List Received Stock - '.APP_NAME;
        $this->global['pageMenu'] = 'List Received Stock';
        $this->global['contentHeader'] = 'List Received Stock';
        $this->global['contentTitle'] = 'List Received Stock';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $data['readonly'] = $this->readonly;
        $data['hashub'] = $this->hasHub;
        $data['classname'] = $this->cname;
        $data['url_list'] = base_url($this->cname.'/list/json');
        if($this->hasHub){
            $data['list_warehouse'] = $this->get_list_warehouse();
        }
        $this->loadViews($this->view_dir.'index', $this->global, $data);
    }
    
    /**
     * This function is used to get list information described by function name
     */
    private function get_list_warehouse(){
        $rs = array();
        $arrWhere = array();
        
        $fcoverage = $this->session->userdata ( 'ovCoverage' );
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        $arrWhere = array('fdeleted'=>0, 'flimit'=>0);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
 
            if($this->hasCoverage){
                if(in_array($row['code'], $e_coverage)){
                    if($row['code'] !== "WSPS"){
                        $data[] = $row;
                    }
                }
            }else{
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
        $isParam = FALSE;

        //Parameters for cURL
        $fdest_code = $this->repo;
        $fdate1 = $this->input->get('fdate1', TRUE);
        $fdate2 = $this->input->get('fdate2', TRUE);
        $fpurpose = "RWH";
        $fstatus = "open";
        $coverage = !empty($_GET['fcoverage']) ? implode(';',$_GET['fcoverage']) : "";
        
        if($this->hasHub){
            if($this->hasCoverage){
                if(empty($coverage)){
                    $fcoverage = $this->session->userdata ( 'ovCoverage' );
                }else{
                    if (strpos($coverage, ',') !== false) {
                        $fcoverage = str_replace(',', ';', $coverage);
                    }else{
                        $fcoverage = $coverage;
                    }
                }
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
            
            $fcode = "";
        }else{
            $fcode = $this->repo;
        }
        
        //Parameters for cURL
        $arrWhere = array('fdest_code'=>$fdest_code, 'fdate1'=>$fdate1, 'fdate2'=>$fdate2, 'fpurpose'=>$fpurpose, 'fstatus'=>$fstatus);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                    $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $transfer_from = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                    $fsldest = filter_var($r->fsl_dest, FILTER_SANITIZE_STRING);
                    $transfer_to = filter_var($r->fsl_dest_name, FILTER_SANITIZE_STRING);
                    $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
                    $status = filter_var($r->outgoing_status, FILTER_SANITIZE_STRING);
                    $curdatetime = new DateTime();
                    $datetime2 = new DateTime($transdate);
                    $interval = $curdatetime->diff($datetime2);
        //            $elapsed = $interval->format('%a days %h hours');
                    $elapsed = $interval->format('%a days');

                    $row['transnum'] = $transnum;
                    $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
                    $row['transfer_from'] = $transfer_from;
                    $row['transfer_to'] = $transfer_to;
                    $row['qty'] = $qty;
                    $row['status'] = $status === "open" ? strtoupper($status)."<br> (".$elapsed.")" : strtoupper($status);
                    $row['button'] = '<a href="'.base_url($this->cname."/print/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';

                    if($fpurpose === "RWH"){
                        if($this->hasHub){
                            if(in_array($fslcode, $e_coverage)){
                                $data[] = $row;
                            }
                        }else{
                            $data[] = $row;
                        }
                    }
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r) {
                    $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                    $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
                    $qty = filter_var($r->outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                    $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
                    $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                    $transfer_from = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                    $fsldest = filter_var($r->fsl_dest, FILTER_SANITIZE_STRING);
                    $transfer_to = filter_var($r->fsl_dest_name, FILTER_SANITIZE_STRING);
                    $notes = filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
                    $status = filter_var($r->outgoing_status, FILTER_SANITIZE_STRING);
                    $curdatetime = new DateTime();
                    $datetime2 = new DateTime($transdate);
                    $interval = $curdatetime->diff($datetime2);
        //            $elapsed = $interval->format('%a days %h hours');
                    $elapsed = $interval->format('%a days');

                    $row['transnum'] = $transnum;
                    $row['transdate'] = date('d/m/Y H:i', strtotime($transdate));
                    $row['transfer_from'] = $transfer_from;
                    $row['transfer_to'] = $transfer_to;
                    $row['qty'] = $qty;
                    $row['status'] = $status === "open" ? strtoupper($status)."<br> (".$elapsed.")" : strtoupper($status);

                    if($fpurpose === "RWH"){
                        if($this->hasHub){
                            if(in_array($fslcode, $e_coverage)){
                                $data[] = $row;
                            }
                        }else{
                            $data[] = $row;
                        }
                    }
                }
                $output = $data;
            break;
        }
        return $output;
    }
    
    /**
     * This function is used to load the add new form
     */
    public function add()
    {
        $this->global['pageTitle'] = 'Add Supply FSL to FSL - '.APP_NAME;
        $this->global['pageMenu'] = 'Add Supply FSL to FSL';
        $this->global['contentHeader'] = 'Input Reff No';
        $this->global['contentTitle'] = 'Add Supply FSL to FSL';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        
        $data['classname'] = $this->cname;
        $data['classname_transfer'] = $this->cname_transfer;
        $data['cart_postfix'] = $this->cart_postfix;
        $this->loadViews($this->view_dir.'create', $this->global, $data);
    }
    
    /**
     * This function is used to complete close transfer stock transaction
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
        $date = date('Y-m-d'); 
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $ffe_report = $this->input->post('ffe_report', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        
        //Parameters for cURL
        $arrWhere = array('ftrans_out'=>$ftrans_out);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();

        if(!empty($rs)){
            $total_qty = 0;
            $partstock = 0;
            $dataUpdateStock = array();
            foreach ($rs as $r) {
                $outgoingnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
                $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
                $qty = filter_var($r->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $return = filter_var($r->return_status, FILTER_SANITIZE_STRING);
                $deleted = filter_var($r->is_deleted, FILTER_SANITIZE_NUMBER_INT);
                $partstock = $this->get_stock($fcode, $partnum);
                
                $dataUpdateStock = array('fcode'=>$fcode, 'fpartnum'=>$partnum, 'fqty'=>(int)$partstock+(int)$qty, 'fflag'=>'N');
                //update stock by fsl code and part number
                $update_stock_res = send_curl($this->security->xss_clean($dataUpdateStock), $this->config->item('api_edit_stock_part_stock'), 
                        'POST', FALSE);
            }
        }
        
        //update outgoing status by outgoing number
        $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'ffe_report'=>$ffe_report, 'fstatus'=>$fstatus);
        $update_status_outgoing = send_curl($this->security->xss_clean($updateOutgoing), $this->config->item('api_update_outgoings_trans'), 
                'POST', FALSE);

        if($update_status_outgoing->status){
            $success_response = array(
                'status' => 1,
                'message' => $ftrans_out
            );
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
     * This function is used to complete close transaction
     */
    public function submit_trans_close(){
        $success_response = array(
            'status' => 1
        );
        $error_response = array(
            'status' => 0,
            'message'=> 'Failed to submit transaction'
        );
        
        $fcode = $this->repo;
        $date = date('Y-m-d'); 
        $ftrans_out = $this->input->post('ftrans_out', TRUE);
        $ffe_report = $this->input->post('ffe_report', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        
        //update outgoing status by outgoing number
        $updateOutgoing = array('ftrans_out'=>$ftrans_out, 'ffe_report'=>$ffe_report, 'fstatus'=>$fstatus);
        $update_status_outgoing = send_curl($this->security->xss_clean($updateOutgoing), $this->config->item('api_update_outgoings_trans'), 
                'POST', FALSE);

        if($update_status_outgoing->status){
            $success_response = array(
                'status' => 1,
                'message' => $ftrans_out
            );
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

    public function print_transaction($ftrans_out){
        $rs = array();
        $arrWhere = array();
        $arrWhere2 = array();

        //Parameters for cURL
        $arrWhere = array('ftrans_out'=>$ftrans_out);
            
        if(empty($ftrans_out) || $ftrans_out = ""){
            redirect('cl');
        }else{
            $orientation = "P";
            $paper_size = "A4";
            $width = 0;
            $height = 0;
            list($width, $height) = set_pdf_size($orientation, $paper_size);

            // config fpdf options
            $config=array($orientation=>'P','size'=>$paper_size);
            $this->load->library('mypdf',$config);
            $this->mypdf->AliasNbPages();
            $this->mypdf->AddPage();
            $this->mypdf->Image(base_url().'assets/public/images/logo.png',10,8,($width*(15/100)),15);
            
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_view_outgoings'), 'POST', FALSE);
            $results = $rs_data->status ? $rs_data->result : array();
            
            $transnum = "";
            $purpose = "";
            $transdate = "";
            $fpurpose = "";
            $fslname = "";
            $fsldest = "";
            $fsldestname = "";
            $notes = "";
            foreach ($results as $r){
                $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
                $get_purpose = $this->config->config['purpose']['out'];
                $purpose = isset($get_purpose[$fpurpose]) ? $get_purpose[$fpurpose] : "-";
                $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
                $transdate = date("d/m/Y H:i:s", strtotime(filter_var($r->created_at, FILTER_SANITIZE_STRING)));
                $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
                $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
                $fsldestcode = filter_var($r->fsl_dest, FILTER_SANITIZE_STRING);
                $fsldestname = filter_var($r->fsl_dest_name, FILTER_SANITIZE_STRING);
                $notes = empty($r->outgoing_notes) ? "-" : filter_var($r->outgoing_notes, FILTER_SANITIZE_STRING);
            }

//            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print
            $this->mypdf->SetFont('Arial','B',11);
            $this->mypdf->Code39(($width*(50/100)),10,$transnum,1,10);
            $this->mypdf->ln(20);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Purpose',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$purpose,1,1, 'L');

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Delivery Notes',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->CellFitScale(($width*(35/100)),7,$notes,1,1, 'L');

            $this->mypdf->ln(5);
            $this->mypdf->setFont('Arial','B',13);
            $this->mypdf->Cell(($width*(60/100)),7,'STOCK TRANSFER FORM',0,0,'R');
            $this->mypdf->ln(5);
            // Garis atas untuk header
//            $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);

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
            
            //Parse Data for cURL
            $rs_data2 = send_curl($arrWhere, $this->config->item('api_list_view_detail_outgoings'), 'POST', FALSE);
            $rslist = $rs_data2->status ? $rs_data2->result : array();
            
            foreach( $rslist as $row )
            {
                $partnum = filter_var($row->part_number, FILTER_SANITIZE_STRING);
                $partname = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                $qty = filter_var($row->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
                $serialnum = filter_var($row->serial_number, FILTER_SANITIZE_STRING);
                $deleted = filter_var($row->is_deleted, FILTER_SANITIZE_NUMBER_INT);

                if($deleted < 1){
                    $this->mypdf->Cell(($width*(15/100)),6,$partnum,1,0);
                    $this->mypdf->CellFitScale(($width*(20/100)),6,$partname,1,0);
                    $this->mypdf->CellFitScale(($width*(7.5/100)),6,$qty,1,0,'C');
                    $this->mypdf->CellFitScale(($width*(15/100)),6,' ',1,0);
                    $this->mypdf->CellFitScale(($width*(18/100)),6,$serialnum,1,0);
                    $this->mypdf->CellFitScale(($width*(5/100)),6,' ',1,0);
                    $this->mypdf->CellFitScale(($width*(10/100)),6,' ',1,1);
                }
            }

            $this->mypdf->ln(1);
            $this->mypdf->SetFont('Arial','',8.5);
            $this->mypdf->drawTextBox('Notes:', $width-20, 12, 'L', 'T');
            $this->mypdf->ln(10);
            $this->mypdf->Cell(($width*(25/100)),6,'Transfered by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Approved by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Processed by:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Received by:',0,1,'L');
            $this->mypdf->ln(12);
            $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$fslname,0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Name:'.$fsldestname,0,1,'L');
            $this->mypdf->ln(0.1);
            $this->mypdf->Cell(($width*(25/100)),6,'Date:'.$transdate,0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,0,'L');
            $this->mypdf->Cell(($width*(25/100)),6,'Date:',0,1,'L');

            $title = 'Transfered Stock #'.$transnum;
            $this->mypdf->SetTitle($title);
    //        $this->mypdf->Output('D', $title.'.pdf');
            return $this->mypdf->Output('D', $title.'.pdf');
        }
    }
    
    /**
     * This function is used to get list information described by function name
     */
    private function get_stock($fcode, $partnum){
        $rs = array();
        $arrWhere = array();
        $val_stock = 0;
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $minval = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            if($initflag === "Y"){
                $val_stock = $initstock;
            }else{
                $val_stock = $stock;
            }
        }
        
        return $val_stock;
    }
    
    /**
     * This function is used to get detail information
     */
    private function get_warehouse_name($fcode){
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
     * This function is used to get detail information
     */
    private function get_part_name($fpartnum){
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
}