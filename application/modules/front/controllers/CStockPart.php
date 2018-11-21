<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CStockPart (CStockPartController)
 * CStockPart Class to control Data Stock Parts.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CStockPart extends BaseController
{
    private $cname = 'spareparts-stock';
    private $view_dir = 'front/stock-part/';
    private $readonly = TRUE;
    private $hasCoverage = FALSE;
    private $hasHub = FALSE;
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isWebAdmin() || $this->isSpv() || $this->isStaff()){
            if($this->isStaff()){
                $this->readonly = FALSE;
            }elseif($this->isSpv()){
                $this->readonly = TRUE;
                $this->hasHub = TRUE;
                $this->hasCoverage = TRUE;
            }else{
                $this->readonly = FALSE;
                $this->hasHub = TRUE;
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
        $this->global['pageTitle'] = 'List Stock - '.APP_NAME;
        $this->global['pageMenu'] = 'List Stock';
        $this->global['contentHeader'] = 'List Stock';
        $this->global['contentTitle'] = 'List Stock';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;

        $data['readonly'] = $this->readonly;
        $data['hashub'] = $this->hasHub;
        $data['classname'] = $this->cname;
        if($this->hasHub){
            $data['list_warehouse'] = $this->get_list_warehouse();
        }
        $data['url_list'] = base_url($this->cname.'/onhand-list/json');
        $data['url_list_detail'] = base_url($this->cname.'/detail-list');
        $this->loadViews($this->view_dir.'index', $this->global, $data);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_onhand_list($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
        $isParam = FALSE;
        
        if($this->hasHub){
            $fcode = $this->input->get('fcode', TRUE);
            if(!empty($fcode)){
                $code = $fcode;
                $arrWhere['fcode'] = $code;
            }
        }else{
            $code = $this->repo;
            $arrWhere['fcode'] = $code;
        }
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_detail_fsl_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
                    $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
                    $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                    $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                    $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                    $qtyonhand = filter_var($r->qty_onhand, FILTER_SANITIZE_NUMBER_INT);
                    $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                    $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);

                    $row['code'] = $code;
                    $row['partno'] = $partno;
                    $row['partname'] = $partname;
                    $row['minstock'] = $minstock;
                    $row['initstock'] = $initstock;
                    $row['onhand'] = $qtyonhand;
                    if($initflag === "Y"){
                        $row['stock'] = $initstock;
                    }else{
                        $row['stock'] = $stock;
                    }
                    $row['initflag'] = $initflag;

                    $data[] = $row;
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r) {
                    $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
                    $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
                    $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                    $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                    $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                    $qtyonhand = filter_var($r->qty_onhand, FILTER_SANITIZE_NUMBER_INT);
                    $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                    $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);

                    $row['code'] = $code;
                    $row['partno'] = $partno;
                    $row['partname'] = $partname;
                    $row['minstock'] = $minstock;
                    $row['initstock'] = $initstock;
                    $row['onhand'] = $qtyonhand;
                    if($initflag === "Y"){
                        $row['stock'] = $initstock;
                    }else{
                        $row['stock'] = $stock;
                    }
                    $row['initflag'] = $initflag;

                    $data[] = $row;
                }
                $output = $data;
            break;
        }
        return $output;
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function central()
    {        
        $this->global['pageTitle'] = 'List Stock - '.APP_NAME;
        $this->global['pageMenu'] = 'List Stock';
        $this->global['contentHeader'] = 'List Stock';
        $this->global['contentTitle'] = 'List Stock';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;

        $data['classname'] = $this->cname;
        $data['hashub'] = $this->hasHub;
        if($this->hasHub){
            $data['list_warehouse'] = $this->get_list_warehouse();
        }
        $data['url_list'] = base_url($this->cname.'/central-list/json');
        $data['url_list_detail'] = base_url($this->cname.'/detail-list');
        $this->loadViews($this->view_dir.'index-central', $this->global, $data);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_central_list($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
        $isParam = FALSE;
        
        $fcode = "WSPS";
        $arrWhere['fcode'] = $fcode;
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_detail_fsl_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
                    $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
                    $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                    $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                    $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                    $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                    $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);

                    $row['code'] = $code;
                    $row['partno'] = $partno;
                    $row['partname'] = $partname;
                    $row['initstock'] = $initstock;
                    $row['minstock'] = $minstock;
                    if($initflag === "Y"){
                        $row['stock'] = $initstock;
                    }else{
                        $row['stock'] = $stock;
                    }

                    $data[] = $row;
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r) {
                    $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
                    $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
                    $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                    $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                    $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                    $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                    $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);

                    $row['code'] = $code;
                    $row['partno'] = $partno;
                    $row['partname'] = $partname;
                    $row['initstock'] = $initstock;
                    $row['minstock'] = $minstock;
                    if($initflag === "Y"){
                        $row['stock'] = $initstock;
                    }else{
                        $row['stock'] = $stock;
                    }

                    $data[] = $row;
                }
                $output = $data;
            break;
        }
        return $output;
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
        
        $fcode = $this->repo;
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_fsl_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
                    $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
                    $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                    $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                    $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                    $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                    $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
                    
                    $row['code'] = $code;
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
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r) {
                    $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
                    $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
                    $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
                    $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
                    $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
                    $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
                    $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
                    $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
                    
                    $row['code'] = $code;
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
                $output = $data;
            break;
        }
        return $output;
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        $arrWhere = array();

        $fcode = $this->repo;
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
            $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
            $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
            $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            $row['code'] = $code;
            $row['partno'] = $partno;
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
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get list for part subtitute datatables
     */
    public function get_list_partsub_datatable($fcode){
        $rs = array();
        $arrWhere = array();
        
        if(empty($fcode) || $fcode == ""){
            $fcode = $this->repo;
        }
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_fsl_sub_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_parts = array();
        $names = '';
        foreach ($rs as $r) {
            $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
            $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
            $partsub = filter_var($r->partsub, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            if(!empty($partsub)){
                $names = '<ul class="list-unstyled">';
                $e_partsub = explode(';', $partsub);
                $data_parts = array();
                foreach ($e_partsub as $n){
                    $n = trim($n);
                    if(!empty($n)){
                        array_push($data_parts, $this->get_stock_by($code, $n));
                    }
                }
                foreach ($data_parts as $datas){
                    foreach($datas as $d){
//                        $names .= '<li style="display:inline; padding-left:5px;">'.$d["name"].'</li>';
                        $names .= '<li>'.$d["partno"].' -> '.$d["partname"].' -> '.$d["stock"].'</li>';
                    }
                }
                $names .= '</ul>';
            }else{
                $names = '-';
            }
            $row['code'] = $code;
            $row['partno'] = $partno;
            $row['partname'] = $partname;
            $row['stock'] = $stock;
            $row['partnosub'] = $names;
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
        exit();
    }
    
    /**
     * This function is used to get detail information
     */
    public function get_stock_by($fcode, $fpartnum){
        $rs = array();
        $arrWhere = array();
        
        if(empty($fcode) || $fcode == ""){
            $fcode = $this->repo;
        }
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_fsl_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
            $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
//            $qtyonhand = filter_var($r->qty_onhand, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            $row['code'] = $code;
            $row['partno'] = $partno;
            $row['partname'] = $partname;
            $row['initstock'] = $initstock;
            $row['minstock'] = $minstock;
//            $row['onhand'] = $qtyonhand;
            if($initflag === "Y"){
                $row['stock'] = $initstock;
            }else{
                $row['stock'] = $stock;
            }
            $row['initflag'] = $initflag;
 
            $data[] = $row;
        }
        
        return $data;
//        return $this->output
//        ->set_content_type('application/json')
//        ->set_output(
//            json_encode(array('data'=>$data))
//        );
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_fsl_sub_datatable($fcode){
        $rs = array();
        $arrWhere = array();

        if(empty($fcode) || $fcode == ""){
            $fcode = $this->repo;
        }
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_fsl_sub_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
            
        $data_parts = array();
        $data_stocks = array();
        foreach ($rs as $r) {
            $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
            $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
            $partsub = filter_var($r->partsub, FILTER_SANITIZE_STRING);
            $partname = filter_var($r->part_name, FILTER_SANITIZE_STRING);
            $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            if(!empty($partsub) || ($partsub != "")){
                if($partsub != "NO SUBTITUTION"){
                    $e_partsub = explode(';', $partsub);
                    foreach ($e_partsub as $n){
                        if(!empty($n)){
//                            if(!in_array($n, $data_stocks, true)){
//                                array_push($data_stocks, $this->get_list_info_stock($code, trim($n)));
//                            }
//                            array_push($data_stocks, $this->get_list_info_stock($code, trim($n)));
                            array_push($data_parts, trim($n));
                            array_unique($data_parts);
                        }
                    }
                }
            }
        }
//        var_dump($data_parts);exit();
        foreach ($data_parts AS $dp){
            array_push($data_stocks, $this->get_stock_by($code, $dp));
        }
//        var_dump($data_stocks);exit();
        foreach ($data_stocks as $datas){
            foreach($datas as $d){
                $row['code'] = $d["code"];
                $row['partno'] = $d["partno"];
                $row['partname'] = $d["partname"];
                $row['minstock'] = $d["minstock"];
                $row['initstock'] = $d["initstock"];
                $row['stock'] = $d["stock"];
                $row['initflag'] = $d["initflag"];

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
     * This function used to load the first screen of the user
     */
    public function detail($fcode)
    {
        $upfcode = strtoupper($fcode);
        $lofcode = strtolower($fcode);
        $fslname = $this->get_warehouse_name($upfcode);
        if($this->isWebAdmin()){
            $this->global['pageTitle'] = 'Part Stock in '.$fslname.' - '.APP_NAME;
            $this->global['pageMenu'] = 'Part Stock in '.$fslname;
            $this->global['contentHeader'] = 'Part Stock in '.$fslname;
            $this->global['contentTitle'] = 'Part Stock in '.$fslname;
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['code'] = $upfcode;
            
            $arrWhere = array('fcode'=>$fcode);
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_part_stock'), 'POST', FALSE);
            $status = $rs_data->status ? TRUE : FALSE;
            if($status){
                $this->loadViews('front/stock-part/lists-detail', $this->global, $data);
            }else{
                ?>
                <script type="text/javascript">
                    alert("Data Stock for your choosen Warehouse is not ready in system!");
                    history.back();
                </script>
                <?php
            }
            
        }else{
            redirect('data-spareparts-stock');
        }
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_detail(){
        $rs = array();
        $arrWhere = array();

        $fcode = $this->input->post('fcode', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        
        //Parse Data for cURL
        $arrWhere = array('fcode'=>strtoupper($fcode), 'fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_history_part_e'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $transnum = filter_var($r->outgoing_num, FILTER_SANITIZE_STRING);
            $transdate = filter_var($r->created_at, FILTER_SANITIZE_STRING);
            $ticketnum = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
            $eg_name = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $partner = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $eg_name_2 = filter_var($r->engineer_2_name, FILTER_SANITIZE_STRING);
            $fpurpose = filter_var($r->outgoing_purpose, FILTER_SANITIZE_STRING);
            $partnum = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $serialnum = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
            $qty = filter_var($r->dt_outgoing_qty, FILTER_SANITIZE_NUMBER_INT);
            $fslcode = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $fslname = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            
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
            $row['transdate'] = $transdate;
            $row['ticket'] = $ticketnum;
            $row['reqby'] = $eg_name;
            $row['takeby'] = $eg_name_2;
            $row['partnum'] = $partnum;
            $row['serialnum'] = $serialnum;
            $row['qty'] = $qty;
            $row['purpose'] = $purpose;
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get lists for populate data
     */
    private function get_list_warehouse(){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $isParam = FALSE;
        
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
        
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
            $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
            $row['spv'] = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
 
            if($this->hasCoverage){
                if(in_array($row['code'], $e_coverage)){
                    $data[] = $row;
                }
            }else{
                $data[] = $row;
            }
        }
        
        return $data;
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
     * This function is used to get list information described by function name
     */
    public function get_list_info($fkey){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fkey);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $id = filter_var($r->stock_id, FILTER_SANITIZE_NUMBER_INT);
            $code = filter_var($r->stock_fsl_code, FILTER_SANITIZE_STRING);
            $partno = filter_var($r->stock_part_number, FILTER_SANITIZE_STRING);
            $minstock = filter_var($r->stock_min_value, FILTER_SANITIZE_NUMBER_INT);
            $initstock = filter_var($r->stock_init_value, FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_var($r->stock_last_value, FILTER_SANITIZE_NUMBER_INT);
            $initflag = filter_var($r->stock_init_flag, FILTER_SANITIZE_STRING);
            
            $row['code'] = $code;
            $row['partno'] = $partno;
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
        
        return $data;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if(!$this->readonly){
            $this->global['pageTitle'] = "Add New Stock Parts - ".APP_NAME;
            $this->global['pageMenu'] = 'Add New Stock Parts';
            $this->global['contentHeader'] = 'Add New Stock Parts';
            $this->global['contentTitle'] = 'Add New Stock Parts';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;
            
            $data['classname'] = $this->cname;
            $data['hashub'] = $this->hasHub;
            if($this->hasHub){
                $data['list_warehouse'] = $this->get_list_warehouse();
            }
            $data['url_list'] = base_url($this->cname.'/list/json');
            $data['url_check_part'] = base_url('spareparts-stock/check-part');
            $this->loadViews($this->view_dir.'create', $this->global, $data);
        }else{
            redirect($this->cname.'/view-central');
        }
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {
        $success_response = array();
        $error_response = array();

        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = (int)$this->input->post('fqty', TRUE);

        $dataInfo = array('fcode'=> $fcode, 'fpartnum'=> $fpartnum, 'fminval'=> 0, 'finitval'=> $fqty, 
        'flastval'=> 0, 'fflag'=> 'Y');
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_part_stock'), 'POST', FALSE);

        if($rs_data->status)
        {
            $success_response = array(
                'status' => 1,
                'message'=> $rs_data->message
            );
            $response = $success_response;
        }
        else
        {
            $error_response = array(
                'status' => 0,
                'message'=> $rs_data->message
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
     * This function is used load edit information
     * @param $fkey : Optional : This is data unique key
     */
    function edit($fkey = NULL)
    {
        if($this->isWebAdmin()){
            if($fkey == NULL)
            {
                redirect($this->cname.'/view-central');
            }

            $this->global['pageTitle'] = "Edit Data Stock Parts - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Stock Parts';
            $this->global['contentHeader'] = 'Edit Data Stock Parts';
            $this->global['contentTitle'] = 'Edit Data Stock Parts';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['records'] = $this->get_list_info($fkey);
            $this->loadViews($this->view_dir.'edit', $this->global, $data);
        }else{
            redirect($this->cname.'/view-central');
        }
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $success_response = array();
        $error_response = array();

        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fqty = (int)$this->input->post('fqty', TRUE);
        $partstock = $this->get_stock($fcode, $fpartnum);

        $dataInfo = array('fcode'=> $fcode, 'fpartnum'=> $fpartnum, 'fqty'=> (int)$partstock+$fqty, 'fflag'=> 'N');
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_stock_part_stock'), 'POST', FALSE);

        if($rs_data->status)
        {
            $success_response = array(
                'status' => 1,
                'message'=> $rs_data->message
            );
            $response = $success_response;
        }
        else
        {
            $error_response = array(
                'status' => 0,
                'message'=> $rs_data->message
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
     * This function is used to delete the data
     * @return boolean $result : TRUE / FALSE
     */
    function delete($fkey = NULL)
    {
        $arrWhere = array();
        $arrWhere = array('fkey'=>$fkey);

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_part_stock'), 'POST', FALSE);
        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }
        redirect($this->cname.'/view-central');
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
     * This function is used to get list for datatables
     */
    public function get_part_nearby(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->repo;
        $partnum = $this->input->post('fpartnum', TRUE);
        
        $arrWhere = array('fcode'=>$fcode, 'fpartnum'=>$partnum);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        if($rs){
            $wh_nearby = "";
            foreach ($rs as $r){
                $wh_nearby = $r->fsl_nearby;
            }
            $exp_wh_nearby = explode(";", $wh_nearby);
            $arrData = array();
            foreach ($exp_wh_nearby as $code){
                $result = $this->get_detail_stock($code, $partnum);

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
     * This function is used to check part
     */
    public function check_part(){
        $rs = array();
        $arrWhere = array();
        $success_response = array();
        $error_response = array();
        
        $fcode = $this->repo;
        $fpartnum = $this->input->post('fpartnum', TRUE);

        //Parse Data for cURL
        $rs_data = send_curl(array('fpartnum'=>$fpartnum), $this->config->item('api_info_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();

        if(empty($rs)){
            $error_response = array(
                'status' => 2,
                'message'=> 'Sparepart data is not available in data master, please contact central warehouse!'
            );
            $response = $error_response;
        }else{
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

                if($stock > 0){
                    $success_response = array(
                        'status' => 1,
                        'stock'=> $stock,
                        'message'=> 'Spartpart data is available, stock = '.$stock
                    );
                    $response = $success_response;
                }else{
                    $error_response = array(
                        'status' => 1,
                        'stock'=> $stock,
                        'message'=> 'Spartpart data is available but out of stock!'
                    );
                    $response = $error_response;
                }
            }else{
                $error_response = array(
                    'status' => 0,
                    'message'=> 'Sparepart data is not available, please add this sparepart data in data stock'
                );
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
     * This function is used to get list information described by function name
     */
    private function get_detail_stock($fcode, $partnum){
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
            $row['warehouse'] = $this->get_warehouse_name($code);
            $row['part'] = $this->get_part_name($partno);
            
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