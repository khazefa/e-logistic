<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//adding config items.

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
$config['api_edit_account'] = constant('urlapi').'api/users/update_account/'; //POST
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
$config['api_list_parts_like'] = constant('urlapi').'api/cparts/list_like/'; //POST
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
$config['api_partsub_part_sub'] = constant('urlapi').'api/cpartsub/get_part_sub/'; //POST
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
$config['api_list_fsl_stock'] = constant('urlapi').'api/cstockwh/list_fsl_stock/'; //POST
$config['api_list_fsl_sub_stock'] = constant('urlapi').'api/cstockwh/list_fsl_sub_stock/'; //POST
$config['api_list_view_part_stock'] = constant('urlapi').'api/cstockwh/list_view/'; //POST
$config['api_info_part_stock'] = constant('urlapi').'api/cstockwh/info/'; //POST
$config['api_info_part_stock_wh'] = constant('urlapi').'api/cstockwh/info_wh/'; //POST
$config['api_add_part_stock'] = constant('urlapi').'api/cstockwh/insert/'; //POST
$config['api_edit_part_stock'] = constant('urlapi').'api/cstockwh/update/'; //POST
$config['api_edit_stock_part_stock'] = constant('urlapi').'api/cstockwh/update_stock/'; //POST
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

// ===========================
//  Begin Incoming Trans
// ===========================
$config['api_list_incomings'] = constant('urlapi').'api/cpincomings/list/'; //POST
$config['api_list_view_incomings'] = constant('urlapi').'api/cpincomings/list_view/'; //POST
$config['api_info_view_incomings'] = constant('urlapi').'api/cpincomings/info_view/'; //POST
$config['api_list_view_detail_incomings'] = constant('urlapi').'api/cpincomings/list_view_detail/'; //POST
$config['api_list_incomings_cart'] = constant('urlapi').'api/cpincomings/list_tmp/'; //POST
$config['api_add_incomings_r_cart'] = constant('urlapi').'api/cpincomings/create_trans_tmp_r/'; //POST
$config['api_add_incomings_cart'] = constant('urlapi').'api/cpincomings/create_trans_tmp/'; //POST
$config['api_add_incomings_trans'] = constant('urlapi').'api/cpincomings/create_trans/'; //POST
$config['api_add_incomings_trans_detail'] = constant('urlapi').'api/cpincomings/create_trans_detail/'; //POST
$config['api_update_incomings_cart'] = constant('urlapi').'api/cpincomings/update_cart/'; //POST
$config['api_delete_incomings_cart'] = constant('urlapi').'api/cpincomings/delete_cart/'; //POST
$config['api_clear_incomings_cart'] = constant('urlapi').'api/cpincomings/delete_multi_cart/'; //POST
$config['api_total_incomings_cart'] = constant('urlapi').'api/cpincomings/total_cart/'; //POST
$config['api_get_incoming_num'] = constant('urlapi').'api/cpincomings/grab_ticket_num/'; //POST
$config['api_get_incoming_num_ext'] = constant('urlapi').'api/cpincomings/grab_ticket_num_ext/'; //POST
$config['api_get_cart_in_info'] = constant('urlapi').'api/cpincomings/get_cart_info/'; //POST
// ===========================
//  End Incoming Trans
// ===========================

