<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->form_validation->CI = &$this;
        $this->template->set_layout('lhcadmin/login');
        $this->load->model('auth/lhc_model');
        $this->load->model('email_model');
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('url', 'language'));
        $this->form_validation->set_error_delimiters('<span class="help-block" style="color:red;">', '</span>');

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->get_users_groups()->row()->id == "2"){
                 redirect('lp/home');
            }
        } 

    }

    public function index()
    {
        $this->form_validation->CI = $this;

        if ($this->input->post()) {
            //set validation rules //field, label, rules
            $this->form_validation->set_rules('email', 'email', 'required|trim|xss_clean');
            $this->form_validation->set_rules('password', 'password', 'required|trim|xss_clean');

            //false
            if ($this->form_validation->run() == false) {
                $this->load->view('lhclogin');
            } else {

                //validate email and password
                /*
                 * check whether login is success or not
                 * 1. if login is success, redirect to the protected area
                 * 2. if fails, return back with the appropriate error message
                 */
                if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember = false, $user_group = "2")) {
                    if ($this->ion_auth->user()->row()->active == 1){
                        redirect('lp/home', 'refresh');
                    }
                } else {
                    //login fails
                    $this->session->set_flashdata('message', 'Invalid email/password combination');
                    redirect('lp/admin');
                }

            }

        }

        $this->data = '';
        $this->template->title("Login | ");
        $this->template->build("lhclogin", $this->data);
    }

    public function forgotpassword()
    {
        if ($this->input->post()) {
            $email = $this->input->post('email');
            if ($this->lhc_model->checkEmail($email) == 1) {
                /*
                 * add a forgot password code first
                 */
                $code = hashCode(32);
                $this->lhc_model->insertCode($email, $code);
                $this->sendResetEmail($email, $code);
            } else {
                //invalid email
                $this->session->set_flashdata('error', 'The supplied email is invalid');
                redirect('lp/admin');
            }

        }
    }

    public function sendResetEmail($email, $code)
    {
        $template = $this->db->select('*')
            ->from('tbl_email_templates')
            ->where('template_id', '2')
            ->get();

        $body = $template->row();



        $_POST['user_name']       = 'User';
        $encrypt_hash             = $code;
        $_POST['activation_code'] = '<a href="' . base_url('lp/admin/resetpassword') . '/' . $encrypt_hash . '" title="Reset Password" style="color:#f4eb38">' . base_url('lp/admin/resetpassword') . '/' . $encrypt_hash . '</a>';
        $_POST['email']           = $email;
        if ($this->email_model->send_passwordreset_mail()){
            $this->session->set_flashdata('success', 'Please check your email for reset instruction');
            redirect('lp/admin');
        }
    }

    public function resetpassword($code)
    {

        $data = array();

        $data['code'] = $code;

        if ($this->lhc_model->checkValidity($code) != 1) {
            $this->session->set_flashdata('error', 'Invalid Token');
            redirect('lp/admin');
        }

        if ($this->input->post()) {
            //set validation rule
            //update password
            $this->form_validation->set_rules('password', 'password', 'required|trim|min_length[6]|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');

            //false
            if ($this->form_validation->run() == false) {
                $this->load->view('resetpassword');
            } else {
                //update the password
                $encrypted = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                
                if ($this->lhc_model->updatePassword($encrypted, $code) == 1){
                     $this->session->set_flashdata('success', 'Password updated successfully');
                     redirect('lp/admin');
                }
            }
        }

        $this->template->build("resetpassword", $data);
    }

}
