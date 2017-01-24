<?php

/**
 * Created by PhpStorm.
 * User: rabin
 * Date: 11/30/16
 * Time: 10:20 AM
 */
class Staff_model extends MY_Model
{
    const TOTAL_DAY_SECONDS = 24 * 60 * 60;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if user is logged in using google
     * @param string $google_id
     * @param string $email
     * @return mixed
     */
    public function check_google_login($google_id = "", $email = "")
    {
        $res = $this->db->select('u.id,u.password')
            ->from('tbl_user as u')
            ->join('tbl_user_group as ug', 'u.id = ug.user_id', 'inner')
            ->where("(`u`.`google_id` =  '{$google_id}' OR `u`.`email` =  '{$email}') AND `ug`.`group_id` =  " . STAFF)
            ->get()->row();
        return $res;
    }

    /**
     * Get All skills list
     * @return array
     */
    public function get_skills()
    {
        $this->db->select('s.id,s.name')->from('tbl_con_skill as s');
        $this->db->join('tbl_con_skill_level as sl', 'sl.skill_id = s.id', 'inner');
        $this->db->group_by('s.id');
        $this->db->where('s.status', '1');
        $res = $this->db->get()->result_array();
        $_this = $this;
        if (count($res)) {
            $res = array_map(function ($data) use (&$_this) {
                $_this->db->select('l.id, l.name')->from('tbl_con_level as l');
                $_this->db->join('tbl_con_skill_level as sl', 'sl.level_id = l.id');
                $_this->db->where('l.status', '1');
                $_this->db->where('sl.skill_id', $data['id']);
                $levels = $_this->db->get()->result_array();
                $data['levels'] = $levels;
                return $data;
            }, $res);
        }
        return ($res);
    }

    /**
     * Check if email is used as staff
     * @param $email
     * @return mixed
     */
    public function check_if_staff($email)
    {
        $this->db->select('u.id,u.name,u.email,u.full_address,u.profile_image')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('ug.group_id', STAFF);
        $this->db->where('u.email', $email);
        return $this->db->get()->row();
    }

    /**
     * @param array $data Insert data on staff detail table
     * @param bool|FALSE $staffId
     */
    public function add_staff_detail($data = array(), $staffId)
    {
        $this->table_name = 'tbl_staff_add_info';
        $this->updateOrInsert($this->table_name, $data, array('staff_user_id' => $staffId));
    }

    /**
     * Get Job list
     * @param int $userId User Id
     */
    public function get_job_alerts($userId)
    {
        $where = array(
            'ja.staff_user_id' => $userId,
            'ja.status' => 0,
        );
        $upcomingEventDateTime = $this->get_upcoming_engaged_date_time($userId);
        $this->db->select("ja.id as job_alert_id, j.id as job_id, DATE_FORMAT(start_time, '%Y-%m-%d') AS start_date, unix_timestamp(jd.start_time) AS start_time,unix_timestamp(jd.end_time) AS end_time,jai.full_address, jai.street, CONCAT_WS(' ',pg.suburb,pg.state,pg.postcode) AS geo_detail,j.has_peak_price,jd.total_cost, TRUNCATE((jd.total_cost/jd.required_number),2) as actual_cost, cs.name as skill, cl.name as level", FALSE)
            ->from('tbl_job_alert as ja')
            ->join('tbl_job_detail as jd', 'jd.id = ja.job_detail_id', 'inner')
            ->join('tbl_job as j', 'j.id = jd.job_id', 'inner')
            ->join('tbl_job_add_info as jai', 'jai.job_id = j.id', 'left')
            ->join('tbl_postcodes_geo as pg', 'pg.id = jai.postcode_id', 'left')
            ->join('tbl_con_skill as cs', 'cs.id = jd.skill_id', 'left')
            ->join('tbl_con_level as cl', 'cl.id = jd.level_id', 'left');

        if (count($upcomingEventDateTime)) {
            foreach ($upcomingEventDateTime as $dateTime) {
                $this->db->where("(unix_timestamp(start_time) NOT BETWEEN " . $dateTime->start_time . " AND " . $dateTime->end_time . ")");
                $this->db->where("(unix_timestamp(end_time) NOT BETWEEN " . $dateTime->start_time . " AND " . $dateTime->end_time . ")");
            }
        }
        $this->db->where($where);
        //filter job with past date time
        $this->db->where("unix_timestamp(start_time) > unix_timestamp()");
        $this->db->order_by('jd.start_time', 'desc');
        $jobAlerts = $this->db->get()->result();
        return $jobAlerts;
    }

