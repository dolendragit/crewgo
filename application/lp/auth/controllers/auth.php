<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Auth extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->form_validation->CI = &$this;
        $this->template->set_layout('public/default');
        $this->load->model('lhc_model');
        $this->load->library(array('ion_auth'));
        $this->load->model('email_model');
        $this->load->helper('url');
    }
    public function index()
    {
        $data             = array();
        $data['industry'] = $this->lhc_model->getIndustry();
        $data['lhcdoc']   = $this->lhc_model->getLhcDoc();

        $this->template->build('register', $data);
    }

    public function register()
    {

        $this->load->library('form_validation');
        $this->form_validation->CI = $this;
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

        //upload logic

        /*$config['upload_path'] = IMAGEPATH . 'uploads/';

        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '1000';
        $config['max_width']     = '';
        $config['max_height']    = '';
        $config['overwrite']     = true;
        $config['remove_spaces'] = true;*/

        $this->load->library('upload', $config);

        $this->form_validation->set_rules(
            array(
                array('field' => 'industry[]', 'label' => 'industry', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'businessname', 'label' => 'business name', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'contactphoneno', 'label' => 'contactphoneno', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'logo', 'label' => 'logo', 'rules' => 'trim|xss_clean'),
                array('field' => 'address', 'label' => 'address', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'abn', 'label' => 'abn', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'email', 'label' => 'email', 'rules' => 'required|trim|valid_email|callback_lp_email_check'),
                array('field' => 'expiry[]', 'label' => 'expiry', 'rules' => 'required|trim|min_length[8]|max_length[20]'),
                array('field' => 'allfiles1[]', 'label' => 'document', 'rules' => 'required'),
                //array('field' => 'expirydate', 'label' => 'expirydate', 'rules' => 'required'),
                array('field' => 'contactperson', 'label' => 'contact person', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'bio', 'label' => 'bio', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'password', 'label' => 'password', 'rules' => 'required|trim|xss_clean|min_length[6]'),
                array('field' => 'c_password', 'label' => 'confirm password', 'rules' => 'required|matches[password]'),

            )
        );

        if ($this->form_validation->run() == false) {
            $this->index();
        } else {

            $names  = array();
            $expiry = array();
            $docid  = array();
            $this->load->library('upload');
            $file_no = count($_FILES['allfiles1']['name']);

            for ($i = 0; $i < $file_no; $i++) {
                if (!empty($_FILES['allfiles1']['name'])) {

                    $_FILES['userfile']['name']     = $_FILES['allfiles1']['name'][$i];
                    $_FILES['userfile']['type']     = $_FILES['allfiles1']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $_FILES['allfiles1']['tmp_name'][$i];
                    $_FILES['userfile']['error']    = $_FILES['allfiles1']['error'][$i];
                    $_FILES['userfile']['size']     = $_FILES['allfiles1']['size'][$i];
                }

                $config = array(
                    'file_name'     => date('his') . hashCode(3),
                    'allowed_types' => '*',
                    'max_size'      => 3000,
                    'overwrite'     => false,

                    /* real path to upload folder ALWAYS */
                    'upload_path'   => IMAGEPATH . 'uploads/',
                );

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    echo $this->upload->display_errors();
                } else {
                    $my_docs  = $this->upload->data();
                    $names[]  = $my_docs['file_name'];
                    $docid[]  = $this->input->post('docid')[$i];
                    $expiry[] = $this->input->post('expiry')[$i];
                }

            }

            $cf1['upload_path'] = IMAGEPATH . 'uploads/';

            $cf1['allowed_types'] = 'gif|jpg|png|jpeg';
            $cf1['file_name']     = date('his') . hashCode(4);
            $cf1['max_size']      = '10000';
            $cf1['max_width']     = '';
            $cf1['max_height']    = '';
            $cf1['overwrite']     = true;
            $cf1['remove_spaces'] = true;

            $this->upload->initialize($cf1);
            if (!$this->upload->do_upload('profile')) {
                echo $this->upload->display_errors();
            } else {
                $data  = $this->upload->data();
                $fname = $data['file_name'];
            }


            $encrypted     = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $contactperson = $this->input->post('contactperson');
            $bio           = $this->input->post('bio');
            $hashCode      = hashCode(32);

            $data = array(

                // 'industry' => $this->input->post('industry'),
                //  'businessname' =>$this->input->post('businessname'),
                'phone_number'    => $this->input->post('contactphoneno'),
                'email'           => $this->input->post('email'),
                'full_address'    => $this->input->post('address'),
                'password'        => $encrypted,
                'active'          => '0',
                'name'            => $this->input->post('businessname'),
                'activation_code' => $hashCode,
                'profile_image'   => $fname,
                //'abn' => $this->input->post('abn'),
                //'logo'=> $this->input->post('logo'),
                //'expiry'=> $this->input->post('expiry'),
                //'expirydate'=>$this->input->post('expirydate')

            );

            $data1 = array(
                'description'    => trim($this->input->post('bio')),
                'contact_person' => $this->input->post('contactperson'),
                'abn'            => $this->input->post('abn'),
            );

            $industry = $this->input->post('industry');

            $expiry = $this->input->post('expiry');
            if ($this->lhc_model->register($data, $data1, $names, $expiry, $docid, $industry) == 1) {

                //add document

                $this->session->set_flashdata('success_message', 'User added successfully. Please verify your email address');
                $this->sendEmail($data);
                redirect('lp/auth');
            }

        }
        //end of condition
    }

    public function lp_email_check($email)
    {
        $lpUsers               = $this->ion_auth->users(2)->result();
        $emailToBeCheckAgainst = array();
        foreach ($lpUsers as $val) {
            $emailToBeCheckAgainst[] = $val->email;
        }

        if (in_array($email, $emailToBeCheckAgainst)) {
            $this->form_validation->set_message('lp_email_check', 'Email already taken');
            return false;
        } else {
            return true;
        }
    }

    public function sendEmail($data)
    {

        $template = $this->db->select('*')
            ->from('tbl_email_templates')
            ->where('template_id', '1')
            ->get();

        $body = $template->row();

        $_POST['user_name']       = 'User';
        $encrypt_hash             = $data['activation_code'];
        $_POST['activation_link'] = '<a href="' . base_url('lp/auth/activate') . '/' . $encrypt_hash . '" title="Reset Password" style="color:#f4eb38">' . base_url('lp/auth/activate') . '/' . $encrypt_hash . '</a>';
        $_POST['email']           = $data['email'];
        $this->email_model->send_registration_mail();

    }

    public function activate($code)
    {
        $findUser = $this->db->select('id, email, activation_code, email_verified')
            ->from('tbl_user')
            ->where('activation_code', $code)
            ->get();

        if ($findUser->num_rows() == 1) {
            $detail = $findUser->row();

            $data = array('activation_code' => null, 'email_verified' => '1', 'active' => '1');
            $this->db->where('id', $detail->id);
            $this->db->update('tbl_user', $data);
             $this->ion_auth->logout();
            $this->session->set_flashdata('success', 'Email verified successfully. Please login');
            redirect('lp/admin');

        } else {
            //invalid token
            echo "Token invalid or expired";
        }

    }

    public function setcustomerpassword($code, $lhc)
    {
        $data         = array();
        $data['code'] = $code;

        $this->form_validation->set_rules(
            array(
                array('field' => 'password', 'label' => 'password', 'rules' => 'required|trim|xss_clean|min_length[6]'),
                array('field' => 'confirm_password', 'label' => 'confirm password', 'rules' => 'required|matches[password]'),

            )
        );

        $findUser = $this->db->select('id, email, activation_code, email_verified')
            ->from('tbl_user')
            ->where('activation_code', $code)
            ->get();

        if ($findUser->num_rows() == 0) {
            $this->ion_auth->logout();
            $this->session->set_flashdata('error', 'Token invalid');
            redirect('lp/admin');
        }

        if ($this->input->post()) {
            if ($this->form_validation->run() == true) {
                /*
                 * remove activation code
                 * set the association to active state
                 * update user table for password
                 */

                if ($findUser->num_rows() == 1) {
                    $detail = $findUser->row();

                    $data = array('activation_code' => null, 'email_verified' => '1', 'active' => '1', password => password_hash($this->input->post('password'), PASSWORD_DEFAULT));
                    $this->db->where('id', $detail->id);
                    $this->db->update('tbl_user', $data);

                    $lhcdata = array('lhc_user_id' => $this->input->post('lhcuserid'), 'customer_user_id' => $detail->id, 'requested_date' => date('Y-m-d'), 'action_date' => '', 'status' => 1);
                    $this->db->insert('tbl_lhc_customer_association', $lhcdata);
                }
                $this->session->set_flashdata('success', 'Password added successfully');
                redirect('lp/admin');
            }
        }

        $this->template->set_layout('public/default');
        $this->template->build('admin/setcustomerpassword', $data);
    }

}
