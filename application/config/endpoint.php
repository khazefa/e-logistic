<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//adding config items.

if(file_exists(FCPATH.'local.txt')) {
    // Local Server
//    define('urlapi',   "https://localhost:8080/dn-logistic-api/");
    define('urlapi',   "http://localhost:8080/dn-logistic-api/");
    $config['emailsender']  = "noreplyplease@service-division.com";
}elseif(file_exists(FCPATH.'dev.txt')) {
    // Development Server
    define('urlapi',   "http://localhost:8080/dn-logistic-api/");
    $config['emailsender']  = "noreplyplease@service-division.com";
}else{
    // Live Server
    define('urlapi',   "http://localhost:8080/dn-logistic-api/");
    $config['emailsender']  = "noreplyplease@service-division.com";
}

// ===========================
//		# ADDITIONAL CONFIG
// ===========================
$config['oauth_client_id'] = '10018982774a664435'; //Client ID
$config['oauth_client_secret'] = '7cb1715236b173473c4d4a08b8ca1c83'; //Client Secret
$config['dn-key'] = 'dn-key: 3f71d03f874bcd53cb5dc97472a59d3e'; //Key for API DN

// ===========================
//		DN Controller
// ===========================
$config['api_auth'] = constant('urlapi').'api/auth/auth'; //POST
$config['api_reset_pass'] = constant('urlapi').'api/auth/reset_pass/'; //POST
$config['api_reset_pass_confirm'] = constant('urlapi').'api/auth/reset_pass_confirm/'; //POST
$config['api_new_pass'] = constant('urlapi').'api/auth/create_pass/'; //POST
$config['api_update_user'] = constant('urlapi').'api/users/update/'; //POST
$config['api_get_user'] = constant('urlapi').'api/users/edit/'; //POST

$config['api_ticket_num'] = constant('urlapi').'api/cptickets/grab_ticket_num/'; //GET
$config['api_list_parts'] = constant('urlapi').'api/cparts/list/'; //POST
$config['api_list_warehouses'] = constant('urlapi').'api/cwarehouse/list/'; //POST

$config['api_add_cart'] = constant('urlapi').'api/cptickets/create_tickets_cart/'; //POST
$config['api_update_cart'] = constant('urlapi').'api/cptickets/update_tickets_cart/'; //POST
$config['api_delete_cart'] = constant('urlapi').'api/cptickets/delete_tickets_cart/'; //POST
$config['api_clear_cart'] = constant('urlapi').'api/cptickets/clear_tickets_cart/'; //POST
$config['api_check_cart'] = constant('urlapi').'api/cptickets/check_tickets_cart/'; //POST
$config['api_list_cart'] = constant('urlapi').'api/cptickets/list_tmp/'; //POST

$config['api_add_ticket_detail'] = constant('urlapi').'api/cptickets/create_tickets_detail/'; //POST
$config['api_list_detail'] = constant('urlapi').'api/cptickets/list_detail/'; //POST

$config['api_add_ticket_trans'] = constant('urlapi').'api/cptickets/create_tickets/'; //POST
$config['api_list_ticket'] = constant('urlapi').'api/cptickets/list/'; //POST