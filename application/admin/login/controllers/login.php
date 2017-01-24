<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends MX_Controller
{

    public function __construct()
    {
		  
        parent::__construct();
        $this->data = array();
        $this->load->database();
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('url', 'language'));
		$this->ion_auth_model->hash_password('password', FALSE);

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->template->set_layout('admin/adminlogin');

        //if the admin is already logged in do not show login page
        //redirect to admin home page
//        if($this->ion_auth->logged_in()){
//            redirect(base_url('admin'));
//        }
    }

    public function index()
    {

        $this->data['title'] = $this->lang->line('login_heading');

        //validate form input
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
        $this->form_validation->set_rules('user_group', str_replace(':', '', $this->lang->line('login_user_group_label')), 'required');
        if ($this->form_validation->run() == true) {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember, $this->input->post('user_group'))) {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect(base_url('admin'), 'refresh');
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect(base_url('admin/login'), 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = array('name' => 'identity',
                'id'                                   => 'identity',
                'type'                                 => 'text',
                'placeholder'                          => "Email ID",
                'class'                                => 'form-control',
                'value'                                => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id'                                   => 'password',
                'placeholder'                          => "Password",
                'class'                                => 'form-control',
                'type'                                 => 'password',
            );
            //admin or lhc can login to administrator section
            $this->data['user_groups'] = array('1' => 'Administrator',
                '2'                                    => 'LHC',

            );

            $this->template->title("Login | ");
            $this->template->build("adminlogin", $this->data);
        }

    }

    // log the user out
    public function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect(base_url('admin/login'), 'refresh');
    }

    /*
     * Forgot password
     */

    public function forgotPassword()
    {
        $data = array();
        //check email in user

        $this->db->select('tbl_user.email,tbl_user.name,tbl_user.active,tbl_user.email_verified, tbl_user.id, tbl_user.active, last_login');
        $this->db->from('tbl_user');
        $this->db->join('tbl_user_group', 'tbl_user.id = tbl_user_group.user_id', 'inner');
        $this->db->where('tbl_user.email', $this->input->post('email'));
        //$this->db->where('tbl_user.active', '1');
        //$this->db->where('tbl_user.email_verified', '1');
        $this->db->where('tbl_user_group.group_id', $this->input->post('user_group'));
        $user_details = $this->db->get()->row();

        //if found then create reset password link
        if ($user_details) {

            //check account is active or not
            $success = true;
            if ($user_details->active == 0) {
                $message = 'Your account is not active.Please activate your account';
                $success = false;

            }
            if ($user_details->email_verified == 0) {
                $message = 'Your account is not verified.Please verify your account';
                $success = false;

            }
            $this->load->model('email_model');
            if ($success) {

                $encrypt_hash                 = hashCode(32);
                $link_url                     = '<a href="' . base_url('admin/login/reset_password') . '/' . $encrypt_hash . '" title="Reset Password" style="color:#f4eb38">' . base_url('admin/login/reset_password') . '/' . $encrypt_hash . '</a>';
                $_POST['user_name']           = $user_details->name;
                $_POST['reset_password_link'] = $link_url;
                $_POST['email']               = $user_details->email;
                if ($this->email_model->send_all_email('admin_reset_password')) {
                    $message = 'Reset password link has been sent to your mail. Please follow the link to set a new password.';
                    $success = true;
                    $this->session->set_flashdata('successMessage', $message);
                    $data = array(
                        'forgotten_password_code' => $encrypt_hash,
                    );
                    $this->db->where('email', $user_details->email);
                    $this->db->update('tbl_user', $data);
                } else {
                    $message = 'Email could not delivered.';
                    $success = false;

                    $this->session->set_flashdata('errorMessage', $message);

                }
            }

        } else {
            $message = 'Your email is not found in our system. Contact administrator';
            $success = false;
        }
        $data['success'] = $success;
        $data['message'] = $message;
        echo json_encode($data);exit;
    }
    /*
     *Reset Password
     */
    public function reset_password($password_string = "")
    {

        if ($_POST) {

            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('new_password', 'Password', 'required');
            if ($this->input->post('reset_password_string') != "") {
                $get_user_details = $this->db->select('*')->from('tbl_user')
                    ->where('forgotten_password_code', $this->input->post('reset_password_string'))
                    ->get()->row();
            } else {
                $get_user_details = false;
            }

            if ($this->form_validation->run() == true && $get_user_details) {

                $hash_password = password_hash($this->input->post('new_password'), PASSWORD_DEFAULT);
                $data          = array(
                    'forgotten_password_code' => '',
                    'password'                => $hash_password,
                );
                $this->db->where('id', $get_user_details->id);
                $this->db->update('tbl_user', $data);

                $this->session->set_flashdata('message', 'Your password has been changed successfully!! ');
                redirect(base_url('admin/login'));
            } else {

                $this->template->build('reset_password', $data);
            }

        } else {
            //check reset password code
            $get_user_details = $this->db->select('*')->from('tbl_user')
                ->where('forgotten_password_code', $password_string)
                ->get()->row();

            $data['is_avalid'] = "";
            if ($get_user_details && $password_string != "") {

                $data['is_avalid']    = "Yes";
                $data['user_details'] = $get_user_details;

            } else {
                $data['is_avalid'] == "No";

            }

            $this->template->build('reset_password', $data);
        }

    }
 
}
