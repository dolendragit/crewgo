<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Induction extends Base_Controller
{

    private $users;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->form_validation->CI = &$this;
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
        $this->template->set_layout('lhcadmin/default');
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('url', 'language'));
        $this->load->model(array('ion_auth_model', 'induction_model'));
        $this->load->library('session');

        if (!$this->ion_auth->logged_in() || ($this->ion_auth->logged_in() && $this->ion_auth->get_users_groups()->row()->id != "2")) {
            $this->ion_auth->logout();
            redirect('lp/admin');
        } 
        $this->users = $this->ion_auth->user()->row();
    }

    public function index()
    {
        $data              = array();
        $data['induction'] = $this->induction_model->getAll($this->users->id);
        $this->template->build('list', $data);
    }

    public function add()
    {
        $data = array();

        if ($this->input->post()) {
            //form validation
            $this->form_validation->set_rules('name', 'inductee name', 'required|xss_clean');
            $this->form_validation->set_rules('url', 'url', 'required|xss_clean');
            $this->form_validation->set_rules('expiry', 'expiry', 'required|xss_clean');
            $this->form_validation->set_rules('document', 'document', 'required|xss_clean');
            $this->form_validation->set_rules('provider', 'provider', 'required|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('add');
            } else {

                //validation success
                //1. first handle upload

                $induction = array(
                    'name'          => $this->input->post('name'),
                    'document_url'  => $this->input->post('url'),
                    'expiry_date'   => $this->input->post('expiry'),
                    'provider_name' => $this->input->post('provider'),
                    'document_id'   => hashCode(11),
                    'entered_by'    => $this->users->id,

                );

                $config['upload_path']   = IMAGEPATH . 'uploads/induction/';
                $config['file_name']     = date('his') . hashCode(4);
                $config['allowed_types'] = 'pdf|doc|docx';
                $config['max_size']      = 1000;
                $config['max_width']     = 1024;
                $config['max_height']    = 768;
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('document')) {
                    $error = array('error' => $this->upload->display_errors());
                      $this->session->set_flashdata('error', 'Invalid file type. upload error');
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                        exit();
                } else {
                    $data                 = $this->upload->data();
                    $induction['remarks'] = $data['file_name'];
                    if ($this->induction_model->saveInduction($induction)) {
                        $this->session->set_flashdata('success', 'New Induction added successfully');
                    } else {
                        $this->session->set_flashdata('error', 'New Induction couldnot be added');
                    }
                    redirect('lp/induction/add');

                }
            }

        }

        $this->template->build('add', $data);
    }

    public function edit($id)
    {

        $data              = array();
        $data['induction'] = $this->induction_model->findById($id);

        $this->form_validation->set_rules('name', 'inductee name', 'required|xss_clean');
        $this->form_validation->set_rules('url', 'url', 'required|xss_clean');
        $this->form_validation->set_rules('expiry', 'expiry', 'required|xss_clean');
        $this->form_validation->set_rules('document', 'document', 'required|xss_clean');
        $this->form_validation->set_rules('provider', 'provider', 'required|xss_clean');

        if ($this->input->post()) {

            if ($this->form_validation->run() == true) {
                //validation success
                $induction = array(
                    'name'          => $this->input->post('name'),
                    'document_url'  => $this->input->post('url'),
                    'expiry_date'   => $this->input->post('expiry'),
                    'provider_name' => $this->input->post('provider'),

                );

                if ($this->input->post('document') != "") {
                    //handle upload
                    $config['upload_path']   = IMAGEPATH . 'uploads/induction/';
                    $config['file_name']     = date('his') . hashCode(4);
                    $config['allowed_types'] = 'pdf|doc|docx';
                    $config['max_size']      = 1000;
                    $config['max_width']     = 1024;
                    $config['max_height']    = 768;
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('document')) {
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_flashdata('error', 'Invalid file type. upload error');
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                        exit();
                    } else {
                        $data = $this->upload->data();
                        //delete previous file
                        $previous_file = base_url('assets/uploads/induction/' . $this->induction_model->findById($id)->remarks);
                        unlink($previous_file);
                        $induction['remarks'] = $data['file_name'];
                    }
                }

                //call model here
                if ($this->induction_model->updateInduction($induction, $this->input->post('inductionid')) == true) {
                    $this->session->set_flashdata('success', 'Induction updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Induction couldnot be updated');
                }
                redirect('lp/induction');
            } 

        }
        $this->template->build('edit', $data);
    }
}
