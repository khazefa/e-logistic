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
        $this->isSignin();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {     
        $this->global['pageTitle'] = 'Outgoing Transaction - '.APP_NAME;
        $this->global['pageMenu'] = 'Outgoing Transaction';
        $this->global['contentHeader'] = 'Outgoing Transaction';
        $this->global['contentTitle'] = 'Outgoing Transaction';
        $this->global ['ovRole'] = $this->ovRole;
        $this->global ['ovName'] = $this->ovName;
        $this->global ['ovRepo'] = $this->ovRepo;

        $this->loadViews2('superintend/outgoing-trans/index', $this->global, NULL);
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
        $fticket = $this->input->post('fticket', TRUE);
        $fpurpose = $this->input->post('fpurpose', TRUE);
        $fstatus = $this->input->post('fstatus', TRUE);
        //Parameters for cURL
        $arrWhere = array('fcode'=>$fcode, 'fdate1'=>$fdate1, 'fdate2'=>$fdate2, 
            'fticket'=>$fticket, 'fpurpose'=>$fpurpose, 'fstatus'=>$fstatus);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_view_outgoings'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
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
            
//            if($this->isSpv()){
//                $row['button'] = '<div class="btn-group dropdown">';
//                $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
//                $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
//                $row['button'] .= '<a class="dropdown-item" href="'.base_url("edit-outgoing-trans/").$transnum.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
//                $row['button'] .= '<a class="dropdown-item" href="'.base_url("remove-outgoing-trans/").$transnum.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
//                $row['button'] .= '</div>';
//                $row['button'] .= '</div>';
//            }elseif($this->isStaff()){
                $row['button'] = '<a href="'.base_url("oversee/print-outgoing-trans/").$transnum.'" target="_blank"><i class="mdi mdi-printer mr-2 text-muted font-18 vertical-middle"></i></a>';
//            }
 
            $data[] = $row;
        }
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );
    }
    
    public function print_transaction($ftrans_out){
        $rs = array();
        $arrWhere = array();
        $arrWhere2 = array();

        //Parameters for cURL
        $arrWhere = array('ftrans_out'=>$ftrans_out);
            
        if(empty($ftrans_out) || $ftrans_out = ""){
            redirect('oversee');
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
            
            //Parse Data for cURL
            $rs_data = send_curl($arrWhere, $this->config->item('api_info_view_outgoings'), 'POST', FALSE);
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
            $fpurpose = "";
            $customer = "";
            $location = "";
            $ssb_id = "";
            $fslname = "";
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
            }

//            $this->mypdf->SetProtection(array('print'));// restrict to copy text, only print
            $this->mypdf->SetFont('Arial','B',11);
            $this->mypdf->Code39(($width*(65/100)),10,$transnum,1,10);
            $this->mypdf->ln(20);

            $this->mypdf->setFont('Arial','B',10);
            $this->mypdf->Cell(($width*(15/100)),7,'Purpose',0,0,'L');
            $this->mypdf->setFont('Arial','',10);
            $this->mypdf->Cell(($width*(25/100)),7,$purpose,1,1, 'L');

            if($fpurpose != "RWH"){
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
                $this->mypdf->Cell(($width*(30/100)),7,$customer,1,1, 'L');

                $this->mypdf->ln(0);

                $this->mypdf->setFont('Arial','B',10);
                $this->mypdf->Cell(($width*(15/100)),7,'Assigned FSE',0,0,'L');
                $this->mypdf->setFont('Arial','',10);
                $this->mypdf->Cell(($width*(25/100)),7,$engineer_name,1,0, 'L');
                $this->mypdf->setFont('Arial','B',10);
                $this->mypdf->Cell(($width*(20/100)),7,'        Location',0,0,'L');
                $this->mypdf->setFont('Arial','',10);
                $this->mypdf->Cell(($width*(30/100)),7,$location,1,1, 'L');

                $this->mypdf->ln(0);

                $this->mypdf->setFont('Arial','B',10);
                $this->mypdf->Cell(($width*(15/100)),7,'FSE ID Number',0,0,'L');
                $this->mypdf->setFont('Arial','',10);
                $this->mypdf->Cell(($width*(25/100)),7,$engineer_id,1,0, 'L');
                $this->mypdf->setFont('Arial','B',10);
                $this->mypdf->Cell(($width*(20/100)),7,'        SSB/ID',0,0,'L');
                $this->mypdf->setFont('Arial','',10);
                $this->mypdf->Cell(($width*(30/100)),7,$ssb_id,1,1, 'L');

                $this->mypdf->ln(5);
                $this->mypdf->setFont('Arial','B',13);
                $this->mypdf->Cell(($width*(60/100)),7,'STOCK REQUEST FORM',0,0,'R');
                $this->mypdf->ln(5);
                // Garis atas untuk header
//                $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);
            }else{
                $this->mypdf->setFont('Arial','B',10);
                $this->mypdf->Cell(($width*(15/100)),7,'Delivery Notes',0,0,'L');
                $this->mypdf->setFont('Arial','',10);
                $this->mypdf->CellFitScale(($width*(35/100)),7,$notes,1,1, 'L');
                
                $this->mypdf->ln(5);
                $this->mypdf->setFont('Arial','B',13);
                $this->mypdf->Cell(($width*(60/100)),7,'STOCK TRANSFER FORM',0,0,'R');
                $this->mypdf->ln(5);
                // Garis atas untuk header
//                $this->mypdf->Line(10, $height/3.9, $width-10, $height/3.9);
            }

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
            return $this->mypdf->Output('D', $title.'.pdf');
        }
    }
}