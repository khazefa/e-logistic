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
$route['spareparts-sub/list-sub'] = 'front/cpartsub/get_list_part_sub';
// ===========================
//  End Routing Front Spareparts Subtitute
// ===========================

// ===========================
//  Routing Front Stock Spareparts
// ===========================
$route['spareparts-stock/view'] = 'front/cstockpart';
$route['spareparts-stock/view-central'] = 'front/cstockpart/central';
$route['spareparts-stock/add'] = 'front/cstockpart/add';
$route['spareparts-stock/insert'] = 'front/cstockpart/create';
$route['spareparts-stock/edit/(:any)'] = 'front/cstockpart/edit/$1';
$route['spareparts-stock/modify'] = 'front/cstockpart/update';
$route['spareparts-stock/remove/(:any)'] = 'front/cstockpart/delete/$1';
$route['spareparts-stock/onhand-list/([a-zA-Z]+)'] = 'front/cstockpart/get_onhand_list/$1';
$route['spareparts-stock/central-list/([a-zA-Z]+)'] = 'front/cstockpart/get_central_list/$1';
$route['spareparts-stock/detail-list'] = 'front/cstockpart/get_list_detail';
$route['spareparts-stock/list-nearby'] = 'front/cstockpart/get_part_nearby';
$route['spareparts-stock/check-part'] = 'front/cstockpart/check_part';
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
$route['outgoing-trans/dashboard'] = 'front/coutgoing/dashboard';
$route['view-outgoing-trans'] = 'front/coutgoing/views';
$route['new-outgoing-trans'] = 'front/coutgoing/add';
$route['print-outgoing-trans/(:any)'] = 'front/coutgoing/print_transaction/$1';

$route['request-parts/view'] = 'front/crequestparts';
$route['request-parts/add'] = 'front/crequestparts/add';
$route['request-parts/list/([a-zA-Z]+)'] = 'front/crequestparts/get_list/$1';
$route['request-parts/detail'] = 'front/crequestparts/get_request';
$route['request-parts/list_detail'] = 'front/crequestparts/get_request_detail';
$route['request-parts/check-ticket'] = 'front/crequestparts/check_ticket';
$route['request-parts/check-transaction'] = 'front/crequestparts/check_transaction';
$route['request-parts/insert'] = 'front/crequestparts/submit_trans';
$route['request-parts/modify-detail'] = 'front/crequestparts/update_detail_status';
$route['request-parts/bulk-modify-detail'] = 'front/crequestparts/update_detail_status_all';
$route['request-parts/print/(:any)'] = 'front/crequestparts/print_transaction/$1';
// ===========================
//  End Routing Front Outgoing Trans
// ===========================

// ===========================
//  Routing Front Transfer to FSL
// ===========================
$route['transfer-stock-to-fsl/view'] = 'front/ctransfertofsl';
$route['transfer-stock-to-fsl/add'] = 'front/ctransfertofsl/add';
$route['transfer-stock-to-fsl/list/([a-zA-Z]+)'] = 'front/ctransfertofsl/get_list/$1';
$route['transfer-stock-to-fsl/detail'] = 'front/ctransfertofsl/get_transfer';
$route['transfer-stock-to-fsl/list_detail'] = 'front/ctransfertofsl/get_transfer_detail';
$route['transfer-stock-to-fsl/check-ticket'] = 'front/ctransfertofsl/check_ticket';
$route['transfer-stock-to-fsl/check-transaction'] = 'front/ctransfertofsl/check_transaction';
$route['transfer-stock-to-fsl/insert'] = 'front/ctransfertofsl/submit_trans';
$route['transfer-stock-to-fsl/modify-detail'] = 'front/ctransfertofsl/update_detail_id';
$route['transfer-stock-to-fsl/print/(:any)'] = 'front/ctransfertofsl/print_transaction/$1';
// ===========================
//  End Routing Front Transfer to FSL
// ===========================

// ===========================
//  Routing Cart
// ===========================
$route['cart/outgoing/list/([a-zA-Z]+)'] = 'front/cart/outgoing/$1';
$route['cart/outgoing/add/([a-zA-Z]+)'] = 'front/cart/create_outgoing/$1';
$route['cart/outgoing/update'] = 'front/cart/update_outgoing';
$route['cart/outgoing/delete'] = 'front/cart/delete_outgoing';

$route['cart/incoming/list/([a-zA-Z]+)'] = 'front/cart/incoming/$1';
$route['cart/incoming/add/([a-zA-Z]+)'] = 'front/cart/create_incoming/$1';
$route['cart/incoming/update'] = 'front/cart/update_incoming';
$route['cart/incoming/delete'] = 'front/cart/delete_incoming';
$route['cart/incoming/bulk-delete/([a-zA-Z]+)'] = 'front/cart/delete_all_incoming/$1';
// ===========================
//  End Routing Cart
// ===========================

