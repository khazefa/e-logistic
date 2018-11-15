<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// $route['default_controller'] = 'welcome';

/* RESERVED ROUTES */
$route['default_controller'] = 'front/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
/* END RESERVED ROUTES */

/* BEGIN USER CONTROLLER */

// ===========================
//  Routing Front Auth
// ===========================
$route['login'] = 'front/login';
$route['login/proccess'] = 'front/login/auth_log';
$route['login/reset_pass'] = 'front/login/reset_pass';
$route['login/reset_pass_confirm'] = 'front/login/reset_pass_confirm';
$route['logout'] = 'handling/logout';
$route['cl'] = 'front/dashboard';
// ===========================
//  End Routing Front Auth
// ===========================

// ===========================
//  Routing Front Accounts
// ===========================
$route['users/view'] = 'front/cusers';
$route['users/add'] = 'front/cusers/add';
$route['users/insert'] = 'front/cusers/create';
$route['users/edit/(:any)'] = 'front/cusers/edit/$1';
$route['users/modify'] = 'front/cusers/update';
$route['users/remove/(:any)'] = 'front/cusers/delete/$1';
$route['users/list/([a-zA-Z]+)'] = 'front/cusers/get_list/$1';
// ===========================
//  End Routing Front Accounts
// ===========================

// ===========================
//  Routing Front My Accounts
// ===========================
$route['my-account'] = 'front/cprofile';
// ===========================
//  End Routing Front My Accounts
// ===========================

// ===========================
//  Routing Front Accounts Group
// ===========================
$route['user-groups/view'] = 'front/cusersgroup';
$route['user-groups/add'] = 'front/cusersgroup/add';
$route['user-groups/insert'] = 'front/cusersgroup/create';
$route['user-groups/edit/(:any)'] = 'front/cusersgroup/edit/$1';
$route['user-groups/modify'] = 'front/cusersgroup/update';
$route['user-groups/remove/(:any)'] = 'front/cusersgroup/delete/$1';
$route['user-groups/list/([a-zA-Z]+)'] = 'front/cusersgroup/get_list/$1';
// ===========================
//  End Routing Front Accounts Group
// ===========================

// ===========================
//  Routing Front Spareparts
// ===========================
$route['spareparts/view'] = 'front/cparts';
$route['spareparts/add'] = 'front/cparts/add';
$route['spareparts/insert'] = 'front/cparts/create';
$route['spareparts/edit/(:any)'] = 'front/cparts/edit/$1';
$route['spareparts/modify'] = 'front/cparts/update';
$route['spareparts/remove/(:any)'] = 'front/cparts/delete/$1';
$route['spareparts/list/([a-zA-Z]+)'] = 'front/cparts/get_list/$1';
// ===========================
//  End Routing Front Spareparts
// ===========================

// ===========================
//  Routing Front Spareparts Subtitute
// ===========================
$route['spareparts-sub/view'] = 'front/cpartsub';
$route['spareparts-sub/add'] = 'front/cpartsub/add';
$route['spareparts-sub/insert'] = 'front/cpartsub/create';
$route['spareparts-sub/edit/(:any)'] = 'front/cpartsub/edit/$1';
$route['spareparts-sub/modify'] = 'front/cpartsub/update';
$route['spareparts-sub/remove/(:any)'] = 'front/cpartsub/delete/$1';
$route['spareparts-sub/list/([a-zA-Z]+)'] = 'front/cpartsub/get_list/$1';
// ===========================
//  End Routing Front Spareparts Subtitute
// ===========================

// ===========================
//  Routing Front Stock Spareparts
// ===========================
$route['data-spareparts-stock'] = 'front/cstockpart';
$route['stock-in-fsl'] = 'front/cstockpart/lists';
$route['stock-in-cwh'] = 'front/cstockpart/views';
$route['add-spareparts-stock'] = 'front/cstockpart/add';
$route['import-spareparts-stock'] = 'front/cstockpart/add_import';
$route['detail-spareparts-stock/(:any)'] = 'front/cstockpart/detail/$1';
$route['list-detail-spareparts-stock'] = 'front/cstockpart/get_list_detail_datatable';
$route['edit-spareparts-stock/(:num)'] = 'front/cstockpart/edit/$1';
$route['remove-spareparts-stock/(:num)'] = 'front/cstockpart/delete/$1';
// ===========================
//  End Routing Front Stock Spareparts
// ===========================

