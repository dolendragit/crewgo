<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Orderstaff_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->form_validation->CI = &$this;
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function getAllUserDetail($skill, $level, $id)
    {

       

            $this->db->select('tbl_user.*, tbl_staff_skill.staff_user_id, tbl_staff_skill.skill_id, tbl_staff_skill.level_id, tbl_con_level.level_position, tbl_con_level.id as levelid')
            ->from('tbl_user')
            ->join('tbl_user_group', 'tbl_user_group.user_id = tbl_user.id')
            ->join('tbl_staff_skill', 'tbl_staff_skill.staff_user_id = tbl_user.id')
            ->join('tbl_con_level', 'tbl_staff_skill.level_id = tbl_con_level.id')
            ->where('tbl_staff_skill.skill_id', $skill)
            ->where('tbl_user_group.group_id', '4')
            ->where('tbl_user.lhc_user_id', $id);

            if ($level != ""){
                $this->db->having('tbl_con_level.level_position >='. $level);
            }

            //->having('tbl_con_level.level_position >='. $level)
            $this->db->group_by('tbl_user.name');
            $user = $this->db->get();
            //->get();




        return $user->result();
    }

    public function getShiftsbySkillAndLevel($skill, $level, $lhc)
    {

        $query = $this->db->select('*')
            ->from('tbl_job_detail')
            ->join('tbl_job', 'tbl_job_detail.job_id = tbl_job.id')
            ->where('skill_id', $skill)
            ->where('level_id', $level)
            ->where('tbl_job.lhc_user_id', $lhc)
            ->get();

        return $query->result();

    }

    public function jobsWithAssignedStaff($jo_id)
    {
        $query = $this->db->select('*')
            ->from('tbl_job_manual_alert_setting')
            ->join('tbl_user', 'tbl_job_manual_alert_setting.staff_user_id = tbl_user.id')
            ->order_by('order_by', 'ASC')
            ->where('job_detail_id', $jo_id)
            ->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            $data['res'] = $this->getAllUserDetail($this->input->get('sk_id'), $this->input->get('l_id'), $this->ion_auth->user()->row()->id);
            return $data;
        }
    }

    public function assignJobsToStaff($data)
    {
        $this->db->insert('tbl_job_manual_alert_setting', $data);
        return true;
    }

    public function checkPreviousJobs($staff_id, $jo_id)
    {

        $countJob = $this->db->select('*')
            ->from('tbl_job_manual_alert_setting')
            ->where('job_detail_id', $jo_id)
            ->where('staff_user_id', $staff_id)
            ->get();

        //dd($countJob->num_rows());
        if ($countJob->num_rows() > 0) {
            $this->db->delete('tbl_job_manual_alert_setting', array('job_detail_id' => $jo_id));
        }
    }

}