// ===========================
//  Begin Outgoing Trans
// ===========================
$config['api_list_outgoings'] = constant('urlapi').'api/cpoutgoings/list/'; //POST
$config['api_list_detail_outgoings'] = constant('urlapi').'api/cpoutgoings/list_detail/'; //POST
$config['api_list_view_outgoings'] = constant('urlapi').'api/cpoutgoings/list_view/'; //POST
$config['api_info_view_outgoings'] = constant('urlapi').'api/cpoutgoings/info_view/'; //POST
$config['api_list_view_detail_outgoings'] = constant('urlapi').'api/cpoutgoings/list_view_detail/'; //POST
$config['api_list_outgoings_cart'] = constant('urlapi').'api/cpoutgoings/list_tmp/'; //POST
$config['api_add_outgoings_cart'] = constant('urlapi').'api/cpoutgoings/create_trans_tmp/'; //POST
$config['api_add_outgoings_trans'] = constant('urlapi').'api/cpoutgoings/create_trans/'; //POST
$config['api_add_outgoings_trans_detail'] = constant('urlapi').'api/cpoutgoings/create_trans_detail/'; //POST
$config['api_update_outgoings_trans'] = constant('urlapi').'api/cpoutgoings/update/'; //POST
$config['api_update_outgoings_trans_detail'] = constant('urlapi').'api/cpoutgoings/update_detail/'; //POST
$config['api_update_outgoings_cart'] = constant('urlapi').'api/cpoutgoings/update_cart/'; //POST
$config['api_delete_outgoings_cart'] = constant('urlapi').'api/cpoutgoings/delete_cart/'; //POST
$config['api_clear_outgoings_cart'] = constant('urlapi').'api/cpoutgoings/delete_multi_cart/'; //POST
$config['api_total_outgoings_cart'] = constant('urlapi').'api/cpoutgoings/total_cart/'; //POST
$config['api_get_outgoing_num'] = constant('urlapi').'api/cpoutgoings/grab_ticket_num/'; //POST
$config['api_get_outgoing_num_ext'] = constant('urlapi').'api/cpoutgoings/grab_ticket_num_ext/'; //POST
$config['api_get_cart_info'] = constant('urlapi').'api/cpoutgoings/get_cart_info/'; //POST
// ===========================
//  End Outgoing Trans
// ===========================

// ===========================
//  Begin Delivery Note Trans
// ===========================

$config['api_list_detail_delivery_note'] = constant('urlapi').'api/cpdeliverynote/list_detail/'; //POST

$config['api_list_view_detail_delivery_note'] = constant('urlapi').'api/cpdeliverynote/list_view_detail/'; //POST

$config['api_update_delivery_note_trans'] = constant('urlapi').'api/cpdeliverynote/update/'; //POST
$config['api_update_delivery_note_trans_detail'] = constant('urlapi').'api/cpdeliverynote/update_detail/'; //POST


$config['api_get_cart_delivery_note_info'] = constant('urlapi').'api/cpdeliverynote/get_cart_info/'; //POST

$config['api_get_eta_time'] = constant('urlapi').'api/cpdeliverynote/list_delivery_time/';
$config['api_add_delivery_note_cart'] = constant('urlapi').'api/cpdeliverynote/create_trans_tmp/'; //POST
$config['api_total_delivery_note_cart'] = constant('urlapi').'api/cpdeliverynote/total_cart/'; //POST
$config['api_list_delivery_note_cart'] = constant('urlapi').'api/cpdeliverynote/list_tmp/'; //POST
$config['api_delete_delivery_note_cart'] = constant('urlapi').'api/cpdeliverynote/delete_cart/'; //POST
$config['api_get_delivery_note_num'] = constant('urlapi').'api/cpdeliverynote/grab_ticket_num/'; //POST
$config['api_add_delivery_note_trans_detail'] = constant('urlapi').'api/cpdeliverynote/create_trans_detail/'; //POST
$config['api_add_delivery_note_trans'] = constant('urlapi').'api/cpdeliverynote/create_trans/'; //POST
$config['api_clear_delivery_note_cart'] = constant('urlapi').'api/cpdeliverynote/delete_multi_cart/'; //POST
$config['api_list_delivery_note'] = constant('urlapi').'api/cpdeliverynote/list/'; //POST
$config['api_list_view_delivery_note'] = constant('urlapi').'api/cpdeliverynote/list_view/';
$config['api_get_data_detail'] = constant('urlapi').'api/cpdeliverynote/get_data_detail/';//get
$config['api_update_delivery_note_cart'] = constant('urlapi').'api/cpdeliverynote/update_cart/'; //POST
// ===========================
//  End Delivery Note  Trans
// ===========================

// ===========================
//  Begin Procedure
// ===========================
$config['api_detail_outgoings'] = constant('urlapi').'api/cprocedures/list_detail_outgoings/'; //POST
$config['api_daily_reports'] = constant('urlapi').'api/cpreports/list_outgoing_daily_reports/'; //POST
$config['api_used_reports'] = constant('urlapi').'api/cpreports/list_outgoing_used_part/'; //POST
$config['api_replenish_plan'] = constant('urlapi').'api/cpreports/list_outgoing_replenish_plan/'; //POST
// ===========================
//  End Procedure
// ===========================