// ===========================
//  Routing Front Partners
// ===========================
$route['partners/view'] = 'front/cpartners';
$route['partners/add'] = 'front/cpartners/add';
$route['partners/insert'] = 'front/cpartners/create';
$route['partners/edit/(:any)'] = 'front/cpartners/edit/$1';
$route['partners/modify'] = 'front/cpartners/update';
$route['partners/remove/(:any)'] = 'front/cpartners/delete/$1';
$route['partners/list/([a-zA-Z]+)'] = 'front/cpartners/get_list/$1';
// ===========================
//  End Routing Front Partners
// ===========================

// ===========================
//  Routing Front Engineers
// ===========================
$route['engineers/view'] = 'front/cengineers';
$route['engineers/add'] = 'front/cengineers/add';
$route['engineers/insert'] = 'front/cengineers/create';
$route['engineers/edit/(:any)'] = 'front/cengineers/edit/$1';
$route['engineers/modify'] = 'front/cengineers/update';
$route['engineers/remove/(:any)'] = 'front/cengineers/delete/$1';
$route['engineers/list/([a-zA-Z]+)'] = 'front/cengineers/get_list/$1';
// ===========================
//  End Routing Front Engineers
// ===========================

// ===========================
//  Routing Front Warehouses
// ===========================
$route['warehouse/view'] = 'front/cwarehouse';
$route['warehouse/add'] = 'front/cwarehouse/add';
$route['warehouse/insert'] = 'front/cwarehouse/create';
$route['warehouse/edit/(:any)'] = 'front/cwarehouse/edit/$1';
$route['warehouse/modify'] = 'front/cwarehouse/update';
$route['warehouse/remove/(:any)'] = 'front/cwarehouse/delete/$1';
$route['warehouse/list/([a-zA-Z]+)'] = 'front/cwarehouse/get_list/$1';
$route['warehouse/list_nearby/([a-zA-Z]+)'] = 'front/cwarehouse/get_list_nearby/$1';
$route['warehouse/list_detail/(:any)'] = 'front/cwarehouse/get_detail/$1';
// ===========================
//  End Routing Front Warehouses
// ===========================

// ===========================
//  Routing Front ATM
// ===========================
$route['atm/view'] = 'front/catm';
$route['atm/add'] = 'front/catm/add';
$route['atm/insert'] = 'front/catm/create';
$route['atm/edit/(:any)'] = 'front/catm/edit/$1';
$route['atm/modify'] = 'front/catm/update';
$route['atm/remove/(:any)'] = 'front/catm/delete/$1';
$route['atm/list/([a-zA-Z]+)'] = 'front/catm/get_list/$1';
$route['atm/list_detail/(:any)'] = 'front/catm/get_detail/$1';
$route['atm/get_distinct/([a-zA-Z]+)/([a-zA-Z]+)'] = 'front/catm/get_unique/$1/$2';
// ===========================
//  End Routing Front ATM
// ===========================

// ===========================
//  Routing Front Outgoing Trans
// ===========================
$route['outgoing-trans'] = 'front/coutgoing';
$route['view-outgoing-trans'] = 'front/coutgoing/views';
$route['new-outgoing-trans'] = 'front/coutgoing/add';
$route['print-outgoing-trans/(:any)'] = 'front/coutgoing/print_transaction/$1';


$route['outgoing/request_parts'] = 'front/crequestparts';
$route['outgoing/request_parts/add'] = 'front/coutgoing/add';
// ===========================
//  End Routing Front Outgoing Trans
// ===========================

// ===========================
//  Delivary Note
// ===========================
//menu
$route['delivery-note-trans'] = 'front/cdeliverynote';
$route['view-delivery-note-trans'] = 'front/cdeliverynote/views';
$route['new-delivery-note-trans'] = 'front/cdeliverynote/add';
$route['edit-delivery-note-trans/(:any)'] = 'front/cdeliverynote/edit/$1';
$route['print-delivery-note-trans/(:any)'] = 'front/cdeliverynote/print_trans/$1';

//api index.php
$route['api-delivery-note-get-datatable']  = 'front/cdeliverynote/get_list_view_datatable';
$route['api-delivery-note-get-trans']  = 'front/cdeliverynote/get_trans';
$route['api-delivery-note-get-trans-detail']  = 'front/cdeliverynote/get_trans_detail';

// ===========================
//  End Routing Delivery Note Trans
// ===========================

// ===========================
//  FSL To CWH Trans
// ===========================
$route['fsltocwh-trans'] = 'front/cfsltocwh';
$route['view-fsltocwh-trans'] = 'front/cfsltocwh/views';
$route['new-fsltocwh-trans'] = 'front/cfsltocwh/add';
$route['print-fsltocwh-trans/(:any)'] = 'front/cfsltocwh/print_trans/$1';

