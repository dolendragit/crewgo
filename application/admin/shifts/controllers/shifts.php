<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Shifts extends Base_Controller
{

   

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->form_validation->CI = &$this;
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
        $this->template->set_layout('admin/default');
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('url', 'language'));
        $this->load->model(array('ion_auth_model', 'myshift_model'));
        $this->load->library('session');
        $this->load->helper('my_helper');

    }

    public function index()
    {
        $filters = array();
        $data = array();

        $data['region']   = $this->myshift_model->getAllRegion();
        $data['skill']    = $this->myshift_model->specifcIndustryForLhcStaff();
        $data['allshift'] = $this->myshift_model->getShift();


        if ($this->input->get()){
           $data['allshift'] = $this->myshift_model->getShift($this->input->get());
        }

        $this->template->build('shift', $data);
    }

   
}
