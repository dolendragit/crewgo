<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property  input
 * @property  session
 */
class Model_master_data extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * set error/success message as per the status received from Procedure sp_removeMasterData
     * @param int $result
     */
    public function get_master_data_delete_status($result)
    {
 
        if ($result->status == 1) {
            $this->session->set_flashdata('successMessage', "Data deleted successfully.");
        } else if ($result->status == 0) {
            $this->session->set_flashdata('errorMessage', "Record not found.");
        } else if ($result->status == 2) {
            $this->session->set_flashdata('errorMessage', "The record can not be deleted..Child record exist.");
        } else if ($result->status == 3) {
            $this->session->set_flashdata('errorMessage', "Table not found.");
        }
    }

    /**
     * set error/success message as per the status received from Procedure sp_removeMasterData
     * @param int $result
     * @return string
     */
    public function get_master_data_change_status($result)
    {
        if ($result->status == 1) {
            return "Data deleted successfully.";
        } else if ($result->status == 0) {
            return "Record not found.";
        } else if ($result->status == 2) {
            return "Status can not be changed for the record.Child record exist.";
        } else if ($result->status == 3) {
            return "Table not found.";
        }
    }

    /**
     * set error/success message as per the status received from Procedure sp_removeMasterDataMapping
     * @param int $result
     * @return string
     */
    public function get_master_data_mapping_delete_status($result)
    {
        if ($result->status == 1) {
            return "Data deleted successfully";
        } else if ($result->status == 0) {
            return "Record not found.";
        } else if ($result->status == 2) {
            return "Record can not be deleted.Child record exist.";
        } else if ($result->status == 3) {
            return "Table not found.";
        }
    }

    /**
     * Insert update skill data
     * @param type $action
     * @return boolean
     */
    public function skill_add_edit($action) //insert or update
    {

        $table_data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'status' => $this->input->post('status')
        );
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_skill', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_skill', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Insert update lhc doc data
     * @param type $action
     * @return bool
     */

    public function lhc_doc_add_edit($action)
    {
        $has_expiry_date = $this->input->post('has_expiry_date');
        $warn_expiry_before = $this->input->post('warn_expiry_before');

        if ($has_expiry_date == 1) {
            if (!$warn_expiry_before) {
                return FALSE;
            }
        }
        $table_data = array(
            'country_id' => $this->input->post('country'),
            'region_id' => $this->input->post('region'),
            'industry_id' => $this->input->post('industry'),
            'name' => $this->input->post('name'),
            'status' => $this->input->post('status'),
            'has_expiry_date' => $has_expiry_date,
            'warn_expiry_before' => $warn_expiry_before
        );
        //echo implode("<br>", $table_data);
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_lhc_doc', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update lhc_doc table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_lhc_doc', $table_data);            //echo "update lhc_doc !!";
        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }
    }

    /**
     * Insert update Industry data
     * @param type $action
     * @return boolean
     */
    public function industry_add_edit($action) //insert or update
    {

        $table_data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'status' => $this->input->post('status')
        );
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_industry', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_industry', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    public function objecttoarray($val)
    {
        return $val;
    }

    /**
     * Insert update Industry Skill data
     * @param type $action
     * @return boolean
     */
    public function industry_skill_add_edit($action) //insert or update
    {
        $industry_id = $this->input->post('industry_id');
        //get skill array
        $industry_skills = $this->db->select('skill_id')->from('tbl_con_industry_skill')->where('industry_id', $industry_id)->get()->result();
        $industry_skill_array = array();
        if ($industry_skills) {
            foreach ($industry_skills as $in_skill) {
                $industry_skill_array[] = $in_skill->skill_id;
            }
        }
        $skills = $this->input->post('skill_id');
        $new_industry_skill = array_diff($skills, $industry_skill_array);
        $skills_array = array_values($new_industry_skill);
        $my_arr = array();
        if (is_array($skills_array) && count($skills_array) > 0) {
            foreach ($skills_array as $inst_industry_skill) {
                $my_arr[] = array('industry_id' => $industry_id, 'skill_id' => $inst_industry_skill, 'entered_by' => $this->session->userdata('user_id'),);
            }
        }
        $this->db->trans_begin();
        if (is_array($my_arr) && count($my_arr) > 0) {
            $this->db->insert_batch('tbl_con_industry_skill', $my_arr);
        } else {
            return true;
        }

        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }


    /**
     * Insert update Skill sub-Skill data
     * @param type $action
     * @return boolean
     */
    public function skill_subskill_add_edit($action) //insert or update
    {   $industry_id = $this->input->post('select_industry');

        $skill_id = $this->input->post('skill_id');
        //get skill array
        $skill_subskills = $this->db->select('level_id')->from('tbl_con_skill_level')->where('skill_id', $skill_id)->get()->result();
        $skill_subskill_array = array();
        if ($skill_subskills) {
            foreach ($skill_subskills as $skill_sub_skill) {
                $skill_subskill_array[] = $skill_sub_skill->level_id;
            }
        }
        $sub_skills = $this->input->post('level_id');
        $new_skill_subskill = array_diff($sub_skills, $skill_subskill_array);
        $subskills_array = array_values($new_skill_subskill);
        $my_arr = array();
        if (is_array($subskills_array) && count($subskills_array) > 0) {
            foreach ($subskills_array as $inst_industry_skill) {
                $my_arr[] = array('industry_id'=>$industry_id,'skill_id' => $skill_id, 'level_id' => $inst_industry_skill, 'entered_date' => date('Y-m-d'), 'entered_by' => $this->session->userdata('user_id'),);
            }
        }
        $this->db->trans_begin();
        if (is_array($my_arr) && count($my_arr) > 0) {
            $this->db->insert_batch('tbl_con_skill_level', $my_arr);
        } else {
            return true;
        }

        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Insert update subskill data
     * @param type $action
     * @return boolean
     */
    public function subskill_add_edit($action) //insert or update
    {

        $table_data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'status' => $this->input->post('status')
        );
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_level', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_level', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Insert update PPE data
     * @param type $action
     * @return boolean
     */
    public function ppe_add_edit($action) //insert or update
    {

        $table_data = array(
            'name' => $this->input->post('name'),
            'status' => $this->input->post('status')
        );
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_ppe', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_ppe', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Insert update Priority data
     * @param type $action
     * @return boolean
     */
    public function priority_add_edit($action) //insert or update
    {

        $table_data = array(
            'name' => $this->input->post('name'),
            'status' => $this->input->post('status'),
            'is_mandatory' => (int)$this->input->post('is_mandatory'),
            'score' => $this->input->post('score')
        );
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_priority', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_priority', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Insert update Qualification data
     * @param type $action
     * @return boolean
     */
    public function qualification_add_edit($action) //insert or update
    {

        $table_data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'status' => $this->input->post('status')
        );
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_qualification', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_qualification', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Insert update Activty level
     * @param type $action
     * @return boolean
     */
    public function activity_level_add_edit($action) //insert or update
    {

        $table_data = array(
            'name' => $this->input->post('name'),
            'type' => $this->input->post('type'),
            'p_staff_result_range_from' => $this->input->post('p_staff_result_range_from'),
            'p_staff_result_range_to' => $this->input->post('p_staff_result_range_to'),
            'shift_request_number' => $this->input->post('shift_request_number'),
            'shift_time_interval' => $this->input->post('shift_time_interval'),
            'shift_notice_time' => $this->input->post('shift_notice_time'),
            'peak_price_factor' => $this->input->post('peak_price_factor'),
            'filling_probability' => $this->input->post('filling_probability')
        );
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_activity_level', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_activity_level', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Insert update Sociable Hour
     * @param type $action
     * @return boolean
     */
    public function sociable_hour_add_edit($action) //insert or update
    {

        $table_data = array(
            'days' => $this->input->post('days'),
            'sociable_from_hour' => $this->input->post('sociable_from_hour'),
            'sociable_to_hour' => $this->input->post('sociable_to_hour'),
            'sociable_factor' => $this->input->post('sociable_factor'),
            'non_sociable_hour' => $this->input->post('non_sociable_hour'),
            'ns_peak' => $this->input->post('ns_peak'),
            'country_region_id' => $this->input->post('region_id'),
            'status' => $this->input->post('status')

        );
        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_social_hour', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_social_hour', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Insert update Cacellation
     * @param type $action
     * @return boolean
     */
    public function cancellation_add_edit($action) //insert or update
    {


        $table_data = array(
            'cancel_prior' => $this->input->post('cancel_prior'),
            'cancel_fee' => $this->input->post('cancel_fee'),
            'remarks' => $this->input->post('remarks'),
            'country_region_id' => $this->input->post('region_id'),
            'status' => $this->input->post('status')
        );


        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_con_job_cancellation', $table_data);
        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_con_job_cancellation', $table_data);

        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

    }

    /**
     * Use to format time 00:00:00 to 00:00
     * @param type $time_data
     * @return string
     */
    function formatTime($time_data)
    {
        $time_data = explode(':', $time_data);
        $am_pm = "";
        if($time_data[0] > 12)
        {
           $am_pm =" PM"; 
        }else{
            $am_pm =" AM"; 
        }
        array_pop($time_data);
        return $time_data = implode(':', $time_data).$am_pm;

    }
    /**
     * replace am,pm,AM,PM from time
     * @param type $am_pm_time
     * @return type
     */
    function extractAMPM($am_pm_time){
      $am_pm = array(' AM',' PM',' am',' pm');
      if($am_pm_time!="")
          return str_replace ($am_pm, '', $am_pm_time);
      else
          return $am_pm_time;
}

    /**
     * holiday_calendar add and edit
     * @param type $action
     * @return boolean
     */
    public function holiday_add_edit($action)
    {
        
        $dateInput = $this->input->post('holiday_date');
        $date = DateTime::createFromFormat('Y-m-d', $dateInput);
     
        $year = $date->format("Y");

        $table_data = array(
            'country_id' => $this->input->post('country'),
            'country_region_id' => $this->input->post('region'),
            'date' => $dateInput,
            'year' => $year,
            'name' => $this->input->post('description'),
            'description' => $this->input->post('description')
        );

        //start the transaction
        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_wr_calendar', $table_data);

        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update lhc_doc table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_wr_calendar', $table_data);
        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;

        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;

        }
    }

    /**
     * shift type
     * @param type $action
     * @return bool
     */
    public function shift_type_add_edit($action)
    {
        $table_data = array(
            'name' => $this->input->post('name'),
            'time_from' => $this->input->post('time_from'),
            'time_to' => $this->input->post('time_to')

        );

        $this->db->trans_begin();
        if ($action == "insert") {
            $table_data['entered_by'] = $this->session->userdata('user_id');
            $insert = $this->db->insert('tbl_wr_shift_type', $table_data);

        } else {
            //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update shift_type table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_wr_shift_type', $table_data);            //echo "update lhc_doc !!";
        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;

        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;

        }
    }

    /**
     * update tbl_customer_industry
     * assign customer access to different industry
     * @param array|type $record
     * @return bool
     */
    public function updateIndustryAccess($record = array())
    {

        $this->db->trans_begin();
        $industry_id = $record['industry_id'];
        $user_id = $record['customer_user_id'];
        $has_access = $record['has_access'];

        //check if the data exists already
        $check = $this->db->get_where('tbl_customer_industry', array('industry_id' => $industry_id, 'customer_user_id' => $user_id));
        $row_exists = $check->num_rows;

        //if data does not exist and industry_access=true insert data
        if ($has_access === 'true' && $row_exists == 0) {
            $record['entered_by'] = $this->session->userdata('user_id');
            $record['has_access'] = 1;
            $insert = $this->db->insert('tbl_customer_industry', $record);
        } //if data exists and access=false, remove data from db
        elseif ($has_access !== 'true' && $row_exists == 1) {
            $this->db->delete('tbl_customer_industry', array('industry_id' => $industry_id, 'customer_user_id' => $user_id));
        } elseif ($has_access === 'true' && $row_exists == 1) {
            $record['has_access'] = 1;
            $this->db->where(array('customer_user_id' => $user_id, 'industry_id' => $industry_id));
            $this->db->update('tbl_customer_industry', $record);
        }

        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;

        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;

        }
    }

    /**
     * add states for country
     * @param type $action
     * @return boolean
     */
    public function country_region_add_edit($action)
    {
        $table_data = array(
            'name' => $this->input->post('state_name'),
            'short_name' => $this->input->post('short_name'),
            'country_id' => $this->input->post('select_country')
        );

        $this->db->trans_begin();
        if ($action == "insert") {
            $insert = $this->db->insert('tbl_country_region', $table_data);
            //echo "model_maste_data all good"; var_dump($insert);
        } else {
            //add extra column to be updated
            //$table_data['updated_date'] = date('Y-m-d');
            //$table_data['updated_by'] = $this->session->userdata('user_id');
            //update state table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_country_region', $table_data);            //echo "update lhc_doc !!";
        }
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;

        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            //echo "model_maste_data all good"; var_dump($insert);
            return TRUE;

        }
    }

    /**
     * add and edit the lp_users
     * @param type $action
     * @param $tbl_user_data
     * @param array|type $upload_data
     * @param type $industries
     * @return bool
     * insert to tbl_user, tbl_user_group, tbl_lhc_add_info,tbl_lhc_doc, tbl_lhc_industry
     */
    public function lhc_manage_add_edit($action, $tbl_user_data, $upload_data = array(), $industries)
    {

        $this->db->trans_begin();

        $email = $tbl_user_data['email'];
        if ($action == 'insert') {
            $this->db->select('*');

            $this->db->join('tbl_user_group', 'tbl_user.id = tbl_user_group.user_id');
            $this->db->where(array('tbl_user.email' => $email, 'tbl_user_group.group_id' => 2));
            $checkEmail = $this->db->get('tbl_user');


            $emailUnique = $checkEmail->num_rows();
            //if email already exists
            if ($emailUnique > 0) {
                return NULL;
            }
            //insert data in tbl_user and get the new user id
            $this->db->insert('tbl_user', $tbl_user_data);

            $newUserId = $this->db->insert_id();

            //data for tbl_lhc_add_info
            $tbl_lhc_add_info_data = array(
                'lhc_user_id' => $newUserId,
                'contact_person' => $this->input->post('contact_person'),
                'description' => trim($this->input->post('bio')),
                'abn' => $this->input->post('abn'),
            );
            //data for tbl_user_group
            $tbl_user_group_data = array(
                'user_id' => $newUserId,
                'group_id' => 2
            );
            //Insert to TBL_LHC_ADD_INFO
            $this->db->insert('tbl_lhc_add_info', $tbl_lhc_add_info_data);
            //Insert to TBL_USER_GROUP
            $this->db->insert('tbl_user_group', $tbl_user_group_data);


            //data for tbl_lhc_industry
            foreach ($industries as $industry_id) {
                $tbl_lhc_industry_data = array('lhc_user_id' => $newUserId,
                    'industry_id' => $industry_id);

                //Insert to TBL_LHC_INDUSTRY
                $this->db->insert('tbl_lhc_industry', $tbl_lhc_industry_data);
            }

            //data for tbl_lhc_doc
            $lhc_doc_id = $this->input->post('doc_id');



            $upload_data = array_slice($upload_data, 1);
            $count = 0;
            foreach ($upload_data as $doc_data) {


                $doc_id = $lhc_doc_id[$count];

                $expiry_date_id = 'expiry_'.$doc_id;
                $input_date = $this->input->post($expiry_date_id);

                //$input_date = $expiry_date[$count];
                if ($input_date == NULL) {
                    $formatted_date = "0000-00-00";
                } else {
                    //change date format as needed
                    $formatted_date = date("Y-m-d", strtotime($input_date));

                }

                $tbl_lhc_doc_data = array('lhc_user_id' => $newUserId,
                    'doc_id' => $doc_id,
                    'expiry_date' => $formatted_date,
                    'doc_name' => $doc_data['file_name']);
                $count++;
                //Insert to TBL_LHC_DOC
                $this->db->insert('tbl_lhc_doc', $tbl_lhc_doc_data);
            }
        }
        //edit
        else {
            $user_id = $this->input->post('hidden_id');
            $newUserId=-1;
            $this->db->where('id', $user_id);
            $this->db->update('tbl_user', $tbl_user_data);

            if(!$this->db->affected_rows()){
                $newUserId=0;
            }

            //data for tbl_lhc_add_info
            $tbl_lhc_add_info_data = array(
                'lhc_user_id' => $user_id,
                'contact_person' => $this->input->post('contact_person'),
                'description' => trim($this->input->post('bio')),
                'abn' => $this->input->post('abn'),
            );
            $this->db->where('lhc_user_id', $user_id);
            $this->db->update('tbl_lhc_add_info', $tbl_lhc_add_info_data);

//            if(!$this->db->affected_rows()){
//                $newUserId=0;
//            }

            //data for tbl_lhc_industry
            $this->db->where('lhc_user_id', $user_id);
            $this->db->delete('tbl_lhc_industry');

//            if(!$this->db->affected_rows()){
//                $newUserId=0;
//            }

            foreach ($industries as $industry_id) {
                $tbl_lhc_industry_data = array('lhc_user_id' => $user_id,
                    'industry_id' => $industry_id);

                //echo implode("<br>", $tbl_lhc_industry_data);
                $this->db->insert('tbl_lhc_industry', $tbl_lhc_industry_data);
            }

            //data for tbl_lhc_doc

            $upload_data = array_slice($upload_data, 1);
            $lhc_doc_id = $this->input->post('doc_id');
            $count = 0;
            //first delete the existing data
            $this->db->where('lhc_user_id', $user_id);
            $this->db->delete('tbl_lhc_doc');

//            if(!$this->db->affected_rows()){
//                $newUserId=0;
//            }

            foreach ($upload_data as $doc_data) {

                $doc_id = $lhc_doc_id[$count];

                $expiry_date_id = 'expiry_'.$doc_id;
                $input_date = $this->input->post($expiry_date_id);




                if ($input_date == NULL) {
                    $formatted_date = "0000-00-00";
                } else {
                    //change date format as needed
                    $formatted_date = date("Y-m-d", strtotime($input_date));
                }


                $tbl_lhc_doc_data = array('lhc_user_id' => $user_id,
                    'doc_id' => $doc_id,
                    'expiry_date' => $formatted_date,
                    'doc_name' => $doc_data['file_name']);
                $count++;


                $this->db->insert('tbl_lhc_doc', $tbl_lhc_doc_data);
            }
        }


        $this->db->trans_complete();


        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;

        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return $newUserId;
        }
    }




}
