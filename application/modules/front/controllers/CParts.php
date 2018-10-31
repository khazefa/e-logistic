<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CParts (CPartsController)
 * CParts Class to control Data Parts.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CParts extends BaseController
{
    private $cname = 'spareparts';
    private $view_dir = 'front/parts/';
    private $readonly = TRUE;
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isWebAdmin()){
            //load page
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'List Sparepart - '.APP_NAME;
        $this->global['pageMenu'] = 'List Sparepart';
        $this->global['contentHeader'] = 'List Sparepart';
        $this->global['contentTitle'] = 'List Sparepart';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['readonly'] = $this->readonly;
        $data['classname'] = $this->cname;
        $data['url_list'] = base_url($this->cname.'/list/json');
        $this->loadViews($this->view_dir.'index', $this->global, $data);
    }
    
    /**
     * This function used to manage data
     */
    public function lists()
    {
        if($this->isWebAdmin()){
            $this->global['pageTitle'] = 'Manage Sparepart - '.APP_NAME;
            $this->global['pageMenu'] = 'Manage Sparepart';
            $this->global['contentHeader'] = 'Manage Sparepart';
            $this->global['contentTitle'] = 'Manage Sparepart';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $this->loadViews('front/parts/lists', $this->global, NULL);
        }else{
            redirect('data-spareparts');
        }
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
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $pid = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
                    $partnum = $r->part_number;
                    $row['partno'] = $partnum;
                    $row['name'] = $this->common->nohtml($r->part_name);
                    $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
                    $row['stock'] = '0';
                    $row['returncode'] = filter_var($r->part_return_code, FILTER_SANITIZE_STRING);
                    $row['machine'] = filter_var($r->part_machine, FILTER_SANITIZE_STRING);
                    
                    $row['button'] = '<div class="btn-group dropdown">';
                    $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                    $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                    $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/edit/").$partnum.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                    $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/remove/").$partnum.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
                    $row['button'] .= '</div>';
                    $row['button'] .= '</div>';

                    $data[] = $row;
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r) {
                    $pid = filter_var($r->part_id, FILTER_SANITIZE_NUMBER_INT);
                    $partnum = $r->part_number;
                    $row['partno'] = $partnum;
                    $row['name'] = $this->common->nohtml($r->part_name);
                    $row['desc'] = filter_var($r->part_desc, FILTER_SANITIZE_STRING);
                    $row['stock'] = '0';
                    $row['returncode'] = filter_var($r->part_return_code, FILTER_SANITIZE_STRING);
                    $row['machine'] = filter_var($r->part_machine, FILTER_SANITIZE_STRING);
                    
                    $data[] = $row;
                }
                $output = $data;
            break;
        }
        return $output;
    }
    
    /**
     * This function is used to get detail information
     */
    public function get_edit($fpartnum){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fpartnum'=>$fpartnum);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_parts'), 'POST', FALSE);
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
     * This function is used to load the add new form
     */
    function add()
    {
        if($this->isWebAdmin()){
            $this->global['pageTitle'] = "Add New Sparepart - ".APP_NAME;
            $this->global['pageMenu'] = 'Add New Sparepart';
            $this->global['contentHeader'] = 'Add New Sparepart';
            $this->global['contentTitle'] = 'Add New Sparepart';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['classname'] = $this->cname;
            $this->loadViews($this->view_dir.'create', $this->global, $data);
        }else{
            redirect($this->cname.'/view');
        }
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fdesc = $this->input->post('fdesc', TRUE);
        $fmachine = $this->input->post('fmachine', TRUE);

        $dataInfo = array('fpartnum'=>$fpartnum, 'fname'=>$fname, 'fdesc'=>$fdesc, 'fmachine'=>$fmachine);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_parts'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect($this->cname.'/view');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect($this->cname.'/add');
        }
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
                redirect($this->cname.'/view');
            }

            $this->global['pageTitle'] = "Edit Data Sparepart - ".APP_NAME;
            $this->global['pageMenu'] = 'Edit Data Sparepart';
            $this->global['contentHeader'] = 'Edit Data Sparepart';
            $this->global['contentTitle'] = 'Edit Data Sparepart';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['classname'] = $this->cname;
            $data['records'] = $this->get_edit($fkey);
            $this->loadViews($this->view_dir.'edit', $this->global, $data);
        }else{
            redirect($this->cname.'/view');
        }
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fpartnum = $this->input->post('fpartnum', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fdesc = $this->input->post('fdesc', TRUE);
        $fmachine = $this->input->post('fmachine', TRUE);

        $dataInfo = array('fpartnum'=>$fpartnum, 'fname'=>$fname, 'fdesc'=>$fdesc, 'fmachine'=>$fmachine);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_parts'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect($this->cname.'/view');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect($this->cname.'/edit/'.$fpartnum);
        }
    }
    
    /**
     * This function is used to delete the data
     * @return boolean $result : TRUE / FALSE
     */
    function delete($fkey = NULL)
    {
        $arrWhere = array();
        $arrWhere = array('fpartnum'=>$fkey);

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_parts'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect($this->cname.'/view');
    }
    
    /**
     * This function is used to load the add new form
     */
    function add_import()
    {
        if($this->isWebAdmin()){
            $this->global['pageTitle'] = "Import Data Sparepart - ".APP_NAME;
            $this->global['pageMenu'] = 'Import Data Sparepart';
            $this->global['contentHeader'] = 'Import Data Sparepart';
            $this->global['contentTitle'] = 'Import Data Sparepart';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $data['classname'] = $this->cname;
            $this->loadViews($this->view_dir.'import', $this->global, NULL);
        }else{
            redirect($this->cname.'/view');
        }
    }
    
    /*
     * file value and type check during validation
     */
    public function file_check(){
        $file_type = array('.csv');
        if(!empty($_FILES['fupload']['name']))
        {
            $ext = strtolower(strrchr($_FILES['fupload']['name'],"."));
            if(in_array($ext,$ext_array))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('file_check','Attachment allowed only csv');
                return false;
            }
        }else{
           $this->form_validation->set_message('file_check','upload field is required');
                return false;
        }
    }

    public function import_csv_adv(){
        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Settings
//        $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
//        $ini_val = ini_get('upload_tmp_dir') . DIRECTORY_SEPARATOR . "wlupload";
//        $targetDir = $ini_val ? $ini_val : sys_get_temp_dir() . DIRECTORY_SEPARATOR . "wlupload";
        $targetDir = FCPATH . "uploads/csv_s";
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
        //
        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $this->security->sanitize_filename($_FILES["file"]["name"]);
        } else {
            $fileName = uniqid("file_");
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

        // Remove old temp files	
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                $filename = $this->security->sanitize_filename($_FILES["file"]["name"]);
                if(!empty($filename))  
                {
                    $allowed_ext = array("csv");
                    $tmp = explode('.', $filename);
                    $extension = end($tmp);
                    if(in_array($extension, $allowed_ext))  
                    {
                        ob_implicit_flush(true);
                        ini_set('auto_detect_line_endings',TRUE);
                        $file_data = fopen($_FILES["file"]["tmp_name"], 'r');  
                        if ($file_data) {
        //                    fgetcsv($file_data);
                            fgetcsv($file_data, 0, "|");
                            $i = 0;
                            while($row = fgetcsv($file_data, '', "|")) 
                            {
                                $formid = $row[16];
                                $claimno = $row[17];
                                if (strrpos($formid, '0') == strlen($formid) - 1) {
                                    $claim = strrpos($claimno, '-') ? substr($claimno, 0, -3) : $claimno;
                                    $date1 = $row[5];
                                    $dater = null;
                                    if(!empty($date1) || $date1 != 0){
                                        $dayr1 = substr($date1, 0, 2);
                                        $monthr1 = substr($date1, 2, 2);
                                        $yearr1 = substr($date1, -4);
                                        $dater = $yearr1."-".$monthr1."-".$dayr1;
                                        $dater = date('Y-m-d', strtotime($dater));
                                    }
                                    $date2 = $row[7];
                                    $dates = null;
                                    if(!empty($date2) || $date2 != 0){
                                        $days1 = substr($date2, 0, 2);
                                        $months1 = substr($date2, 2, 2);
                                        $years1 = substr($date2, -4);
                                        $dates = $years1."-".$months1."-".$days1;
                                        $dates = date('Y-m-d', strtotime($dates));
                                    }
                                    $data_insert = array(
                                        'dt_policy_num' => $row[0],
                                        'dt_member_num' => $row[4],
                                        'dt_date_received' => $dater,
                                        'dt_date_received_batch' => $row[6],
                                        'dt_date_scanned' => $dates,
                                        'dt_type' => $row[8],
                                        'dt_date_med' => $row[11],
                                        'dt_form_id' => $row[16],
                                        'dt_claim_id' => $claim."-".$row[18],
        //                                'dt_claim_id2' => $row[18],
                                        'created_by' => $this->vendorId,
                                        'created_date' => date('Y-m-d H:i:sa'),
                                    );
                                    if(strpos($claim, '99999999') !== false){
                                        $result = $this->MSeed->insert_data($this->security->xss_clean($data_insert));
                                        $i++;
                                    }else{
                                        $arrWhere = array('claim_id'=>$claim."-".$row[18],'is_deleted'=>0);
                                        $arrWhere1 = array('dt_claim_id'=>$claim."-".$row[18],'is_deleted'=>0);
                                        $cnt_detailsub = (int)$this->MDetailSub->check_data_exists($arrWhere);
                                        $cnt_seed = (int)$this->MSeed->check_data_exists($arrWhere1);
                                        if($cnt_detailsub > 0){
                                            if($cnt_seed < 1){
                                                $result = $this->MSeed->insert_data($this->security->xss_clean($data_insert));
                                                $i++;
                                            }else{
                                                //data already exists
        //                                        $this->debug_to_console("data ".$claim."-".$row[18]." already exists in data seed!");
                                            }
                                        }else{
                                            //data not available in detail submission
        //                                    $this->debug_to_console("data ".$claim."-".$row[18]." not available in detail submission!");
                                        }
                                    }
                                }
                                else
                                {
                                    //continue
                                }

                            }
                            if($result > 0){
                                $response = array(
                                    'status' => 1,
                                    'message' => 'Data sukses diupload dengan jumlah '.$i.' data'
                                );
                            }else{
                                $response = array(
                                    'status' => 0,
                                    'message' => 'Data tidak dapat diupload atau sudah pernah diupload.'
                                );    
                            }
                        } else {
                            $response = array(
                                'status' => 0,
                                'message' => 'Unable to open file: '.$filename
                            );
                        }

                    }else{
                        $response = array(
                            'status' => 0,
                            'message' => 'Extension not allowed.'
                        );
                    }
                }else{
                    $response = array(
                        'status' => 0,
                        'message' => 'Something wrong with file: '.$filename
                    );
                }
                return $this->output
                ->set_content_type('application/json')
                ->set_output(
                    json_encode($response)
                );
                
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }	


        // Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {	
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off 
            rename("{$filePath}.part", $filePath);
        }  
    }
}