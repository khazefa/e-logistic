<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : COutgoing (TicketsController)
 * COutgoing Class to control Tickets.
 * @author : Aris Baskoro
 * @version : 1.0
 * @since : August 2018
 */
class CSearchParts extends BaseController{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        
    }
    
    //View List Index
    public function index(){ 
        $this->global['pageTitle'] = 'Search Parts - '.APP_NAME;
        $this->global['pageMenu'] = 'Search Parts';
        $this->global['contentHeader'] = 'Search Parts';
        $this->global['contentTitle'] = 'Search Parts';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;

        $this->loadViews('front/search-parts/index', $this->global, NULL);
    }
    
    public function get_list_view_datatable(){
        $fsearch = $this->input->post('fsearch', TRUE);
        
        $par = array(
            'fsearch'=>$fsearch
        );
        $rs_data = send_curl($par, $this->config->item('api_list_search_parts'), 'POST', FALSE);
        var_dump($rs_data);
        $rs = $rs_data->status ? $rs_data->result : array();
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$rs))
        );    
    }
    
}

