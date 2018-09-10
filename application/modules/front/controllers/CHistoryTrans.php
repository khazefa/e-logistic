<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CHistoryTrans (HistoryTransController)
 * CHistoryTrans Class to control Transaction History.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CHistoryTrans extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if(!$this->isSuperAdmin()){
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect('cl');
    }
}