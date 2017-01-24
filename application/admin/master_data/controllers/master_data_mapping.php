<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_data_mapping extends Admin_Controller
{
    public $days = array();

    public function __construct()
    {
        parent::__construct();
        $this->template->set_layout('admin/default');
        $this->load->model('model_master_data');
        $this->load->model('model_master_data_view');
        $this->days = array(
            'weekday' => 'Weekday(Mon-Fri)',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
            'public_holiday' => 'Public Holiday'
        );
    }

    /**
     *
     * @param type $page_action
     * @param type $id
     */


    public function industrySkill($page_action = "", $id = "")
    {

        $data = array();
        $data['page_url'] = "admin/master_data/mapping/industrySkill";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Industry/Skill Mapping";
        $data['industries'] = $this->model_master_data_view->getIndustry('', 1);
        $data['skills'] = $this->model_master_data_view->getskill('', 1);
        if ($this->input->post()) {

            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('industry_id', 'Industry', 'required');
            $this->form_validation->set_rules('skill_id', 'Skills', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_sucess_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->industry_skill_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_sucess_msg} successfully.");
                    redirect(base_url('admin/master_data/mapping/industrySkill'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterDataMapping('tbl_con_industry_skill_all','{$id}','0')");
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/mapping/industrySkill'));

        } else if ($page_action == "edit" && $id != "") {


            $industry_skill_data = $this->model_master_data_view->getIndustrySkill($id);
            $industry_skill_array_container = $this->parseIndustrySkill($industry_skill_data);
            //get selected industry_id
            reset($industry_skill_array_container['industry_data_array']);
            $sel_industry_id = key($industry_skill_array_container['industry_data_array']);

            $data['sel_industry_id'] = $sel_industry_id;
            $data['industry_skill_detail_data_array'] = $industry_skill_array_container['industry_skill_detail_data_array'];
            $data['industry_skill_name_array'] = $industry_skill_array_container['industry_skill_name_array'];
            $data['industry_skill_id_array'] = $industry_skill_array_container['industry_skill_id_array'];
//               / $data['industry_skill'] = 

        } else if ($page_action == "add" && $id != "") {
            $data['sel_industry_id'] = $id;
        } else {
            $industry_skill_data = $this->model_master_data_view->getIndustrySkill();
            $industry_skill_array_container = $this->parseIndustrySkill($industry_skill_data);

            $data['industry_data_array'] = $industry_skill_array_container['industry_data_array'];
            $data['industry_skill_detail_data_array'] = $industry_skill_array_container['industry_skill_detail_data_array'];
            $data['industry_skill_name_array'] = $industry_skill_array_container['industry_skill_name_array'];

        }

        $this->template->build('industry_skill', $data);
    }

    public function parseIndustrySkill($industry_skill_obj)
    {
        $return_industry_skill_data = array();
        $industry_data_array = array(); // hold industry detail
        $industry_skill_detail_data_array = array(); //hold industry skill detail data
        $skills_detail_data_array = array(); // hold skill detail
        $industry_skill_name_array = array();//hold only industry skill
        $industry_skill_id_array = array();

        if ($industry_skill_obj) {
            foreach ($industry_skill_obj as $industry_skill) {

                if (!array_key_exists($industry_skill->industry_id, $industry_skill_detail_data_array)) {
                    $industry_data_array[$industry_skill->industry_id] =
                        $industry_skill->industry_name;
                    $skills_detail_data_array = array();

                }
                $industry_skill_name_array[$industry_skill->industry_id][] = $industry_skill->skill_name;
                $industry_skill_id_array [$industry_skill->industry_id][] = $industry_skill->skill_id;
                $skills_detail_data_array['skills'][] =
                    array(
                        'skill_id' => $industry_skill->skill_id,
                        'skill_name' => $industry_skill->skill_name);
                $industry_skill_detail_data_array[$industry_skill->industry_id] = $skills_detail_data_array;
            }
        }
        $return_industry_skill_data['industry_data_array'] = $industry_data_array;
        $return_industry_skill_data['industry_skill_detail_data_array'] = $industry_skill_detail_data_array;
        $return_industry_skill_data['industry_skill_name_array'] = $industry_skill_name_array;
        $return_industry_skill_data['industry_skill_id_array'] = $industry_skill_id_array;

        return $return_industry_skill_data;
    }

    /**
     * Use to remove industry skill
     * @param type $indutry_id
     * @param type $skill_id
     */

    public function removeIndustrySkill()
    {
        $message = array();
        $industry_id = $this->input->post('industry_id');
        $skill_id = $this->input->post('skill_id');
        if ($industry_id != "" && $skill_id != "") {

            $query = $this->db->query("CALL sp_removeMasterDataMapping('tbl_con_industry_skill','{$industry_id}','{$skill_id}')");
//echo $this->db->last_query();
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                if ($result->status == '1') {
                    $message['status'] = 1;
                    $message['msg'] = $this->model_master_data->get_master_data_mapping_delete_status($result);
                } else {
                    $message['status'] = $result->status;
                    $message['msg'] = $this->model_master_data->get_master_data_mapping_delete_status($result);
                }
            } else {
                $message['status'] = 0;
                $message['msg'] = "Something wrong";
            }

        } else {
            $message['status'] = 0;
            $message['msg'] = "industry id or skill id can not be null";
        }
        echo json_encode($message);
        exit;
    }

    public function checkIndustrySkill()
    {
        $industry_id = $this->input->post('industry_id');
        $industry_skill_data = $this->model_master_data_view->getIndustrySkill($industry_id);

        if ($industry_skill_data)
            echo 1;
        else {
            echo 0;
        }
        exit;
    }

    /**
     * Skill sub-skill/sublevel mapping
     * @param string|type $page_action
     * @param string|type $id
     * @param string $industry_id
     */

    public function skillSubSkill($page_action = "", $id = "", $industry_id = "")
    {
        $data = array();

        $data['page_url'] = "admin/master_data/mapping/skillSubSkill";
        $data['page_action'] = $page_action;
        $data['page_title'] = "Skill/Subskill Mapping";

        //get list of industries for select option
        $data['industry_select'] = $this->model_master_data_view->getIndustryWithSkills();

         $data['subskills'] = $this->model_master_data_view->getSubskill('',1);
        $data['skills'] = $this->model_master_data_view->getskill('',1);
        
       

        if ($this->input->post()) {

            //do the required field validation validation
            $this->form_validation->set_error_delimiters('<span class="p-head">', '</span>');
            $this->form_validation->set_rules('skill_id', 'Skill', 'required');
            $this->form_validation->set_rules('level_id', 'Level', 'required');
            if ($this->form_validation->run() == true) {
                // if $this->input->post(hidden_id) == '' then insert else update
                $action = $this->input->post('hidden_id') == '' ? 'insert' : 'update';
                $error_sucess_msg = $action == 'insert' ? 'saved' : 'updated';

                if ($this->model_master_data->skill_subskill_add_edit($action)) {
                    $this->session->set_flashdata('successMessage', "Data {$error_sucess_msg} successfully.");
                    redirect(base_url('admin/master_data/mapping/skillSubSkill'));
                } else {
                    $this->session->set_flashdata('errorMessage', 'Problem occured');
                }

            } else {

                $this->session->set_flashdata('errorMessage', validation_errors());

            }

        } else if ($page_action == "remove" && $id != "") {
            $query = $this->db->query("CALL sp_removeMasterDataMapping('tbl_con_skill_level_all','{$id}','0')");


            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                //set delete message
                $this->model_master_data->get_master_data_delete_status($result);
            } else {
                $this->session->set_flashdata('errorMessage', "Unknown error.");
            }
            redirect(base_url('admin/master_data/mapping/skillSubSkill'));

        } else if ($page_action == "edit" && $id != "") {   //set selected industry_id
            $data['sel_industry_id'] = $industry_id;
            //get and set skills according to selected industry
            $data['skills'] = $this->model_master_data_view->getskill('',1);//getIndustrySkill($industry_id);
            //get and set sub_skills
            $data['subskills'] = $this->model_master_data_view->getSubskill('', 1);

            //get sub_skill as per selected skill_id
            $skill_subskill_data = $this->model_master_data_view->getSkillSubskill($id, NULL, NULL);
            $skill_subskill_array_container = $this->parseSkillSubSkill($skill_subskill_data);
            reset($skill_subskill_array_container['skill_data_array']);

            $sel_skill_id = key($skill_subskill_array_container['skill_data_array']);
            //set selected skill id
            $data['sel_skill_id'] = $sel_skill_id;

            $data['skill_subskill_detail_data_array'] = $skill_subskill_array_container['skill_subskill_detail_data_array'];
            $data['skill_subskill_name_array'] = $skill_subskill_array_container['skill_subskill_name_array'];
            $data['skill_subskill_id_array'] = $skill_subskill_array_container['skill_subskill_id_array'];


        } else if ($page_action == "add" && $id != "") {    //set selected industry_id
            $data['sel_industry_id'] = $industry_id;
            //get and set skills according to selected industry
            $data['skills'] = $this->model_master_data_view->getskill('',1);//getIndustrySkill($industry_id);
            //set selected_skill_id
            $data['sel_skill_id'] = $id;
            //show all sub skills for add
            $data['subskills'] = $this->model_master_data_view->getSubskill('', 1);
        } // get data for listing view
        else {
            $skill_subskill_data = $this->model_master_data_view->getSkillSubskill(NULL, NULL, NULL);

            $skill_subskill_array_container = $this->parseSkillSubSkill($skill_subskill_data);

            //contains skill_id => skill_name for skill column
            $data['skill_data_array'] = $skill_subskill_array_container['skill_data_array'];

            //contains skill_id => subskills_name for sub-skills column
            $data['skill_subskill_name_array'] = $skill_subskill_array_container['skill_subskill_name_array'];

            //contains skill_id => subskill_details
            $data['skill_subskill_detail_data_array'] = $skill_subskill_array_container['skill_subskill_detail_data_array'];

            //contains skill_id => industry_name
            $data['industry_skill_id_array'] = $skill_subskill_array_container['industry_skill_id_array'];

//            echo "Origigial data from db<br>";
//            echo json_encode($skill_subskill_data);
//
//            echo "<br><br>For skills column : skill_data_array<br>".implode('&nbsp ',$data['skill_data_array']);
//
//            echo "<br><br>FOR subskill column : skill_subskill_name_array<br>";
//            echo json_encode($data['skill_subskill_name_array']);
//
//            echo "<br><br>skill_subskill_detail_data_array<br>";
//            echo json_encode($data['skill_subskill_detail_data_array']);
//
//            echo "<br><br>skill_subskill_id_array<br>";
//            echo json_encode($skill_subskill_array_container['skill_subskill_id_array']);

//            echo "<br><br>industry_skill_id_array<br>";
//            echo json_encode($data['industry_skill_id_array']);

        }

        $this->template->build('skill_subskill', $data);
    }

    public function parseSkillSubSkill($skill_subskill_obj)
    {
        $return_skill_subskill_data = array();
        $skill_data_array = array(); // hold skill detail
        $skill_subskill_detail_data_array = array(); //hold skill subskill detail data
        $subskills_detail_data_array = array(); // hold skill detail
        $skill_subskill_name_array = array();//hold only industry skill
        $skill_subskill_id_array = array();

        $industry_skill_id_array = array();//hold skill->id industry_name

        if ($skill_subskill_obj) {
            foreach ($skill_subskill_obj as $skill_subskill) {

                if (!array_key_exists($skill_subskill->skill_id, $skill_subskill_detail_data_array)) {

                    $skill_data_array[$skill_subskill->skill_id] = $skill_subskill->skill_name;

                    $industry_skill_id_array[$skill_subskill->skill_id] = $skill_subskill->industry_name;

                    $subskills_detail_data_array = array();
                }

                $skill_subskill_name_array[$skill_subskill->skill_id][] = $skill_subskill->level_name;
                $skill_subskill_id_array [$skill_subskill->skill_id][] = $skill_subskill->level_id;

                $subskills_detail_data_array['subskills'][] =
                    array(
                        'level_id' => $skill_subskill->level_id,
                        'level_name' => $skill_subskill->level_name);
                $skill_subskill_detail_data_array[$skill_subskill->skill_id] = $subskills_detail_data_array;
            }
        }
        //skill_id -> skill_name array for skill column
        $return_skill_subskill_data['skill_data_array'] = $skill_data_array;

        //for skill_id => subskills_name for sub-skill column
        $return_skill_subskill_data['skill_subskill_name_array'] = $skill_subskill_name_array;

        //contains skill_id => subskill_details
        $return_skill_subskill_data['skill_subskill_detail_data_array'] = $skill_subskill_detail_data_array;


        $return_skill_subskill_data['skill_subskill_id_array'] = $skill_subskill_id_array;

        $return_skill_subskill_data['industry_skill_id_array'] = $industry_skill_id_array;

        return $return_skill_subskill_data;
    }


    /**
     * Use to remove skill subskill
     *
     *
     */

    public function removeSkillSubSkill()
    {
        $message = array();
        $skill_id = $this->input->post('skill_id');
        $subskill_id = $this->input->post('level_id');
        if ($skill_id != "" && $subskill_id != "") {

            $query = $this->db->query("CALL sp_removeMasterDataMapping('tbl_con_skill_level','{$skill_id}','{$subskill_id}')");
//echo $this->db->last_query();exit;
            $this->db->freeDBResource();
            if ($query) {
                $result = $query->row();
                if ($result->status == '1') {
                    $message['status'] = 1;
                    $message['msg'] = $this->model_master_data->get_master_data_mapping_delete_status($result);
                } else {
                    $message['status'] = $result->status;
                    $message['msg'] = $this->model_master_data->get_master_data_mapping_delete_status($result);
                }
            } else {
                $message['status'] = 0;
                $message['msg'] = "Something wrong";
            }

        } else {
            $message['status'] = 0;
            $message['msg'] = "industry id or skill id can not be null";
        }
        echo json_encode($message);
        exit;
    }

    /**
     * change select skill list based on selected industry
     * for skillSubSkill mapping
     */
    public function changeSkill()
    {
        $industry_id = $this->input->post('industry_id');


        $updated_skills = $this->model_master_data_view->getIndustrySkill($industry_id);
        $data = array();
        foreach ($updated_skills as $skills) {
            $id = $skills->skill_id;
            $name = $skills->skill_name;
            $temp = array($id => $name);
            $data[] = $temp;
        }

        echo json_encode($data);
    }

    public function checkSkillSubSkill()
    {
        $skill_id = $this->input->post('skill_id');
        $skill_subskill_data = $this->model_master_data_view->getSkillSubskill($skill_id);

        if ($skill_subskill_data)
            echo 1;
        else {
            echo 0;
        }
        exit;
    }

}