//api index.php
$route['api-fsltocwh-get-view-datatable']  = 'front/cfsltocwh/get_list_view_datatable2';
$route['api-fsltocwh-get-datatable']  = 'front/cfsltocwh/get_list_view_datatable';
$route['api-fsltocwh-get-trans']  = 'front/cfsltocwh/get_trans';
$route['api-fsltocwh-get-trans-detail']  = 'front/cfsltocwh/get_trans_detail';
$route['api-fsltocwh-check-trans'] = 'front/cfsltocwh/check_trans';

// ===========================
//  End Routing FSL To CWH Trans
// ===========================

// ===========================
//  Supply From Vendor Trans
// ===========================

//menu
$route['supply-from-vendor-trans']              = 'front/csupplyfromvendor';
$route['view-supply-from-vendor-trans']         = 'front/csupplyfromvendor/views';
$route['new-supply-from-vendor-trans']          = 'front/csupplyfromvendor/add';
$route['print-supply-from-vendor-trans/(:any)'] = 'front/csupplyfromvendor/print_trans/$1';

//api index.php
$route['api-supply-from-vendor-get-datatable']  = 'front/csupplyfromvendor/get_list_datatable';

//api create.php
$route['api-supply-from-vendor-submit-trans']   = 'front/csupplyfromvendor/submit_trans';
$route['api-supply-from-vendor-get-trans']  = 'front/csupplyfromvendor/get_trans';
$route['api-supply-from-vendor-get-trans-detail']  = 'front/csupplyfromvendor/get_trans_detail';

//api cart.php
$route['api-supply-from-vendor-add-cart']       = 'front/csupplyfromvendor/add_cart';
$route['api-supply-from-vendor-delete-cart']    = 'front/csupplyfromvendor/delete_cart';
$route['api-supply-from-vendor-get-cart-table'] = 'front/csupplyfromvendor/get_list_cart_datatable';
$route['api-supply-from-vendor-check-partnum']  = 'front/csupplyfromvendor/checkpartnum';
$route['api-supply-from-vendor-get-total-cart'] = 'front/csupplyfromvendor/get_total_cart';

// ===========================
//  End Routing Supply From Vendor Trans
// ===========================

// ===========================
//  Supply From Repair Trans
// ===========================

//menu
$route['supply-from-repair-trans']              = 'front/csupplyfromrepair';
$route['view-supply-from-repair-trans']         = 'front/csupplyfromrepair/views';
$route['new-supply-from-repair-trans']          = 'front/csupplyfromrepair/add';
$route['print-supply-from-repair-trans/(:any)'] = 'front/csupplyfromrepair/print_trans/$1';

//api index.php
$route['api-supply-from-repair-get-datatable']  = 'front/csupplyfromrepair/get_list_datatable';
$route['api-supply-from-repair-get-trans']  = 'front/csupplyfromrepair/get_trans';
$route['api-supply-from-repair-get-trans-detail']  = 'front/csupplyfromrepair/get_trans_detail';

//api create.php
$route['api-supply-from-repair-submit-trans']   = 'front/csupplyfromrepair/submit_trans';

//api cart.php
$route['api-supply-from-repair-add-cart']       = 'front/csupplyfromrepair/add_cart';
$route['api-supply-from-repair-delete-cart']    = 'front/csupplyfromrepair/delete_cart';
$route['api-supply-from-repair-get-cart-table'] = 'front/csupplyfromrepair/get_list_cart_datatable';
$route['api-supply-from-repair-check-partnum']  = 'front/csupplyfromrepair/checkpartnum';
$route['api-supply-from-repair-get-total-cart'] = 'front/csupplyfromrepair/get_total_cart';

// ===========================
//  End Routing Supply From Repair Trans
// ===========================

// ===========================
//  Supply To Repair Trans
// ===========================

//menu
$route['supply-to-repair-trans']              = 'front/csupplytorepair';
$route['view-supply-to-repair-trans']         = 'front/csupplytorepair/views';
$route['new-supply-to-repair-trans']          = 'front/csupplytorepair/add';
$route['print-supply-to-repair-trans/(:any)'] = 'front/csupplytorepair/print_trans/$1';

//api index.php
$route['api-supply-to-repair-get-datatable']  = 'front/csupplytorepair/get_list_datatable';
$route['api-supply-to-repair-get-trans']  = 'front/csupplytorepair/get_trans';
$route['api-supply-to-repair-get-trans-detail']  = 'front/csupplytorepair/get_trans_detail';

