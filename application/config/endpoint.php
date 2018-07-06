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
//  Begin Additional Config
// ===========================
$config['oauth_client_id'] = '10018982774a664435'; //Client ID
$config['oauth_client_secret'] = '7cb1715236b173473c4d4a08b8ca1c83'; //Client Secret
$config['dn-key'] = 'dn-key: 3f71d03f874bcd53cb5dc97472a59d3e'; //Key for API DN
// ===========================
//  End Additional Config
// ===========================

// ===========================
//  Begin Users and Auth
// ===========================
$config['api_auth'] = constant('urlapi').'api/auth/auth'; //POST
$config['api_reset_pass'] = constant('urlapi').'api/auth/reset_pass/'; //POST
$config['api_reset_pass_confirm'] = constant('urlapi').'api/auth/reset_pass_confirm/'; //POST
$config['api_new_pass'] = constant('urlapi').'api/auth/create_pass/'; //POST

$config['api_list_users'] = constant('urlapi').'api/users/list/'; //POST
$config['api_info_users'] = constant('urlapi').'api/users/info/'; //POST
$config['api_add_users'] = constant('urlapi').'api/users/insert/'; //POST
$config['api_edit_users'] = constant('urlapi').'api/users/update/'; //POST
$config['api_remove_users'] = constant('urlapi').'api/users/delete/'; //POST
// ===========================
//  End Users and Auth
// ===========================

// ===========================
//  Begin Users Group
// ===========================
$config['api_list_user_group'] = constant('urlapi').'api/usergroup/list/'; //POST
$config['api_info_user_group'] = constant('urlapi').'api/usergroup/info/'; //POST
$config['api_add_user_group'] = constant('urlapi').'api/usergroup/insert/'; //POST
$config['api_edit_user_group'] = constant('urlapi').'api/usergroup/update/'; //POST
$config['api_remove_user_group'] = constant('urlapi').'api/usergroup/delete/'; //POST
// ===========================
//  Begin Users Group
// ===========================
// 
// ===========================
//  Begin Tickets
// ===========================
$config['api_ticket_num'] = constant('urlapi').'api/cptickets/grab_ticket_num/'; //GET
//$config['api_add_cart'] = constant('urlapi').'api/cptickets/create_tickets_cart/'; //POST
$config['api_update_cart'] = constant('urlapi').'api/cptickets/update_tickets_cart/'; //POST
$config['api_delete_cart'] = constant('urlapi').'api/cptickets/delete_tickets_cart/'; //POST
$config['api_clear_cart'] = constant('urlapi').'api/cptickets/clear_tickets_cart/'; //POST
$config['api_check_cart'] = constant('urlapi').'api/cptickets/check_tickets_cart/'; //POST
$config['api_list_cart'] = constant('urlapi').'api/cptickets/list_tmp/'; //POST
$config['api_add_ticket_detail'] = constant('urlapi').'api/cptickets/create_tickets_detail/'; //POST
$config['api_list_detail'] = constant('urlapi').'api/cptickets/list_detail/'; //POST
$config['api_add_ticket_trans'] = constant('urlapi').'api/cptickets/create_tickets/'; //POST
$config['api_list_ticket'] = constant('urlapi').'api/cptickets/list/'; //POST
// ===========================
//  End Tickets
// ===========================

// ===========================
//  Begin Partners
// ===========================
$config['api_list_partners'] = constant('urlapi').'api/cpartners/list/'; //POST
$config['api_info_partners'] = constant('urlapi').'api/cpartners/info/'; //POST
$config['api_add_partners'] = constant('urlapi').'api/cpartners/insert/'; //POST
$config['api_edit_partners'] = constant('urlapi').'api/cpartners/update/'; //POST
$config['api_remove_partners'] = constant('urlapi').'api/cpartners/delete/'; //POST
// ===========================
//  End Partners
// ===========================

// ===========================
//  Begin Engineers
// ===========================
$config['api_list_engineers'] = constant('urlapi').'api/cengineers/list/'; //POST
$config['api_list_view_engineers'] = constant('urlapi').'api/cengineers/list_view/'; //POST
$config['api_info_engineers'] = constant('urlapi').'api/cengineers/info/'; //POST
$config['api_add_engineers'] = constant('urlapi').'api/cengineers/insert/'; //POST
$config['api_edit_engineers'] = constant('urlapi').'api/cengineers/update/'; //POST
$config['api_remove_engineers'] = constant('urlapi').'api/cengineers/delete/'; //POST
// ===========================
//  End Engineers
// ===========================

// ===========================
//  Begin Spareparts
// ===========================
$config['api_list_parts'] = constant('urlapi').'api/cparts/list/'; //POST
$config['api_info_parts'] = constant('urlapi').'api/cparts/info/'; //POST
$config['api_add_parts'] = constant('urlapi').'api/cparts/insert/'; //POST
$config['api_edit_parts'] = constant('urlapi').'api/cparts/update/'; //POST
$config['api_remove_parts'] = constant('urlapi').'api/cparts/delete/'; //POST
// ===========================
//  End Spareparts
// ===========================

// ===========================
//  Begin Spareparts Subtitute
// ===========================
$config['api_list_part_sub'] = constant('urlapi').'api/cpartsub/list/'; //POST
$config['api_info_part_sub'] = constant('urlapi').'api/cpartsub/info/'; //POST
$config['api_add_part_sub'] = constant('urlapi').'api/cpartsub/insert/'; //POST
$config['api_edit_part_sub'] = constant('urlapi').'api/cpartsub/update/'; //POST
$config['api_remove_part_sub'] = constant('urlapi').'api/cpartsub/delete/'; //POST
// ===========================
//  End Spareparts Subtitute
// ===========================

// ===========================
//  Begin Stock Spareparts
// ===========================
$config['api_list_part_stock'] = constant('urlapi').'api/cstockwh/list/'; //POST
$config['api_info_part_stock'] = constant('urlapi').'api/cstockwh/info/'; //POST
$config['api_add_part_stock'] = constant('urlapi').'api/cstockwh/insert/'; //POST
$config['api_edit_part_stock'] = constant('urlapi').'api/cstockwh/update/'; //POST
$config['api_remove_part_stock'] = constant('urlapi').'api/cstockwh/delete/'; //POST
// ===========================
//  End Stock Spareparts
// ===========================

// ===========================
//  Begin Warehouses
// ===========================
$config['api_list_warehouses'] = constant('urlapi').'api/cwarehouse/list/'; //POST
$config['api_info_warehouses'] = constant('urlapi').'api/cwarehouse/info/'; //POST
$config['api_add_warehouses'] = constant('urlapi').'api/cwarehouse/insert/'; //POST
$config['api_edit_warehouses'] = constant('urlapi').'api/cwarehouse/update/'; //POST
$config['api_remove_warehouses'] = constant('urlapi').'api/cwarehouse/delete/'; //POST
// ===========================
//  End Warehouses
// ===========================