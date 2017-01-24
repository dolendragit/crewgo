<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Customer_model extends MY_Model 
{
		
    public function __construct() 
    {
            parent::__construct();

    }
/**
 * Fetch job data
 * @param type $id
 * @return type
 */	
    public function getJobDetail($job_id="",$user_id=""){
        $response = array();
        $res = $this->db->select('id,job_full_address,job_street,job_postcode_id,meeting_full_address,meeting_street,meeting_postcode_id,description,book_amount,quote_id')
            ->from('tbl_job')
            ->where('id',$job_id)
            ->where('customer_user_id',$user_id)
            ->get()->row();
        if(!empty($res)){
            $response['job_detail'] = $res;
            $id = $res->id;
            $this->db->select('jd.id,jd.required_number,s.name as skill, l.name as level,jd.start_date,jd.total_cost')->from('tbl_job_detail as jd');
            $this->db->join('tbl_con_skill as s','jd.skill_id = s.id');
            $this->db->join('tbl_con_level as l','jd.level_id = l.id');
            $this->db->where('jd.job_id',$id);
            $skills = $this->db->get()->result();

            $job_detail_id = array();
            $total_cost = 0; 
            if(!empty($skills)){
                $skills_array = array();
                
                foreach ($skills as $s) {
                    $job_detail_id[] = $s->id;
                    $array = array(
                        'start_date' => $s->start_date,
                        'skill' => $s->skill,
                        'level' => $s->level,
                        'number' => $s->required_number,
                        'total_cost' => is_null($s->total_cost) ? 0 : $s->total_cost
                    );
                    $skills_array[] = $array;
                    //$total_cost += $s->total_cost; 

                }

                $response['skills'] = $skills_array;
            }
            $response['total_amount'] = ($res->book_amount == null) ? 0 : $res->book_amount;
            if(!empty($job_detail_id)){
                $break_deatil = $this->db->select('start_time,end_time')->from('tbl_job_detail_break')->where_in('job_detail_id',$job_detail_id)->get()->result();
                if(!empty($break_deatil)){
                     $response['break_detail'] = $break_deatil;
                }
            }
        }
        return $response;
    }
/*
    public function get_skills($term="",$user_id=""){
        $ids = $this->get_admin_ids();
        $ids[] = $user_id; 
        $this->db->select('id,name')->from('tbl_con_skill');
        if(!empty($term)){
            $where = "(name like '$term%')";
            $this->db->where($where);
           // $this->db->where('entered_by',$user_id);
            $this->db->where_in('entered_by',$ids);

        }
        else{
            $this->db->where('entered_by',$user_id);
            $this->db->or_where_in('entered_by',$ids);
        }
        $this->db->limit(20);
        //$where = "(entered_by  = '$user_id' OR entered_by = '1')"; 
        //$this->db->where('entered_by',$user_id)->or_where('entered_by',1);
        $res = $this->db->get()->result();

        return $res;
    }*/

        public function get_skills($term="",$industry_id=""){

        $this->db->select('s.id,s.name')->from('tbl_con_skill as s');
        $this->db->join('tbl_con_industry_skill as si','s.id = si.skill_id');
        $this->db->join('tbl_con_industry as i','si.industry_id = i.id');
        if(!empty($term)){
            $where = "(s. name like '$term%')";
            $this->db->where($where);
        }
        $this->db->where('si.status',1);
        $this->db->limit(20);

        $res = $this->db->get()->result();

        return $res;
    }


/*    public function get_levels($user_id=""){

        $ids = $this->get_admin_ids();
        $this->db->select('id,name')->from('tbl_con_level');
        $this->db->where('entered_by',$user_id);

        $this->db->or_where_in('entered_by',$ids);
        //$this->db->where('entered_by',$user_id)->or_where('entered_by',1);
        $res = $this->db->get()->result();

        return $res;
    }*/

    public function get_levels($term="",$skill_id=""){

        $this->db->select('l.id,l.name')->from('tbl_con_level as l');
        $this->db->join('tbl_con_skill_level as sl','sl.level_id = l.id');
        $this->db->where('sl.skill_id',$skill_id);
        if(!empty($term)){
            $where = "(l. name like '$term%')";
            $this->db->where($where);
        }
        $this->db->order_by('l.name');
        $res = $this->db->get()->result();

        return $res;
    }

    public function get_supervisors($user_id="",$term="",$offset=0){
        $this->db->select('SQL_CALC_FOUND_ROWS u.id, u.id,u.name,u.email,u.phone_number,u.full_address, IFNULL(u.profile_image,"") as profile_image',FALSE)->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('ug.group_id','5'); 
        $this->db->where('u.customer_user_id',$user_id); 
        if(!empty($term)){
           $where = "(name like '$term%' OR email like 'term%')";
            $this->db->where($where);
        }
        //if(!empty($offset)){
            $this->db->limit(10,$offset);
        //}
        $this->db->order_by('u.name');
        $res['res'] = $this->db->get()->result();
        $res['total'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        

        return $res;
    }

    public function check_valid_supervisor($id = "",$user_id=""){
        $this->db->select('u.id,u.name')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('ug.group_id','5'); 
        $this->db->where('u.id',$id); 
        $this->db->where('u.customer_user_id',$user_id); 
        $res = $this->db->get()->row();

        return $res;
    }

    public function get_user_info($id = ""){
        $this->db->select('u.id,u.name,u.email,u.phone_number,u.full_address')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('u.id',$id); 
        $res = $this->db->get()->row();

        return $res;
    }	

    public function check_job($job_id="",$user_id=""){
        $this->db->select('id')->from('tbl_job');
        $this->db->where('customer_user_id',$user_id);
        $this->db->where('id',$job_id);
        $res = $this->db->count_all_results();
        if($res > 0){
            return true;
        }
        return false;
    }

    public function set_job_supervisors($job_id="",$supervisor_id=""){
        $this->db->where('id',$job_id);
        $this->db->update("tbl_job", array('supervisor_user_id' => $supervisor_id)); 
    }

    public function get_admin_ids(){
        $this->db->select('u.id')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('ug.group_id',1); 
        $res = $this->db->get()->result();
        $ret = array();
        if(!empty($res)){
            foreach ($res as $r) {
                $ret[] = $r->id;
            }
        }
        return $ret;
    }

    public function get_hourly_rate($skill_id="",$level_id=""){
        $this->db->select('rate')->from('tbl_con_skill_level');
        $this->db->where('skill_id',$skill_id);
        $this->db->where('level_id',$level_id);
        $res = $this->db->get()->row();
        if(isset($res->rate)){
            return $res->rate;
        }
        return 0 ;
    }

    function book_job($job_id=""){
        if(!empty($job_id)){
            $booking_number = $this->generate_booking_number();
            $this->db->where('id',$job_id);
            $this->db->update("tbl_job", array('booking_number' => $booking_number,'status' => 1)); 

            return $booking_number;
        }  
    }

    public function generate_booking_number(){

        while(1) {

            $booking_number = $this->generateRandomString(10,'num');
            $this->db->select('booking_number')->from('tbl_job');
            $this->db->where('booking_number',$booking_number);
            $count = $this->db->count_all_results();
  
            if($count < 1) {
                break;
            }
        }
        return $booking_number;
   
    }


    public function generate_quote_id($job_id=""){

        while(1) {

            $quote_id = $this->generateRandomString();
            $this->db->select('quote_id')->from('tbl_job');
            $this->db->where('quote_id',$quote_id);
            $count = $this->db->count_all_results();
  
            if($count < 1) {
                break;
            }
        }

        return $quote_id;
      
        // $paded = str_pad($randomNumber, 9, '0', STR_PAD_LEFT);

        // $delimited = '';

        // for($i = 0; $i < 9; $i++) {
        //     $delimited .= $paded[$i];
        //     if($i == 2 || $i == 5) {
        //         $delimited .= '-';
        //     }
        // }
        // $delimited; 
   
    }

    public function generateRandomString($length = 10,$char = '') {
        //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($char == 'num'){
            $characters = '0123456789';
        }
        if($char == 'alphanum'){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function get_industry($term="",$user_id=""){
        $this->db->select('id,name')->from('tbl_con_industry');
        if(!empty($term)){
            $where = "(name like '$term%')";
            $this->db->where($where);
        }
        $this->db->where('status',1);
        $this->db->limit(20);

        $res = $this->db->get()->result();

        return $res;
    }

    public function get_meeting_place($term="",$user_id=""){
        $this->db->select('meeting_full_address,meeting_street,meeting_postcode_id,meeting_lat,meeting_long')->from('tbl_job');
        if(!empty($term)){
            $where = "(meeting_full_address like '$term%')";
            $this->db->where($where);
        }
        $this->db->where('customer_user_id',$user_id);
        $res = $this->db->get()->result();
        return $res;
    }

    public function get_job($job_id="",$user_id=""){
        $this->db->select('*')->from('tbl_job');
        $this->db->where('customer_user_id',$user_id);
        $this->db->where('id',$job_id);
        $res = $this->db->get()->row();
        return $res;
    }

    public function generate_induction_number(){

        while(1) {

            $number = $this->generateRandomString(10,'alphanum');
            $this->db->select('induction_number')->from('tbl_job_induction');
            $this->db->where('induction_number',$number);
            $count = $this->db->count_all_results();
  
            if($count < 1) {
                break;
            }
        }
        return $number;
    }

    public function set_induction($array = array() ){
        $job_id = isset($array['job_id']) ? $array['job_id'] : 0 ;
        if(!empty($job_id)){
            $this->db->select('*')->from('tbl_job_induction')->where('job_id',$job_id);
            $res = $this->db->get()->row();
            if(!empty($res)){
                $this->db->where('id',$res->id);
                $this->db->update("tbl_job_induction", $array); 
                return $res->id;
            }
            else{
                $id = $this->db->insert("tbl_job_induction", $array); 
                return $id;
            }
        }
        return false;
    }

    public function get_qualifications($user_id=""){
        $ids = $this->get_admin_ids();
        $ids[] = $user_id; 

        $this->db->select('id,name,code')->from('tbl_con_qualification');
        $this->db->where_in('entered_by',$ids);
        $this->db->limit(20);
        $this->db->where('status',1);
        $res = $this->db->get()->result();

        return $res;
    }

    public function get_attributes($user_id=""){
        $ids = $this->get_admin_ids();

        $this->db->select('id,name,is_mandatory')->from('tbl_con_priority');
        $this->db->where_in('entered_by',$ids);
        $this->db->limit(20);
        $this->db->where('status',1);
        $res = $this->db->get()->result();

        return $res; 
    }

    public function set_attributes($attributes=array(),$job_id=""){
        if(!empty($attributes)){
            $arr = explode(',', $attributes);
            if(!empty($arr)){
                $batch = array();
                foreach ($arr as $k => $v) {
                   $batch[] = array(
                    'job_id' => $job_id,
                    'priority_id' => $v,
                    'order' => $k+1
                    );
                }
                $this->db->where('job_id',$job_id)->delete('tbl_job_attribute');
                $res = $this->db->insert_batch('tbl_job_attribute', $batch);
                return $res;
            }
        }
    }

    public function get_job_skills($id=""){
        $this->db->select('jd.id,jd.required_number,jd.total_hour, jd.start_date, s.name as skill, l.name as level,jd.start_date')->from('tbl_job_detail as jd');
        $this->db->join('tbl_con_skill as s','jd.skill_id = s.id');
        $this->db->join('tbl_con_level as l','jd.level_id = l.id');
        $this->db->where('jd.job_id',$id);
        $res = $this->db->get()->result();
        return $res;

    }

    public function generate_quote($job_id=""){
        $res = $this->db->select('quote_id')->from('tbl_job')->where('id',$job_id)->get()->row();
        if(isset($res->quote_id) && !empty($res->quote_id)){
            return $res->quote_id;
        }
        else{
            $quote_id = $this->generate_quote_id();
            $array = array('quote_id',$quote_id);
            $this->db->where('id',$job_id);
            $this->db->update("tbl_job", $array); 
            return $quote_id;
        }
    }

    public function check_google_login($google_id="",$email=""){
        $this->db->select('u.id,u.password')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('google_id',$google_id);
        $this->db->where('ug.group_id',CUSTOMER); 
        $this->db->where('u.email',$email); 
        $res = $this->db->get()->row();
        return $res;
    }

    public function check_customer_fb($facebook_id="",$email=""){
        $this->db->select('u.id,u.password')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('facebook_id',$facebook_id);
        $this->db->where('ug.group_id',CUSTOMER); 
        if(!empty($email)){
            $this->db->where('u.email',$email); 
        }
        $res = $this->db->get()->row();
        return $res;
    }

    public function check_if_customer($email=""){
        $this->db->select('u.id,u.name,u.email,u.full_address,u.profile_image')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('ug.group_id',CUSTOMER); 
        $this->db->where('u.email',$email); 
       
        $res = $this->db->get()->row();

        return $res;
    }

    public function check_customer_by_id($id=""){
        $this->db->select('u.id,register_from')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('ug.group_id',CUSTOMER); 
        $this->db->where('u.id',$id); 
       
        $res = $this->db->get()->row();

        return $res;
    }

    public function get_customer_attributes($user_id=""){
        $this->db->select('cp.order,p.name')->from('tbl_customer_priority as cp');
        $this->db->join('tbl_con_priority as p','p.id=cp.priority_id');
        $this->db->where('cp.customer_user_id',$user_id);
        $this->db->order_by('cp.order');
        $res = $this->db->get()->result();
       
        return $res;
    }

    public function get_customer_profile($user_id=""){
        $this->db->select('u.name,u.email,u.full_address,u.phone_number,u.description,i.default_break_time,u.profile_image')->from('tbl_user as u');
        $this->db->join('tbl_customer_add_info as i','u.id = i.customer_user_id','left');
        $this->db->where('u.id',$user_id);
        $res = $this->db->get()->row();
        return $res;
    }
    
    public function set_customer_attributes($attributes=array(),$user_id=""){
        if(!empty($attributes)){
            $arr = explode(',', $attributes);
            if(!empty($arr)){
                $batch = array();
                foreach ($arr as $k => $v) {
                   $batch[] = array(
                    'customer_user_id' => $user_id,
                    'priority_id' => $v,
                    'order' => $k+1
                    );
                }
                $this->db->where('customer_user_id',$user_id)->delete('tbl_customer_priority');
                $res = $this->db->insert_batch('tbl_customer_priority', $batch);
                return $res;
            }
        }
    }

    public function get_lhc_customer_association($customer_user_id=""){
        $query =  $this->db->select('lca.lhc_user_id,u.name,u.email,u.full_address')
            ->from('tbl_lhc_customer_association as lca')
            ->join('tbl_user as u','u.id = lca.lhc_user_id')
            ->where('lca.customer_user_id',$customer_user_id)
            ->where('lca.status',1);
        $res = $query->get()->result();

        $ret = new stdClass();
        $ret->lhcs = $res;
        $ret->count = count($res);
        return $ret;
    }

    public function check_lhc_customer_association($lhc_user_id="",$customer_user_id=""){
        $res = $this->db->select('id')
        ->from('tbl_lhc_customer_association')
        ->where('lhc_user_id',$lhc_user_id)
        ->where('customer_user_id',$customer_user_id)
        ->where('status',1)
        ->get()
        ->num_rows();
        if($res > 0 ){
            return true;
        }
        return false;
    }

    public function set_lhc_customer_association($lhc_user_id="",$customer_user_id=""){
        $res = $this->db->select('u.id')->from('tbl_user as u')->where('id',$lhc_user_id)->get()->row();
        if(!empty($res)){
            $array = array('lhc_user_id' => $lhc_user_id);
            $this->db->where('user_id',$customer_user_id);
            $this->db->update("keys", $array); 
            return $res->id;
        }
        return false;
    }

    public function get_jobs_oldw($user_id="",$lhc_user_id=0){
        $this->db->select('j.id,j.job_full_address,j.job_street,j.job_postcode_id,
            j.meeting_full_address,j.meeting_street,j.meeting_postcode_id,j.description,j.entered_date,s.name')
        ->from('tbl_job as j')
        ->join('tbl_job_detail as jd','jd.id = j.id')
        ->join('tbl_con_skill as s','jd.skill_id = s.id')
        ->where('j.customer_user_id',$user_id);
        if(!empty($lhc_user_id)){
            $this->db->where('j.lhc_user_id',$lhc_user_id);
        }
        $this->db->order_by('j.entered_date','desc');
        $res = $this->db->get()->result();
        return $res;
    }  


    public function get_jobs_mmm($user_id="",$lhc_user_id=0,$date=0,$specific_date=false){
        $all_jobs = array();
        $this->db->select('j.id as job_id,j.job_full_address,j.status as job_status,j.shift_status')
            ->from('tbl_job as j')
            ->where('j.customer_user_id',$user_id);
        if(!empty($lhc_user_id)){
            $this->db->where('j.lhc_user_id',$lhc_user_id);
        }
        $jobs = $this->db->get()->result();
        $job_ids = array();
        if(!empty($jobs)){
            foreach ($jobs as $j) {
                $job_ids[] = $j->job_id;
            }
        }
        if(!empty($job_ids)){
            $this->db->select('jd.job_id,jd.start_date,jd.start_time,jd.end_time,s.name as skill')
            ->from('tbl_job_detail as jd')
            ->join('tbl_con_skill as s','jd.skill_id = s.id')
            ->where_in('jd.job_id',$job_ids)
            ->order_by('jd.start_date','asc');
            $shifts = $this->db->get()->result();
            $job_shifts = array();
            $skills_array = array();
            if(!empty($shifts)){
                foreach ($shifts as $s) {
                    $job_shifts[$s->job_id][] = $s; 
                    $skills_array[$s->job_id][] = $s->skill;
                }
            }
        }
        if(!empty($jobs)){
            foreach ($jobs as $j) {
                if(isset($job_shifts[$j->job_id]) && isset($skills_array[$j->job_id])){
                    $job = new stdClass();
                    $job = $j;
                    $job->start_date = $job_shifts[$j->job_id][0]->start_date;
                    $job->end_date = end($job_shifts[$j->job_id])->start_date;
                    $job->skills = $skills_array[$j->job_id];
                    $all_jobs[] = $job;
                }  
            }
        }
        if(!empty($all_jobs)){
            usort($all_jobs, function($a, $b) {
                return strtotime($a->start_date) - strtotime($b->start_date);
            });
        }
        return $all_jobs;
    }  

    public function get_jobs($user_id="",$lhc_user_id=0,$date=0,$specific_date=false){
        $this->db->select('j.id as job_id,jd.id as shift_id, j.job_full_address,j.job_street,jd.shift_status,
            jd.start_date,jd.start_time,jd.end_time,s.name as skill,j.status as job_status')
        ->from('tbl_job_detail as jd')
        ->join('tbl_con_skill as s','jd.skill_id = s.id')
        ->join('tbl_job as j','j.id = jd.job_id')
        ->where('j.customer_user_id',$user_id);
        if(!empty($lhc_user_id)){
            $this->db->where('j.lhc_user_id',$lhc_user_id);
        }
        if(!empty($date)){
            $timestamp    = strtotime($date);
            $start = date('Y-m-01', $timestamp);
            $end  = date('Y-m-t', $timestamp);
            if($specific_date){
                $start = date('Y-m-d', $timestamp);
                $end  = date('Y-m-d', $timestamp);
            }
            $this->db->where('DATE(jd.start_date) >=', $start);
            $this->db->where('DATE(jd.start_date) <=', $end);
        }
        $this->db->limit(20);
        $this->db->group_by('jd.id');
        $this->db->order_by('jd.start_date','asc');

        $res = $this->db->get()->result();

        return $res;
        
        $shifts = array();
        $final_shifts = array();
        if(!empty($res)){
            foreach ($res as $shift) {
                $shifts[$shift->start_date][] = $shift;
            }
            foreach ($shifts as $s) {
                $a = array(
                    'date' => $s[0]->start_date,
                    'jobs' => $s
                );
                $final_shifts[] = $a;
            }
        }
        return $final_shifts;
    }  

    function get_peak_activity_level(){
        $this->db->select('peak_price_factor,filling_probability')->from('tbl_con_activity_level')->where('type','Peak');
        $res = $this->db->get()->result();
        return $res;
    }

    public function get_supervisor_info($id = "",$user_id=""){
        $this->db->select('u.id,u.name,u.email,u.phone_number,u.full_address,IFNULL(u.profile_image,"") as profile_image',FALSE)->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
        $this->db->where('ug.group_id',SUPERVISOR); 
        $this->db->where('u.customer_user_id',$user_id); 
        $this->db->where('u.id',$id); 
        $res = $this->db->get()->row();

        return $res;
    }   

    public function validate_shift($shift_id,$user_id){
        $this->db->select('jd.id,j.status')
            ->from('tbl_job_detail as jd')
            ->join('tbl_job as j','jd.job_id = j.id')
            ->where('jd.id',$shift_id)
            ->where('j.customer_user_id',$user_id);
        $res = $this->db->get()->row();
        return $res;
    }

    public function delete_shift($shift_id=""){

        if($this->db->where('job_detail_id',$shift_id)->delete('tbl_job_detail_break')){
            return $this->db->where('id',$shift_id)->delete('tbl_job_detail');
        }
        
    }

    public function delete_job($job_id=""){
        if($this->delete_job_detail($job_id)){
            return $this->db->where('id',$job_id)->delete('tbl_job');
        }
        return false;
    }

    public function delete_job_detail($job_id=""){
        $res = $this->db->select('id')->from('tbl_job_detail')->where('job_id',$job_id)->get()->result();
        $detail_ids = array();
        if(!empty($res)){
            foreach ($res as $r) {
               $detail_ids[] = $r->id;
            }
            
        }
        $res = $this->db->select('id')->from('tbl_job_alert')->where_in('job_detail_id',$detail_ids)->get()->row();
        if(!empty($res)){
            return false;
        }

        if(!empty($detail_ids)){
            $this->db->where_in('job_detail_id',$detail_ids)->delete('tbl_job_detail_break');
            return $this->db->where_in('id',$detail_ids)->delete('tbl_job_detail');
        }
       
    }

    public function validate_job($job_id,$user_id){
        $this->db->select('j.id,j.status')
            ->from('tbl_job as j')
            ->join('tbl_job_detail as jd','jd.job_id = j.id')
            ->where('j.id',$job_id)
            ->where('j.customer_user_id',$user_id);
        $res = $this->db->get()->row();
        return $res;
    }

	 
}
