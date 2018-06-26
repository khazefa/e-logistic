<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Common 
{

    public function nohtml($message) 
    {
        $message = strip_tags($message);
        $message = htmlspecialchars($message, ENT_QUOTES);
        return $message;
    }
    
    /**
     * filterInput
     *
     * Sanitize Input String
     *
     * @return	Clean Input String
     */
    public function filterInput($content)
    {
        $content = trim($content);
        $content = stripslashes($content);

        return $content;
    }

    /**
     * filterOutput
     *
     * Sanitize Output String for viewing data
     *
     * @return	Clean Output String
     */
    public function filterOutput($content)
    {
        $content = htmlentities($content, ENT_NOQUOTES);
        $content = nl2br($content, false);

        return $content;
    }
    
    public function valid_emails($email)
    {
        if (function_exists('filter_var'))
        {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
        else
        {
            return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,10}$/ix", $email)) ? FALSE : TRUE;
        }
    }

    public function valid_int_number($num)
    {
        if (function_exists('filter_var'))
        {
            return filter_var($num, FILTER_SANITIZE_NUMBER_INT);
        }
    }

    public function valid_phone($str)
    {
        $num = $str;
        $num = preg_replace("#[^0-9]#", null, $str);

        if(!is_numeric($num))
        {
            return FALSE;
        }

        if(strlen($num) < 7)
        {
            return FALSE;
        }

        return TRUE;
    }	

    public function is_equal_to($val, $val2)
    {
        if($val == $val2) 
        {
            return TRUE;
        }

        return FALSE;
    }	

    public function is_not_equal_to($val, $val2)
    {
        if($val != $val2) 
        {
            return TRUE;
        }

        return FALSE;
    }	

    public function is_of_file_type($fileName, $fileType)
    {
        if($fileType == 'zip') 
        {
            $fileType = 'application/zip';
        }
        if(!empty($_FILES[$fileName]['type']))
        {
            if($_FILES[$fileName]['type'] != $fileType)
            {
                return FALSE;
            }
        }

        return TRUE;
    }

    public function valid_file_upload($file_name)
    {
        if(empty($_FILES[$file_name]) OR $_FILES[$file_name]['error'] > 0)
        {
            return FALSE;
        } 
        else {
            return TRUE;
        }
    }	

    public function is_safe_character($val, $allowed = array('_', '-'))
    {
        $newVal = str_replace($allowed, '', $val);
        if(ctype_alnum($newVal)) 
        {
            return TRUE;
        }

        return FALSE;
    }		

    public function randomString() 
    {
    	$letters = array(
            "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q",
            "r","s","t","u","v","w","x","y","z"
        );
    	$str = "";
    	for($i=0;$i<10;$i++) {
    		shuffle($letters);
    		$letter = $letters[0];
    		if(rand(1,2) == 1) {
	    		$str .= $letter;
    		} else {
	    		$str .= strtoupper($letter);
    		}
    		if(rand(1,3)==1) {
    			$str .= rand(1,9);
    		}
    	}
    	return $str;
    }

    public function getAccessLevel($level) 
    {
        if($level == 1) {
            return "System Administrator";
        } elseif($level == 2) {
            return "Supervisor";
        } elseif($level == 3) {
            return "Operator";   
        } elseif($level == -1) {
            return "Banned";
        } else {
            return "Invalid Access";
        }
    }

    public function checkAccess($level, $required) 
    {
        $CI =& get_instance();
        if($level < $required) {
            $CI->template->error(
                "You do not have the required access to use this page. 
                You must be a ". $this->getAccessLevel($required)
                . "to use this page."
            );
        }
    }

	public function getClientLevel($level) 
    {
        if($level == 1) {
            return "Administrator";
        } elseif($level == 2) {
            return "Manager";
        } elseif($level == 3) {
            return "Staff";   
        } elseif($level == -1) {
            return "Banned";
        } else {
            return "Invalid Access";
        }
    }

    public function checkClient($level, $required) 
    {
        $CI =& get_instance();
        if($level < $required) {
            $CI->template->error(
                "You do not have the required access to use this page. 
                You must be a ". $this->getClientLevel($required)
                . "to use this page."
            );
        }
    }

    public function send_email($subject, $body, $emailt) 
    {
        $CI =& get_instance();
        $CI->load->library('email');

        $CI->email->from($CI->settings->info->site_email, $CI->settings->info->site_name);
        $CI->email->to($emailt);

        $CI->email->subject($subject);
        $CI->email->message($body);

        $CI->email->send();
    }

    public function check_mime_type($file) {
        return true;
    }

    public function replace_keywords($array, $message) {
        foreach($array as $k=>$v) {
            $message = str_replace($k, $v, $message);
        }
        return $message;
    }

}

?>
