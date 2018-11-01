<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class Login extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('encrypt');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {     
        /** define the directory **/
        $dir = "tmp/";
        $path = $dir."*.xlsx";
        $path_all = $dir."*";

        /*** cycle through all files in the directory ***/
        foreach (glob($path_all) as $file) {
            /*** if file is 24 hours (86400 seconds) old then delete it ***/
            if(time() - filectime($file) > 86400){
                if (file_exists($file)) {
                    delete_files($file);
                }
            }
        }
        $this->isSessionFilled();
    }
    
    /**
     * This function used to check the user is logged in or not
     */
    function isSessionFilled()
    {
        $isSessionFilled = $this->session->userdata('isSessionFilled');
        
        if(!isset($isSessionFilled) || $isSessionFilled != TRUE)
        {
            $this->load->view('front/v_login');
        }
        else
        {
            redirect('cl');
        }
    }
    
    /**
     * This function used to logged in user
     */
    public function auth_log()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|max_length[30]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[64]|trim');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('error', 'Error Login');
            $this->index();
        }
        else
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            //Parse Data for cURL
            $arrWhere = array(
                "username"=>$this->security->xss_clean($username),
                "password"=>$this->security->xss_clean($password)
            );
            
            $res = send_curl($arrWhere, $this->config->item('api_auth'), 'POST', FALSE);
            //Check Result ( Get status TRUE or FALSE )
            if($res->status){
                $wh_name = $res->accessRepo === "00" ? "WH" : $this->get_info_warehouse_name($res->accessRepo);
//                if($res->role === ROLE_SPV){
//                    //Set Session for login
//                    $sessionArray = array(
//                        'ovId'=>$res->accessId,         
//                        'ovUR'=>$res->accessUR,   
//                        'ovName'=>$res->accessName,
//                        'ovRepo'=>$res->accessRepo,   
//                        'ovCoverage'=>$res->accessCoverage,
//                        'ovRepoName'=>$wh_name,
//                        'ovRole'=>$res->role,
//                        'ovRoleText'=>$res->roleText,
//                        'isSessionSettled' => TRUE
//                    );
//                    $this->session->set_userdata($sessionArray);
//                    redirect('oversee');
//                }else{
                    //Set Session for login
                    $sessionArray = array(
                        'vendorId'=>$res->accessId,         
                        'vendorUR'=>$res->accessUR,   
                        'vendorName'=>$res->accessName,          
                        'isAdm'=>$res->isAdmin,          
                        'vendorRepo'=>$res->accessRepo,   
                        'ovCoverage'=>$res->accessCoverage,       
                        'vendorRepoName'=>$wh_name,
                        'role'=>$res->role,
                        'roleText'=>$res->roleText,
                        'cart_session'=> sha1($res->accessUR.$res->accessName.$res->accessRepo),
                        'isSessionFilled' => TRUE
                    );
                    $this->session->set_userdata($sessionArray);
                    redirect('cl');
//                }
            }
            else{
                $this->session->set_flashdata('error', $res->message);
                redirect('login');
            }
        }
    }
    
    /**
     * This function is used to get detail information
     */
    private function get_info_warehouse_name($fcode){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_info_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $wh_name = "";
        foreach ($rs as $r) {
            $wh_name = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
        }
        
        return $wh_name;
    }

    /**
    * This function used to generate reset password request link
    */
    function reset_pass()
    {
        $status = '';

        $this->form_validation->set_rules('femail','Email','trim|required|valid_email');

        if($this->form_validation->run() == FALSE)
        {
           $this->index();
        }
        else 
        {
           $email = $this->input->post('femail', TRUE);
           //Parse Data for cURL
           $arrWhere = array(
               "email"=>$email
           );
           $res = send_curl($arrWhere, $this->config->item('api_reset_pass'), 'POST', FALSE);

           //Check Result ( Get status TRUE or FALSE )
           if($res->status){
               $this->session->set_flashdata('success', $res->message);
               redirect('login');
           }
           else{
               $this->session->set_flashdata('error', $res->message);
               redirect('login');
           }
        }
    }
 
    // This function used to reset the password 
    function reset_pass_confirm($activation_id, $email)
    {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);

       //Parse Data for cURL
       $arrWhere = array(
           "email"=>$email, "activation_id"=>$activation_id
       );
       $res = send_curl($arrWhere, $this->config->item('api_reset_pass_confirm'), 'GET', FALSE);

       //Check Result ( Get status TRUE or FALSE )
       if($res->status){
           $this->session->set_flashdata('success', $res->message);
           $data['email'] = $res->email;
           $data['activation_code'] = $res->activation_code;
           $this->load->view('front/auth/newPassword', $data);
       }
       else{
           $this->session->set_flashdata('error', $res->message);
           redirect('login');
       }
    }
    
    // This function used to act to change the new password 
    function change_new_password()
    {
        $this->form_validation->set_rules('femail','Email','trim|valid_email');

        if($this->form_validation->run() == FALSE)
        {
           $this->index();
        }
        else 
        {
            $email = $this->input->post('femail', TRUE);
            $activation_id = $this->input->post('activation_code', TRUE);
            // Get email and activation code from URL values at index 3-4
            $email = urldecode($email);

            //Parse Data for cURL
            $arrWhere = array(
                "femail"=>$email, "activation_code"=>$activation_id
            );
            $res = send_curl($arrWhere, $this->config->item('api_new_pass'), 'POST', FALSE);

            //Check Result ( Get status TRUE or FALSE )
            if($res->status){
                $this->session->set_flashdata('success', $res->message);
                redirect('login');
            }
            else{
                $this->session->set_flashdata('error', $res->message);
                redirect('login');
            }
        }
    }
}

?>