<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Staffs_model extends MY_Model 
{
		
    public function __construct()
    {

        parent::__construct();

    }

    /**Get List of staffs for certain Job
     * @param int $jobId
     * @param int $staffId
     * @param boolean $track
     * @return mixed
     */

    public function getJobStaffs($jobId='',$staffId="",$track = FALSE){

        if(!empty($staffId)){
            $this->db->where('js.staff_user_id',$staffId);
        }
        $this->db->select('u.id as staff_id,js.id as job_staff_id, IFNULL(u.profile_image,"") as staff_image ,u.name as staff_name,IFNULL(u.full_address,"") as full_address , 
            IFNULL(u.phone_number,"") as phone, u.email, s.name as job,l.name as level,IFNULL(js.total_time,"") as total_time ,
            UNIX_TIMESTAMP(js.gps_out_time) as gps_out_time,  UNIX_TIMESTAMP(js.gps_in_time) as gps_in_time,
            IFNULL(js.total_break_time,"") as break_time,IFNULL(js.job_site_lat,"") as job_site_lat, IFNULL(js.job_site_lng,"") as job_site_lng,
            MIN(jd.start_time) as start_time, MAX(jd.end_time) as end_time, js.job_detail_id,
            MAX(jsl.id) as jsl_id, IFNULL(jsl.gps_lng,"") as gps_lng, IFNULL(jsl.gps_lat,"") as gps_lat',FALSE)
        ->from('tbl_job_staff as js')
        ->join('tbl_job_detail as jd','js.job_detail_id =  jd.id')
        ->join('tbl_job as j','jd.job_id = j.id')
        ->join('tbl_job_staff_location as jsl','jsl.staff_user_id = js.staff_user_id','left')
        ->join('tbl_con_skill as s','s.id =  jd.skill_id')
        ->join('tbl_con_level as l','l.id =  jd.level_id')
        ->join('tbl_user as u','u.id = js.staff_user_id')
        ->order_by('jd.start_time')
        ->where('j.id',$jobId)
        ->group_by('js.staff_user_id');

        $res = $this->db->get()->result();
   
        if($track){
            $jobTime = $this->getJobInfo($jobId); 
            $staffLocations = $this->getStaffLocations($jobId,$jobTime->start_time,$jobTime->end_time);
        }
        $staffBreakTotal = $this->getStaffTotalBreakTime($jobId);
        $staffs = array();
        $i=1;
        $initials_array = array();
        $this->load->model('staff_webservice/staff_model');
        if(!empty($res)){
            foreach ($res as $r) {
        
                $staffInfo = new stdClass();  
                $staffInfo->staff_id = $r->staff_id;
                $staffInfo->job_staff_id = $r->job_staff_id;
                $staff_initial = "";
                $words =preg_split("/\s+/", $r->staff_name);
                foreach ($words as $w) {
                  $staff_initial .= $w[0];
                }
                if(in_array($staff_initial, $initials_array)){
                    $staff_initial .= $i;
                    $i++;
                }
                $initials_array[] = $staff_initial;
                $staff_initial = ucwords($staff_initial);
                $staffInfo->staff_initial = $staff_initial;
                $staffInfo->staff_email = $r->email;
                $staffInfo->staff_name = $r->staff_name;
                $staffInfo->staff_image = $r->staff_image;
                $staffInfo->job = $r->job;
                $staffInfo->level = $r->level;
                $staffInfo->address = $r->full_address;
                $staffInfo->location = array('latitude' => $r->gps_lat,'longitude' =>$r->gps_lng);
                $staffInfo->job_site = array('latitude' => $r->job_site_lat,'longitude' =>$r->job_site_lng);
                if($track){
                    $staffInfo->staff_locations = isset($staffLocations[$r->staff_id]) ? $staffLocations[$r->staff_id] : array();
                }
                $out = ($r->gps_out_time == 0 ) ? time() : $r->gps_out_time;
                $in = $r->gps_in_time;
                $total_time = ($out > $in) ? ($out - $in) : 0;

                $staffInfo->total_time =  "$total_time";
                $total_break_time =  isset($staffBreakTotal[$r->job_staff_id]) ? $staffBreakTotal[$r->job_staff_id] : 0;
                //$total_break_time = empty($r->break_time) ? isset($staffBreakTotal[$r->job_staff_id]) ? $staffBreakTotal[$r->job_staff_id] : 0 : 0;
                $staffInfo->break_time =  "$total_break_time";


                $staffInfo->phone = $r->phone;

                $staff = new stdClass();
                $staff->staffInfo = $staffInfo;
                $staff->staffTimeSheet = (object) array();
                if($track){
                    $staff->staffTimeSheet = @$this->staff_model->get_job_staff_overview($r->job_detail_id, $r->job_staff_id);
                }
                $staffs[] = $staff;
            } 
        }
         if(!empty($staffId) && !empty($staffs)){
            return $staffs[0];
        }
        return $staffs;
    }

    /** Returns Staff Info 
     * @param int $staffId is from tbl_user table
     * @param int $staffId
     * @return mixed
     */

    public function getStaffInfo($staffId=""){
        $this->db->select('u.id as staff_id,IFNULL(u.profile_image,"") as staff_image ,u.email as staff_email,
         u.name as staff_name,IFNULL(u.phone_number,"") as phone',FALSE)
            ->from('tbl_user u')
            ->from('tbl_user_group as ug','u.id = ug.user_id')
            ->where('ug.group_id',STAFF)
            ->where('u.id',$staffId);
        $staff = $this->db->get()->row();

        $staffInductions = $this->getStaffInductions($staffId);
        $staffQualifications = $this->getStaffInductions($staffId);
        $staffInfo = new stdClass(); 
        $staffInfo->staffInfo = $staff; 
        $staffInfo->staffInductions = $staffInductions; 
        $staffInfo->staffQualifications = $staffQualifications; 
        return $staffInfo;
    }

     /** Returns Staff Induction 
     * @param int $staffId
     * @param int $staffId is from tbl_user table
     * @return mixed
     */

    public function getStaffInductions($staffId=""){
        $this->db->select('id.name,id.document_url,id.provider_name')
            ->from('tbl_con_induction_detail as id')
            ->join('tbl_staff_induction as si','si.induction_detail_id = id.id')
            ->where('si.staff_user_id',$staffId);
        $res = $this->db->get()->result();
        return $res;
    }

      /** Returns Staff Qualification 
     * @param int $staffId
     * @param int $staffId is from tbl_user table
     * @return mixed
     */

    public function getStaffQualifications($staffId=""){
        $this->db->select('sq.name,sq.description,sq.duration')
            ->from('tbl_staff_qualification as sq')
            ->where('sq.staff_user_id',$staffId);
        $res = $this->db->get()->result();
        return $res;
    }

    public function getStaffTimeSheet($staffId=""){

    }

       /** Returns Array of Staff Location for Job
     * @param int $jobId
     * @param string $start_time 
     * @param string $end_time 
     * @return mixed
     */

    public function getStaffLocations($jobId="",$start_time="",$end_time=""){
        $this->db->select('jsl.id,IFNULL(jsl.gps_lat,"") as latitude, IFNULL(jsl.gps_lng,"") as longitude,
            jsl.created_at,js.staff_user_id',FALSE)
            ->from('tbl_job as j')
            ->join('tbl_job_detail as jd','jd.job_id = j.id')
            ->join('tbl_job_staff as js','js.job_detail_id = jd.id')
            ->join('tbl_job_staff_location as jsl','jd.id = jsl.job_detail_id','left')
            ->where('j.id',$jobId)
            ->order_by('jsl.id');
        $res = $this->db->get()->result();
    
        $staffLocations = array();
        if(!empty($res)){
            foreach ($res as $s) {
                $staffLocations[$s->staff_user_id][] = $s;
            }
        }
       // return $staffLocations;
        $period = new DatePeriod(
             new DateTime($start_time),
             new DateInterval('PT'.STAFF_TRACK_INTERVAL.'M'),
             new DateTime($end_time)
        );
        $staffLocationsFinal = array();
        foreach ($staffLocations as $sl) {
                $periods = array();
                $i = 1;
                foreach ($period as $dt) {
                    $filtered = $this->searchArray($i,$dt->format("Y-m-d H:i"),$sl);
                    $i++;
                    $periods[] = (object) $filtered;
                }
                $staffLocationsFinal[reset($sl)->staff_user_id] = $periods;
        }
        return $staffLocationsFinal;
       
        foreach ($periods as $p) {
             array_reduce($sl, function($a,$b){
                return isset($a->created_at) > isset($b->created_at);
            },"");
         }
        return $staffLocations;

    }

    /** Returns Staff Location for specific time
     * @param string $date
      * @param array $array
     * @return mixed
     */
    function searchArray($key, $date, $array) {
        
        foreach ($array as $a) {
           if (strtotime($a->created_at) > strtotime($date)) {
                $arr = array(
                    'key' =>$key,
                    'date' => strtotime($date),
                    'latitude' => $a->latitude,
                    'longitude' => $a->longitude,
                );
                return $arr;
           }
        }
        $arr = array(
            'key' =>$key,
            'date' => strtotime($date),
            'latitude' => "",
            'longitude' => ""
        );
        return $arr;
    }

        /** Returns Staff Break Times for Job
     * @param int $jobId
     * @return mixed
     */

    public function getStaffTotalBreakTime($jobId=""){
        $this->db->select('jsb.job_staff_id,jsb.in_time,jsb.out_time,jsb.gps_in_time,jsb.gps_out_time',FALSE)
            ->from('tbl_job as j')
            ->join('tbl_job_detail as jd','jd.job_id = j.id')
            ->join('tbl_job_staff as js','js.job_detail_id = jd.id ')
            ->join('tbl_job_staff_break as jsb','jsb.job_staff_id = js.id')
            ->where('j.id',$jobId)
            ->order_by('jsb.id');
        $res = $this->db->get()->result();
        $staffBreaks = array();
        $staffBreaksTotal = array();

        if(!empty($res)){
            foreach ($res as $s) {
                $break = (strtotime($s->out_time) - strtotime($s->in_time));
                $break = ($break > 0) ? $break : 0;
                $staffBreaks[$s->job_staff_id][] = $break;
            }

            foreach ($staffBreaks as $k=>$v) {
                $staffBreaksTotal[$k] = array_sum($v);
            }
        }

        return $staffBreaksTotal;
    }

        /** Returns Job Info
     * @param int $jobId
     * @return mixed
     */
    public function getJobInfo($jobId="",$staffId=""){
        $this->db->select('j.id as job_id, MIN(jd.start_time) as start_time, MAX(jd.end_time) as end_time, IFNULL(pg.longitude,"") as longitude, IFNULL(pg.latitude,"") as latitude',FALSE)
            ->from('tbl_job as j')
            ->join('tbl_job_detail as jd','jd.job_id = j.id')
            ->join('tbl_postcodes_geo as pg','j.job_postcode_id = pg.postcode','left')
            ->where('j.id',$jobId)
            ->where('jd.job_id',$jobId);
            if(!empty($staffId)){
                $this->db->join('tbl_job_staff as js','js.job_detail_id = jd.id');
                $this->db->where('js.staff_user_id',$staffId);
            }
            $res = $this->db->get()->row();
            return $res;
    }

}
