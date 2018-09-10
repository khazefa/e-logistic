<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Handling (HandlingController)
 * Handling Class to control all global related operations.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class Handling extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * This function used to load 404 page
     */
    public function index()
    {
        $this->global['pageTitle'] = APP_NAME.' : 404 - Page Not Found';   
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    /**
     * This function used to logout
     */
    public function logout()
    {
        $this->logout_app();
    }
    
    /**
     * This function used to signout
     */
    public function signout()
    {
        $this->signout_app();
    }
    
    /**
     * This function used to logout client
     */
    public function logoutClient()
    {
        $this->logoutClients();
    }
}

?>