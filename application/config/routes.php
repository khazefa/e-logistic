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

/* GLOBAL CONTROLLER */
$route['default_controller'] = 'front/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
/* END GLOBAL CONTROLLER */

// ===========================
//  Routing Auth
// ===========================
$route['login'] = 'front/login';
$route['login/proccess'] = 'front/login/auth_log';
$route['login/reset_pass'] = 'front/login/reset_pass';
$route['login/reset_pass_confirm'] = 'front/login/reset_pass_confirm';
$route['logout'] = 'handling/logout';
$route['cl'] = 'front/dashboard';
// ===========================
//  End Routing Auth
// ===========================

// ===========================
//  Routing Accounts
// ===========================
$route['manage-users'] = 'front/cusers';
$route['add-users'] = 'front/cusers/add';
$route['edit-users/(:any)'] = 'front/cusers/edit/$1';
$route['remove-users/(:any)'] = 'front/cusers/delete/$1';
// ===========================
//  End Routing Accounts
// ===========================

// ===========================
//  Routing Accounts Group
// ===========================
$route['manage-groups'] = 'front/cusersgroup';
$route['add-groups'] = 'front/cusersgroup/add';
$route['edit-groups/(:num)'] = 'front/cusersgroup/edit/$1';
$route['remove-groups/(:num)'] = 'front/cusersgroup/delete/$1';
// ===========================
//  End Routing Accounts Group
// ===========================

// ===========================
//  Routing Spareparts
// ===========================
$route['manage-spareparts'] = 'front/cparts/lists';
$route['data-spareparts'] = 'front/cparts';
$route['add-spareparts'] = 'front/cparts/add';
$route['import-spareparts'] = 'front/cparts/add_import';
$route['edit-spareparts/(:any)'] = 'front/cparts/edit/$1';
$route['remove-spareparts/(:any)'] = 'front/cparts/delete/$1';
// ===========================
//  End Routing Spareparts
// ===========================

// ===========================
//  Routing Spareparts Subtitute
// ===========================
$route['manage-spareparts-sub'] = 'front/cpartsub/lists';
$route['data-spareparts-sub'] = 'front/cpartsub';
$route['add-spareparts-sub'] = 'front/cpartsub/add';
$route['import-spareparts-sub'] = 'front/cpartsub/add_import';
$route['edit-spareparts-sub/(:num)'] = 'front/cpartsub/edit/$1';
$route['remove-spareparts-sub/(:num)'] = 'front/cpartsub/delete/$1';
// ===========================
//  End Routing Spareparts Subtitute
// ===========================

// ===========================
//  Routing Stock Spareparts
// ===========================
$route['manage-spareparts-stock'] = 'front/cstockpart/lists';
$route['data-spareparts-stock'] = 'front/cstockpart';
$route['add-spareparts-stock'] = 'front/cstockpart/add';
$route['import-spareparts-stock'] = 'front/cstockpart/add_import';
$route['detail-spareparts-stock/(:any)'] = 'front/cstockpart/detail/$1';
$route['edit-spareparts-stock/(:num)'] = 'front/cstockpart/edit/$1';
$route['remove-spareparts-stock/(:num)'] = 'front/cstockpart/delete/$1';
// ===========================
//  End Routing Stock Spareparts
// ===========================

// ===========================
//  Routing Partners
// ===========================
$route['manage-partners'] = 'front/cpartners/lists';
$route['data-partners'] = 'front/cpartners';
$route['add-partners'] = 'front/cpartners/add';
$route['edit-partners/(:any)'] = 'front/cpartners/edit/$1';
$route['remove-partners/(:any)'] = 'front/cpartners/delete/$1';
// ===========================
//  End Routing Partners
// ===========================

// ===========================
//  Routing Engineers
// ===========================
$route['manage-engineers'] = 'front/cengineers/lists';
$route['data-engineers'] = 'front/cengineers';
$route['add-engineers'] = 'front/cengineers/add';
$route['edit-engineers/(:any)'] = 'front/cengineers/edit/$1';
$route['remove-engineers/(:any)'] = 'front/cengineers/delete/$1';
// ===========================
//  End Routing Engineers
// ===========================

// ===========================
//  Routing Warehouses
// ===========================
$route['manage-warehouses'] = 'front/cwarehouse/lists';
$route['data-warehouses'] = 'front/cwarehouse';
$route['add-warehouses'] = 'front/cwarehouse/add';
$route['edit-warehouses/(:any)'] = 'front/cwarehouse/edit/$1';
$route['remove-warehouses/(:any)'] = 'front/cwarehouse/delete/$1';
// ===========================
//  End Routing Warehouses
// ===========================

// ===========================
//  Routing Tickets
// ===========================
$route['tickets'] = 'front/ctickets';
// ===========================
//  End Routing Tickets
// ===========================

// ===========================
//  Routing Outgoing Trans
// ===========================
$route['outgoing-trans'] = 'front/coutgoing';
$route['view-outgoing-trans'] = 'front/coutgoing/views';
$route['new-outgoing-trans'] = 'front/coutgoing/add';
// ===========================
//  End Routing Outgoing Trans
// ===========================

// ===========================
//  Routing Incoming Trans
// ===========================
$route['incoming-trans'] = 'front/cincoming';
$route['view-incoming-trans'] = 'front/cincoming/views';
$route['new-incoming-trans'] = 'front/cincoming/add';
// ===========================
//  End Routing Incoming Trans
// ===========================

/* END USER CONTROLLER */