<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Directory Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Arief Budi Santoso
 * @link		https://codeigniter.com/user_guide/helpers/directory_helper.html
 */

// ------------------------------------------------------------------------
  	if( !function_exists('load_template_CSS') ) {
    	/**
		 * Load CSS di dalam template
		 *
		 * Write CSS tag and path for template.
		 *
		 * @return	CSS HTML tag with url path
		*/
	    function load_template_CSS() {
	    	$baseurl = get_instance()->config->base_url();

	    	echo '
	    		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900">
	    		<link rel="stylesheet" href="'.$baseurl.'assets/css/layout/icons/fontawesome/styles.min.css">
	    		<link rel="stylesheet" href="'.$baseurl.'assets/css/layout/icons/icomoon/styles.css">
	    		<link rel="stylesheet" href="'.$baseurl.'assets/css/layout/bootstrap.css">
	    		<link rel="stylesheet" href="'.$baseurl.'assets/css/layout/core.css">
	    		<link rel="stylesheet" href="'.$baseurl.'assets/css/layout/components.css">
	    		<link rel="stylesheet" href="'.$baseurl.'assets/css/layout/colors.css">
	    		<link rel="stylesheet" href="'.$baseurl.'assets/css/layout/custom.css">
	    		';
	    }

  	}

  	if( !function_exists('load_template_JS') ) {
    	/**
		 * Load JS di dalam template
		 *
		 * Write JS tag and path for template.
		 *
		 * @return	JS HTML tag with url path
		*/
	    function load_template_JS() {
	    	$baseurl = get_instance()->config->base_url();

	    	echo '
	    		<script src="'.$baseurl.'assets/vendors/plugins/loaders/pace.min.js"></script>
	    		<script src="'.$baseurl.'assets/vendors/core/libraries/jquery.min.js"></script>
	    		<script src="'.$baseurl.'assets/vendors/core/libraries/bootstrap.min.js"></script>
	    		<script src="'.$baseurl.'assets/vendors/plugins/loaders/blockui.min.js"></script>

	    		<script src="'.$baseurl.'assets/vendors/plugins/visualization/d3/d3.min.js"></script>
	    		<script src="'.$baseurl.'assets/vendors/plugins/forms/styling/switchery.min.js"></script>
	    		<script src="'.$baseurl.'assets/vendors/plugins/forms/styling/uniform.min.js"></script>
	    		<script src="'.$baseurl.'assets/vendors/plugins/forms/selects/bootstrap_multiselect.js"></script>
	    		<script src="'.$baseurl.'assets/vendors/plugins/ui/nicescroll.min.js"></script>

	    		<script src="'.$baseurl.'assets/vendors/plugins/ui/ripple.min.js"></script>

	    		';
	    }

  	}

  	if( !function_exists('load_template_menu') ) {
    	/**
		 * Load Menu di dalam template
		 *
		 * Write Menu list and url for template.
		 *
		 * @return	HTML tag list menu with url path
		*/
	    function load_template_menu($strRole = 1) {
	    	$baseurl = get_instance()->config->base_url();

	    	$tagStart = '<ul class="nav side-menu">
							<li>
								<a href="'.$baseurl.'dashboard"><i class="fa fa-home"></i> Dashboard </a>
							</li>';
	    	$tagEnd = '</ul>';

	    	$tagMenuUser = '<li><a><i class="fa fa-user"></i> Users <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
						        	<li><a href="'.$baseurl.'user">User List</a></li>
						        </ul>
						    </li>';
	    	$tagMenuApp = '<li><a><i class="fa fa-th-large"></i> Applications <span class="fa fa-chevron-down"></span></a>
						        <ul class="nav child_menu">
						            <li><a href="'.$baseurl.'applicationClient">Application List</a></li>
						        </ul>
						    </li>';
	    	$tagMenuToken = '<li><a><i class="fa fa-key"></i> Tokens <span class="fa fa-chevron-down"></span></a>
						        <ul class="nav child_menu">
						            <li><a href="'.$baseurl.'token">Token List</a></li>
						        </ul>
						    </li>';
	    	$tagMenuLog = '<li><a><i class="fa fa-braille"></i> Logs <span class="fa fa-chevron-down"></span></a>
						        <ul class="nav child_menu">
						        	<li><a href="'.$baseurl.'log">Application Logs</a></li>
						        	<!--<li><a href="'.$baseurl.'log/advanced">Search Log</a></li>-->
						        </ul>
						    </li>';
	    	$tagMenuSettings = '<li><a><i class="fa fa-cog"></i> Settings <span class="fa fa-chevron-down"></span></a>
						        <ul class="nav child_menu">
						            <li><a href="'.$baseurl.'setting/myprofile">My Profile</a></li>
						            <li><a href="'.$baseurl.'setting/changepassword">Change Password</a></li>
						            <li><a href="'.$baseurl.'setting/help">Help</a></li>
						        </ul>
						    </li>';
	    	$tagMenuAdmin = '<li><a><i class="fa fa-user-secret"></i> Admins <span class="fa fa-chevron-down"></span></a>
						        <ul class="nav child_menu">
						            <li><a href="'.$baseurl.'admin">Admin List</a></li>
						        </ul>
						    </li>';

			if ($strRole == 1) {
				echo $tagStart.$tagMenuUser.$tagMenuApp.$tagMenuLog.$tagMenuSettings.$tagEnd;
			}else{
				echo $tagStart.$tagMenuUser.$tagMenuApp.$tagMenuToken.$tagMenuLog.$tagMenuAdmin.$tagMenuSettings.$tagEnd;
			}
	    }

  	}
