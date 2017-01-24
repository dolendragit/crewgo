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

    public function getAllRegion()
    {
        $query = $this->db->select('id, name')
            ->from('tbl_country_region')
            ->get();

        return $query->result();
    }

    public function getAllShift($lhc, $filters)
    {

        $this->db->select('tbl_job_detail.id as job_detail_id, tbl_job.job_full_address, tbl_job_detail.job_id, tbl_job_detail.start_time, tbl_job_detail.end_time, tbl_job_detail.total_cost, tbl_job.job_number, tbl_user.name, tbl_job_detail.start_date, tbl_job_detail.hourly_rate, tbl_job_detail.start_time, tbl_job_detail.end_time, tbl_job_staff.total_break_time, tbl_job_detail.shift_status as job_status, tbl_job_staff.id as mystaffjobid, tbl_job_detail.total_hour, tbl_job_detail.total_cost, tbl_job.job_full_address')
            ->from('tbl_job_detail')
            ->join('tbl_job', 'tbl_job_detail.job_id = tbl_job.id')
            ->join('tbl_job_staff', 'tbl_job_staff.job_detail_id = tbl_job_detail.id', 'left')
            ->join('tbl_user', 'tbl_job_staff.staff_user_id = tbl_user.id', 'left')
            ->where('tbl_job.lhc_user_id', $lhc)
            ->group_by('tbl_job_detail.job_id');
        // ->get()->result_array();

        if ($filters['jo_id'] != "") {
            $this->db->where('tbl_job.job_number', $filters['jo_id']);
        }

        if ($filters['jo_from'] != "") {
            $this->db->where('tbl_job_detail.start_time  >=', $filters['jo_from'] . " 00:00:00");
        }

        if ($filters['jo_to'] != "") {
            $this->db->where('tbl_job_detail.end_time <=', $filters['jo_to'] . " 23:59:00");
        }

        if ($filters['st_na'] != "") {
            $this->db->like('tbl_user.name', $filters['st_na']);
        }

        if ($filters['jo_sk'] != "") {
            $this->db->where('tbl_job_detail.skill_id', $filters['jo_sk']);
        }

        if ($filters['jo_st'] != "") {

            if ($filters['jo_st'] == 0) {
                $this->db->where('tbl_job_detail.shift_status', 3);
                $this->db->or_where('tbl_job_detail.shift_status', 4);
            }

            $this->db->where('tbl_job_detail.shift_status', $filters['jo_st']);
        }

        if ($filters['ve'] != "") {
            $this->db->like('tbl_job.job_full_address', $filters['ve']);
        }

        $query = $this->db->get()->result_array();

        foreach ($query as $key => $val) {
            $query[$key]['shift'] = $this->db->where('job_id', $val['job_id'])->select('job_id, start_time, end_time')->get('tbl_job_detail')->result_array();
            //$query[$key]['breaks'] = $this->get_total_break(36, 36);
            foreach ($query[$key]['shift'] as $hr => $allval) {
                $query[$key]['totalhr'][$hr] = (strtotime($allval['end_time']) - strtotime($allval['start_time'])) / 3600;
            }

            $query[$key]['break'] = $this->get_total_break($val['job_detail_id'], $val['mystaffjobid'])['total_break_time'] / 3600;

        }

        return $query;
    }

    public function get_job_staff_breaks($jobDetailId, $jobStaffId, $takenBreaksOnly = false)
    {
        $availableBreaks = $takenBreaks = [];
        if ($takenBreaksOnly == false) {
            $availableBreaks = $this->db->select('id, unix_timestamp(start_time) as start_time, unix_timestamp(end_time) as end_time')
                ->where(array(
                    'job_detail_id' => $jobDetailId,
                ))
                ->get('tbl_job_detail_break')
                ->result();
        }

        $takenBreaks = $this->db->select("id,unix_timestamp(in_time) as in_time,unix_timestamp(out_time) as out_time,unix_timestamp(gps_in_time) as gps_in_time,unix_timestamp(gps_out_time) as gps_out_time,is_break_complete, is_approved", false)
            ->where(array(
                'job_staff_id' => $jobStaffId,
            ))
            ->get('tbl_job_staff_break')
            ->result();
        return array(
            'available' => $availableBreaks,
            'taken'     => $takenBreaks,
        );
    }

    public function get_total_break($jobDetailId, $jobStaffId)
    {
        $totalBreakTime          = false;
        $containsIncompleteBreak = false;
        $takenBreak              = $this->get_job_staff_breaks($jobDetailId, $jobStaffId, true);
        $takenBreak              = $takenBreak['taken'];
        if ($takenBreak) {
            foreach ($takenBreak as $break) {
                if ($break->is_break_complete == '0') {
                    $containsIncompleteBreak = true;
                    continue;
                }

                $inDateTime  = $break->gps_in_time;
                $outDateTime = $break->gps_out_time;
                if ($totalBreakTime) {
                    $totalBreakTime = $totalBreakTime + ($outDateTime - $inDateTime);
                } else {
                    $totalBreakTime = $outDateTime - $inDateTime;
                }

            }
        }

        return array(
            'contains_incomplete_break' => $containsIncompleteBreak,
            'total_break_time'          => $totalBreakTime,
        );

    }

}