    /**
     * Get Job Details for Staff
     * @param $userId int User ID
     * @param $jobAlertId int Job Alert ID
     * @return mixed
     */
    public function get_job_alert_detail($userId, $jobAlertId)
    {
        $jobAlertDetail = $this->db->select("ja.id AS job_alert_id,j.id AS job_id, unix_timestamp(jd.start_time) AS start_time, unix_timestamp(jd.end_time) AS end_time, IF(ISNULL(jai.full_address),'',jai.full_address) AS full_address, if(ISNULL(jai.street),'',jai.street) AS street, CONCAT_WS(' ', pg.suburb, pg.state, pg.postcode) AS geo_detail,
                                              IF(ISNULL(j.meeting_full_address),'',j.meeting_full_address) AS meeting_full_address, IF(ISNULL(j.meeting_street),'',j.meeting_street) as meeting_street, CONCAT_WS(' ', pgm.suburb, pgm.state, pgm.postcode) AS meeting_geo_detail, jd.has_peak_price, IF(jd.has_peak_price > 0, jd.peak_price, jd.total_cost) AS total_cost, TRUNCATE((IF(jd.has_peak_price > 0, jd.peak_price, jd.total_cost) / jd.required_number), 2)   AS actual_cost,
                                              cs.name AS skill, cl.name AS level, IF(ISNULL(jd.qualification),'',jd.qualification) AS qualification, IF(ISNULL(ji.induction_number),'',ji.induction_number) AS induction_number, IF(ISNULL(j.description),'',j.description) AS job_description, IF(ISNULL(jd.total_hour),'',jd.total_hour) AS total_hour", FALSE)
            ->from('tbl_job_alert as ja')
            ->join('tbl_job_detail as jd', 'jd.id = ja.job_detail_id', 'inner')
            ->join('tbl_job as j', 'j.id = jd.job_id', 'inner')
            ->join('tbl_job_add_info as jai', 'jai.job_id = j.id', 'inner')
            ->join('tbl_postcodes_geo as pg', 'pg.id = jai.postcode_id', 'left')
            ->join('tbl_postcodes_geo as pgm', 'pgm.id = j.meeting_postcode_id', 'left')
            ->join('tbl_con_skill as cs', 'cs.id = jd.skill_id', 'left')
            ->join('tbl_con_level as cl', 'cl.id = jd.level_id', 'left')
            ->join('tbl_job_induction as ji', 'ji.job_id = j.id', 'left')
            ->where(array(
                'ja.staff_user_id' => $userId,
                'ja.status' => 0,
                'ja.id' => $jobAlertId
            ))->get()->row();
        if (count($jobAlertDetail))
            return $jobAlertDetail;
        return FALSE;
    }

    /**
     * @param $userId int User Id
     * @param $post mixed Post Data
     * @return bool
     */
    public function job_alert_response($userId, $post)
    {
        //return variables used from common model
        $jobAlertId = $post['job_alert_id'];
        // 0:pending, 1=accepted ,2=ignored ,3=make unavailable, 4=expired, 5=position filled, 6=staff already engaged
        $jobAlertStatus = 0;
        $returnCommonCode = '0000';

        $where = array(
            'ja.id' => $jobAlertId,
            'ja.staff_user_id' => $userId,
            'ja.status' => 0,
        );

        // check if job alert is available and on pending(0) status
        $jobDetail = $this->db->select('ja.job_detail_id, jd.start_time, jd.end_time')
            ->from('tbl_job_alert as ja')
            ->join('tbl_job_detail as jd', 'ja.job_detail_id = jd.id', 'inner')
            ->where($where)
            ->get()->row();

        // job alert status not pending
        if (!$jobDetail) {
            $returnCommonCode = '0014';
        } // check if job time has expired
        elseif ($this->has_job_expired($jobDetail->job_detail_id)) {
            $jobAlertStatus = 4;
            $returnCommonCode = '0017';
        } // check if job positions are full
        elseif ($this->has_job_position_already_filled($jobDetail->job_detail_id)) {
            $jobAlertStatus = 5;
            $returnCommonCode = '0016';
        } // check if Staff already engaged on other job
        elseif ($this->has_staff_already_engaged($userId, $jobDetail->job_detail_id)) {
            $jobAlertStatus = 6;
            $returnCommonCode = '0015';
        } else {
            $returnCommonCode = '0001';
            switch ($post['response_flag']) {
                case 1:
                    //accepted
                    //insert data into tbl_job_staff
                    $data = array(
                        'job_detail_id' => @$jobDetail->job_detail_id,
                        'staff_user_id' => $userId,
                        'accepted_date' => date('Y-m-d H:i:s'),
                        'job_alert_id' => $jobAlertId
                    );
                    $this->updateOrInsert('tbl_job_staff', $data, array('job_alert_id' => $jobAlertId));
                    $jobAlertStatus = 1;
                    break;
                case 2:
                    //ignored
                    //insert data into tbl_staff_add_info
                    $data = array(
                        'staff_user_id' => $userId,
                        'comm_unavailability_from' => $jobDetail->start_time,
                        'comm_unavailability_to' => $jobDetail->end_time,
                        'updated_date' => date('Y-m-d H:i:s'),
                    );
                    $this->updateOrInsert('tbl_staff_add_info', $data, array('staff_user_id' => $userId));
                    $jobAlertStatus = 2;
                    break;
                case 3:
                    //make unavailable
                    $jobAlertStatus = 3;
                    break;
                default:
                    $this->db->trans_rollback();
                    $returnCommonCode = '0000';
                    break;
            }
        }


        if ($jobAlertStatus) {
            $this->db->trans_begin();
            //update job alert
            $data = array(
                'status' => $jobAlertStatus,
                'action_date' => date('Y-m-d H:i:s'),
            );
            $this->db->where('id', $jobAlertId)->update('tbl_job_alert', $data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $returnCommonCode = '0000';
            } else {
                $this->db->trans_commit();
            }
        }
        return $returnCommonCode;
    }

    /**
     * get_accepted_jobs_list
     * @param $userId int Staff User Id
     * @return mixed
     */
    public function get_accepted_jobs_list($userId)
    {
        $this->db->select("js.id as job_staff_id, j.id as job_id, jd.id as job_detail_id, DATE_FORMAT(start_time, '%Y-%m-%d') AS start_date, unix_timestamp(jd.start_time) as start_time, unix_timestamp(jd.end_time) as end_time , jai.full_address, jai.street, CONCAT_WS(' ', pg.suburb, pg.state, pg.postcode) AS geo_detail, cs.name AS skill, cl.name AS level, jd.shift_status", FALSE)
            ->from('tbl_job_staff as js')
            ->join('tbl_job_detail as jd', 'jd.id = js.job_detail_id', 'inner')
            ->join('tbl_job as j', 'j.id = jd.job_id', 'inner')
            ->join('tbl_job_add_info as jai', 'jai.job_id = j.id', 'inner')
            ->join('tbl_postcodes_geo as pg', 'pg.id = jai.postcode_id', 'left')
            ->join('tbl_con_skill as cs', 'cs.id = jd.skill_id', 'left')
            ->join('tbl_con_level as cl', 'cl.id = jd.level_id', 'left')
            ->where(array(
                'js.staff_user_id' => $userId,
            ))
//            ->order_by('jd.start_date', 'desc')
            ->order_by('jd.start_time', 'desc');

        $jobAlerts = $this->db->get()->result();
        return $jobAlerts;
    }

    /**
     * Get already engaged Date Time for Staff
     * @param $staffUserId int Staff User Id
     */
    private function get_upcoming_engaged_date_time($staffUserId)
    {
        $jobs = $this->db->select("unix_timestamp(jd.start_time) as start_time, unix_timestamp(jd.end_time) as end_time", false)
            ->from('tbl_job_staff as js')
            ->join('tbl_job_detail as jd', 'js.job_detail_id = jd.id', 'inner')
            ->where("(js.staff_user_id = " . $staffUserId . ") AND (unix_timestamp(jd.start_time) > unix_timestamp()) AND " . "jd.is_completed = 0")
            ->get()->result();
        return $jobs;
    }

    /**
     * Has job expired
     * @param $jobDetailId int Job Detail Id
     * @return bool
     */
    private function has_job_expired($jobDetailId)
    {
        $isJobDetailExpired = $this->db->where("id = {$jobDetailId} AND (unix_timestamp(start_time) > unix_timestamp())")
            ->get('tbl_job_detail')->num_rows();

        return ($isJobDetailExpired) ? FALSE : TRUE;
    }

    /**
     * Has job ended
     * @param $jobDetailId int Job Detail Id
     * @return bool
     */
    private function has_job_ended($jobDetailId)
    {
        $hasJobDetailEnded = $this->db->where("id = {$jobDetailId} AND (unix_timestamp(end_time) < unix_timestamp())")
            ->get('tbl_job_detail')
            ->num_rows();

        return ($hasJobDetailEnded) ? true : false;
    }

    /**
     * has_job_position_already_filled
     * @param $jobDetailId int Job Detail Id
     * @return bool
     */
    private function has_job_position_already_filled($jobDetailId)
    {
        $jobDetail = $this->db->select('required_number')->where('id', $jobDetailId)->get('tbl_job_detail')->row();
        if (!$jobDetail)
            return true;

        // get assigned staff count on this job detail
        $jobStaffCount = $this->db->where('job_detail_id', $jobDetailId)->get('tbl_job_staff')->num_rows();
        if ($jobStaffCount >= $jobDetail->required_number)
            return true;
        return false;

    }

    /**
     * Check if Staff is already engaged on other job during provided Job Detail
     * @param $staffUserId int Staff User Id
     * @param $jobDetailId int Job Detail Id for comparison
     * @return bool
     */

    private function has_staff_already_engaged($staffUserId, $jobDetailId)
    {
        $staffEngagedTimestamps = $this->get_upcoming_engaged_date_time($staffUserId);
        $this->db->where('id', $jobDetailId);
        if (count($staffEngagedTimestamps)) {
            foreach ($staffEngagedTimestamps as $dateTime) {
                $this->db->where("(unix_timestamp(start_time) NOT BETWEEN " . $dateTime->start_time . " AND " . $dateTime->end_time . ")");
                $this->db->where("(unix_timestamp(end_time) NOT BETWEEN " . $dateTime->start_time . " AND " . $dateTime->end_time . ")");
            }
        }
        if ($this->db->get('tbl_job_detail')->num_rows())
            return false;
        return true;
    }


    /**
     * @param $staffUserID int Staff User Id
     * @param string $intervalMinutes Interval in minutes
     */
    public function get_ongoing_job($staffUserID, $intervalMinutes = '10')
    {
        $siteSetting = $this->get_site_setting(STAFF_PERIMETER_SETTING_SLUG, array(
            'tng.group_id' => STAFF
        ));
        $perimeterSize = isset($siteSetting[0]->allowed_distance) ? ($siteSetting[0]->allowed_distance) : (DEFAULT_JOB_PERIMETER_SIZE_METERS);
        $ongoingJob = $this->db->select("j.id as job_id, js.id as job_staff_id, jd.id as job_detail_id, j.job_number, j.job_location_lat, j.job_location_lng, j.job_full_address, j.job_street, CONCAT_WS(' ',pg.suburb,pg.state,pg.postcode) AS job_geo_detail, su.id as supervisor_user_id, su.name as supervisor_name, su.email as supervisor_email, su.phone_number as supervisor_phone_number, su.profile_image as supervisor_profile_image, IF(js.job_status = '1' OR js.job_status = '2', '1','0') AS job_checked_in, " . $perimeterSize . " AS perimeter_size_meter", false)
            ->from('tbl_job_staff as js')
            ->join('tbl_job_detail as jd', 'jd.id = js.job_detail_id', 'inner')
            ->join('tbl_job as j', 'j.id = jd.job_id', 'inner')
            ->join('tbl_postcodes_geo as pg', 'pg.id = j.job_postcode_id', 'left')
            ->join('tbl_user as su', 'su.id = j.supervisor_user_id', 'left')
            ->where("(js.staff_user_id = {$staffUserID}) AND (NOW() >= DATE_SUB(DATE_FORMAT(jd.start_time,'%Y-%m-%d %H:%i:%s'), INTERVAL {$intervalMinutes} MINUTE)) AND (DATE_FORMAT(jd.end_time,'%Y-%m-%d %H:%i:%s') > NOW())")
            ->order_by('jd.start_date', 'desc')
            ->order_by('jd.start_time', 'desc')
            ->get()->row();
        return $ongoingJob;

    }

    /**
     * @param $staffUserId int Staff User Id
     * @param $jobStaffId int tbl_job_staff id
     * @param $latLng array array of (latitude,longitude)
     * @return bool
     */
    public function set_job_site($staffUserId, $jobStaffId, $latLng)
    {
        $this->db->where('id', $jobStaffId);
        $this->db->where('staff_user_id', $staffUserId);
        return $this->db->update('tbl_job_staff', array(
            'job_site_lat' => $latLng[0],
            'job_site_lng' => $latLng[1]
        ));
    }

    /**
     * @param $staffUserId int Staff User Id
     * @param $jobDetailId int Job Detail Id
     * @param $inTime DateTime Job staff Check in time
     * @return string Common code for staff from model/common.php file
     */
    public function job_check_in($staffUserId, $jobDetailId, $inTime)
    {
        if ($this->job_already_checked_in($staffUserId, $jobDetailId)) {
            return '0018';
        }
        if ($this->has_job_ended($jobDetailId))
            return '0019';

        $this->db->where(array(
            'staff_user_id' => $staffUserId,
            'job_detail_id' => $jobDetailId
        ));
        $this->db->update('tbl_job_staff', array(
            'in_time' => $inTime,
            'gps_in_time' => $inTime,
            'job_status' => JOB_STAFF_STATUS_CHECKED_IN
        ));
        if ($this->db->affected_rows())
            return '0001';
        return '0000';

    }

    /**
     * Check if Job Staff has been already checked in
     * @param $staffUserId Staff user Id
     * @param $jobDetailId
     * @return bool
     */
    private function job_already_checked_in($staffUserId, $jobDetailId)
    {
        return (bool)$this->db->where("job_detail_id = {$jobDetailId} AND staff_user_id = {$staffUserId} AND job_status != " . JOB_STAFF_STATUS_DEFAULT)
            ->get('tbl_job_staff')
            ->num_rows();
    }

    /**
     * Delete staff job
     * @param $staffUserId int staff user id
     * @param $jobDetailId job detail id
     * @return bool
     */
    public function staff_job_delete($staffUserId, $jobDetailId)
    {
        $where = array(
            'staff_user_id' => $staffUserId,
            'job_detail_id' => $jobDetailId
        );
        //update status on job_alert to 7
        $this->db->where($where)->update('tbl_job_alert', array('status' => '7'));
        // delete job staff entry
        $this->db->where($where)->delete('tbl_job_staff');
        return (bool)$this->db->affected_rows();
    }

    /**
     * @param $staffUserId int Staff User Id
     * @param $jobDetailId int Job Detail Id
     * @return mixed
     */
    public function get_job_detail($staffUserId, $jobDetailId)
    {
        $jobDetail = $this->db->select("j.id AS job_id, unix_timestamp(jd.start_time) as start_time, unix_timestamp(jd.end_time) as end_time, IF(ISNULL(jai.full_address),'',jai.full_address) AS full_address, IF(ISNULL(jai.street),'',jai.street) AS street, CONCAT_WS(' ', pg.suburb, pg.state, pg.postcode) AS geo_detail,
                                              IF(ISNULL(j.meeting_full_address),'',j.meeting_full_address) AS meeting_full_address, IF(ISNULL(j.meeting_street),'',j.meeting_street) AS meeting_street, CONCAT_WS(' ', pgm.suburb, pgm.state, pgm.postcode) AS meeting_geo_detail, jd.has_peak_price, IF(jd.has_peak_price > 0, jd.peak_price, jd.total_cost) AS total_cost, TRUNCATE((IF(jd.has_peak_price > 0, jd.peak_price, jd.total_cost) / jd.required_number), 2)   AS actual_cost,
                                              cs.name AS skill, cl.name AS level, IF(ISNULL(jd.qualification),'',jd.qualification) AS qualification, IF(ISNULL(j.description),'',j.description) AS job_description, jd.total_hour", FALSE)
            ->from('tbl_job_staff as js')
            ->join('tbl_job_detail as jd', 'jd.id = js.job_detail_id', 'inner')
            ->join('tbl_job as j', 'j.id = jd.job_id', 'inner')
            ->join('tbl_job_add_info as jai', 'jai.job_id = j.id', 'inner')
            ->join('tbl_postcodes_geo as pg', 'pg.id = jai.postcode_id', 'left')
            ->join('tbl_postcodes_geo as pgm', 'pgm.id = j.meeting_postcode_id', 'left')
            ->join('tbl_con_skill as cs', 'cs.id = jd.skill_id', 'left')
            ->join('tbl_con_level as cl', 'cl.id = jd.level_id', 'left')
//            ->join('tbl_job_induction as ji', 'ji.job_id = j.id', 'left')
            ->where(array(
                'js.staff_user_id' => $staffUserId,
                'js.job_detail_id' => $jobDetailId
            ))->get()->row();
        if (empty($jobDetail))
            return false;
        $jobDetail->induction = $this->get_staff_job_induction($jobDetail->job_id, $staffUserId);
        return $jobDetail;
    }

    /**
     * @param $staffUserId int Staff User Id
     * @param $jobDetailId int Job Detail Id
     * @return mixed
     */
    public function get_checked_in_job_detail($staffUserId, $jobDetailId)
    {
        $jobDetail = $this->db->select("j.id AS job_id, js.id as job_staff_id,
                                        unix_timestamp(jd.start_time) as start_time, unix_timestamp(jd.end_time) as end_time, TIMESTAMPDIFF(SECOND,NOW(),jd.end_time) as remaining_time, TIMESTAMPDIFF(SECOND,js.gps_in_time,NOW()) as elapsed_time, jai.full_address, jai.street, CONCAT_WS(' ', pg.suburb, pg.state, pg.postcode) AS geo_detail,jd.total_hour,
                                        j.job_location_lat, j.job_location_lng, IF(js.job_status = '2', '1','0') AS job_completed, js.job_site_lat, js.job_site_lng", FALSE)
            ->from('tbl_job_staff as js')
            ->join('tbl_job_detail as jd', 'jd.id = js.job_detail_id', 'inner')
            ->join('tbl_job as j', 'j.id = jd.job_id', 'inner')
            ->join('tbl_job_add_info as jai', 'jai.job_id = j.id', 'inner')
            ->join('tbl_postcodes_geo as pg', 'pg.id = jai.postcode_id', 'left')
            ->where(array(
                'js.staff_user_id' => $staffUserId,
                'js.job_detail_id' => $jobDetailId,
                'js.job_status != ' => '0'
            ))->get()->row();
        if (empty($jobDetail))
            return false;
        else {
            // find associated  assigned job breaks
            $breaks = $this->db->select('id, unix_timestamp(start_time) as start_time, unix_timestamp(end_time) as end_time')
                ->from('tbl_job_detail_break as jdb')
                ->where(array(
                    'job_detail_id' => $jobDetailId
                ))->get()->result();

            $onProgressBreaks = $this->db->select("id AS job_staff_break_id,unix_timestamp(in_time) as in_time,unix_timestamp(out_time) as out_time,unix_timestamp(gps_in_time) as gps_in_time,unix_timestamp(gps_out_time) as gps_out_time,is_break_complete", false)
                ->where(array(
                    'job_staff_id' => $jobDetail->job_staff_id
                ))
                ->get('tbl_job_staff_break')
                ->result();
        }
        $jobDetail->breaks = $breaks;
        $jobDetail->on_progress_breaks = $onProgressBreaks;
        return $jobDetail;
    }

    /**
     * Complete ongoing job
     * @param $staffUserId
     * @param $jobDetailId
     * @return bool
     */
    public function complete_job($staffUserId, $jobDetailId)
    {
        if ($this->job_already_completed($staffUserId, $jobDetailId)) {
            return '0022';
        } elseif (!$this->job_already_checked_in($staffUserId, $jobDetailId)) {
            return '0021';
        } else {
            $where = array(
                'staff_user_id' => $staffUserId,
                'job_detail_id' => $jobDetailId,
            );
            // update status on job_alert to 8
            $this->db->where($where)->update('tbl_job_alert', array('status' => '8'));
            // delete job staff entry
            $updated = $this->db->where($where)->update('tbl_job_staff', array(
                'gps_out_time' => date('Y-m-d H:i:s'),
                'out_time' => date('Y-m-d H:i:s'),
                'job_status' => JOB_STAFF_STATUS_COMPLETED,
            ));
            return ($updated) ? '0001' : '0000';
        }
    }

    /**
     * Check if job has been completed already
     * @param $staffUserId
     * @param $jobDetailId
     * @return bool
     */
    private function job_already_completed($staffUserId, $jobDetailId)
    {
        $completed = $this->db->where(array(
            'staff_user_id' => $staffUserId,
            'job_detail_id' => $jobDetailId,
            'job_status' => JOB_STAFF_STATUS_COMPLETED,
        ))->get('tbl_job_staff')->num_rows();
        return (bool)$completed;
    }

    /**
     * Insert staff latitude/longitude for their location tracking
     * @param $staffUserId
     * @param $jobDetailId
     * @param $latLng
     * @param $isStaffAway bool Check if staff is on site or not
     * @return string
     */
    public function post_staff_location($staffUserId, $jobDetailId, $latLng, $isStaffAway = false)
    {
        $jobStaff = $this->db->select('tjs.id as id, tjd.start_date')
            ->from('tbl_job_staff as tjs')
            ->where(array(
                'staff_user_id' => $staffUserId,
                'job_detail_id' => $jobDetailId
            ))
            ->join('tbl_job_detail as tjd', 'tjd.id = tjs.job_detail_id', 'inner')->get()->row();
        if (!$jobStaff)
            return '0012';
        if ($this->job_already_completed($staffUserId, $jobDetailId))
            return '0022';
        if (!$this->job_already_checked_in($staffUserId, $jobDetailId))
            return '0021';
        if ($jobStaff->start_date != date('Y-m-d'))
            return '0026';

        $insert = $this->db->insert('tbl_job_staff_location', array(
            'job_detail_id' => $jobDetailId,
            'staff_user_id' => $staffUserId,
            'gps_lat' => $latLng[0],
            'gps_lng' => $latLng[1]
        ));

        if ($isStaffAway) {
            $this->staff_away_processing($jobStaff->id);
            // notify specified users
//            $settings = $this->get_site_setting(STAFF_PERIMETER_SETTING_SLUG);
//            $this->send_notifications($settings);
        }
        return ($insert) ? '0001' : '0000';
    }

//    private function send_notifications($settings = array())
//    {
//        if ($settings) {
//            foreach ($settings as $setting) {
//                if (1) {
//
//                }
//            }
//        }
//
//    }

    /**
     * @param $jobStaffId
     */
    private function staff_away_processing($jobStaffId)
    {
        // update gps_out_time for break
        $break = $this->get_ongoing_break($jobStaffId);
        if ($break == false) {
            $this->db->where(array(
                'id' => $jobStaffId
            ))->update('tbl_job_staff', array(
                'gps_out_time' => date('H:i:s'),
            ));

        }
    }

    /**
     * Start Job Break
     * @param $staffUserId
     * @param $jobStaffId
     * @param $breakStartTime
     * @return string
     */
    public function post_start_job_break($staffUserId, $jobStaffId, $breakStartTime)
    {
        $jobDetailId = $this->db->select('job_detail_id')->where('id', $jobStaffId)->get('tbl_job_staff')->row();
        if (!$jobDetailId)
            return '0012';
        $jobDetailId = $jobDetailId->job_detail_id;
        if (!$this->job_already_checked_in($staffUserId, $jobDetailId))
            return '0021';
        if ($this->job_already_completed($staffUserId, $jobDetailId))
            return '0022';
        if ($this->staff_already_on_break($jobStaffId))
            return '0024';

        $insert = $this->db->insert('tbl_job_staff_break', array(
            'job_staff_id' => $jobStaffId,
            'in_time' => $breakStartTime,
            'gps_in_time' => $breakStartTime,
            'updated_by' => $staffUserId,
            'updated_date' => date('Y-m-d H:i:s'),
        ));
        return ($insert) ? '0001' : '0000';
    }

    /**
     * Stop job break
     * @param $staffUserId
     * @param $jobStaffId
     * @param $breakEndTime
     * @return string
     */
    public function post_stop_job_break($staffUserId, $jobStaffId, $breakEndTime)
    {
        $currentBreak = $this->get_ongoing_break($jobStaffId);
        if (!$currentBreak)
            return '0025';
        $update = $this->db->where('id', $currentBreak->id)->update('tbl_job_staff_break', array(
            'out_time' => $breakEndTime,
            'gps_out_time' => $breakEndTime,
            'updated_by' => $staffUserId,
            'updated_date' => date('Y-m-d H:i:s'),
            'is_break_complete' => '1',
        ));
        return ($update) ? '0001' : '0000';
    }

    /**
     * Get ongoing break detail
     * @param $jobStaffId
     * @return mixed
     */
    private function get_ongoing_break($jobStaffId)
    {
        $currentBreak = $this->db->select('id, job_staff_id, in_time, out_time, gps_in_time, gps_out_time, remarks')->where(array(
            'job_staff_id' => $jobStaffId,
            'is_break_complete' => '0'
        ))->get('tbl_job_staff_break')->row();
        if (!$currentBreak)
            return false;
        return $currentBreak;

    }

    /**
     * Check if staff already on break
     * @param $jobStaffId
     * @return bool
     */
    private function staff_already_on_break($jobStaffId)
    {
        $breakAvailableCount = $this->db->where(array(
            'job_staff_id' => $jobStaffId,
            'is_break_complete' => '0'
        ))->get('tbl_job_staff_break')->num_rows();
        return ($breakAvailableCount) ? true : false;

    }

    /**
     * Get Site setting data
     * @param $slug
     * @param $where mixed
     * @return mixed
     */
    private function get_site_setting($slug, $where = array())
    {
        $this->db->select('notification_time, allowed_distance, message')
            ->where(array(
                'slug' => $slug,
                'tng.value' => '1',
            ));

        if (!empty($where))
            $this->db->where($where);
        $settings = $this->db->join('tbl_notification_group AS tng', 'tns.id = tng.notification_id', 'INNER')
            ->get('tbl_notification_settings AS tns')->result();
        return $settings;
    }

    /**
     * Get Staff Details
     * @param $staffUserId
     * @return mixed
     */
    public function get_staff_details($staffUserId)
    {
        $user = $this->db->select("tu.id as staff_id, IFNULL(tu.profile_image, '') AS staff_image, tu.name as staff_name, IFNULL(tcs.name, '') AS job, IFNULL(tcl.name, '') AS level, IFNULL(tu.full_address, '') AS address, IFNULL(tu.street, '') AS street, IFNULL(tpg.postcode,'') AS geo_postcode, IFNULL(tpg.suburb,'') AS geo_suburb, IFNULL(tpg.state,'') AS geo_state, IFNULL(tu.phone_number, '') AS phone, IFNULL(tu.email, '') AS email", false)
            ->where(array(
                'tu.id' => $staffUserId,
            ))
            ->join('tbl_postcodes_geo as tpg', 'tu.postcode_id = tpg.id', 'left')
            ->join('tbl_staff_skill as tss', 'tss.staff_user_id = tu.id', 'left')
            ->join('tbl_con_skill as tcs', 'tcs.id = tss.skill_id', 'left')
            ->join('tbl_con_level as tcl', 'tcl.id = tss.level_id', 'left')
            ->get('tbl_user as tu')->row();
        return $user;
    }

    /**
     * Get Break details for job
     * @param $jobDetailId
     * @param $jobStaffId
     * @param $takenBreaksOnly
     * @return array
     */

    public function get_job_staff_breaks($jobDetailId, $jobStaffId, $takenBreaksOnly = false)
    {
        $availableBreaks = $takenBreaks = [];
        // find associated assigned job breaks
        if ($takenBreaksOnly == false) {
            $availableBreaks = $this->db->select('id, unix_timestamp(start_time) as start_time, unix_timestamp(end_time) as end_time')
                ->where(array(
                    'job_detail_id' => $jobDetailId
                ))
                ->get('tbl_job_detail_break')
                ->result();
        }

        $takenBreaks = $this->db->select("id,unix_timestamp(in_time) as in_time,unix_timestamp(out_time) as out_time,unix_timestamp(gps_in_time) as gps_in_time,unix_timestamp(gps_out_time) as gps_out_time,is_break_complete, is_approved", false)
            ->where(array(
                'job_staff_id' => $jobStaffId
            ))
            ->get('tbl_job_staff_break')
            ->result();
        return array(
            'available' => $availableBreaks,
            'taken' => $takenBreaks
        );
    }

    /**
     * Get timesheet details
     * @param $jobDetailId
     * @param $jobStaffId
     * @return array
     */
    public function get_job_staff_timesheet_details($jobDetailId, $jobStaffId)
    {
        $timeSheetBreaks = $planHours = [];
        $breaks = $this->get_job_staff_breaks($jobDetailId, $jobStaffId);
        if (count($breaks['taken'])) {
            foreach ($breaks['taken'] as $key => $takenBreak) {
                $timeSheetBreaks[$key]['hours'] = (object)['in' => $takenBreak->in_time, 'out' => $takenBreak->out_time];
                $timeSheetBreaks[$key]['gps'] = (object)['in' => $takenBreak->gps_in_time, 'out' => $takenBreak->gps_out_time];

                $availableBreak = isset($breaks['available'][$key]) ? $breaks['available'][$key] : false;
                if ($availableBreak) {
                    $timeSheetBreaks[$key]['plan'] = (object)['in' => $availableBreak->start_time, 'out' => $availableBreak->end_time];

                } else {
                    $timeSheetBreaks[$key]['plan'] = (object)[];
                }
                $timeSheetBreaks[$key]['status'] = ($takenBreak->is_approved) ? JOB_STAFF_BREAK_CONFLICT_RESOLVED : ($this->get_conflict_status($timeSheetBreaks[$key]));
            }
        }

        $jobStaff = $this->db->select("unix_timestamp(in_time) as in_time, unix_timestamp(out_time) as out_time, unix_timestamp(gps_in_time) as gps_in_time, unix_timestamp(gps_out_time) as gps_out_time, tjd.total_hour, unix_timestamp(tjd.start_time) as start_time, unix_timestamp(tjd.end_time) as end_time, is_approved", false)
            ->where(array(
                'tjs.id' => $jobStaffId
            ))
            ->join('tbl_job_detail as tjd', 'tjd.id = tjs.job_detail_id', 'inner')
            ->get('tbl_job_staff as tjs')
            ->row();
        if ($jobStaff) {
            $planHours['hours'] = (object)['in' => $jobStaff->in_time, 'out' => $jobStaff->out_time];
            $planHours['gps'] = (object)['in' => $jobStaff->gps_in_time, 'out' => $jobStaff->gps_out_time];
            $planHours['plan'] = (object)['in' => $jobStaff->start_time, 'out' => $jobStaff->end_time];
            $planHours['status'] = ($jobStaff->is_approved) ? JOB_STAFF_BREAK_CONFLICT_RESOLVED : ($this->get_conflict_status($planHours));
        }
        return array(
            'breaks' => $timeSheetBreaks,
            'planHours' => $planHours
        );
    }

    /**
     * Check conflict status for time range
     * @param array $timestamp
     * @return string
     */
    private function get_conflict_status($timestamp = array())
    {
        if (!empty($timestamp)) {
            try {
                $hour = $timestamp['hours'];
                $plan = $timestamp['plan'];
                $gps = $timestamp['gps'];
                if ($this->conflict_status_data_not_set($timestamp)) {
                    return JOB_STAFF_BREAK_CONFLICT_YES;

                }
                if ((($plan->in <= $hour->out) and ($plan->out >= $hour->in))
                    or (($plan->in <= $gps->out) and ($plan->out >= $gps->in))
                ) {
                    // overlapped condition
                    return JOB_STAFF_BREAK_CONFLICT_YES;
                }

            } catch (Exception $e) {
                return JOB_STAFF_BREAK_CONFLICT_YES;
            }

        }
        return JOB_STAFF_BREAK_CONFLICT_NO;

    }

    /**
     * Check Conflict data not set status
     * @param $inOutTimestamp
     * @return bool
     */
    private function conflict_status_data_not_set($inOutTimestamp)
    {
        foreach ($inOutTimestamp as $timestamp) {
            if (!$timestamp)
                return true;
            if (!$timestamp->in || !$timestamp->out)
                return true;
        }
        return false;
    }

    /**
     * Get Job staff Overview
     * @param $jobDetailId
     * @param $jobStaffId
     * @return mixed
     */
    public function get_job_staff_overview($jobDetailId, $jobStaffId)
    {
        $return = (object)[];
        $details = $this->get_job_staff_timesheet_details($jobDetailId, $jobStaffId);
        if ($details) {
            $return->breaks = [];
            $return->hours = [
                'start' => $details['planHours']['plan']->in,
                'end' => $details['planHours']['plan']->out,
                'status' => $details['planHours']['status'],
            ];
            if (count($details['breaks'])) {
                foreach ($details['breaks'] as $key => $takenBreak) {
                    $return->breaks[$key] = (object)[
                        'start' => @$takenBreak['gps']->in,
                        'end' => @$takenBreak['gps']->out,
                        'status' => $takenBreak['status']
                    ];
                }
            }
        }
        return $return;
    }

    /**
     * Get Job staff Details
     * @param $jobStaffId
     * @return mixed
     */
    public function get_job_staff_details($jobStaffId)
    {
        $return = $this->db->select("tu.id as staff_id, IFNULL(tu.profile_image, '') AS staff_image, tu.name as staff_name,
                                        IFNULL(tcs.name, '') AS job, IFNULL(tcl.name, '') AS level, IFNULL(tj.job_full_address, '') AS address,
                                        IFNULL(tj.job_street, '') AS street, IFNULL(tpg.postcode,'') AS geo_postcode,
                                        IFNULL(tpg.suburb,'') AS geo_suburb, IFNULL(tpg.state,'') AS geo_state, IFNULL(tu.phone_number, '') AS phone,
                                        IFNULL(tu.email, '') AS email", false)
            ->where(array(
                'tjs.id' => $jobStaffId
            ))
            ->join('tbl_job_detail as tjd', 'tjd.id = tjs.job_detail_id', 'inner')
            ->join('tbl_job as tj', 'tj.id = tjd.job_id', 'inner')
            ->join('tbl_user as tu', 'tu.id = tjs.staff_user_id', 'inner')
            ->join('tbl_con_skill as tcs', 'tcs.id = tjd.skill_id', 'left')
            ->join('tbl_con_level as tcl', 'tcl.id = tjd.level_id', 'left')
            ->join('tbl_postcodes_geo as tpg', 'tj.job_postcode_id = tpg.id', 'left')
            ->get('tbl_job_staff as tjs')
            ->row();
        return $return;
    }

    /**
     * Get total break time as interval
     * @param $jobDetailId
     * @param $jobStaffId
     * @return array
     */
    public function get_total_break($jobDetailId, $jobStaffId)
    {
        $totalBreakTime = 0;
        $containsIncompleteBreak = false;
        $takenBreak = $this->get_job_staff_breaks($jobDetailId, $jobStaffId, true);
        $takenBreak = $takenBreak['taken'];
        if ($takenBreak)
            foreach ($takenBreak as $break) {
                if ($break->is_break_complete == '0') {
                    $containsIncompleteBreak = true;
                    continue;
                }
//                $inDateTime = new DateTime($break->gps_in_time);
//                $outDateTime = new DateTime($break->gps_out_time);
//                if ($totalBreakTime) {
//                    $newDateTime = new DateTime('00:00:00');
//                    $newDateTimeClone = clone $newDateTime;
//                    $totalBreakTime = $newDateTime->add($totalBreakTime)->add($inDateTime->diff($outDateTime));
//                    $totalBreakTime = $newDateTimeClone->diff($totalBreakTime);
//                } else {
//                    $totalBreakTime = $inDateTime->diff($outDateTime);
//                }

                $inDateTime = $break->gps_in_time;
                $outDateTime = $break->gps_out_time;
                if ($totalBreakTime) {
                    $totalBreakTime = $totalBreakTime + ($outDateTime - $inDateTime);
                } else {
                    $totalBreakTime = $outDateTime - $inDateTime;
                }

            }
        return array(
            'contains_incomplete_break' => $containsIncompleteBreak,
            'total_break_time' => $totalBreakTime,
        );

    }

    /**
     * Get Timesheet for Staff
     * @param $staffUserId
     * @return mixed
     */
    public function get_timesheet($staffUserId)
    {
        $timesheets = $this->db->select("tjs.job_detail_id as job_detail_id, tj.id as job_id, tjs.staff_user_id, tjs.id as job_staff_id, tjs.is_approved, tj.description as note")
            ->join('tbl_job_detail as tjd', 'tjd.id = tjs.job_detail_id', 'inner')
            ->join('tbl_job as tj', 'tj.id = tjd.job_id', 'inner')
            ->where("tjs.job_status = " . JOB_STAFF_STATUS_COMPLETED . " AND tjs.staff_user_id = {$staffUserId}")
            ->order_by('start_time', 'desc')
            ->order_by('end_time', 'desc')
            ->get('tbl_job_staff as tjs')->result();
        if ($timesheets) {
            $this->load->model('staff_webservice/staff_model');
            foreach ($timesheets as $timesheet) {
                $overview = $this->staff_model->get_total_break($timesheet->job_detail_id, $timesheet->job_staff_id);
                $timesheet->staffTimeSheet = $this->staff_model->get_job_staff_overview($timesheet->job_detail_id, $timesheet->job_staff_id);
                $timesheet->staffInfo = $this->staff_model->get_job_staff_details($timesheet->job_staff_id);
                $timesheet->staffInfo->break_time = (string)$overview['total_break_time'];
                $timesheet->staffInfo->total_time = (string)($timesheet->staffTimeSheet->hours['end'] - $timesheet->staffTimeSheet->hours['start']);
                $timesheet->staffTimeSheetDetails = $this->staff_model->get_job_staff_timesheet_details($timesheet->job_detail_id, $timesheet->job_staff_id);
            }
        }
        return $timesheets;
    }

    // get array of message sent by staff user
    public function get_sent_message($user_id = 0, $limit=0, $offset=0)
    {
        $create_date = $this->db->select('DATE(jm.entered_date) as date')
            ->from('tbl_job_message jm')
            ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
            ->where(array('jm.entered_by' => $user_id, 'sm.is_delete' => 0, 'jm.upper_message_id !=' => 0))
            ->group_by('date')
            ->order_by('date', 'desc')
            ->limit($limit, $offset)
            ->get()->result();

        $message = array();
        if (!empty($create_date)) {
            foreach ($create_date as $key => $date) {
                $date_only = date('Y-m-d', strtotime($date->date));
                $msg_data = $this->db->select('sm.job_message_id, jm.job_id, j.job_title, pg.suburb, jm.title, jm.message, jm.image, jm.video, jm.delete_option, jm.can_reply, unix_timestamp(jm.entered_date) as created_at, sm.user_id as staff_id', false)
                    ->from('tbl_job_message jm')
                    ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
                    ->join('tbl_job j', 'jm.job_id = j.id')
                    ->join('tbl_postcodes_geo pg', 'j.job_postcode_id = pg.postcode', 'left')
                    ->where(array('jm.entered_by' => $user_id, 'sm.is_delete' => 0, 'jm.upper_message_id !=' => 0, 'DATE(jm.entered_date)' => $date_only))
                    ->get()->result();

                $message[$key]['date'] = $date_only;
                $message[$key]['message'] = $msg_data;
            }
        }
        return $message;
    }

    // get array of message sent by staff user
    public function get_received_message($user_id = 0, $limit=0, $offset=0)
    {
        $create_date = $this->db->select('DATE(jm.entered_date) as date')
            ->from('tbl_job_message jm')
            ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
            ->where(array('sm.user_id' => $user_id, 'sm.is_delete' => 0, 'jm.upper_message_id' => 0))
            ->group_by('date')
            ->order_by('date', 'desc')
            ->limit($limit, $offset)
            ->get()->result();

        $message = array();
        if ($create_date) {
            foreach ($create_date as $key => $date) {
                $date_only = date('Y-m-d', strtotime($date->date));
                $msg_data = $this->db->select('sm.job_message_id, jm.job_id, j.job_title, pg.suburb, jm.title, jm.message, jm.image, jm.video, jm.delete_option, jm.can_reply, unix_timestamp(jm.entered_date) as created_at, sm.user_id as staff_id, sm.is_read')
                    ->from('tbl_job_message jm')
                    ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
                    ->join('tbl_job j', 'jm.job_id = j.id')
                    ->join('tbl_postcodes_geo pg', 'j.job_postcode_id = pg.postcode', 'left')
                    ->where(array('sm.user_id' => $user_id, 'sm.is_delete' => 0, 'jm.upper_message_id' => 0, 'DATE(jm.entered_date)' => $date_only))
                    ->get()->result();

                $message[$key]['date'] = $date_only;
                $message[$key]['message'] = $msg_data;
            }
        }
        return $message;
    }

    public function get_received_message_num($user_id){
        $num_rows = $this->db->select('DATE(jm.entered_date) as date')
            ->from('tbl_job_message jm')
            ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
            ->where(array('jm.entered_by' => $user_id, 'sm.is_delete' => 0, 'jm.upper_message_id !=' => 0))
            ->group_by('date')
            ->get()->num_rows();
        return $num_rows;
    }

    public function get_sent_message_num($user_id){
        $num_rows = $this->db->select('DATE(jm.entered_date) as date')
            ->from('tbl_job_message jm')
            ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
            ->where(array('jm.entered_by' => $user_id, 'sm.is_delete' => 0, 'jm.upper_message_id !=' => 0))
            ->group_by('date')
            ->get()->num_rows();

            return $num_rows;
    }

    public function get_message_details($msg_id = 0, $user_id = 0)
    {

        $message = $this->db->select('jm.id as message_id, jm.to_user_id, jm.to_team_id, jm.job_id, j.job_number,pg.suburb, jm.title, jm.message, jm.delete_option, jm.can_reply, jm.entered_by')
        ->from('tbl_job_message jm')
        ->join('tbl_job j', 'jm.job_id = j.id')
        ->join('tbl_postcodes_geo pg', 'j.job_postcode_id = pg.id', 'left')
        ->where('jm.id', $msg_id)
        ->get()
        ->row();

        if($user_id > 0){
            $mssage_staff_status = $this->db->select('is_read')->get_where('tbl_staff_message_read_reply', array('job_message_id' => $msg_id, 'user_id' => $user_id))->row();

            if ($mssage_staff_status && $mssage_staff_status->is_read) {
                $this->db->where(array('job_message_id' => $msg_id, 'user_id' => $user_id));
                $this->db->update('tbl_staff_message_read_reply', array('is_read' => 1, 'read_date' => date('Y-m-d H:i:s')));
            }
        }
        return $message;
    }

    public function get_message_reply($msg_id){
    
    $reply = $this->db->select('jm.id as reply_id, jm.to_user_id, jm.to_team_id, jm.message, jm.entered_by')
                ->from('tbl_job_message jm')
                ->where('jm.upper_message_id', $msg_id)
                ->order_by('jm.entered_date', 'desc')
                ->get()->result();

        return $reply;
    }

    // update staff's reply
    public function send_reply($user_id = 0)
    {
        $message_id = $this->input->post('message_id', true);
        $main_msg = $this->db->select('job_id, entered_by, delete_option, can_reply')
            ->from('tbl_job_message')
            ->where('id', $message_id)
            ->get()
            ->row();

        if (empty($main_msg)) {
            return false;
        }

        $data = array(
            'job_id' => $main_msg->job_id,
            'upper_message_id' => $this->input->post('message_id', true),
            'to_user_id' => $main_msg->entered_by,
            'message' => $this->input->post('message', true),
            'delete_option' => $main_msg->delete_option,
            'can_reply' => $main_msg->can_reply,
            'entered_date' => date('Y-m-d H:i:s'),
            'entered_by' => $user_id
        );
        $this->db->insert('tbl_job_message', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $read_reply_data = array(
                'job_message_id' => $insert_id,
                'user_id' => $user_id,
                'is_read' => 0,
                'is_reply' => 1,
                'is_delete' => 0
            );
            $this->db->insert('tbl_staff_message_read_reply', $read_reply_data);
            return $insert_id;
        }
        return false;
    }

    /**
     * Get induction data for Job
     * @param $job_id
     * @param $staff_id
     * @return mixed
     */
    public function get_staff_job_induction($job_id, $staff_id)
    {
        $query = $this->db->select('cid.provider_name, cid.name,ji.url, ji.other_detail, ji.induction_number')
            ->from('tbl_job_induction ji')
            ->join('tbl_con_induction_detail cid', 'ji.url = cid.document_url')
            ->join('tbl_staff_induction si', 'cid.id = si.induction_detail_id')
            ->where(array('ji.job_id' => $job_id, 'si.staff_user_id' => $staff_id))
            ->get();

        if ($query->num_rows() > 0) {
            $return = $query->row();
            $return->email = 'example@example.com';
            $return->location = 'Surry Hills NSW 2010';
            return $return;
        }
        return null;
    }

    /**
     * Get job staff calendar view
     * @param $staffUserId
     * @param $startTimestamp
     * @param $endTimestamp
     * @return mixed
     */
    public function get_job_calendar($staffUserId, $startTimestamp, $endTimestamp)
    {
        $notAvailableDate = $hasJobDate = $partiallyAvailableDate = $hasJobDateAndUnAvailability = [];
        // for $hasJobDate data
        $jobStaff = $this->db->select("DATE_FORMAT(tjd.start_time, '%Y-%m-%d')      AS start_date,
                                          DATE_FORMAT(tjd.start_time, '%Y-%m-%d')      AS _start_date,
                                          tj.id                                        AS job_id,
                                          group_concat(tjd.id)                         AS job_detail_id,
                                          group_concat(unix_timestamp(tjd.start_date)) AS start_time,
                                          group_concat(unix_timestamp(tjd.end_time))   AS end_time", false)
            ->join('tbl_job_detail as tjd', 'tjs.job_detail_id = tjd.id', 'inner')
            ->join('tbl_job as tj', 'tj.id = tjd.job_id', 'inner')
            ->where("DATE_FORMAT(tjd.start_time,'%Y-%m-%d') BETWEEN (DATE_FORMAT(FROM_UNIXTIME({$startTimestamp}),'%Y-%m-%d')) AND (DATE_FORMAT(FROM_UNIXTIME({$endTimestamp}),'%Y-%m-%d'))")
            ->where(array(
                'tjs.staff_user_id' => $staffUserId
            ))
            ->group_by("_start_date")
            ->order_by("tjd.start_time", 'asc')
            ->get('tbl_job_staff as tjs')->result();

        foreach ((array)$jobStaff as $job) {
            $hasJobDate[] = $job->start_date;
        }

        // for $partiallyAvailableDate data
        $staffUnavailabilityData = $this->get_staff_unavailability_data($staffUserId, $startTimestamp, $endTimestamp);
        foreach ((array)$staffUnavailabilityData as $data) {
            $isWholeDay = $this->is_whole_day_unavailability(explode(',', $data->from_time), explode(',', $data->to_time));
            if ($isWholeDay) {
                $notAvailableDate[] = $data->start_date;
                continue;
            }
            $partiallyAvailableDate[] = $data->start_date;
        }
        // for $hasJobDateAndUnAvailability data
        $hasJobDateAndUnAvailability = array_intersect($hasJobDate, $partiallyAvailableDate);
        return array(
            'has_job_date' => array_values($hasJobDate),
            'partially_available_date' => array_values($partiallyAvailableDate),
            'not_available_date' => array_values($notAvailableDate),
            'has_job_date_and_unavailability' => array_values($hasJobDateAndUnAvailability),
        );

    }

    /**
     * Check if unavailability data is for whole day or not
     * @param $fromTimeArray
     * @param $toTimeArray
     * @return bool
     */
    private function is_whole_day_unavailability($fromTimeArray, $toTimeArray)
    {
        if (count($fromTimeArray) != count($toTimeArray))
            return false;
        $totalSeconds = 0;
        foreach ($fromTimeArray as $key => $fromTime) {
            $totalSeconds += ($toTimeArray[$key] - $fromTimeArray[$key]);
        }
        if ($totalSeconds >= self::TOTAL_DAY_SECONDS)
            return true;
        return false;

    }

    /**
     * Get staff unavailability data
     * @param $staffUserId
     * @param $startTimestamp
     * @param $endTimestamp
     * @param $grouped bool If return data is grouped or not
     * @return mixed
     */
    private function get_staff_unavailability_data($staffUserId, $startTimestamp, $endTimestamp, $grouped = true)
    {
        if ($grouped) {
            $result = $this->db->select("group_concat(tsu.id) AS staff_unavailability_id,
                                    DATE_FORMAT(tsu.from_time,'%Y-%m-%d') AS start_date,
                                    group_concat(unix_timestamp(tsu.from_time)) AS from_time,
                                    group_concat(tsu.from_time) AS from_time_dt,
                                    group_concat(unix_timestamp(tsu.to_time))   AS to_time,
                                    group_concat(tsu.to_time)   AS to_time_dt", false)
                ->where("(unix_timestamp(tsu.from_time) >= {$startTimestamp}) AND (unix_timestamp(tsu.to_time) <= {$endTimestamp})")
                ->where(array(
                    'tsu.staff_user_id' => $staffUserId
                ))
                ->group_by('start_date')
                ->order_by('from_time')
                ->get('tbl_staff_unavailability as tsu')
                ->result();
        } else {
            $result = $this->db->select("tsu.id AS staff_unavailability_id,
                                    DATE_FORMAT(tsu.from_time,'%Y-%m-%d') AS start_date,
                                    unix_timestamp(tsu.from_time) AS from_time,
                                    tsu.from_time AS from_time_dt,
                                    unix_timestamp(tsu.to_time)   AS to_time,
                                    tsu.to_time   AS to_time_dt", false)
                ->where("(unix_timestamp(tsu.from_time) >= {$startTimestamp}) AND (unix_timestamp(tsu.to_time) <= {$endTimestamp})")
                ->where(array(
                    'tsu.staff_user_id' => $staffUserId
                ))
                ->order_by('from_time')
                ->get('tbl_staff_unavailability as tsu')
                ->result();
        }
        return $result;
    }

    /**
     * Get Staff Unavailability for specified period
     * @param $staffUserId
     * @param $startTimestamp
     * @param $endTimestamp
     * @return mixed
     */
    public function get_staff_unavailability($staffUserId, $startTimestamp, $endTimestamp = null)
    {
        if ($endTimestamp == null) {
            $endTimestamp = (int)$startTimestamp + self::TOTAL_DAY_SECONDS;
        }
        $data = $this->get_staff_unavailability_data($staffUserId, $startTimestamp, $endTimestamp, false);
        foreach ((array)$data as $key => $value) {
            if ($this->is_whole_day_unavailability(array($value->from_time), array($value->to_time))) {
                $value->is_whole_day = "1";
            } else {
                $value->is_whole_day = "0";
            }
        }
        return $data;

    }

    /**
     * Delete staff Unavailability
     * @param $staffUserId
     * @param $staffUnavailabilityId
     * @return string
     */
    public function delete_staff_unavailability($staffUserId, $staffUnavailabilityId)
    {
        $delete = $this->db->delete('tbl_staff_unavailability', array(
            'id' => $staffUnavailabilityId,
            'staff_user_id' => $staffUserId,
        ));
        if (!$delete)
            return '0000';
        if ($this->db->affected_rows())
            return '0001';
        return '0029';

    }


    /**
     * Check if staff unavailability intersects or not
     * @param $staffUserId
     * @param $startTimestamp
     * @param $endTimestamp
     * @return mixed
     */
    private function get_staff_unavailability_with_in($staffUserId, $startTimestamp, $endTimestamp)
    {
        $result = $this->db->select("tsu.id", false)
            ->where("(unix_timestamp(tsu.from_time) < {$endTimestamp}) AND (unix_timestamp(tsu.to_time) > {$startTimestamp})")
            ->where(array(
                'tsu.staff_user_id' => $staffUserId
            ))
            ->get('tbl_staff_unavailability as tsu')
            ->result();
        return $result;
    }

    /**
     * Get Job staff with in the provided timestamp range
     * @param $staffUserId
     * @param $startTimestamp
     * @param $endTimestamp
     * @param bool|false $countOnly
     * @return mixed
     */
    private function get_job_staff_with_in($staffUserId, $startTimestamp, $endTimestamp, $countOnly = false)
    {
        $result = $this->db->select()
            ->where(array(
                'tjs.staff_user_id' => $staffUserId
            ))
            ->where("(unix_timestamp(tjd.start_time) <= {$endTimestamp}) AND (unix_timestamp(tjd.end_time) >= {$startTimestamp})")
            ->join('tbl_job_detail as tjd', 'tjd.id = tjs.job_detail_id', 'inner')
            ->get('tbl_job_staff as tjs');
        if ($countOnly)
            return $result->num_rows();
        return $result->result();

    }

    /**
     * Post Staff Unavailability
     * @param $staffUserId
     * @param $timestamps
     * @return string
     */
    public function post_staff_unavailability($staffUserId, $timestamps)
    {
        log_message('error', json_encode(func_get_args()));
        $returnCode = '0001';
        $timestamps = json_decode($timestamps);
        $this->db->trans_begin();
        //delete all existing unavailability for this staff
        $this->db->delete('tbl_staff_unavailability', array(
            'staff_user_id' => $staffUserId,
            "DATE_FORMAT(from_time, '%Y-%m-%d') = " => $timestamps->date,
        ));
        if ($this->check_timestamp_intersection($timestamps)) {
            $returnCode = '0028';
        } else {
            foreach ((array)$timestamps->availability as $key => $value) {
                if ($this->get_job_staff_with_in($staffUserId, $value->start, $value->end, true)) {
                    $returnCode = '0029';
                    break;
                }
            }

            if ($returnCode == '0001') {
                $date = new \DateTime();
                foreach ((array)$timestamps->availability as $key => $value) {
                    $startDateTime = $date->setTimestamp($value->start)->format('Y-m-d H:i:s');
                    $endDateTime = $date->setTimestamp($value->end)->format('Y-m-d H:i:s');
                    $this->db->insert('tbl_staff_unavailability', array(
                        'staff_user_id' => $staffUserId,
                        'from_time' => $startDateTime,
                        'to_time' => $endDateTime
                    ));
                }
            }
        }

        if (($this->db->trans_status() === FALSE) || ($returnCode != '0001')) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $returnCode;
    }

    /**
     * Check timestamp intersection case
     * @param array $timestamps
     * @return bool
     */
    private function check_timestamp_intersection($timestamps = array())
    {
        $hasConflict = false;
        foreach ((array)$timestamps as $key => $value) {
            foreach ((array)$timestamps as $k => $v) {
                if ($key == $k)
                    continue;
                if (($value->start < $v->end) and ($value->end > $v->start)) {
                    $hasConflict = true;
                    break 2;
                }

            }
        }
        return $hasConflict;
    }

}