// ===========================
//  Delivary Note
// ===========================
//menu
$route['delivery-note-trans'] = 'front/cdeliverynote';
$route['view-delivery-note-trans'] = 'front/cdeliverynote/views';
$route['new-delivery-note-trans'] = 'front/cdeliverynote/add';
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
$route['incoming-trans/dashboard'] = 'front/cincoming/dashboard';
$route['view-incoming-trans'] = 'front/cincoming/views';
$route['new-incoming-trans'] = 'front/cincoming/add';
$route['supply-from-other-fsl'] = 'front/cincoming/supply_from_other_fsl';
$route['print-incoming-supply/(:any)'] = 'front/cincoming/print_trans_supply/$1';
$route['print-incoming-return/(:any)'] = 'front/cincoming/print_trans_return/$1';
// ===========================
//  End Routing Front Incoming Trans
// ===========================

// ===========================
//  Routing Front Return Parts
// ===========================
$route['return-parts/view'] = 'front/creturnparts';
$route['return-parts/add'] = 'front/creturnparts/add';
$route['return-parts/add/(:any)'] = 'front/creturnparts/add/$1';
$route['return-parts/list/([a-zA-Z]+)'] = 'front/creturnparts/get_list/$1';
$route['return-parts/check-ticket'] = 'front/creturnparts/check_ticket';
$route['return-parts/insert'] = 'front/creturnparts/submit_trans';
$route['return-parts/print/(:any)'] = 'front/creturnparts/print_transaction/$1';
// ===========================
//  End Routing Front Return Parts
// ===========================

// ===========================
//  Routing Front Supply from FSL to FSL
// ===========================
$route['supply-fsl-to-fsl/view'] = 'front/csupplyfromfsl';
$route['supply-fsl-to-fsl/add'] = 'front/csupplyfromfsl/add';
$route['supply-fsl-to-fsl/add/(:any)'] = 'front/csupplyfromfsl/add/$1';
$route['supply-fsl-to-fsl/list/([a-zA-Z]+)'] = 'front/csupplyfromfsl/get_list/$1';
$route['supply-fsl-to-fsl/check-ticket'] = 'front/csupplyfromfsl/check_ticket';
$route['supply-fsl-to-fsl/insert'] = 'front/csupplyfromfsl/submit_trans';
$route['supply-fsl-to-fsl/insert_close'] = 'front/csupplyfromfsl/submit_trans_close';
$route['supply-fsl-to-fsl/print/(:any)'] = 'front/csupplyfromfsl/print_transaction/$1';
// ===========================
//  End Routing Front Supply from FSL to FSL
// ===========================

// ===========================
//  Routing Front Supply from CWH
// ===========================
$route['supply-from-cwh'] = 'front/csupplyfromcwh';
$route['add-supply-from-cwh'] = 'front/csupplyfromcwh/add';

$route['supply-from-cwh/view'] = 'front/csupplyfromcwh';
$route['supply-from-cwh/add'] = 'front/csupplyfromcwh/add';
$route['supply-from-cwh/add/(:any)'] = 'front/csupplyfromcwh/add/$1';
$route['supply-from-cwh/list/([a-zA-Z]+)'] = 'front/csupplyfromcwh/get_list/$1';
$route['supply-from-cwh/check-ticket'] = 'front/csupplyfromcwh/check_ticket';
$route['supply-from-cwh/insert'] = 'front/csupplyfromcwh/submit_trans';
$route['supply-from-cwh/insert_close'] = 'front/csupplyfromcwh/submit_trans_close';
$route['supply-from-cwh/print/(:any)'] = 'front/csupplyfromcwh/print_transaction/$1';
// ===========================
//  End Routing Front Supply from CWH
// ===========================

// ===========================
//  Routing Front Supply from FSL
// ===========================
$route['supply-from-fsl-bad'] = 'front/csupplyfromfslbad';
$route['add-supply-from-fsl-bad'] = 'front/csupplyfromfslbad/add';
// ===========================
//  End Routing Front Supply from FSL

// ===========================
//  Routing Front Reports
// ===========================
$route['report/consumed-parts'] = 'front/creports/report_consumed_part';
$route['report/consumed-parts/print'] = 'front/creports/export_consumed_part';
$route['report/used-parts'] = 'front/creports/report_used_part';
$route['report/used-parts/print'] = 'front/creports/export_used_part';
$route['report/replenish-plan'] = 'front/creports/report_replenish_plan';
$route['report/replenish-plan/print'] = 'front/creports/export_replenish_plan';
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