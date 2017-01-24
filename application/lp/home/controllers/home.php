<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Home extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->form_validation->CI = &$this;
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
        $this->template->set_layout('lhcadmin/default');
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('url', 'language'));
        $this->load->model(array('ion_auth_model', 'auth/lhc_model'));
        $this->load->library('session');

        if (!$this->ion_auth->logged_in() || ($this->ion_auth->logged_in() && $this->ion_auth->get_users_groups()->row()->id != "2")) {
            $this->ion_auth->logout();
            redirect('lp/admin');
        } 
    }

    public function index()
    {
       /* $this->ion_auth->logout();
        $this->session->sess_destroy();  
        echo "<pre>";
        print_r($this->session->all_userdata());*/
        $data = array();
        $this->template->build('home', $data);
    }

    public function changepassword()
    {

        $data  = array();
        $users = $this->ion_auth->user()->row();
        //if the form is submitted
        if ($this->input->post()) {
            /*
             * setting up validation rules
             */
            $this->form_validation->set_rules('oldpassword', 'old password', 'required|trim|xss_clean');
            $this->form_validation->set_rules('password', 'password', 'required|trim|min_length[6]|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');

            if ($this->form_validation->run() == false) {
                $this->load->view('changepassword');
            } else {

                if ($this->ion_auth->hash_password_db($users->id, $this->input->post('oldpassword')) == 1) {
                    //now check whether old password and new password are same
                    if ($this->input->post('password') == $this->input->post('oldpassword')) {
                        $this->session->set_flashdata('warning', 'Your old password and new password must be different');
                        redirect('lp/home/changepassword');
                    } else {
                        //change user password here
                        $enc = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                        if ($this->lhc_model->changepassword($users->id, $enc) == 1) {
                            $this->session->set_flashdata('success', 'Password updated successfully');
                            redirect('lp/home/changepassword');
                        }
                    }
                } else {
                    //entered old password is not valid
                    $this->session->set_flashdata('error', 'The old password you have entered is invalid');
                    redirect('lp/home/changepassword');
                }

            }
        }

        $this->template->build('changepassword', $data);
    }

    public function updateprofile()
    {
        $currentUser = $this->ion_auth->user()->row();

        $data = array();

        $data['userDetail'] = $this->lhc_model->allDetail($currentUser->id);
        $toArray            = $this->lhc_model->allIndustry($currentUser->id);
        $data['industry']   = $this->lhc_model->getIndustry();

        foreach ($toArray as $key => $value) {
            $industries[] = $value['industry_id'];
        }
        $data['assignedIndustry'] = $industries;

        if ($this->input->post()) {
            $this->form_validation->set_rules(
                array(
                    array('field' => 'industry[]', 'label' => 'industry', 'rules' => 'required|trim|xss_clean'),
                    array('field' => 'businessname', 'label' => 'business name', 'rules' => 'required|trim|xss_clean'),
                    array('field' => 'phone_number', 'label' => 'phone number', 'rules' => 'required|trim|xss_clean'),
                    array('field' => 'profile_image', 'label' => 'logo', 'rules' => 'trim|xss_clean'),
                    array('field' => 'address', 'label' => 'address', 'rules' => 'required|trim|xss_clean'),
                    array('field' => 'abn', 'label' => 'abn', 'rules' => 'required|trim|xss_clean'),
                    array('field' => 'email', 'label' => 'email', 'rules' => 'required|trim|valid_email|callback_email_check'),
                    array('field' => 'contactperson', 'label' => 'contact person', 'rules' => 'required|trim|xss_clean'),
                    array('field' => 'description', 'label' => 'bio', 'rules' => 'required|trim|xss_clean'),

                )
            );

            if ($this->form_validation->run() == false) {
                $this->load->view('updateprofile');
            } else {
                $user = array(
                    'name'         => $this->input->post('businessname'),
                    'phone_number' => $this->input->post('phone_number'),
                    'full_address' => $this->input->post('address'),
                    'email'        => $this->input->post('email'),
                );

                $info = array(
                    'contact_person' => $this->input->post('contactperson'),
                    'description'    => trim($this->input->post('description')),
                    'abn'            => $this->input->post('abn'),
                );

                $industry = $this->input->post('industry');

                $updateProfile = $this->lhc_model->updateProfile($currentUser->id, $user, $info, $industry);
                //update the profile

                if ($updateProfile == 1) {
                    $this->session->set_flashdata('success', 'Profile Updated successfully');
                    redirect('lp/home/updateprofile');
                }
            }
        }

        $this->template->build('updateprofile', $data);
    }

    public function email_check($email)
    {
        $currentUser = $this->ion_auth->user()->row();
        if ($currentUser->email == $email) {
            return true;
        } else {
            $checkEmail = $this->lhc_model->checkExistence($email);

            if ($checkEmail) {
                return true;
            } else {
                $this->form_validation->set_message('email_check', 'Email already taken');
                return false;
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();  
        $this->ion_auth->logout();
        $this->session->set_flashdata('message', 'You have been logged out');
        redirect('lp/admin', 'refresh');

    }


    public function loguserout()
    {
       // $this->session->sess_destroy();  
        $this->ion_auth->logout();
        $this->session->set_flashdata('message', 'You have been logged out');
        redirect('lp/admin', 'refresh');
    }

}
