<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Dashboard (DashboardController)
 * Dashboard Class to control Dashboard.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class Dashboard extends BaseController
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
        $this->global['pageTitle'] = 'Dashboard - '.APP_NAME;
        $this->global['pageMenu'] = 'Dashboard';
        $this->global['contentHeader'] = 'Dashboard';
        $this->global['contentTitle'] = 'Welcome to E-Logistic';
        $this->global ['ovRole'] = $this->ovRole;
        $this->global ['ovName'] = $this->ovName;
        $this->global ['ovRepo'] = $this->ovRepo;

        $data['logtime'] = tgl_indo(date("Y-m-d"));
        
        $this->loadViews2('superintend/v_dashboard', $this->global, $data, NULL);
    }
}