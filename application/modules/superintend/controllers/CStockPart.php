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
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isSignin();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global ['ovRole'] = $this->ovRole;
        $this->global ['ovName'] = $this->ovName;
        $this->global ['ovRepo'] = $this->ovRepo;
        
        $this->global['pageTitle'] = 'List Stock Parts - '.APP_NAME;
        $this->global['pageMenu'] = 'List Stock Parts';
        $this->global['contentHeader'] = 'List Stock Parts';
        $this->global['contentTitle'] = 'List Stock Parts';
            
        $data['list_data_wh'] = $this->get_list_data_wh();
        $this->loadViews2('superintend/stock-part/lists', $this->global, $data);
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function lists()
    {
        if($this->isWebAdmin()){
            $this->global['pageTitle'] = 'Manage Stock Parts - '.APP_NAME;
            $this->global['pageMenu'] = 'Manage Stock Parts';
            $this->global['contentHeader'] = 'Manage Stock Parts';
            $this->global['contentTitle'] = 'Manage Stock Parts';
            $this->global ['ovRole'] = $this->ovRole;
            $this->global ['ovName'] = $this->ovName;
            $this->global ['ovRepo'] = $this->ovRepo;

            $data['list_data_wh'] = $this->get_list_data_wh();
            $this->loadViews2('superintend/stock-part/lists', $this->global, $data);
        }else{
            redirect('data-spareparts-stock');
        }
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list_datatable(){
        $rs = array();
        $arrWhere = array();

        $fcode = $this->ovRepo;
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
            $fcode = $this->ovRepo;
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
                        array_push($data_parts, $this->get_list_info_stock($code, $n));
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
     * This function is used to get list for datatables
     */
    public function get_list_fsl_datatable($fcode){
        $rs = array();
        $arrWhere = array();

        if(empty($fcode) || $fcode == ""){
            $fcode = $this->ovRepo;
        }
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_fsl_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
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
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    /**
     * This function is used to get detail information
     */
    public function get_list_info_stock($fcode, $fpartnum){
        $rs = array();
        $arrWhere = array();
        
        if(empty($fcode) || $fcode == ""){
            $fcode = $this->ovRepo;
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
            $fcode = $this->ovRepo;
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
            array_push($data_stocks, $this->get_list_info_stock($code, $dp));
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
     * This function is used to get list for datatables
     */
    public function get_m_list_datatable($fcode){
        $rs = array();
        $arrWhere = array();

        if(empty($fcode) || $fcode == ""){
            $fcode = $this->ovRepo;
        }
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_fsl_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
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
//            $row['initflag'] = $initflag;
            
            /**
            $row['button'] = '<div class="btn-group dropdown">';
            $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
            $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-spareparts-stock/").$partno.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
            $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-spareparts-stock/").$partno.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
            $row['button'] .= '</div>';
            $row['button'] .= '</div>';
            */
 
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
     * This function used to load the first screen of the user
     */
    public function detail($fcode)
    {
        $upfcode = strtoupper($fcode);
        $lofcode = strtolower($fcode);
        $fslname = $this->get_info_warehouse_name($upfcode);
        
        $this->global['pageTitle'] = 'Detail List Stock '.$fslname.' - '.APP_NAME;
        $this->global['pageMenu'] = 'Detail List Stock '.$fslname;
        $this->global['contentHeader'] = 'Detail List Stock '.$fslname;
        $this->global['contentTitle'] = 'Detail List Stock '.$fslname;
        $this->global ['ovRole'] = $this->ovRole;
        $this->global ['ovName'] = $this->ovName;
        $this->global ['ovRepo'] = $this->ovRepo;

        $data['code'] = $upfcode;

        $arrWhere = array('fcode'=>$fcode);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_part_stock'), 'POST', FALSE);
        $status = $rs_data->status ? TRUE : FALSE;
        if($status){
            $this->loadViews2('superintend/stock-part/lists-detail', $this->global, $data);
        }else{
            ?>
            <script type="text/javascript">
                alert("Data Stock for your choosen Warehouse is not ready in system!");
                history.back();
            </script>
            <?php
        }
    }
    
    /**
     * This function is used to get lists for json or populate data
     */
    public function get_list_json(){
        $rs = array();
        $arrWhere = array();
        
        $fid = $this->input->post('fid', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fflag = $this->input->post('fflag', TRUE);

        if ($fid != "") $arrWhere['fid'] = $fid;
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fpartnum != "") $arrWhere['fpartnum'] = $fname;
        if ($fflag != "") $arrWhere['fflag'] = $fflag;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
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
            json_encode($data)
        );
    }
    
    /**
     * This function is used to get lists for populate data
     */
    public function get_list_data(){
        $rs = array();
        $arrWhere = array();
        
        $fid = $this->input->post('fid', TRUE);
        $fcode = $this->input->post('fcode', TRUE);
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fflag = $this->input->post('fflag', TRUE);

        if ($fid != "") $arrWhere['fid'] = $fid;
        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fpartnum != "") $arrWhere['fpartnum'] = $fname;
        if ($fflag != "") $arrWhere['fflag'] = $fflag;
//        if ($f_date != ""){
//            $arrWhere['submission_date_1'] = $f_date;
//            $arrWhere['submission_date_2'] = $f_date;
//        }

//        $arrWhere['is_deleted'] = 0;
//        array_push($arrWhere, $arrWhere['is_deleted']);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_part_stock'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_warehouse = array();
        $names = '';
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
     * This function is used to get lists for populate data
     */
    public function get_list_data_wh(){
        $rs = array();
        $arrWhere = array();
        
        $fcode = $this->input->post('fcode', TRUE);
        $fname = $this->input->post('fname', TRUE);

        if ($fcode != "") $arrWhere['fcode'] = $fcode;
        if ($fname != "") $arrWhere['fname'] = $fname;
        
        $fcoverage = $this->session->userdata ( 'ovCoverage' );
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouses'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        $data_nearby = array();
        $names = '';
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
            $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
            $row['spv'] = filter_var($r->fsl_spv, FILTER_SANITIZE_STRING);
 
            if(in_array($row['code'], $e_coverage)){
                $data[] = $row;
            }
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
}