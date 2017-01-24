<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Shift extends Base_Controller
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
        $this->load->model(array('ion_auth_model', 'shift_model', 'staff/staff_model'));
        $this->load->library('session');
        $this->load->helper('my_helper');

        if (!$this->ion_auth->logged_in() || ($this->ion_auth->logged_in() && $this->ion_auth->get_users_groups()->row()->id != "2")) {
            $this->ion_auth->logout();
            redirect('lp/admin');
        } 
        $this->users = $this->ion_auth->user()->row();
    }

    public function index()
    {
        $data = array();
        $this->template->build('list', $data);
    }

    public function shiftsByDate()
    {
        $selectedDate = date('Y-m-d', strtotime($this->input->post('mydate')));
        $shifts       = $this->shift_model->getShiftsbyLhc($this->users->id, $selectedDate);

        $rows = '';
        if (isset($shifts)) {
            foreach ($shifts as $key => $val) {
                $rows .= "<tr>
                            <td>$val->job_number</td>
                            <td>$val->start_date</td>
                            <td>$val->start_time - $val->end_time</td>
                            <td>$val->total_break_time</td>
                            <td>$val->total_hour</td>
                            <td>$val->total_cost</td>
                            <td>$val->name</td>
                            <td>$val->job_full_address</td>
                            <td>N/A</td>
                        </tr>";
            }
        }

        echo json_encode($rows);
    }

    public function getallshifts()
    {
        $filters = array();
        $data = array();

        $data['region']   = $this->shift_model->getAllRegion();
        $data['skill']    = $this->staff_model->specifcIndustryForLhcStaff($this->users->id);
        $data['allshift'] = $this->shift_model->getAllShift($this->users->id);


        if ($this->input->get()){
           $alldata = $this->input->get();
           $data['allshift'] = $this->shift_model->getAllShift($this->users->id, $alldata);
        }


        $this->template->build('allshift', $data);
    }

}
