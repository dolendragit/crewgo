<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Master_data extends Admin_Controller
{
    public $days = array();

    public function __construct()
    {
        parent::__construct();
        $this->template->set_layout('admin/default');
        $this->load->library('form_validation');
        $this->load->model('model_master_data');
        $this->load->model('model_master_data_view');
        $this->load->model('Registration_email_model');
        $this->load->helper('file');
        $this->days = array(
            'weekday' => 'Weekday(Mon-Fri)',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
            'public_holiday' => 'Public Holiday'
        );
    }


    /**
     * Industry
     * @param string|type $page_action
     * @param string|type $id
     */

    public function industry($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = "admin/master_data/industry";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Industry";
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Title', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->industry_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/industry'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $industry_data = $this->model_master_data_view->getIndustry($id);

            $data['industry'] = $industry_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_industry','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/industry'));

        } else {
            $industry_data = $this->model_master_data_view->getIndustry();
            $data['industries'] = $industry_data;

        }

        $this->template->build('industry', $data);
    }

    /**
     *
     * @param string|type $page_action
     * @param string|type $id
     */

    public function skill($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = "admin/master_data/skill";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Skills";
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Title', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->skill_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/skill'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {
                $this->session->set_flashdata('errorMessage', validation_errors());
            }

        } else if ($page_action == "edit" && $id != "") {
            $skill_data = $this->model_master_data_view->getSkill($id);
            
             
            $data['skill'] = $skill_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_skill','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/skill'));

        } else {
            $skill_data = $this->model_master_data_view->getSkill();
            
            
            $data['skills'] = $skill_data;

        }

        $this->template->build('skill', $data);
    }


    /**
     * Insustry-Skill
     * @param string|type $page_action
     * @param string|type $id
     */

    public function industrySkill($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = "admin/master_data/industry_skill";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Industry/Skill Mapping";
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Title', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->industry_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/industry_skill'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $industry_data = $this->model_master_data_view->getIndustry($id);
            $data['industry'] = $industry_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_industry','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/industry_skill'));

        } else {
            $industry_data = $this->model_master_data_view->getIndustry();
            $data['industries'] = $industry_data;

        }

        $this->template->build('industry_skill', $data);
    }

    /**
     *
     * @param string|type $page_action
     * @param string|type $id
     */

    public function subskill($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = "admin/master_data/subskill";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Subskills";
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Title', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->subskill_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/subskill'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $subskill_data = $this->model_master_data_view->getSubskill($id);
            $data['subskill'] = $subskill_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_level','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/subskill'));

        } else {
            $subskill_data = $this->model_master_data_view->getSubskill();

            $data['subskills'] = $subskill_data;

        }

        $this->template->build('subskill', $data);
    }


    /**
     * Add/Edit/Remove PPE
     * @param string|type $page_action
     * @param string|type $id
     */

    public function ppe($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = "admin/master_data/ppe";
        $data['page_action'] = $page_action;
        $data['page_title'] = "PPE";
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Title', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->ppe_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/ppe'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $ppe_data = $this->model_master_data_view->getPPE($id);
            $data['ppe'] = $ppe_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_ppe','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/ppe'));

        } else {
            $ppe_data = $this->model_master_data_view->getPPE();

            $data['ppes'] = $ppe_data;

        }

        $this->template->build('ppe', $data);
    }

    /**
     * Add/Edit/Remove Priority
     * @param string|type $page_action
     * @param string|type $id
     */

    public function priority($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = "admin/master_data/priority";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Priority";
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Title', 'required');
            $this->form_validation->set_rules('score', 'Score', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->priority_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/priority'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $priority_data = $this->model_master_data_view->getPriority($id);
            $data['priority'] = $priority_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_priority','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/priority'));

        } else {
            $priority_data = $this->model_master_data_view->getPriority();

            $data['priorities'] = $priority_data;

        }

        $this->template->build('priority', $data);
    }

    /**
     * Add/Edit/Remove Qualification
     * @param string|type $page_action
     * @param string|type $id
     */

    public function qualification($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = "admin/master_data/qualification";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Qualification";
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Title', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->qualification_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/qualification'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $qualification_data = $this->model_master_data_view->getQualification($id);
            $data['qualification'] = $qualification_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_qualification','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/qualification'));

        } else {
            $qualification_data = $this->model_master_data_view->getQualification();

            $data['qualifications'] = $qualification_data;

        }

        $this->template->build('qualification', $data);
    }

    /**
     * Add/Edit/Remove Activity Level
     * @param string|type $page_action
     * @param string|type $id
     */

    public function activity_level($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = "admin/master_data/activity_level";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Activity Level";
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Activity', 'required');
            $this->form_validation->set_rules('p_staff_result_range_from', 'From', 'required');
            $this->form_validation->set_rules('shift_request_number', 'Number od request', 'required');
            $this->form_validation->set_rules('shift_time_interval', 'Time interval', 'required');
            $this->form_validation->set_rules('shift_notice_time', 'Notice time', 'required');
            $this->form_validation->set_rules('peak_price_factor', 'Peak price', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->activity_level_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/activity_level'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $activty_data = $this->model_master_data_view->getActivitylevel($id);
            $data['activity'] = $activty_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_activity_level','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/activity_level'));

        } else {
            $activities_data = $this->model_master_data_view->getActivitylevel();

            $data['activities'] = $activities_data;

        }

        $this->template->build('activity_level', $data);
    }

    /**
     * Add/Edit/Remove Sociable Hours
     * @param string|type $page_action
     * @param string|type $id
     */

    public function sociable_hours($page_action = "", $id = "")
    {
         
        $data = array();
        $data['page_url'] = "admin/master_data/sociable_hours";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Sociable Hours";
        //array for days
        $days = $this->days;
        $countries = $this->model_master_data_view->getCountry();
        $regions = $this->model_master_data_view->getRegion();

        $data['days'] = $days;
        $data['countries'] = $countries;
        $data['regions'] = $regions;
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('days', 'Days', 'required');
            $this->form_validation->set_rules('sociable_from_hour', 'Sociable time "From"', 'required');
            $this->form_validation->set_rules('sociable_to_hour', 'Sociable time "To"', 'required');
            $this->form_validation->set_rules('sociable_factor', 'Socaible factor', 'required');
            $this->form_validation->set_rules('non_sociable_hour', 'Non-sociable hour', 'required');
            $this->form_validation->set_rules('ns_peak', 'NS preak', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->sociable_hour_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/sociable_hours'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $sociable_hour_data = $this->model_master_data_view->getSociableHour($id);
            $data['sociable_hour'] = $sociable_hour_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_social_hour','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/sociable_hours'));

        } else {
            $sociable_hour_data = $this->model_master_data_view->getSociableHour();

            $data['sociable_hours'] = $sociable_hour_data;

        }


        $this->template->build('sociable_hours', $data);
    }

    /**
     * used to get non-socaible hour and ns peak
     */
    public function getNonsociableHourNSpeak()
    {
        
         
        $sociable_from_hour = $this->model_master_data->extractAMPM($_POST['sociable_from_hour']);
        $sociable_to_hour = $this->model_master_data->extractAMPM($_POST['sociable_to_hour']);
        
       
        $data['success'] = 0;
        $data['message'] = '';
        //from date should be less than to date
        if($sociable_from_hour > $sociable_to_hour){
            $data['success'] = 2;
            $data['message'] = 'From time must be less than to time';
        }else{
        if ($sociable_from_hour != "" && $sociable_to_hour != "") {

            $query = $this->db->query("CALL sp_getNonSociableNSpeak('{$sociable_from_hour}','{$sociable_to_hour}')");
            
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                if ($result->STATUS == 1) {
                    $ns_peak = $result->ns_peak;
                     
                    $dt = new DateTime($ns_peak);

                    $date = $dt->format('Y-m-d');
                    $time = $dt->format('H:i');

                }
                $non_sociable_hour = $result->non_sociable_hour;
                //set delete message
                // $this->model_master_data->get_master_data_delete_status($result);
            }
            $data['ns_peak'] = $time;
            $data['non_sociable_hour'] = $non_sociable_hour;
            $data['success'] = 1;
        }
        }

        echo json_encode($data);
        exit;

    }

    /**
     * Use to get list of social hours   for selected country and state
     */

    public function getSociableNSpeakView()
    {
        $region_id = $this->input->post('region_id');
        $selected_sociable_id = $this->input->post('sciable_id');

        //get data 
        if ($region_id == "")
            $data['sociable_hours'] = FALSE;
        else
            $data['sociable_hours'] = $this->model_master_data_view->getSociableHour($selected_sociable_id, $region_id);

        $data['days'] = $this->days;
        $this->load->view('sociable_hours_list', $data);

    }

    public function checkUniqeSociableData()
    {
        $data['success'] = FALSE;

        $sociable_id = $this->input->post('sociable_id');
        $region_id = $this->input->post('region_id');
        $days = $this->input->post('days');
        $sociable_data = $this->model_master_data_view->getSociableHour("", $region_id, $days, $sociable_id);

        if ($sociable_data)
            $data['success'] = TRUE;
        else
            $data['success'] = FALSE;
        echo json_encode($data);
        exit;
    }


    /**
     * Add/Edit/Remove Cancellation Hours
     * @param string|type $page_action
     * @param string|type $id
     */

    public function cancellation($page_action = "", $id = "")
    {
        $this->load->helper('text');
        $data = array();
        $data['page_url'] = "admin/master_data/cancellation";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Cancellation";
        //array for days
        $days = $this->days;
        $countries = $this->model_master_data_view->getCountry();
        $regions = $this->model_master_data_view->getRegion();

        $data['days'] = $days;
        $data['countries'] = $countries;
        $data['regions'] = $regions;
        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('country_id', 'Country', 'required');
            $this->form_validation->set_rules('region_id', 'Region', 'required');
            $this->form_validation->set_rules('cancel_prior', 'Cancel prior', 'required');
            $this->form_validation->set_rules('cancel_fee', 'Cancellation fee', 'required');

            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->cancellation_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/cancellation'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "edit" && $id != "") {
            $cancellation_data = $this->model_master_data_view->getCancellation($id);
            $data['cancellation'] = $cancellation_data;

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_job_cancellation','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/cancellation'));

        } else {
            $cancellation_data = $this->model_master_data_view->getCancellation();

            $data['cancellations'] = $cancellation_data;

        }

        $this->template->build('cancellation', $data);
    }

    /**
     * change status
     * @param $master_data
     * @param $id
     */
    public function changeMasterDataStatus($master_data, $id)
    {
        $master_data = $this->input->post('master_data');
        $tbl_id = $this->input->post('tbl_id');
        $message = array();
        if ($master_data == 'industry') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_con_industry','{$tbl_id}')");

            $this->db->freeDBResource();
        } else if ($master_data == 'skill') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_con_skill','{$tbl_id}')");

            $this->db->freeDBResource();
        } else if ($master_data == 'ppe') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_con_ppe','{$tbl_id}')");

            $this->db->freeDBResource();
        } else if ($master_data == 'priority') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_con_priority','{$tbl_id}')");

            $this->db->freeDBResource();
        } else if ($master_data == 'qualification') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_con_qualification','{$tbl_id}')");

            $this->db->freeDBResource();
        } else if ($master_data == 'subskill') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_con_sub_skill','{$tbl_id}')");

            $this->db->freeDBResource();
        } else if ($master_data == 'lhc_doc') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_con_lhc_doc','{$tbl_id}')");

            $this->db->freeDBResource();
        } else if ($master_data == 'lhc_manage') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_user','{$tbl_id}')");

            $this->db->freeDBResource();
        }
        else if ($master_data == 'cancellation') {

            $query = $this->db->query("CALL sp_statusMasterData('tbl_con_job_cancellation','{$tbl_id}')");

            $this->db->freeDBResource();
        }

        $result = $query->row();

        if ($result) {

            if ($result->status == 1) {
                //get current status
                $message['status'] = 1;
                $message['msg'] = getStatusData($tbl_id, $result->current_status, $master_data);
            } else {
                $message['status'] = $result->status;
                $message['msg'] = $this->model_master_data->get_master_data_change_status($result);
            }
        } else {
            $message['status'] = 4;//unknown
            $message['msg'] = "Unknown error";
        }
        echo json_encode($message);
    }


    function changeTableOrder()
    {
        $table_master = $this->input->post('master_data');
        $data_table_id_index = json_decode($this->input->post('tbl_id'));
        if (count($data_table_id_index) > 0) {
            foreach ($data_table_id_index as $id_index) {
                $data = array();
                if ($table_master == 'priority') {

                    $data = array('order' => $id_index->index);
                    $this->db->where('id', $id_index->data_id);
                    $this->db->update('tbl_con_priority', $data);

                }
            }
        }
        echo 1;
    }

    /**
     * Add Edit and Delete LHC Doc
     * @param string|type $page_action
     * @param string|type $id
     */
    public function lhc_doc($page_action = "", $id = "")
    {

        $data = array();
        $data['page_url'] = "admin/master_data/lhc_doc";
        $data['page_title'] = "Document required By LP for Industry";
        $data['page_action'] = $page_action;

        //get the select options to be displayed in the add lhc form
        $country = $this->model_master_data_view->getCountry();
        $data['select_country'] = $country;
        $region = $this->model_master_data_view->getRegion();
        $data['select_region'] = $region;
        $industry = $this->model_master_data_view->getIndustry();
        $data['select_industry'] = $industry;

        if ($this->input->post()) {
            //do the required field validation validation 
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'Name', 'required');
            //$this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->lhc_doc_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/lhc_doc'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } elseif ($page_action == "edit" && $id != "") {
            //get data from table to be edited
            //$lhc_edit =   
            //get the doc details to be edited
            $data['lhc_edit'] = $this->model_master_data_view->getLhcDoc($id);

        } elseif ($page_action == "remove" && $id != "") {
            //call stored procedure to delete data from table
            $query = $this->db->query("CALL sp_removeMasterData('tbl_con_lhc_doc','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Database error.");
            }
            redirect(base_url('admin/master_data/lhc_doc'));
        } else {
            //get data to be displayed in the lhc_doc list
            $lhcDocData = $this->model_master_data_view->getLhcDoc();
            $data['lhc'] = $lhcDocData;
        }
        $this->template->build('lhc_doc', $data);
    }

    /**
     * change user Password
     */
    public function changePassword()
    {
        $data = array();
        $data['page_url'] = "admin/master_data/changePassword";
        $data['page_title'] = "Change Password";
        //$data['page_action']=$page_action;


        if ($this->input->post()) {
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('old_password', 'Old Password', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required');
            if ($this->form_validation->run() == true) {
                $user_details = $this->ion_auth->user()->row();
                $user_email = $user_details->email;

                $new_password = $this->input->post('new_password');
                $old_received_password = $this->input->post('old_password');

                $result = $this->ion_auth->change_password($user_email, $old_received_password, $new_password);
                if ($result) {
                    $this->session->set_flashdata('successMessage', "Password Change successful !");
                } else {
                    $this->session->set_flashdata('errorMessage', "Error!");
                }
                redirect(base_url('admin/master_data/changePassword'));
            }

        } else {
            $this->template->build('change_password', $data);
        }
    }

    /**
     * Holiday Calendar
     * @param string|type $page_action
     * @param string|type $id
     */
    public function holiday_calendar($page_action = "", $id = "")
    {


        $data = array();
        $data['page_url'] = "admin/master_data/holiday_calendar";
        $data['page_title'] = "Holiday Calendar";
        $data['page_action'] = $page_action;


        //get the country and states select options to be displayed in the holiday listing
        $country = $this->model_master_data_view->getCountry();
        $data['select_country'] = $country;
        $region = $this->model_master_data_view->getRegion();
        $data['select_region'] = $region;


        //insert holidays
        if ($this->input->post()) {
            
            //do the required field validation validation 
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('description', 'description', 'required');


            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';
 

                if ($this->model_master_data->holiday_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/holiday_calendar'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }
                print_r($_POST); exit;

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } //edit holiday
        elseif ($page_action == "edit" && $id != "") {
            //get data from table to be edited
            $calendar_edit = $this->model_master_data_view->getHolidayData($id);
            //get the holiday to be edited
            $data['edit_day'] = $calendar_edit;

        } //delete holiday
        elseif ($page_action == "remove" && $id != "") {

//                //call stored procedure to delete data from table
            $query = $this->db->query("CALL sp_removeMasterData('tbl_wr_calendar','{$id}')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/holiday_calendar'));
        } else {
            //get data to be displayed in the holiday listing
            $holiday_data = $this->model_master_data_view->getHolidayData();
            $data['holiday_data'] = $holiday_data;

        }
        $this->template->build('holiday_calendar', $data);

    }

    /**
     * add edit delete shift types
     * @param string|type $page_action
     * @param string|type $id
     */
    public function shift_type($page_action = "", $id = "")
    {
        $data[] = array();
        $data['page_url'] = 'admin/master_data/shift_type';
        $data['page_title'] = "Work Shifts";
        $data['page_action'] = $page_action;


        //add shift info
        if ($this->input->post()) {
            //do the required field validation validation 
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('name', 'name', 'required');

            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';


                if ($this->model_master_data->shift_type_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/shift_type'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }
        } //edit shift info

        elseif ($page_action == "edit" && $id != "") {
            //get data from table to be edited
            $shift_edit = $this->model_master_data_view->getShiftData($id);
            //get the shift details to be edited
            $data['shift_edit'] = $shift_edit;
        } //delete shifts
        elseif ($page_action == "remove" && $id != "") {

            $query = $this->db->query("CALL sp_removeMasterData('tbl_wr_shift_type','{$id}')");
            
             
            $this->db->freeDBResource();
            //var_dump($query);
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/shift_type'));


        } //get shift list
        else {
            //get data to be displayed in the shift_type list
            $shift_data = $this->model_master_data_view->getShiftData();
            $data['shift'] = $shift_data;

        }
        $this->template->build('shift_type', $data);
    }

    /**
     * industry access page
     * @param string|type $page_action
     * @param string|type $id
     * @param string $industryId
     */
    public function industry_access($page_action = "", $id = "", $industryId = "")
    {
        $data[] = array();
        $data['page_url'] = 'admin/master_data/industry_access';
        $data['page_title'] = "Industry Access";
        $data['page_action'] = $page_action;

        //get list of users with access to specific industry
        //this post request is for listing page
        if ($this->input->post() && $this->input->post('industry') != 0) {
            $industry_id = $this->input->post('industry');
            //get data to be displayed according to selected industry
            $access = $this->model_master_data_view->getIndustryAccess($industry_id);
            $data['access'] = $access;
            //get select list of industries to be displayed in select industry
            $industry_list = $this->model_master_data_view->getAccessIndustry();
            $data['select_industry'] = $industry_list;

            $data['selected_industry_id'] = $industry_id;
            //var_dump($access);
            $this->template->build('access_industry', $data);
        } elseif ($page_action == "edit" && $id != "") {

        } elseif ($page_action == "remove" && $id != "") {

        } else {
            //get the list of select industry options
            $industry_list = $this->model_master_data_view->getAccessIndustry();
            $data['select_industry'] = $industry_list;
            $this->template->build('access_industry', $data);
        }

    }

    /**
     * change user access to industry
     * get the record to be added/deleted in tbl_customer_industry
     *
     */
    public function changeIndustryAccess()
    {
        $data = array(
            'industry_id' => $this->input->post('industry_id'),
            'has_access' => $this->input->post('industry_access'),
            'customer_user_id' => $this->input->post('customer_user_id'),
            'description' => $this->input->post('description'));

        $result = $this->model_master_data->updateIndustryAccess($data);
        echo json_encode($result);
    }

    /**
     * add and edit states/regions
     * @param string|type $page_action
     * @param string|type $id
     */
    public function manage_region($page_action = "", $id = "")
    {
        $data = array();
        $data['page_url'] = 'admin/master_data/manage_region';
        $data['page_action'] = $page_action;
        $data['page_title'] = "Manage Regions/States";

        $countries = $this->model_master_data_view->getCountry();
        $data['select_country'] = $countries;
        $states = $this->model_master_data_view->getStates();
        $data['states'] = $states;

        if ($this->input->post()) {
            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('select_country', 'Country', 'required');
            $this->form_validation->set_rules('state_name', 'State', 'required');
            $this->form_validation->set_rules('short_name', 'Short Name', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_success_msg = $action == 'insert' ? 'saved' : 'updated';


                if ($this->model_master_data->country_region_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_success_msg} successfully.");
                    redirect(base_url('admin/master_data/manage_region'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());
                redirect(base_url('admin/master_data/manage_region'));

            }
        } //edit state info

        elseif ($page_action == "edit" && $id != "") {
            $state_edit = $this->model_master_data_view->getStates($id);
            //get the state details to be edited
            $data['state_edit'] = $state_edit;

        } //remove state info

        elseif ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterData('tbl_country_region','{$id}')");
            
            $this->db->freeDBResource();

            if ($query) {
                $result = $query->row();
              
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/manage_region'));
        }
        $this->template->build('manage_region', $data);
    }

    /**
     * LP(LHC) manage and edit
     * @param string|type $page_action
     * @param string|type $id
     */
    public function lhc_manage($page_action = "", $id = "")
    {


        $data = array();
        $data['page_url'] = 'admin/master_data/lhc_manage';
        $data['page_action'] = $page_action;
        $data['page_title'] = "List LP";


        //get dynamic lhc document list
        $lhc_documents = $this->model_master_data_view->getLhcDoc();
        $data['lhc_doc_list'] = $lhc_documents;

        //get industry select option
        $industry_list = $this->model_master_data_view->getAccessIndustry();
        $data['select_industry'] = $industry_list;

        //check if the post request exceeds maximum limit of 8MB
        //default post_max_size=8M in php.ini
        $content = $_SERVER['CONTENT_LENGTH'];
        $post = $_POST;
        $files = $_FILES;

        if ($content > 0 && empty($files) && empty($post)) {
            $data['page_action'] = 'add';
            $data['form_error'] = 'Maximum Upload Limit(8MB) Exceeded';
            $this->template->build('lhc_manage', $data);
            return;
        }


        if ($this->input->post()) {

            // if $this->input->post(hidden_id) == '' then insert else update
            $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';

            $this->form_validation->CI = $this;
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

            //config for upload logic
            $config['upload_path'] = '../assets/uploads/lp_doc';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            $config['max_size'] = '2048';
            $config['max_width'] = '';
            $config['max_height'] = '';
            $config['overwrite'] = true;
            $config['remove_spaces'] = true;

            $this->load->library('upload', $config);

            $this->form_validation->set_rules('industry[]', 'industry', 'required');
            $this->form_validation->set_rules('business_name', 'Business Name', 'required');
            $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
            $this->form_validation->set_rules('contact_phoneno', 'Contact Phone No.', 'required');

            $this->form_validation->set_rules('profile', 'Profile', 'required');

            //email field is set read only for update/edit
            if ($action != 'update') {
                $this->form_validation->set_rules('email', 'Email', 'required');
            }

            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('abn', 'ABN', 'required');


            if ($this->form_validation->run() == true) {

                $count = 0;

                foreach ($_FILES as $fieldName => $fileObject) {

                    $config['file_name'] = hashCode(5) . date('his');
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload($fieldName)) {
                        //display errors on upload failure
                        $data['form_error'] = $this->upload->display_errors();
                        $data['page_action'] = 'add';
                        $this->template->build('lhc_manage', $data);
                        return;

                    } else {
                        $file_upload_data[$count] = $this->upload->data();
                        $count++;
                    }
                }

                //get the multiple industries selected
                $selected_industry = $this->input->post('industry');
                $business_name = $this->input->post('business_name');

                //user-credentials for email activation
                $user_name = $business_name;
                $user_email = $this->input->post('email');
                // $user_password = $this->input->post('password');


                $profile_image = $file_upload_data[0]['file_name'];
                //$encrypted = password_hash($user_password, PASSWORD_DEFAULT);
                //$contact_person = $this->input->post('contactperson');
                $bio = $this->input->post('bio');
                $hashCode = hashCode(32);


                //data to be inserted in tbl_user
                $user_data = array(
                    'name' => $business_name,
                    'email' => $user_email,
                    'username' => $user_name,
                    //  'password' => $encrypted,
                    'phone_number' => $this->input->post('contact_phoneno'),
                    'full_address' => $this->input->post('address'),
                    'profile_image' => $profile_image,
                    'description' => $bio);

//                only on adding new user
//                set lp user status 0
//                set new activation_code
//                no change for edit
                if ($action != 'update') {
                    $user_data['active'] = 0;
                    $user_data['activation_code'] = $hashCode;
                }

                //remove old lp docs on edit
                if($action == 'update'){
                    $id=$this->input->post('hidden_id');
                    $this->delete_old_docs($id);
                }

                $new_userId = $this->model_master_data->lhc_manage_add_edit($action, $user_data, $file_upload_data, $selected_industry);

                if ($new_userId === NULL) {
                    $data['form_error'] = 'Email is already registered';
                    $data['page_action'] = 'add';
                    $this->template->build('lhc_manage', $data);
                    return;
                } else if ($new_userId === FALSE) {
                    $data['form_error'] = 'Insert Error. Please Try again';
                    $data['page_action'] = 'add';
                    $this->template->build('lhc_manage', $data);
                    return;
                } else if ($new_userId === -1) {
                    $this->session->set_flashdata('successMessage', 'User updated successfully.');
                    redirect('admin/master_data/lhc_manage');
                } else if ($new_userId === 0) {
                    $this->session->set_flashdata('errorMessage', 'Update Error. Please Try again.');
                    redirect('admin/master_data/lhc_manage');
                } else {
                    //send confirmation email link on registration
                    if ($action == 'insert') {
                        $this->Registration_email_model->send_email($new_userId, $user_email, $hashCode, $user_name);
                    }

                    $this->session->set_flashdata('successMessage', 'User added successfully. Please verify your email address');
                    redirect('admin/master_data/lhc_manage');
                }
            } //if form invalid :
            else {
                $this->session->set_flashdata('errorMessage', validation_errors());
                redirect('admin/master_data/lhc_manage');
            }
        } //edit lp users
        elseif ($page_action == "edit" && $id != "") {
            $lhc_edit = $this->model_master_data_view->getLhcCompanies($id);
            //echo "edit action";
            $data['lhc_company_edit'] = $lhc_edit;

            $lhc_documents = $this->model_master_data_view->getLhcDoc();
            $data['lhc_doc_list'] = $lhc_documents;

            $industry_list = $this->model_master_data_view->getAccessIndustry();
            $data['select_industry'] = $industry_list;

            //get the user's current files uploaded
            $lhc_user_doc_list = $this->model_master_data_view->getLhcUserDoc($id);
            //parse the user_lhc_doc to doc_id => doc_name
            $parsed_doc_details = $this->parseLpUserDoc($lhc_user_doc_list);

            $data['lhc_user_doc'] = $parsed_doc_details['doc_id_file'];
            $data['lhc_user_doc_expiry'] = $parsed_doc_details['doc_id_file_expiry'];

            $this->template->build('lhc_manage', $data);

        } //remove lp users
        elseif ($page_action == "remove" && $id != "") {
            //delete old user docs
            $this->delete_old_docs($id);

            $query = $this->db->query("CALL sp_removeMasterData('tbl_user','{$id}')");
            $this->db->freeDBResource();

            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/lhc_manage'));
        } else {
            //get lp details for listing page
            $lhc = $this->model_master_data_view->getLhcCompanies();
            $data['lhc_companies'] = $lhc;
            $this->template->build('lhc_manage', $data);
        }
    }

    /**
     * parse lp doc details as per doc_id
     * @param $userDoc
     * @return mixed
     */
    public function parseLpUserDoc($userDoc)
    {
        $lhc_doc_id_file = array();
        $lhc_doc_id_expiry = array();

        foreach ($userDoc as $doc_details) {
            if (!array_key_exists($doc_details->doc_id, $lhc_doc_id_file)) {
                $lhc_doc_id_file[$doc_details->doc_id] = $doc_details->doc_name;
                $lhc_doc_id_expiry[$doc_details->doc_id] = $doc_details->expiry_date;
            }
        }
        $return_array['doc_id_file'] = $lhc_doc_id_file;
        $return_array['doc_id_file_expiry'] = $lhc_doc_id_expiry;

        //var_dump($lhc_doc_id_file);
        return $return_array;
    }

    /**
     * delete user uploaded old docs
     * @param $id
     */
    public function delete_old_docs($id)
    {
        $file_path = "../assets/uploads/lp_doc/";
        clearstatcache();

        //get the user profile_image
        $user_details = $this->model_master_data_view->getLhcCompanies($id);
        $profile_image = $user_details->profile_image;
        //set the profile_image path
        $user_image_path = $file_path . $profile_image;
        //check if file exists
        $check = file_exists($user_image_path);
        if ($check) {
            //remove the uploaded file
            unlink($user_image_path);
        }
        $user_docs_to_remove = $this->model_master_data_view->getLhcUserDoc($id);
        foreach ($user_docs_to_remove as $doc_to_delete) {
            $path = $file_path . $doc_to_delete->doc_name;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

}
