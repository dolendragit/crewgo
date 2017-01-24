<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends Customer_Controller
{
    public function __construct()
    {
		  
        parent::__construct();
        $this->load->database();
        $this->load->library(array('email'));
        $this->load->helper(array('language', 'ws_helper'));
        $this->load->model('user_model');

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

        var_dump($this->session->userdata('current_user'), $this->ion_auth->user()->row());
    }

    /**
    *    display customer profile
    */
    public function index() {
        if( $this->input->post() ){

        } 

        $this->template->title(' Customer Website  ');
        $this->template->build('profile',$this->data );
    }

    /**
    *    change password
    */
    public function change_password() {
        if ($this->input->post()) {
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'old', 'label' => 'Current Password', 'rules' => 'trim|required'),
                        array('field' => 'new', 'label' => 'New Password', 'rules' => 'trim|required'),
                    )
            );
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('message', validation_errors() );
                redirect(customer_interface_url('user/change_password'),'refresh');
            } else {
                $this->current_user = $this->session->userdata('current_user');
                if( empty($this->current_user->email) ){
                    $this->session->set_flashdata('message', 'Login Is Required !!!');
                    redirect('customer/login','refresh');
                }
                $old = $this->input->post('old');
                $new = $this->input->post('new');
                $result = $this->ion_auth->change_password($this->current_user->email, $old, $new);
            }
            

        } else {
            $this->template->title(' Customer Website  ');
            $this->template->build('change_password',$this->data );
        }
    }

    // log the user out by clearing session
    public function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('customer/login','refresh');
    }

}//end of class