//api create.php
$route['api-supply-to-repair-submit-trans']   = 'front/csupplytorepair/submit_trans';
$route['api-verify-supply-to-repair']         = 'front/csupplytorepair/verify_data';
$route['api-supply-to-repair-get-detail-trans'] = 'front/csupplytorepair/get_trans_detail_verify';

//api cart.php
$route['api-supply-to-repair-add-cart']       = 'front/csupplytorepair/add_cart';
$route['api-supply-to-repair-delete-cart']    = 'front/csupplytorepair/delete_cart';
$route['api-supply-to-repair-get-cart-table'] = 'front/csupplytorepair/get_list_cart_datatable';

$route['api-supply-to-repair-check-partnum']  = 'front/csupplytorepair/checkpartnum';
$route['api-supply-to-repair-get-total-cart'] = 'front/csupplytorepair/get_total_cart';

// ===========================
//  End Routing To Repair Trans
// ===========================

// ===========================
//  FSL To CWH Trans
// ===========================

//api menu
$route['supply-repair-to-cwh']              = 'front/csupplyrepairtocwh';
$route['supply-repair-to-cwh/view']         = 'front/csupplyrepairtocwh/views';
$route['supply-repair-to-cwh/new']          = 'front/csupplyrepairtocwh/add';
$route['supply-repair-to-cwh/print/(:any)'] = 'front/csupplyrepairtocwh/print_trans/$1';

//api index.php
$route['api-supply-repair-to-cwh/view-datatable']   = 'front/csupplyrepairtocwh/get_list_view_datatable2';
$route['api-supply-repair-to-cwh/datatable']        = 'front/csupplyrepairtocwh/get_list_view_datatable';
$route['api-supply-repair-to-cwh/get-trans']        = 'front/csupplyrepairtocwh/get_trans';
$route['api-supply-repair-to-cwh/get-detail']       = 'front/csupplyrepairtocwh/get_trans_detail';

//api create.php
$route['api-supply-repair-to-cwh/submit-trans']   = 'front/csupplyrepairtocwh/submit_trans';
$route['api-supply-repair-to-cwh/check-partnum']  = 'front/csupplyrepairtocwh/check_part';

//api cart.php
$route['api-supply-repair-to-cwh/cart/add']       = 'front/csupplyrepairtocwh/add_cart';
$route['api-supply-repair-to-cwh/cart/delete']    = 'front/csupplyrepairtocwh/delete_cart';
$route['api-supply-repair-to-cwh/cart/datatable'] = 'front/csupplyrepairtocwh/get_list_cart_datatable';

// ===========================
//  End Routing FSL To CWH Trans
// ===========================

// ===========================
//  Search Parts
// ===========================
$route['search-parts'] = 'front/csearchparts';
// ===========================
//  End Routing search Parts
// ===========================

// ===========================
//  Routing Front Incoming Trans
// ===========================
$route['incoming-trans'] = 'front/cincoming';
$route['view-incoming-trans'] = 'front/cincoming/views';
$route['new-incoming-trans'] = 'front/cincoming/add';
$route['supply-from-other-fsl'] = 'front/cincoming/supply_from_other_fsl';
$route['print-incoming-supply/(:any)'] = 'front/cincoming/print_trans_supply/$1';
$route['print-incoming-return/(:any)'] = 'front/cincoming/print_trans_return/$1';
$route['return-parts'] = 'front/creturnparts/add';
// ===========================
//  End Routing Front Incoming Trans
// ===========================

// ===========================
//  Routing Front Supply from FSL
// ===========================
$route['supply-from-fsl'] = 'front/csupplyfromfsl';
$route['add-supply-from-fsl'] = 'front/csupplyfromfsl/add';
// ===========================
//  End Routing Front Supply from FSL
// ===========================

// ===========================
//  Routing Front Supply from FSL
// ===========================
$route['supply-from-fsl-bad'] = 'front/csupplyfromfslbad';
$route['add-supply-from-fsl-bad'] = 'front/csupplyfromfslbad/add';
// ===========================
//  End Routing Front Supply from FSL
//  Routing Front Supply from CWH
// ===========================
$route['supply-from-cwh'] = 'front/csupplyfromcwh';
$route['add-supply-from-cwh'] = 'front/csupplyfromcwh/add';
// ===========================
//  End Routing Front Supply from CWH
// ===========================

