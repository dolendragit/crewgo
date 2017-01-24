<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Orderstaff extends Base_Controller
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
        $this->load->model(array('ion_auth_model', 'orderstaff_model', 'staff/staff_model'));
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

        $data             = array();
        $data['staff']    = $this->orderstaff_model->getAllUserDetail($this->input->get('sk_id') ? $this->input->get('sk_id') : '', $this->input->get('l_id') ? $this->input->get('l_id') : '', $this->users->id);
        $data['shifts']   = $this->orderstaff_model->getShiftsbySkillAndLevel($this->input->get('sk_id') ? $this->input->get('sk_id') : '', $this->input->get('l_id') ? $this->input->get('l_id') : '', $this->users->id);
        $data['industry'] = $this->staff_model->specifcIndustryForLhcStaff($this->users->id);

        if ($this->input->post()) {
            /*
             * insert all jobs for each listed staff. Suppose if there are 4 staffs in the list and 3 shifts are selected then 4*3 = 12 records should be inserted
             * in proper order.
             *
             */

            if (count(array_filter($this->input->post('jobs'))) == "0") {
                $this->session->set_flashdata('error', 'Please select job');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }

            $listofAvailableStaffs = explode(',', $this->input->post('userid'));
            $listOfSelectedShifts  = array_filter(array_unique($this->input->post('jobs')));

            foreach ($listofAvailableStaffs as $k => $v) {
                //users opeartion
                foreach ($listOfSelectedShifts as $k1 => $v1) {
                    $checkForPreviousAssignedJobs = $this->orderstaff_model->checkPreviousJobs($v, $v1);
                    $data                         = array('job_detail_id' => $v1, 'staff_user_id' => $v, 'lhc_user_id' => $this->users->id, 'order_by' => $k + 1, 'alerted_date' => date('Y-m-d'), 'status' => '0');
                    $this->orderstaff_model->assignJobsToStaff($data);
                }

            }
            //redirect
            $this->session->set_flashdata('success', 'Jobs Assigned successfully');
            //redirect('lhc/orderstaff?sk_id='.$this->input->get('sk_id').'&l_id='.$this->input->get('l_id'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }

        $this->template->build('list', $data);
    }

    public function staffdata()
    {

        $userDatas = $this->orderstaff_model->jobsWithAssignedStaff($this->input->post('myvalue'));

        $cols    = "";
        $userIds = array();

        if (isset($userDatas['res'])) {
            foreach ($userDatas['res'] as $key => $val) {
                $userIds[] = $val->id;
                $cols .= "<tr myattr=" . "'" . $val->id . "'" . " style=''><td style='text-align:center;'><i class='fa fa-list 1x'></i></td><td>" . $val->name . "</td><td>" . $val->phone_number . "</td><td>" . $val->email . "</td><td>" . $val->created_on . "</td></tr>";
            }
        } else {

            foreach ($userDatas as $key => $val) {
                $userIds[] = $val->id;
                $cols .= "<tr myattr=" . "'" . $val->id . "'" . " style='background: blanchedalmond;'><td style='text-align:center;'><i class='fa fa-list 1x'></i></td><td>" . $val->name . "</td><td>" . $val->phone_number . "</td><td>" . $val->email . "</td><td>" . $val->created_on . "</td></tr>";
            }
        }

        echo json_encode(['cols' => $cols, 'users' => $userIds]);

    }

    /*
     * ajax function
     *
     */

    public function industrySkill()
    {
        $value    = $this->input->post('myvalue');
        $subSkill = $this->staff_model->subSkills($value);
        echo json_encode($subSkill);
    }

}
