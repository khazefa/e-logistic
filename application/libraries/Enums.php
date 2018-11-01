<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Enums
{
    public function enumsUserLevel($intEnc){
        // $CI =& get_instance();

        // if($CI->Roles_models->get_data($intEnc){
        // 	return "Anonymous";
        // }else{
        // 	return $arrLevel[$intEnc];
        // }

        $arrLevel = array(ROLE_ABOVE => "One Above All", ROLE_SU => "Super User", ROLE_USER => "User");

        if ($intEnc == 0){
            return "Anonymous";
        }else{
            return $arrLevel[$intEnc];
        }
    }

    public function enums_department($ID){
//        $CI =& get_instance();
//        $CI->db->select('dept_name, dept_alias');
//        $CI->db->where('dept_id = "'.$ID.'"');
//        $query=$CI->db->get('client_departments');
//        $query=$query->row_array();
//        return $query['userlvl'];

        $arrLevel = array(1 => "Claim", 2 => "Finance", 3 => "IT", 4 => "GA");

        if ($intEnc == 0){
            return "Non-Department";
        }else{
            return $arrLevel[$intEnc];		
        }
    }

    public function enumsGender($intId){
        $arrGender = array(0 => "Male", 1 => "Female", 2 => "unknown");

        // if ((int)$intId != 0 || (int)$intId != 1 || (int)$intId != 2 ){
        // 	return $arrGender;
        // }else{
                return $arrGender[$intId];		
        // }
    }

    public function enumsMonthName($intId){
        $arrMonth = array(1 => "Januari", 
                            2 => "Februari", 
                            3 => "Maret", 
                            4 => "April", 
                            5 => "Mei", 
                            6 => "Juni", 
                            7 => "Juli", 
                            8 => "Agustus", 
                            9 => "September", 
                            10 => "Oktober", 
                            11 => "November", 
                            12 => "Desember");
        if ($intId == 0){
            return $arrMonth;		
        }else{
            return $arrMonth[$intId];		
        }
    }
}

