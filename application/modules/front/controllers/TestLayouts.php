<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : TestLayouts (TestLayoutsController)
 * TestLayouts Class to control Data Engineer.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class TestLayouts extends BaseController
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
    
    /**
     * This function is used to load the add new form
     */
    function form_row()
    {
//        if($this->isSuperAdmin()){
            $this->global['pageTitle'] = "Form Row - ".APP_NAME;
            $this->global['pageMenu'] = 'Form Row';
            $this->global['contentHeader'] = 'Form Row';
            $this->global['contentTitle'] = 'Form Row';
            $this->global ['role'] = $this->role;
            $this->global ['name'] = $this->name;
            $this->global ['repo'] = $this->repo;

            $this->loadViews('front/layouts/form-row', $this->global, NULL);
//        }else{
//            redirect('cl');
//        }
    }
}