<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staff_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function specifcIndustryForLhcStaff($user)
    {
        $query = $this->db->select('tbl_con_skill.id, tbl_con_skill.name')
            ->from('tbl_lhc_industry')
            ->join('tbl_con_industry_skill', 'tbl_lhc_industry.industry_id = tbl_con_industry_skill.industry_id')
            ->join('tbl_con_skill', 'tbl_con_skill.id = tbl_con_industry_skill.skill_id')
            ->where('tbl_lhc_industry.lhc_user_id', $user)
            ->distinct()
            ->get();

        return $query->result();

    }

    public function subSkills($id)
    {

        $query = $this->db->select('tbl_con_level.name, tbl_con_level.id')
            ->from('tbl_con_level')
            ->join('tbl_con_skill_level', 'tbl_con_skill_level.level_id = tbl_con_level.id')
            ->where('tbl_con_skill_level.skill_id', $id)
            ->get();

        return $query->result();
    }

    public function allSuburb()
    {
        $this->db->group_by("suburb");
        $this->db->order_by("suburb", "asc");
        $query = $this->db->get('tbl_postcodes_geo', 100);
        return $query->result();
    }

    public function saveUserDetail($user, $id)
    {

        if ($id != "") {
            $this->db->where('id', $id);
            $this->db->update('tbl_user', $user);
            return $id;
        }

        try {
            $query = $this->db->insert('tbl_user', $user);
            if (!$query) {
                throw new Exception("Data error", 1);
            }
            $user_id = $this->db->insert_id();
            $data    = array('user_id' => $user_id, 'group_id' => '4');
            $this->db->insert('tbl_user_group', $data);

            //adding data to association as well
            $staffAssociation = array('lhc_user_id' => $user['lhc_user_id'], 'staff_user_id' => $user_id, 'requested_date' => date('Y-m-d'), 'action_date' => '', 'status' => 1);
            $this->db->insert('tbl_lhc_staff_association', $staffAssociation);

        } catch (\Exception $e) {
            return false;
        }
        return $user_id;

    }

    public function saveStaffInformation($id, $info, $currentEdit)
    {
        if ($id == $currentEdit) {
            $this->db->where('staff_user_id', $id);
            $this->db->update('tbl_staff_add_info', $info);
            return true;
        }

        try {
            $query = $this->db->insert('tbl_staff_add_info', $info);
            if (!$query) {
                throw new Exception("Error Processing Request", 1);
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function staff_availability_store($daysAndAvailability, $currentEdit)
    {

        if ($currentEdit != 0) {
            $this->db->delete('tbl_staff_availability', array('staff_user_id' => $currentEdit));
            // $this->db->insert('tbl_staff_availability', $daysAndAvailability);
        }
        $this->db->insert('tbl_staff_availability', $daysAndAvailability);
    }

    public function staff_skill_store($id, $datas)
    {
        $skill = $datas['maincategory'];
        $level = $datas['subskill'];

        foreach ($skill as $key => $val) {
            $newSkills = array('skill_id' => $val, 'level_id' => $level[$key], 'staff_user_id' => $id);
            $this->db->insert('tbl_staff_skill', $newSkills);
        }
    }

    public function saveQualification($title, $expiry, $names, $userId)
    {
        foreach ($title as $key => $value) {
            $prepare = array('staff_user_id' => $userId, 'name' => $value, 'document_name' => $names[$key], 'expiry_date' => $expiry[$key]);
            $this->db->insert('tbl_staff_qualification', $prepare);
        }
    }

    public function saveTraining($title, $expiry, $names, $userId)
    {
        foreach ($title as $key => $value) {
            $prepare = array('staff_user_id' => $userId, 'name' => $value, 'document_name' => $names[$key], 'expiry_date' => $expiry[$key]);
            $this->db->insert('tbl_staff_training', $prepare);
        }
    }

    public function saveUserArea($id, $datas, $currentEdit)
    {

        if ($id == $currentEdit) {
            $this->db->delete('tbl_staff_preferred_suburb', array('staff_user_id' => $currentEdit));
        }

        foreach ($datas['areas'] as $val) {
            $data = array('staff_user_id' => $id, 'postcode_id' => $val, 'near_by' => $datas['nearby']);
            $this->db->insert('tbl_staff_preferred_suburb', $data);
        }
    }

    public function getAllUserDetail($id)
    {
        $user = $this->db->select('tbl_user.*, tbl_staff_skill.staff_user_id, tbl_staff_skill.skill_id, tbl_staff_skill.level_id')
            ->from('tbl_user')
            ->join('tbl_user_group', 'tbl_user_group.user_id = tbl_user.id')
            ->join('tbl_staff_skill', 'tbl_staff_skill.staff_user_id = tbl_user.id', 'left')
            ->where('tbl_user_group.group_id', '4')
            ->where('tbl_user.lhc_user_id', $id)
            ->group_by('tbl_user.email')
            ->get();

        return $user->result();

        /*$this->db->select('tbl_user.id, tbl_user.name, tbl_user.email, tbl_user.phone_number, tbl_user.lhc_user_id, tbl_user.created_on, tbl_user.active')
    ->from('tbl_user')
    ->join('tbl_user_group', 'tbl_user_group.user_id = tbl_user.id')
    ->where('tbl_user_group.group_id', '4')
    ->where('tbl_user.lhc_user_id', $id);

    $user = $this->db->get()->result_array();

    foreach($user as $key => $val){
    $user[$key]['skillset'] = $this->db->where('staff_user_id', $val['id'])
    ->select('tbl_staff_skill.skill_id, tbl_staff_skill.level_id, tbl_con_skill.name as skillname, tbl_con_level.name as levelname')
    ->join('tbl_con_skill', 'tbl_con_skill.id = tbl_staff_skill.skill_id')
    ->join('tbl_con_level', 'tbl_con_level.id = tbl_staff_skill.level_id')
    ->get('tbl_staff_skill')
    ->result_array();
    }

    return $user;*/
    }

    public function getAllUserDetailWithMultipleSkillSet($id)
    {
        $this->db->select('tbl_user.id, tbl_user.name, tbl_user.email, tbl_user.phone_number, tbl_user.lhc_user_id, tbl_user.created_on, tbl_user.active')
            ->from('tbl_user')
            ->join('tbl_user_group', 'tbl_user_group.user_id = tbl_user.id')
            ->where('tbl_user_group.group_id', '4')
            ->where('tbl_user.lhc_user_id', $id);

        $user = $this->db->get()->result_array();

        foreach ($user as $key => $val) {
            $user[$key]['skillset'] = $this->db->where('staff_user_id', $val['id'])
                ->select('tbl_staff_skill.skill_id, tbl_staff_skill.level_id, tbl_con_skill.name as skillname, tbl_con_level.name as levelname')
                ->join('tbl_con_skill', 'tbl_con_skill.id = tbl_staff_skill.skill_id')
                ->join('tbl_con_level', 'tbl_con_level.id = tbl_staff_skill.level_id')
                ->get('tbl_staff_skill')
                ->result_array();
        }

        return $user;
    }

    public function createInduction($id, $datas, $currentEdit)
    {
        if ($currentEdit != 0) {
            $data = array('induction_detail_id' => $datas['induction']);
            $this->db->where('staff_user_id', $id);
            $this->db->update('tbl_staff_induction', $data);
            return true;
        }
        $data = array('induction_detail_id' => $datas['induction'], 'entered_date' => date('Y-m-d'), 'staff_user_id' => $id);
        $this->db->insert('tbl_staff_induction', $data);
    }

    public function getUserById($id)
    {
        $user = $this->db->select('*')
            ->from('tbl_user')
            ->where('id', $id)
            ->get();

        return $user->row();
    }

    public function getInfoById($id)
    {
        $info = $this->db->select('*')
            ->from('tbl_staff_add_info')
            ->where('staff_user_id', $id)
            ->get();

        return $info->row();
    }

    public function getAvailabilityById($id)
    {
        $available = $this->db->select('*')
            ->from('tbl_staff_availability')
            ->where('staff_user_id', $id)
            ->get();

        return $available->row();
    }

    public function getSkillById($id)
    {
        $skill = $this->db->select('tbl_con_skill.name as skillname, tbl_staff_skill.*, tbl_con_level.name as levelname')
            ->from('tbl_staff_skill')
            ->join('tbl_con_skill', 'tbl_con_skill.id = tbl_staff_skill.skill_id')
            ->join('tbl_con_level', 'tbl_con_level.id = tbl_staff_skill.level_id')
            ->where('staff_user_id', $id)
            ->get();

        return $skill->result();
    }

    public function getSuburbById($id)
    {
        $suburb = $this->db->select('postcode_id')
            ->from('tbl_staff_preferred_suburb')
            ->where('tbl_staff_preferred_suburb.staff_user_id', $id)
            ->get();

        return $suburb->result();
    }

    public function getSelectedInduction($id)
    {
        $ind = $this->db->select('induction_detail_id')
            ->from('tbl_staff_induction')
            ->where('staff_user_id', $id)
            ->get();

        return $ind->row();
    }

    public function findUserByEmail($email)
    {
        $userDetail = $this->db->select('*')
            ->from('tbl_user')
            ->where('email', $email)
            ->get();

        return $userDetail->row();
    }

    public function addNewSkillViaEdit($data)
    {

        $findPreviousSkill = $this->db->select('*')
            ->from('tbl_staff_skill')
            ->where('staff_user_id', $data['staff_user_id'])
            ->where('skill_id', $data['skill_id'])
            ->where('level_id', $data['level_id'])
            ->get();

        if (count($findPreviousSkill->row()) == 0) {
            $this->db->insert('tbl_staff_skill', $data);
            return true;
        } else {
            //return back
            return false;
        }

    }

    public function getSelectedSkillDetail($id)
    {
        $details = $this->db->select('*')
            ->from('tbl_staff_skill')
            ->where('id', $id)
            ->get();

        return $details->row();
    }

    public function updateSkillViaEdit($data, $userid, $skillid)
    {
        $skill = $this->db->select('*')
            ->from('tbl_staff_skill')
            ->where('skill_id', $data['skill_id'])
            ->where('level_id', $data['level_id'])
            ->where('staff_user_id', $userid)
            ->get();

        $this->db->where('id', $skillid);
        $this->db->update('tbl_staff_skill', $data);

        return true;

    }

    public function getQualificationById($id)
    {
        $qualification = $this->db->select('*')
            ->from('tbl_staff_qualification')
            ->where('staff_user_id', $id)
            ->get();

        return $qualification->result();
    }

    public function addNewQualification($data)
    {
        $this->db->insert('tbl_staff_qualification', $data);
        return true;
    }

    public function getSingleQualificationById($id)
    {
        $qualification = $this->db->select('*')
            ->from('tbl_staff_qualification')
            ->where('id', $id)
            ->get();

        return $qualification->row();
    }

    public function updateStaffQualification($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_staff_qualification', $data);
        return true;
    }

    public function getTrainingById($id)
    {
        $training = $this->db->select('*')
            ->from('tbl_staff_training')
            ->where('staff_user_id', $id)
            ->get();

        return $training->result();
    }

    public function addNewTraining($data)
    {
        $this->db->insert('tbl_staff_training', $data);
        return true;
    }

    public function getSingleTrainingById($id)
    {
        $training = $this->db->select('*')
            ->from('tbl_staff_training')
            ->where('id', $id)
            ->get();

        return $training->row();
    }

    public function updateStaffTraining($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_staff_training', $data);
        return true;
    }

}
