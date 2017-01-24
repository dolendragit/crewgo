<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Wagerule extends Base_Controller
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
        $this->load->model(array('ion_auth_model', 'Wagerule_model', 'staff/staff_model'));
        $this->load->library('session');
        $this->load->helper('my_helper');

        if (!$this->ion_auth->logged_in()) {
            redirect('lhc/admin');
        }
        $this->users = $this->ion_auth->user()->row();
    }

    public function index()
    {
        $data                                  = array();
        $data['country']                       = $this->Wagerule_model->getCountries();
        $data['skillsForLhcUser']              = $this->staff_model->specifcIndustryForLhcStaff($this->users->id);
        $data['shiftTypes']                    = $this->Wagerule_model->getTypesOfShift();
        $data['calendar']                      = $this->Wagerule_model->getCalendar(date('Y'));
        $data['alreadyExistingSpecialDayRule'] = $this->Wagerule_model->getalreadyExistingSpecialDayRule();
        $data['alreadyExistingOverTimeRule']   = $this->Wagerule_model->getalreadyExistingOverTimeRule();
        $data['alreadyExistingBreakRule']      = $this->Wagerule_model->getalreadyExistingBreakRule();
        $data['alreadyExistingWorkHrRule']     = $this->Wagerule_model->getalreadyExistingWorkHrRule();

        if ($this->input->get()) {
            $data['specialDayRule'] = $this->getSpecialDayRule($this->input->get());
            $data['overtimeRule']   = $this->getOvertimeRule($this->input->get());
            $data['breakRule']      = $this->getBreakRule($this->input->get());
            $data['workHour']       = $this->getWorkHour($this->input->get());
        }

        $this->template->build('list', $data);
    }

    /* public function getStates()
    {

    $statesForSelectedCountry = $this->Wagerule_model->getStates($this->input->post('country'));
    $selectBuildForState      = null;

    foreach ($statesForSelectedCountry as $recievedData) {
    $selectBuildForState .= "<option value=" . $recievedData->id . ">" . $recievedData->name . "</option>";
    }

    echo json_encode($selectBuildForState, JSON_UNESCAPED_SLASHES);
    }*/

    public function getStates()
    {

        $statesForSelectedCountry = $this->Wagerule_model->getStates($this->input->post('country'));
        echo json_encode($statesForSelectedCountry);
    }

    public function getCalendar()
    {
        $calendar = $this->Wagerule_model->getCalendar($this->input->post('selectedYear'));
        echo json_encode($calendar);
    }

    public function getSpecialDayRule($filters)
    {
        $allSpecialRules = $this->Wagerule_model->specialDayRules($filters);
        return $allSpecialRules;
    }

    public function getOvertimeRule($filters)
    {
        $allOvertimeRules = $this->Wagerule_model->getOvertimeRule($filters);
        return $allOvertimeRules;
    }

    public function savespecialdayrule()
    {
        $saveSpecialRules = $this->Wagerule_model->saveSpecialRules($this->input->post());
        $this->session->set_flashdata('success', 'Special day rule updated successfully');
        header('Location: ' . $_SERVER['HTTP_REFERER']);

    }

    public function saveovertimerule()
    {
        $saveOverTimeRule = $this->Wagerule_model->saveOverTimeRule($this->input->post());
        $this->session->set_flashdata('success', 'Overtime rule updated successfully');
        header('Location: ' . $_SERVER['HTTP_REFERER']);

    }

    public function getBreakRule($filters)
    {
        $breakRules = $this->Wagerule_model->getBreakRules($filters);
        return $breakRules;
    }

    public function saveBreakrule()
    {
        $saveBreakRules = $this->Wagerule_model->saveBreakRule($this->input->post());
        $this->session->set_flashdata('success', 'Break rule updated successfully');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function getWorkHour($filters)
    {
        $workHours = $this->Wagerule_model->getWorkHours($filters);
        return $workHours;
    }

    public function saveWorkHour()
    {
        $saveWorkHourRule = $this->Wagerule_model->saveWorkHourRules($this->input->post());
        $this->session->set_flashdata('success', 'Work hour rule updated successfully');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
