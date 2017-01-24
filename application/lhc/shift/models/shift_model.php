<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Shift_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }



     public function getShiftsbyLhc($lhc, $s_date)
    {

        $query = $this->db->select('tbl_job_detail.job_id, tbl_job.job_number, tbl_job_detail.start_date, tbl_job_detail.start_time, tbl_job_detail.end_time, tbl_job_staff.total_break_time, tbl_job_detail.total_hour, tbl_job_detail.total_cost, tbl_user.name, tbl_job.job_full_address')
            ->from('tbl_job_detail')
            ->join('tbl_job', 'tbl_job_detail.job_id = tbl_job.id')
            ->join('tbl_job_staff', 'tbl_job_staff.job_detail_id = tbl_job_detail.id', 'left')
            ->join('tbl_user', 'tbl_job_staff.staff_user_id = tbl_user.id', 'left')
            ->where('tbl_job.lhc_user_id', $lhc)
            ->where('tbl_job_detail.start_date', $s_date)
            ->get();

        return $query->result();

    }

   

}
