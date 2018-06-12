<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model
{
    var $tbl_users ="client_users";
    var $pkey_users ="cl_userId";
    var $ukey_users ="cl_username";

    var $tbl_roles ="client_roles";
    var $pkey_roles ="cl_roleId";
    var $ukey_roles ="cl_role_enc";
    
    var $tbl_departments ="client_departments";
    var $pkey_departments ="dept_id";
    var $ukey_departments ="dept_alias";
    
    var $column_order = array(null,'cl_username','cl_email','cl_name','cl_mobile','cl_roleId',null); //set column field database for datatable orderable
    var $column_search = array('cl_email','cl_name','cl_mobile'); //set column field database for datatable searchable 
    var $order = array('cl_userId' => 'asc'); // default order 
 
    function __construct()
    {
        parent::__construct();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    function count_all()
    {
        $this->db->from($this->tbl_users);
        return $this->db->count_all_results();
    }

    function get_list(){
        $this->db->select('u.cl_userId, u.cl_username, u.cl_email, u.cl_name, 
        u.cl_mobile, u.cl_role_enc, r.cl_role, d.dept_name');
        $this->db->from($this->tbl_users.' AS u');
        $this->db->join($this->tbl_roles.' AS r','r.cl_role_enc = u.cl_role_enc');
        $this->db->join($this->tbl_departments.' AS d','d.dept_id = u.dept_id');
        $this->db->where('u.cl_isDeleted', 0);
        $this->db->where('r.cl_role_enc <>', ROLE_SUPER);
//        $this->db->where('r.cl_role_enc <>', 2);
        $query = $this->db->get();
        
        $data = $query->result();
        
        if(!empty($data)){
            return $data;
        } else {
            return array();
        }
    }

    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function get_user_roles()
    {
        $this->db->select('cl_roleId, cl_role, cl_role_enc');
        $this->db->from($this->tbl_roles);
        $this->db->where('cl_roleId <>', 1);
        $this->db->where('cl_roleId <>', 2);
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * This function is used to get the user departements information
     * @return array $result : This is result of the query
     */
    function get_departments()
    {
        $this->db->select('*');
        $this->db->from($this->tbl_departments);
        $this->db->where('is_actived <>', 0);
        $this->db->where('dept_alias <>', 'author');
        $query = $this->db->get();
        
        return $query->result();
    }
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function insert_data($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert($this->tbl_users, $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by username
     * @param number $userId : This is user username
     * @return array $result : This is user information
     */
    function get_data_info($userId)
    {
        $this->db->select('cl_userId, cl_username, cl_name, cl_email, cl_mobile, cl_role_enc, dept_id, cl_pict');
        $this->db->from($this->tbl_users);
        $this->db->where('cl_isDeleted', 0);
//        $this->db->where('cl_roleId !=', 1);
        $this->db->where('cl_username', $userId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function get_data_email_byId($userId)
    {
        $this->db->select('cl_email');
        $this->db->from($this->tbl_users);
        $this->db->where('cl_isDeleted', 0);
        $this->db->where('cl_userId', $userId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is client_users updated information
     * @param number $userId : This is user id
     */
    function update_data($userInfo, $userName)
    {
        $this->db->where('cl_username', $userName);
        $this->db->update($this->tbl_users, $userInfo);
        
        return TRUE;
    }
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function delete_data($userId, $userInfo)
    {
        $this->db->where('cl_username', $userId);
        $this->db->update($this->tbl_users, $userInfo);
        
        return $this->db->affected_rows();
    }

    /**
     * This function is used to match client_users password for change password
     * @param number $userId : This is user id
     */
    function match_old_password($userId, $oldPassword)
    {
        $this->db->select('cl_userId, cl_password');
        $this->db->where('cl_userId', $userId);        
        $this->db->where('cl_isDeleted', 0);
        $query = $this->db->get($this->tbl_users);
        
        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    /**
     * This function is used to change client_users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function change_password($userId, $userInfo)
    {
        $this->db->where('cl_userId', $userId);
        $this->db->where('cl_isDeleted', 0);
        $this->db->update($this->tbl_users, $userInfo);
        
        return $this->db->affected_rows();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $username : This is username id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function check_username_exists($str)
    {
        $this->db->select('cl_username');
        $this->db->from($this->tbl_users);
        $this->db->where('cl_username', $str);
        $cnt = $this->db->count_all_results();

        return $cnt;
    }
}

  