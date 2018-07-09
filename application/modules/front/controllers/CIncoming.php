<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CIncoming (TicketsController)
 * CIncoming Class to control Tickets.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CIncoming extends BaseController
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
        $this->global['pageTitle'] = 'Tickets - '.APP_NAME;
        $this->global['pageMenu'] = 'Tickets';
        $this->global['contentHeader'] = 'Tickets';
        $this->global['contentTitle'] = 'Input Used Spareparts';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;

        $rsparts = array();
        $rsfsl = array();
        $rsparts = $this->converter->objectToArray($this->get_parts());
        $rsfsl = $this->converter->objectToArray($this->get_warehouses());
        
        $data["ticket_num"] = $this->get_ticket_num();
        $data["list_parts"] = $rsparts;
        $data["list_warehouses"] = $rsfsl;
        
        $this->loadViews('front/tickets/index', $this->global, $data);
    }
}