<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This function used to get the client called
 */
 if(!function_exists('get_called'))
 {
    function get_called()
    {
        $CI = &get_instance();
        $CI->load->helper('api');
        $CI->load->helper('security');
        $CI->load->library('converter');
        $fcalled = $CI->security->xss_clean("fwd");
        //Parse Data for cURL
        $rs_data = send_curl('', $this->config->item('api_show_clients_bycall').'?call='.$fcalled, 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";
        
        $arrValue = $CI->converter->objectToArray($rs[0]);
        return $arrValue['client_called_name'];
    }
 }

 /**
 * This function used to get the client logo
 */
 if(!function_exists('get_logo'))
 {
    function get_logo()
    {
        $CI = &get_instance();
        $CI->load->helper('api');
        $CI->load->helper('security');
        $CI->load->library('converter');
        $fcalled = $CI->security->xss_clean("fwd");
        //Parse Data for cURL
        $rs_data = send_curl('', $CI->config->item('api_show_clients_bycall').'?call='.$fcalled, 'GET', FALSE);
        $rs = $rs_data->status ? $rs_data->result : "";
        
        $arrValue = $CI->converter->objectToArray($rs[0]);
        return $arrValue['client_logo'];
    }
 }

?>