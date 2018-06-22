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
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {            
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

//            var_dump($res);exit();
            
            //Check Result ( Get status TRUE or FALSE )
            if($res->status){
                //Set Session for login
                $sessionArray = array(
                    'vendorId'=>$res->accessId,         
                    'vendorUR'=>$res->accessUR,   
                    'vendorName'=>$res->accessName,          
                    'isAdm'=>$res->isAdmin,          
                    'vendorRepo'=>$res->accessRepo,          
                    'role'=>$res->role,
                    'roleText'=>$res->roleText,
                    'isSessionFilled' => TRUE
                );
                $this->session->set_userdata($sessionArray);
                redirect('cl');
            }
            else{
                $this->session->set_flashdata('error', $res->message);
                redirect('login');
            }
        }
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
             
             if($this->MLog->check_email_exist($email))
             {
                 $encoded_email = urlencode($email);
 
                 $data['email'] = $email;
                 $data['activation_id'] = random_string('alnum',15);
                 $data['createdDtm'] = date('Y-m-d H:i:s');
                 $data['agent'] = getBrowserAgent();
                 $data['client_ip'] = $this->input->ip_address();
                 
                 $save = $this->MLog->reset_password_user($data);                
                 
                 if($save)
                 {
                     $data1['reset_link'] = base_url() . "login/reset_pass_confirm/" . $data['activation_id'] . "/" . $encoded_email;
                     $userInfo = $this->MLog->get_info_by_email($email);
                     if(!empty($userInfo)){
                         $data1["name"] = $userInfo[0]->cl_name;
                         $data1["email"] = $userInfo[0]->cl_email;
                         $data1["message"] = "Reset Your Password";
                     }
 
                     $sendStatus = resetPasswordEmail($data1);
 
                     if($sendStatus){
                         $status = "success";
                         setFlashData($status, "Reset password link sent successfully, please check your email.");
                     } else {
                         $status = "error";
                         setFlashData($status, "Email has been failed, try again.");
                     }
                 }
                 else
                 {
                     $status = 'error';
                     setFlashData($status, "It seems an error while sending your details, try again.");
                 }
             }
             else
             {
                $ipaddress = $this->input->ip_address();
                $uagent = $this->agent->agent_string();
                $createddate = date('Y-m-d H:i:sa');

                $logInfo = array('la_user'=>$email, 'la_uname'=>$email,
                    'la_log_ip'=> $ipaddress,'la_log_user_agent'=> $uagent, 'la_log_time'=>$createddate, 
                    'la_status'=>'X', 'la_activity'=>'Asking for reset pass');

                $reslogs = $this->MLogacc->insert_data($this->security->xss_clean($logInfo));
                    
                $status = 'error';
                setFlashData($status, "This email is not registered with us.");
             }
             redirect('login');
         }
     }
 
     // This function used to reset the password 
     function reset_pass_confirm($activation_id, $email)
     {
         // Get email and activation code from URL values at index 3-4
         $email = urldecode($email);
         
         // Check activation id in database
         $is_correct = $this->MLog->check_activation_details($email, $activation_id);
         
         $data['email'] = $email;
         $data['activation_code'] = $activation_id;
         
         if ($is_correct == 1)
         {
             $this->load->view('front/auth/newPassword', $data);
         }
         else
         {
             redirect('login');
         }
     }
}

?>