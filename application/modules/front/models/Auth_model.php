<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    var $tbl_users ="users";
    var $pkey_users ="user_id";
    var $ukey_users ="user_key";

    var $tbl_group ="user_group";
    var $pkey_group ="group_id";
    var $ukey_group ="group_enc";

    /**
     * This function used to check the login credentials of the user
     * @param string $username : This is username of the user
     * @param string $password : This is encrypted password of the user
     */
    function auth_log($username, $password)
    {
        $this->db->select('u.user_id, u.user_key, u.user_pass, u.user_fullname, u.group_enc, '
                . 'r.group_name, r.group_enc, d.dept_name, d.dept_id, u.cl_pict');
        $this->db->from($this->tbl_users.' AS u');
        $this->db->join($this->tbl_group.' AS r','r.group_enc = u.group_enc');
        $this->db->where('u.user_key', $username);
        $this->db->where('u.deleted_at', NULL);
        $query = $this->db->get();
        
        $user = $query->result();
        
        if(!empty($user)){
            if(verifyHashedPassword($password, $user[0]->user_pass)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function used to check username exists or not
     * @param {string} $username : This is client_users username id
     * @return {boolean} $result : TRUE/FALSE
     */
     function check_username_exist($username)
     {
         $this->db->select('user_id');
         $this->db->where('user_key', $username);
         $this->db->where('deleted_at', NULL);
         $query = $this->db->get($this->tbl_users);

         if ($query->num_rows() > 0){
             return true;
         } else {
             return false;
         }
     }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is client_users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function check_email_exist($email)
    {
        $this->db->select('user_id');
        $this->db->where('cl_email', $email);
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get($this->tbl_users);

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function reset_password_user($data)
    {
        $result = $this->db->insert('client_reset_password', $data);

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by username-id for forget password username
     * @param string $username : username id of customer
     * @return object $result : Information of customer
     */
    function get_info_by_username($username)
    {
        $this->db->select('user_id, user_key, user_fullname');
        $this->db->from($this->tbl_users);
        $this->db->where('deleted_at', NULL);
        $this->db->where('user_key', $username);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function get_info_by_email($email)
    {
        $this->db->select('user_id, cl_email, user_fullname');
        $this->db->from($this->tbl_users);
        $this->db->where('deleted_at', NULL);
        $this->db->where('cl_email', $email);
        $query = $this->db->get();

        return $query->result();
    }
    
    /**
     * This function is used to get customer registration date and  by username-id for aging password
     * @param string $username : username id of customer
     * @return object $result : Information of customer
     */
    function get_pass_expires_info($username)
    {
        $this->db->select('cl_createdDtm, cl_passUpdatee');
        $this->db->from($this->tbl_users);
        $this->db->where('deleted_at', NULL);
        $this->db->where('user_key', $username);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function check_activation_details($email, $activation_id)
    {
        $this->db->select('cl_id');
        $this->db->from('client_reset_password');
        $this->db->where('cl_email', $email);
        $this->db->where('cl_activation_id', $activation_id);
        $query = $this->db->get();
        return $query->num_rows;
    }

    // This function used to create new password by reset link
    function create_password($email, $password)
    {
        $this->db->where('cl_email', $email);
        $this->db->where('deleted_at', NULL);
        $this->db->update($this->tbl_users, array('user_pass'=>getHashedPassword($password)));
        $this->db->delete('client_reset_password', array('cl_email'=>$email));
    }
}

?>