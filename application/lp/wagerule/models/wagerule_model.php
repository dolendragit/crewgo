<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Wagerule_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCountries()
    {
        $query = $this->db->select('id, name')
            ->from('tbl_country')
            ->get();

        return $query->result();
    }

    public function getStates($country)
    {
        $query = $this->db->select('id, country_id, name')
            ->from('tbl_country_region')
            ->where('country_id', $country)
            ->get();

        return $query->result();
    }

    public function getTypesOfShift()
    {
        $query = $this->db->select('id, name, time_from, time_to')
            ->from('tbl_wr_shift_type')
            ->get();

        return $query->result();
    }

    public function getCalendar($whichDate)
    {
        $query = $this->db->select('date')
            ->from('tbl_wr_calendar')
            ->where('year', $whichDate)
            ->get();
        return $query->result();
    }

    public function specialDayRules($filters)
    {

        $query = $this->db->select('tbl_wr_shift_type.name as shiftname, tbl_wr_shift_type.time_from as timefrom, tbl_wr_shift_type.time_to as timeto, tbl_wr_calendar.date as calendardate, tbl_wr_special_day.*')
            ->from('tbl_wr_special_day')
            ->join('tbl_wr_calendar', 'tbl_wr_calendar.id = tbl_wr_special_day.calendar_id')
            ->join('tbl_wr_shift_type', 'tbl_wr_shift_type.id = tbl_wr_special_day.shift_type_id')
            ->where('tbl_wr_special_day.country_id', $filters['country'])
            ->where('tbl_wr_special_day.country_region_id', $filters['state']);

        if (isset($filters['skill']) && $filters['skill'] != "") {
            $this->db->where('tbl_wr_special_day.skill_id', $filters['skill']);
        }

        if (isset($filters['subskill'])) {
            $this->db->where('tbl_wr_special_day.level_id', $filters['subskill']);
        }

        $query = $this->db->get();

        return $query->result();
    }

    public function saveSpecialRules($datas)
    {

        foreach ($datas['ruleid'] as $key => $val) {

            $findIfRuleExists = $this->db->select('*')
                ->from('tbl_wr_lp_special_day')
                ->where('rule_id', $val)
                ->get();

            if ($findIfRuleExists->num_rows() == 1) {
                $this->db->where('rule_id', $val);
                $this->db->delete('tbl_wr_lp_special_day');
            }

            $data = array('rule_id' => $val, 'pay_rate' => $datas['manual'][$key], 'pay_time' => $datas['times'][$key], 'shift_type_id' => $datas['shifts'][$key], 'created_at' => date('Y-m-d'));
            $this->db->insert('tbl_wr_lp_special_day', $data);
        }

        return true;

    }

    public function getalreadyExistingSpecialDayRule()
    {
        $query = $this->db->select('tbl_wr_special_day.time_from,
                                    tbl_wr_special_day.time_to,
                                    tbl_wr_lp_special_day.shift_type_id,
                                    tbl_wr_lp_special_day.pay_rate as newpay_rates,
                                    tbl_wr_lp_special_day.pay_time as newpay_times'
        )
            ->from('tbl_wr_special_day')
            ->join('tbl_wr_lp_special_day', 'tbl_wr_special_day.id = tbl_wr_lp_special_day.rule_id')
            ->get();

        return $query->result();

    }

    public function getOvertimeRule($filters)
    {

        $this->db->select('tbl_wr_shift_type.name as shiftname,tbl_wr_overtime.*, tbl_wr_interval.name as intervalname, tbl_wr_day.name as dayname')
            ->from('tbl_wr_overtime')
            ->join('tbl_wr_shift_type', 'tbl_wr_shift_type.id = tbl_wr_overtime.shift_type_id')
            ->join('tbl_wr_interval', 'tbl_wr_interval.id = tbl_wr_overtime.interval_id')
            ->join('tbl_wr_day', 'tbl_wr_day.id = tbl_wr_overtime.day_id', 'left')
            ->where('tbl_wr_overtime.country_id', $filters['country'])
            ->where('tbl_wr_overtime.country_region_id', $filters['state']);

        if (isset($filters['skill']) && $filters['skill'] != "") {
            $this->db->where('tbl_wr_overtime.skill_id', $filters['skill']);
        }

        if (isset($filters['subskill'])) {
            $this->db->where('tbl_wr_overtime.level_id', $filters['subskill']);
        }

        $query = $this->db->get();

        return $query->result();
    }

    public function saveOverTimeRule($datas)
    {
        foreach ($datas['ruleidone'] as $key => $val) {

            $findIfRuleExists = $this->db->select('*')
                ->from('tbl_wr_lp_overtime')
                ->where('rule_id', $val)
                ->get();

            if ($findIfRuleExists->num_rows() == 1) {
                $this->db->where('rule_id', $val);
                $this->db->delete('tbl_wr_lp_overtime');
            }

            $data = array('rule_id' => $val, 'pay_rate' => $datas['manualone'][$key], 'pay_time' => $datas['timesone'][$key], 'shift_type_id' => $datas['shiftsone'][$key], 'created_at' => date('Y-m-d H:i:s'));
            $this->db->insert('tbl_wr_lp_overtime', $data);
        }

        return true;
    }

    public function getalreadyExistingOverTimeRule()
    {
        $query = $this->db->select('tbl_wr_lp_overtime.shift_type_id,
                                    tbl_wr_lp_overtime.pay_rate as newpay_rates,
                                    tbl_wr_lp_overtime.pay_time as newpay_times'
        )
            ->from('tbl_wr_overtime')
            ->join('tbl_wr_lp_overtime', 'tbl_wr_overtime.id = tbl_wr_lp_overtime.rule_id')
            ->get();

        return $query->result();

    }

    public function getBreakRules($filters)
    {
        $this->db->select('tbl_wr_bk_not_taken.*, tbl_wr_day.name as mydayname, tbl_wr_shift_type.time_from, tbl_wr_shift_type.time_to')
            ->from('tbl_wr_bk_not_taken')
            ->join('tbl_wr_day', 'tbl_wr_day.id = tbl_wr_bk_not_taken.day_id')
            ->join('tbl_wr_shift_type', 'tbl_wr_shift_type.id = tbl_wr_bk_not_taken.shift_type_id')
            ->where('tbl_wr_bk_not_taken.country_id', $filters['country'])
            ->where('tbl_wr_bk_not_taken.country_region_id', $filters['state']);

        if (isset($filters['skill']) && $filters['skill'] != "") {
            $this->db->where('tbl_wr_bk_not_taken.skill_id', $filters['skill']);
        }

        if (isset($filters['subskill'])) {
            $this->db->where('tbl_wr_bk_not_taken.level_id', $filters['subskill']);
        }

        $query = $this->db->get();

        return $query->result();

    }

    public function saveBreakRule($datas)
    {
        foreach ($datas['ruleidtwo'] as $key => $val) {

            $findIfRuleExists = $this->db->select('*')
                ->from('tbl_wr_lp_bk_not_taken')
                ->where('rule_id', $val)
                ->get();

            if ($findIfRuleExists->num_rows() == 1) {
                $this->db->where('rule_id', $val);
                $this->db->delete('tbl_wr_lp_bk_not_taken');
            }

            $data = array('rule_id' => $val, 'pay_rate' => $datas['manualtwo'][$key], 'created_at' => date('Y-m-d H:i:s'));
            $this->db->insert('tbl_wr_lp_bk_not_taken', $data);
        }

        return true;
    }

    public function getalreadyExistingBreakRule()
    {
        $query = $this->db->select('tbl_wr_lp_bk_not_taken.pay_rate as newpay_rates')
            ->from('tbl_wr_bk_not_taken')
            ->join('tbl_wr_lp_bk_not_taken', 'tbl_wr_lp_bk_not_taken.rule_id = tbl_wr_bk_not_taken.id')
            ->get();

        return $query->result();
    }

    public function getWorkHours($filters)
    {
        $query = $this->db->select('tbl_wr_work_hour.*, tbl_wr_day.name as myday, tbl_wr_shift_type.name as myshiftname, tbl_wr_shift_type.time_from, tbl_wr_shift_type.time_to')
            ->from('tbl_wr_work_hour')
            ->join('tbl_wr_day', 'tbl_wr_day.id = tbl_wr_work_hour.day_id')
            ->join('tbl_wr_shift_type', 'tbl_wr_shift_type.id = tbl_wr_work_hour.shift_type_id')
            ->where('tbl_wr_work_hour.country_id', $filters['country'])
            ->where('tbl_wr_work_hour.country_region_id', $filters['state']);

        if (isset($filters['skill']) && $filters['skill'] != "") {
            $this->db->where('tbl_wr_work_hour.skill_id', $filters['skill']);
        }

        if (isset($filters['subskill'])) {
            $this->db->where('tbl_wr_work_hour.level_id', $filters['subskill']);
        }

        $query = $this->db->get();

        return $query->result();
    }

    public function saveWorkHourRules($datas)
    {
        foreach ($datas['ruleidthree'] as $key => $val) {

            $findIfRuleExists = $this->db->select('*')
                ->from('tbl_wr_lp_work_hour')
                ->where('rule_id', $val)
                ->get();

            if ($findIfRuleExists->num_rows() == 1) {
                $this->db->where('rule_id', $val);
                $this->db->delete('tbl_wr_lp_work_hour');
            }

            $data = array('rule_id' => $val, 'pay_rate' => $datas['manualthree'][$key], 'created_at' => date('Y-m-d H:i:s'));
            $this->db->insert('tbl_wr_lp_work_hour', $data);
        }

        return true;
    }

    public function getalreadyExistingWorkHrRule()
    {
        $query = $this->db->select('tbl_wr_lp_work_hour.pay_rate as newpay_rates')
            ->from('tbl_wr_work_hour')
            ->join('tbl_wr_lp_work_hour', 'tbl_wr_lp_work_hour.rule_id = tbl_wr_work_hour.id')
            ->get();

        return $query->result();
    }
}
