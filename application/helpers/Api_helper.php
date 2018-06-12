<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reference Id to be used for defining an entity
 * @ref string, reference prefix id
 * @mode int, represents the output format option
 *       0 to default four section format
 *       1 to three section format
 */
 
function create_refid($ref='') {
    if(empty($ref)) $ref = strtotime('-14 years -7 months -25 days -6 hours', strtotime(date("Y-m-d H:i:s")));
    $salt = md5(uniqid());
    $res = substr($ref,0,9).substr($salt,11,6).mt_rand(101,998);
    return $res;
}

function token_generator(){
	return md5(uniqid());
}

function duration_time($time) {
    $res = '';
    if($time>=3600)
        $res = sprintf("%02d",floor($time/3600));
    if(($time%3600) > 0)
        $res .= ':'.sprintf("%02d",floor(($time%3600)/60));
    if((($time%3600)%60) > 0)
        $res .= ':'.sprintf("%02d",(($time%3600)%60));
    $res =preg_replace("/^:/", '', $res);
    return $res;
}

function generatePassword($length, $strength){
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';
    if ($strength & 1)
        $consonants .= 'BDGHJLMNPQRSTVWXZ';
    if ($strength & 2) 
        $vowels .= "AEUY";
    if ($strength & 4) 
        $consonants .= '23456789';
    if ($strength & 8) 
        $consonants .= '@#$%';

    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++){
        if ($alt == 1){
            $password .= $consonants[(rand() % strlen($consonants))];
            $alt = 0;
        }else{
            $password .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}

function send_curl($request, $url, $method, $isupload){
    $CI =& get_instance();
    //Initializing curl
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url);
    //If the request method is get append the data into requests header
    if( $method == 'GET' or $method == 'get' ){
        //Setting curl options for request
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
    }

    //If the request method is post add the data we need to enable post 
    //request and define the data in post fields
    if( $method == 'POST' or $method == 'post' ) {
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($CI->security->xss_clean($request)));
    }
    // curl_setopt($ch,CURLOPT_HTTPHEADER, array($this->config->item('dn-key')));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 180);
    curl_setopt($ch, CURLOPT_TIMEOUT, 240);
//    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8 (.NET CLR 3.5.30729)');
    if($isupload){
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); // required as of PHP 5.6.0
    }
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    //Executing the curl request and storing the result in a variable
    $result = curl_exec( $ch );
    //Closing curl conneciton
    $curl_errno = curl_errno($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);
     

    if ($curl_errno > 0) {
        $response = "cURL Error ($curl_errno): $curl_error\n";
    } else {
        $response = json_decode($result);
    }

    return $response;
}