// ===========================
//  Routing Front Reports
// ===========================
$route['report-consumed-parts'] = 'front/creports/report_consumed_part';
$route['report-used-parts'] = 'front/creports/report_used_part';
$route['report-replenish-plan'] = 'front/creports/report_replenish_plan';
//$route['print-consumed-parts'] = 'front/creports/print_daily_report';
$route['print-consumed-parts'] = 'front/creports/export_consumed_part';
$route['print-used-parts'] = 'front/creports/export_used_part';
//$route['print-replenish-plan'] = 'front/creports/print_replenish_plan';
$route['print-replenish-plan'] = 'front/creports/export_replenish_plan';
// ===========================
//  End Routing Front Reports
// ===========================

// ===========================
//  Routing Front History
// ===========================
$route['history-outgoing'] = 'front/chistorytrans/history_outgoing';
$route['edit-outgoing/(:any)'] = 'front/chistorytrans/edit_outgoing/$1';
// ===========================
//  End Routing Front History
// ===========================

// ===========================
//  Routing Front Search
// ===========================
$route['search-part-number-f'] = 'front/csearch/search_part_number_f';
$route['search-part-number-e'] = 'front/csearch/search_part_number_e';
// ===========================
//  End Routing Front Search
// ===========================

/* END USER CONTROLLER */

/* BEGIN OVERSEE CONTROLLER */

// ===========================
//  Routing Superintend Auth
// ===========================
$route['signin'] = 'superintend/login';
$route['signin/proccess'] = 'superintend/login/auth_log';
$route['signin/reset_pass'] = 'superintend/login/reset_pass';
$route['signin/reset_pass_confirm'] = 'superintend/login/reset_pass_confirm';
$route['signout'] = 'handling/signout';
$route['oversee'] = 'superintend/dashboard';
// ===========================
//  End Routing Superintend Auth
// ===========================

// ===========================
//  Routing Superintend My Accounts
// ===========================
$route['oversee/my-account'] = 'superintend/cprofile';
// ===========================
//  End Routing Superintend My Accounts
// ===========================

// ===========================
//  Routing Superintend Spareparts
// ===========================
$route['oversee/data-spareparts'] = 'superintend/cparts';
// ===========================
//  End Routing Superintend Spareparts
// ===========================

// ===========================
//  Routing Superintend Outgoing Trans
// ===========================
$route['oversee/outgoing-report'] = 'superintend/coutgoing';
$route['oversee/print-outgoing-trans/(:any)'] = 'superintend/coutgoing/print_transaction/$1';
// ===========================
//  End Routing Superintend Outgoing Trans
// ===========================

// ===========================
//  Search Parts
// ===========================
$route['oversee/search-parts'] = 'superintend/csearchparts';
// ===========================
//  End Routing search Parts
// ===========================

// ===========================
//  Routing Superintend Incoming Trans
// ===========================
$route['oversee/incoming-report'] = 'superintend/cincoming';
$route['oversee/print-incoming-supply/(:any)'] = 'superintend/cincoming/print_trans_supply/$1';
$route['oversee/print-incoming-return/(:any)'] = 'superintend/cincoming/print_trans_return/$1';
// ===========================
//  End Routing Superintend Incoming Trans
// ===========================

// ===========================
//  Routing Superintend Reports
// ===========================
$route['oversee/report-consumed-parts'] = 'superintend/creports/report_consumed_part';
$route['oversee/report-used-parts'] = 'superintend/creports/report_used_part';
$route['oversee/report-replenish-plan'] = 'superintend/creports/report_replenish_plan';
$route['oversee/print-consumed-parts'] = 'superintend/creports/export_consumed_part';
$route['oversee/print-used-parts'] = 'superintend/creports/export_used_part';
$route['oversee/print-replenish-plan'] = 'superintend/creports/export_replenish_plan';
// ===========================
//  End Routing Superintend Reports
// ===========================

// ===========================
//  Routing Superintend Stock Spareparts
// ===========================
$route['oversee/data-spareparts-stock'] = 'superintend/cstockpart';
$route['oversee/detail-spareparts-stock/(:any)'] = 'superintend/cstockpart/detail/$1';
$route['oversee/list-detail-spareparts-stock'] = 'superintend/cstockpart/get_list_detail_datatable';
// ===========================
//  End Routing Superintend Stock Spareparts
// ===========================

// ===========================
//  Routing Superintend Search
// ===========================
$route['oversee/search-part-number-f'] = 'superintend/csearch/search_part_number_f';
$route['oversee/search-part-number-e'] = 'superintend/csearch/search_part_number_e';
// ===========================
//  End Routing Superintend Search
// ===========================

/* END OVERSEE CONTROLLER */