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

        $this->template->build('register', $data);
    }

    public function register()
    {

        $this->load->library('form_validation');
        $this->form_validation->CI = $this;
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

        //upload logic

        $config['upload_path'] = IMAGEPATH . 'uploads/';

        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '1000';
        $config['max_width']     = '';
        $config['max_height']    = '';
        $config['overwrite']     = true;
        $config['remove_spaces'] = true;

        $this->load->library('upload', $config);

        $this->form_validation->set_rules(
            array(
                array('field' => 'industry[]', 'label' => 'industry', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'businessname', 'label' => 'business name', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'contactphoneno', 'label' => 'contactphoneno', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'logo', 'label' => 'logo', 'rules' => 'trim|xss_clean'),
                array('field' => 'address', 'label' => 'address', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'abn', 'label' => 'abn', 'rules' => 'required|trim|xss_clean'),
                array('field' => 'email', 'label' => 'email', 'rules' => 'required|trim|valid_email|unique[tbl_user.email]|xss_clean'),
                array('field' => 'expiry[]', 'label' => 'expiry', 'rules' => 'required|trim|min_length[8]|max_length[20]'),
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

            if (!empty($_FILES)) {

                $names = array();

                foreach ($_FILES as $fieldname => $fileObject) {
                    // echo $fieldname;
                    $config['file_name'] = hashCode(5) . date('his');
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload($fieldname)) {
                        $this->session->set_flashdata('errorMessage', 'Upload Error');
                        $this->index();
                    } else {
                        $f1 = $this->upload->data();
                        array_push($names, $f1['file_name']);
                        //1. filename, 2. user_id
                        // $this->lhc_model->insert_images();
                        // $data = array('upload_data' => $this->upload->data());
                        // $this->session->set_flashdata('successMessage', 'Upload Sucessfull');
                    }
                }

                $profile_image = $names['0'];
                array_shift($names);
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
                'active'          => '1',
                'name'            => $this->input->post('businessname'),
                'activation_code' => $hashCode,
                'profile_image'   => $profile_image,
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
            if ($this->lhc_model->register($data, $data1, $names, $expiry, $industry) == 1) {

                //add document

                $this->session->set_flashdata('success_message', 'User added successfully. Please verify your email address');
                $this->sendEmail($data);
                redirect('lhc/auth');
            }

        }
        //end of condition
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
        $_POST['activation_link'] = '<a href="' . base_url('lhc/auth/activate') . '/' . $encrypt_hash . '" title="Reset Password" style="color:#f4eb38">' . base_url('lhc/auth/activate') . '/' . $encrypt_hash . '</a>';
        $_POST['email']           = $data['email'];
        $this->email_model->send_registration_mail();
        //print_r($body);
    }

    public function activate($code)
    {
        $findUser = $this->db->select('id, email, activation_code, email_verified')
            ->from('tbl_user')
            ->where('activation_code', $code)
            ->get();

        if ($findUser->num_rows() == 1) {
            $detail = $findUser->row();

            $data = array('activation_code' => null, 'email_verified' => '1');
            $this->db->where('id', $detail->id);
            $this->db->update('tbl_user', $data);
            $this->session->set_flashdata('success_message', 'Email verified successfully');
            redirect('lhc/auth');

        } else {
            //invalid token
            echo "Token invalid or expired";
        }

    }
}
