<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CReports (CReportsController)
 * CReports Class to control Reports.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CReports extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect('cl');
    }
    
    public function report_consumed_part(){
        $this->global['pageTitle'] = 'Report Consumed Part - '.APP_NAME;
        $this->global['pageMenu'] = 'Report Consumed Part';
        $this->global['contentHeader'] = 'Report Consumed Part';
        $this->global['contentTitle'] = 'Report Consumed Part';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $this->loadViews('front/reports/consumed-part', $this->global, NULL);
    }
}