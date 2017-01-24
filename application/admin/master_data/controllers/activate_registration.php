<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of activate_registration
 * After customer clicks on the activation link sent via email
 * @author av
 */
class activate_registration extends Base_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->template->set_layout('lhcadmin/login');
        $this->load->model('Registration_email_model');
        $this->load->library('email');
    }

    /**
     * method to verify and activate LP registration via lhc_manage
     * @param string|type $user_id
     * @param string|type $activation_code
     */
    public function activate($user_id = "", $activation_code = "")
    {

        $identity = (int)$user_id;

        $checkUserId = $this->db->query("select id from tbl_user where id = '$identity' and activation_code = '$activation_code'");
        $data['lp_id'] = $identity;

        //if LP id with the activation code exists load the set_password view
        if ($checkUserId->num_rows() > 0) {

            $this->session->set_flashdata('message', 'Enter your password and activate your LP account');
            $this->template->build('set_lp_password', $data);
        } //if post request from set_lp_password
        else if ($this->input->post()) {

            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required');

            $id = $this->input->post('lp_id');

            if($this->form_validation->run()==false){
                $this->template->build('set_lp_password');
            }
            //form is valid
            if ($this->form_validation->run() == true) {

                $password = $this->input->post('password');

                $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

                $result = $this->Registration_email_model->set_lp_password($id, $encrypted_password);


                //if password is set, remove the activation code
                //and send activation email
                if ($result) {
                    $this->db->update('tbl_user', array('active' => '1', 'activation_code' => '', 'email_verified' => '1'), array('id' => $id));

                    $user_email = $this->db->select('email,name')->from('tbl_user')->where("id = '$id'")->get();
                    $user_email = $user_email->row();

                    $this->load->library('email');

                    $to = $user_email->email;
                    $subject = "Welcome to CREWGO";

                    $user_credentials = array('name' => $user_email->name, 'user_email' => $user_email->email);
                    $message = $this->load->view('email_activation_template', $user_credentials, TRUE);

                    $from = "info@cruva.com";
                    $this->email->to($user_email->email)
                        ->from($from, "CREWGO")
                        ->subject($subject)
                        ->message($message);

                    $this->email->send();
                    $this->session->set_flashdata('message',"LP Account Activated");

                    redirect('lhc/admin');
                }
            }
            else{
                $this->session->set_flashdata('errorMessage',validation_errors());
                $url = base_url('admin/master_data/activate_registration/activate/'.$identity .'/'.$activation_code);
                //echo $url;
                redirect($url);
            }
        }
        else{
            echo "Invalid Link";
            //for testing

//            $this->session->set_flashdata('message', 'Enter your password and activate your LP account');
//            $this->template->build('set_lp_password', $data);
        }
    }
}
