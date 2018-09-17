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
