<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends Base_Controller
{

    protected $data = array();

    public function __construct()
    {
		  
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth', 'email', 'form_validation'));
        $this->load->helper(array('url', 'language', 'ws_helper'));
		$this->ion_auth_model->hash_password('password', FALSE);
        $this->load->model('customerweb_model');
        $this->lang->load('auth');
         $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->template->set_layout('customer/default');
        $this->template->title(' Customer | ');
        $this->data['action_title'] = "Customer Website";

    }

    /**
    *just a test ,print any info.
    */
    public function test()
    {
        echo CI_VERSION;echo "<br />";
        echo customer_interface_url(uri_string());echo "<br />";
        echo uri_string();echo "<br />";
        echo base_url();echo "<br />";
        echo CUSTOMER;echo "<br />";
    }

    /**
    *    check if request is post procces login else load form
    *    @param string $email
    *    @param string $password
    *    @param boolean $remember
    */
    public function index() {
         if( $this->session->userdata('current_user') != NULL ){
            redirect('customer/user', 'refresh');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
                        array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|xss_clean'),
                    )
            );
            $remember = $this->input->post('remember') ? TRUE : FALSE;
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'error: '.validation_errors());
                $this->template->title("Login | ");
                $this->template->build("login", $this->data ); 
            } else {
                if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember, CUSTOMER)) {
                    if ($this->ion_auth->user()->row()->active == 1){
                        redirect('customer/dashboard', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Invalid email/password combination');
                    redirect('customer/login');
                }
            }

        }

        $this->template->title("Login | ");
        $this->template->build("login", $this->data );   
        // $this->template->build('login', array('action_title'=>$this->action_title) );
        
    }

    /**
    *    check if request is post procces register else load register form
    *    @param string $name
    *    @param string $address
    *    @param string $email
    *    @param string $password
    *    @param string $phone
    */
    public function register() {
        if ($this->input->post()) {
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'name', 'label' => 'Name', 'rules' => 'required|trim'),
                        array('field' => 'address', 'label' => 'Address', 'rules' => 'trim|required'),
                        array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email'),
                        array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),
                        array('field' => 'phone', 'label' => 'Mobile Number', 'rules' => 'trim|required'),
                    )
            );
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('message', validation_errors() );
                redirect(customer_interface_url(uri_string('register')),'refresh');
            }
            $return = array();

            $name = trim($this->input->post('name'));
            $address = $this->input->post('address');
            $email = trim($this->input->post('email'));
            $username = $email;
            $password = $this->input->post('password');
            $phone_number = trim($this->input->post('phone'));
            
            $activation_code = sha1(md5(microtime()));

            $additional_data = array(
                'name' => $name,
                'full_address' => $address,
                'phone_number' => $phone_number,
                'activation_code' => $activation_code,
                'active' => 0,
                'created_on' => date('Y-m-d H:i:s')
                
            );
            $res = $this->customerweb_model->check_if_customer($email);

            if(!empty($res)){
                $this->session->set_flashdata('message', "Another user  already exist with this email : $email.");
                redirect(customer_interface_url(uri_string('register')),'refresh');
            }

            if ($user_id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array(CUSTOMER))) {

                if( $this->send_email($user_id, $email, $activation_code, $name) ){
                    $this->session->set_flashdata('message', $this->ion_auth->messages()." Check your email for fonformation ");
                    $this->db->where('id', $user_id);
                    $this->db->update('tbl_user', array('active' => 0) );
                }
                else{
                    $this->session->set_flashdata('message', "Sorry We couldn't deliver the email activation to $email Please Try Again Later");
                    $this->db->where('id', $user_id);
                    $this->db->delete('tbl_user');
                }
            } 
            else {    
                $this->session->set_flashdata('message', $this->ion_auth->messages());
            }
            redirect(customer_interface_url(uri_string('register')),'refresh');

        } else {
            $this->template->title(' Customer Website  ');
            $this->template->build('register',$this->data );
        }
    }

    /**
    *    activate existing user if user_id and $activation code is valid otherwise shows invalid message 
    *    @param int $user_id
    *    @param string $activation_code
    */
    public function activate() {
        $user_id = $this->input->get('user') ? $this->input->get('code') : FALSE ; 
        $activation_code = $this->input->get('code') ? $this->input->get('code') : FALSE ;

        if( $user_id != FALSE && $activation_code != FALSE )
            $checkuserid = $this->db->query("select id from tbl_user where id = '$identity' and activation_code = '$activation_code'");
        else{echo $content = "<p>Invalid Link</p>";exit(0);}


        if ($checkuserid->num_rows() > 0) {
            $this->db->update('tbl_user', array('active' => '1','activation_code' => '','email_verified' => '1'), array('id' => $user_id));
            $user_email = $this->db->select('email,name')->from('tbl_user')->where("id = '$user_id'")->get();
            $user_email = $user_email->row();
            
            $to = $user_email->email;
            $subject = "Welcome to CREWGO";

            $message = $this->load->view('email_template/activation_template', array('name' => $user_email->name), TRUE);

            $from = "info@crewgo.com";
            $this->email->to($user_email->email)
                    ->from($from, "CREWGO")
                    ->subject($subject)
                    ->message($message);

            $this->email->send();
             echo $content = "<p>Your account is validated now.Thank you</p>";
        } else {

            echo $content = "<p>Invalid Link</p>";
        }
    }

    /**
    *    sends confirmation email for user of type customer
    *    @param int $user_id
    *    @param string $email
    *    @param string $activation_code
    *    @param string $username
    *    @return bool
    */
    private function send_email($user_id, $email, $activation_code, $username) {
        $to = $email;
        $subject = "Email confirmation";
        $data = array(
            'username' => $username,
            'user_id' => $user_id,
            'activation_code' => $activation_code,
        );


        $message = $this->load->view("email_template/email_confirmation", $data, TRUE);

        $from = "info@crewgo.com";
        $this->email->to($to)
                ->from($from, "CREWGO")
                ->subject($subject)
                ->message($message);

        return $this->email->send();
       
    }

    /**
    *    check if request is post procces forgotpassword else load forgotpassword form
    *    @param string $email
    */
    public function forgotpassword(){
        if($this->input->post()){
             $this->form_validation->set_rules(
                    array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean')
                );
             //check if email exists
             //if( $this->ion_auth->email_check($this->input->post('email'))){ does not checks email by group
             if( $this->customerweb_model->check_if_customer($this->input->post('email')) ){
                if( $this->ion_auth->forgotten_password($this->input->post('email')) ){
                    $user = $this->customerweb_model->get_user_by_email($this->input->post('email'));
                    if( $user != false ){
                        $subject = " Password Recovery ";
                        $data = array(
                            'name' => $user->name,
                            'user_id' => $user->id,
                            'code' => $user->forgotten_password_code,
                        );

                        $message = $this->load->view("email_template/forgot_password_template", $data, TRUE);

                        $from = "info@crewgo.com";
                        $this->email->to($user->email)
                                ->from($from, "CREWGO")
                                ->subject($subject)
                                ->message($message);

                        if( $this->email->send() ){
                            $this->session->set_flashdata('success', 'Password reset link has been sent to your email' );
                        } else {
                             $this->session->set_flashdata('error', "We could not proceed your request please try again later");
                        }
                    } else {
                        $this->session->set_flashdata('error', "We could not proceed your request please try again later");
                    }
                } else {
                    $this->session->set_flashdata('error', "We could not proceed your request please try again later" );
                }
             } else {
                    $this->session->set_flashdata('error', "this email does not exist with any CREWGO user");
                }
            redirect(customer_interface_url("login/forgotpassword"), 'refresh');
        }
        $this->template->build('forgotpassword', $this->data);
    }

    /**
    *    completes forgotten password generating new password and sending it to user email
         redirects to login on success or forgotpassword on failure and shows 404 error on invalid request
    *    @param int $user_id
    *    @param string $code
    */
    public function complete_forgotten_password(){
        $user_id = $this->input->get('user') ? $this->input->get('code') : FALSE ; 
        $code = $this->input->get('code') ? $this->input->get('code') : FALSE ;
        if( $user_id != FALSE && $code != FALSE ){
            $result = $this->ion_auth->forgotten_password_complete($code);
            if( $result != FALSE ){
                $subject = "New Password";
                $data = array(
                    'username' => $result['identity'],
                    'password' => $result['new_password']
                );


                $message = $this->load->view("email_template/new_password", $data, TRUE);

                $from = "info@crewgo.com";
                $this->email->to($result['identity'])
                        ->from($from, "CREWGO")
                        ->subject($subject)
                        ->message($message);

                if( $this->email->send() ){
                    redirect('customer/login');
                } else {
                    $this->session->set_flashdata('message', 'Sorry we are unable to proceed your request please contact you service provider');
                    redirect(customer_interface_url("login/forgotpassword"), 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', 'Sorry we are unable to proceed your request please try again later');
                    redirect(customer_interface_url("login/forgotpassword"), 'refresh');
            }
        } else {
            show_404();
        }
    }

}//end of class
