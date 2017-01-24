<?php
class Model_wage_rate extends MY_Model 
{
		
    public function __construct() 
    {
            parent::__construct();

    }
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
     * Insert update special rate
     * @param type $action
     * @return boolean
     */
    public function s_wr_rule_add_edit($action) //insert or update
    {
            $table_data = array(
            'country_id' => $this->input->post('sp_country_id'),
            'country_region_id' => $this->input->post('sp_region_id'),
            'skill_id' => $this->input->post('sp_skill_id'),
            'level_id' => $this->input->post('sp_level_id'),
            'calendar_id' => $this->input->post('sp_calendar_id'),
            'lhc_charge' => $this->input->post('sp_lp_charge'),
            'time_from' => $this->input->post('sp_time_from'),
            'time_to' => $this->input->post('sp_time_to'),
            'customer_rule_name' => $this->input->post('customer_rule_name'),
            'lhc_rule_name' => $this->input->post('lhc_rule_name'),
            'pay_rate' => $this->input->post('sp_manual_rate'),
            'pay_times' => $this->input->post('sp_times'),
            'shift_type_id' => $this->input->post('sp_shift_type_id'),
        );
         
        //start the transaction
       $this->db->trans_begin(); 
        if($action == "insert")
        { 
           $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_wr_special_day', $table_data);    
            
             
        }else{
             //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_wr_special_day', $table_data);

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
     * Insert update Overtime rate
     * @param type $action
     * @return boolean
     */
    public function overtime_wr_rule_add_edit($action) //insert or update
    {
        
            $table_data = array(
            'country_id' => $this->input->post('sp_country_id'),
            'country_region_id' => $this->input->post('sp_region_id'),
            'skill_id' => $this->input->post('sp_skill_id'),
            'level_id' => $this->input->post('sp_level_id'),
            'interval_id' => $this->input->post('p_interval'),
            'applicable_for_all'=>($this->input->post('applicable_for_all'))?1:'',
            'day_id' => $this->input->post('p_day'),
            'threshold_hour' => $this->input->post('threshold_hour'),
            'lhc_charge' => $this->input->post('sp_lp_charge'),
            'customer_rule_name' => $this->input->post('customer_rule_name'),
            'lhc_rule_name' => $this->input->post('lhc_rule_name'),
            'pay_rate' => $this->input->post('sp_manual_rate'),
            'pay_times' => $this->input->post('sp_times'),
            'shift_type_id' => $this->input->post('sp_shift_type_id'),
        );
         
        //start the transaction
       $this->db->trans_begin(); 
        if($action == "insert")
        { 
           $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_wr_overtime', $table_data); 
             
             
        }else{
             //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_wr_overtime', $table_data);

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
     * Insert update Breaks not taken rate
     * @param type $action
     * @return boolean
     */
    public function breaks_not_taken_wr_rule_add_edit($action) //insert or update
    {
        
            $table_data = array(
            'country_id' => $this->input->post('sp_country_id'),
            'country_region_id' => $this->input->post('sp_region_id'),
            'skill_id' => $this->input->post('sp_skill_id'),
            'level_id' => $this->input->post('sp_level_id'),
            'day_id' => $this->input->post('p_day'),
            'lhc_charge' => $this->input->post('sp_lp_charge'),
            'customer_rule_name' => $this->input->post('customer_rule_name'),
            'lhc_rule_name' => $this->input->post('lhc_rule_name'),
            'pay_rate' => $this->input->post('sp_manual_rate'),
            'shift_type_id' => $this->input->post('sp_shift_type_id'),
        );
         
        //start the transaction
       $this->db->trans_begin(); 
        if($action == "insert")
        { 
           $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_wr_bk_not_taken', $table_data); 
             
             
        }else{
             //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_wr_bk_not_taken', $table_data);

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
     * Insert update Work Hour 
     * @param type $action
     * @return boolean
     */
    public function work_hour_wr_rule_add_edit($action) //insert or update
    {
        
            $table_data = array(
            'country_id' => $this->input->post('sp_country_id'),
            'country_region_id' => $this->input->post('sp_region_id'),
            'skill_id' => $this->input->post('sp_skill_id'),
            'level_id' => $this->input->post('sp_level_id'),
            'day_id' => $this->input->post('p_day'),
            'lhc_charge' => $this->input->post('sp_lp_charge'),
            'customer_rule_name' => $this->input->post('customer_rule_name'),
            'lhc_rule_name' => $this->input->post('lhc_rule_name'),
            'pay_rate' => $this->input->post('sp_manual_rate'),
            'shift_type_id' => $this->input->post('sp_shift_type_id'),
    );
         
        //start the transaction
       $this->db->trans_begin(); 
        if($action == "insert")
        { 
           $table_data['entered_by'] = $this->session->userdata('user_id');
            $this->db->insert('tbl_wr_work_hour', $table_data); 
             
             
        }else{
             //add extra column to be updated
            $table_data['updated_date'] = date('Y-m-d');
            $table_data['updated_by'] = $this->session->userdata('user_id');
            //update user_account table
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_wr_work_hour', $table_data);

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
     * Insert update Work Hour 
     * @param type $action
     * @return boolean
     */
    public function copy_wr_rule($action) //insert or update
    {
        
        if($this->input->post('srch_skill_id') && $this->input->post('sp_level_id')!="" ){ //take skill from target
           $skill_id  = $this->input->post('srch_skill_id');
        }else{
            $skill_id  = $this->input->post('sp_skill_id');
        }
      
        //copy work hour rule
        $copy_work_hour_query = "INSERT INTO tbl_wr_work_hour (country_id, country_region_id, skill_id,level_id,day_id,
customer_rule_name,lhc_rule_name,lhc_charge,shift_type_id,pay_rate,entered_by
 ) SELECT country_id, country_region_id, '".intval($skill_id)."','".intval($this->input->post('sp_level_id'))."',day_id,
customer_rule_name,lhc_rule_name,lhc_charge,shift_type_id,pay_rate,".$this->session->userdata('user_id')."
FROM   tbl_wr_work_hour where 1=1 ";
         //copy special day rule
        $copy_special_day_query = "INSERT INTO tbl_wr_special_day (country_id, country_region_id, skill_id,level_id,calendar_id,
time_from,time_to,
customer_rule_name,lhc_rule_name,lhc_charge,shift_type_id,pay_rate,pay_times,entered_by
 )
  SELECT country_id, country_region_id, '".intval($skill_id)."','".intval($this->input->post('sp_level_id'))."',calendar_id,
time_from,time_to,
customer_rule_name,lhc_rule_name,lhc_charge,shift_type_id,pay_rate,pay_times,".$this->session->userdata('user_id')."
FROM   tbl_wr_special_day where 1=1 ";
        
         //copy overtime day rule
        $copy_overtime_query = "INSERT INTO tbl_wr_overtime (country_id, country_region_id, skill_id,level_id,day_id,interval_id,threshold_hour,
applicable_for_all, 
customer_rule_name,lhc_rule_name,lhc_charge,shift_type_id,pay_rate,pay_times,entered_by
 ) 
  SELECT country_id, country_region_id, '".intval($skill_id)."','".intval($this->input->post('sp_level_id'))."',day_id,interval_id,threshold_hour,
applicable_for_all, 
customer_rule_name,lhc_rule_name,lhc_charge,shift_type_id,pay_rate,pay_times,".$this->session->userdata('user_id')."
FROM   tbl_wr_overtime where 1=1 ";
        
         //copy break not taken 
        $copy_bk_not_taken_query = "INSERT INTO tbl_wr_bk_not_taken (country_id, country_region_id, skill_id,level_id,day_id,
 
customer_rule_name,lhc_rule_name,lhc_charge,shift_type_id,pay_rate,entered_by
 )
  SELECT country_id, country_region_id, '".intval($skill_id)."','".intval($this->input->post('sp_level_id'))."',day_id,
 
customer_rule_name,lhc_rule_name,lhc_charge,shift_type_id,pay_rate,".$this->session->userdata('user_id')."
FROM   tbl_wr_bk_not_taken where 1=1 ";
        if($this->input->post('srch_country_id')){
            $copy_special_day_sub_query .= "  AND  country_id = '".$this->input->post('srch_country_id')."'";
        }
        if($this->input->post('srch_region_id')){
            $copy_special_day_sub_query .= "  AND  country_region_id = '".$this->input->post('srch_region_id')."'";
        }
        if($this->input->post('srch_skill_id')){
            $copy_special_day_sub_query .= "  AND  skill_id = '".$this->input->post('srch_skill_id')."'";
        }
        if($this->input->post('srch_level_id')){
            $copy_special_day_sub_query .= "  AND  level_id = '".$this->input->post('srch_level_id')."'";
        }
        
       
        //make select query
        
        //insert
         
         
        //start the transaction
       $this->db->trans_begin(); 
       
       //copy special rule
       $copy_special_day_query = $copy_special_day_query.$copy_special_day_sub_query;
      
       
       $this->db->query($copy_special_day_query);
       
      
        //copy work hour rule
       $copy_work_hour_query = $copy_work_hour_query.$copy_special_day_sub_query;
       $this->db->query($copy_work_hour_query);
       //copy break not taken rule
       $copy_overtime_query = $copy_overtime_query.$copy_special_day_sub_query;
       $this->db->query($copy_overtime_query);
       //copy overtime 
       $copy_bk_not_taken_query = $copy_bk_not_taken_query.$copy_special_day_sub_query;
       $this->db->query($copy_bk_not_taken_query);
        
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
    
    
    
    
    public function getOvertimeWR(){
        /*SELECT 
  *,
  (SELECT 
    NAME 
  FROM
    tbl_wr_day 
  WHERE day_id =tbl_wr_overtime.day_id) AS day_name,
  (SELECT 
    NAME 
  FROM
    tbl_wr_interval
  WHERE id = interval_id) AS interval_name,
  (SELECT 
    NAME 
  FROM
    tbl_wr_shift_type 
  WHERE id = shift_type_id) AS shift_name 
FROM
  tbl_wr_overtime  */
     
      $this->db->select('wr_overtime.*,
  (SELECT 
    NAME 
  FROM
    tbl_wr_day 
  WHERE day_id =wr_overtime.day_id) AS day_name,
  wr_int.name AS interval_name,
  (SELECT 
    NAME 
  FROM
    tbl_wr_shift_type 
  WHERE id = shift_type_id) AS shift_name ')
              ->from('tbl_wr_overtime as wr_overtime')
              ->join('tbl_wr_interval wr_int','wr_overtime.interval_id=wr_int.id')
              ->order_by('wr_overtime.order_by','ASC');
      if($this->input->post('country_id')){
          $this->db->where('wr_overtime.country_id',$this->input->post('country_id'));
      }
      if($this->input->post('region_id')){
          $this->db->where('wr_overtime.country_region_id',$this->input->post('region_id'));
      }
      if(!empty($_POST['skill_id'])){
          $this->db->where('wr_overtime.skill_id',$this->input->post('skill_id'));
      }
      
       if(!empty($_POST['level_id'])){
          $this->db->where('wr_overtime.level_id',$this->input->post('level_id'));
      }
      if($this->input->post('overtime_wr_id')){
          $this->db->where('wr_overtime.id',$this->input->post('overtime_wr_id'));
          return  $this->db->get()->row();
      }else{
           return  $this->db->get()->result();
      }
       
      
          
    }
    
    
     public function getBreakNotTakenWR(){
        /*SELECT 
  wr_bk.*,
  wr_shift.`name` AS shift_name,
  wr_day.name AS day_name
 
FROM
  tbl_wr_bk_not_taken AS wr_bk,
  tbl_wr_shift_type  AS wr_shift,
  tbl_wr_day AS wr_day
  
  WHERE 
  
  wr_bk.shift_type_id = wr_shift.id 
  AND wr_bk.day_id = wr_day.day_id
   */
     
      $this->db->select('wr_bk.*,
  wr_shift.`name` AS shift_name,
  wr_day.name AS day_name,
  wr_shift.time_from,
  wr_shift.time_to
 ')
              ->from('tbl_wr_bk_not_taken as wr_bk')
              ->join('tbl_wr_shift_type wr_shift','wr_bk.shift_type_id = wr_shift.id')
              ->join('tbl_wr_day wr_day','wr_bk.day_id = wr_day.id')
              ->order_by('wr_bk.order_by','ASC');
      if($this->input->post('country_id')){
          $this->db->where('wr_bk.country_id',$this->input->post('country_id'));
      }
      if($this->input->post('region_id')){
          $this->db->where('wr_bk.country_region_id',$this->input->post('region_id'));
      }
      if(!empty($_POST['skill_id'])){
          $this->db->where('wr_bk.skill_id',$this->input->post('skill_id'));
      }
      
       if(!empty($_POST['level_id'])){
          $this->db->where('wr_bk.level_id',$this->input->post('level_id'));
      }
      if($this->input->post('breaks_not_taken_wr_id')){
          $this->db->where('wr_bk.id',$this->input->post('breaks_not_taken_wr_id'));
          return  $this->db->get()->row();
      }else{
           return  $this->db->get()->result();
      }
       
      
          
    }
    
     public function getWorkHourWR(){
        /*SELECT 
  wr_hr.*,
  wr_shift.`name` AS shift_name,
  wr_day.name AS day_name,
  wr_shift.time_from,
  wr_shift.time_to
  
FROM
  tbl_wr_work_hour AS wr_hr,
  tbl_wr_shift_type  AS wr_shift,
  tbl_wr_day AS wr_day
  
  WHERE 
  
  wr_hr.shift_type_id = wr_shift.id 
  AND wr_bk.day_id = wr_day.day_id
   */
     
      $this->db->select('wr_hr.*,
  wr_shift.`name` AS shift_name,
  wr_day.name AS day_name,
  wr_shift.time_from,
  wr_shift.time_to
 ')
              ->from('tbl_wr_work_hour as wr_hr')
              ->join('tbl_wr_shift_type wr_shift','wr_hr.shift_type_id = wr_shift.id')
              ->join('tbl_wr_day wr_day','wr_hr.day_id = wr_day.day_id')
              ->order_by('wr_hr.order_by','ASC');
      if($this->input->post('country_id')){
          $this->db->where('wr_hr.country_id',$this->input->post('country_id'));
      }
      if($this->input->post('region_id')){
          $this->db->where('wr_hr.country_region_id',$this->input->post('region_id'));
      }
      if(!empty($_POST['skill_id'])){
          $this->db->where('wr_hr.skill_id',$this->input->post('skill_id'));
      }
      
       if(!empty($_POST['level_id'])){
          $this->db->where('wr_hr.level_id',$this->input->post('level_id'));
      }
      if($this->input->post('work_hour_id')){
          $this->db->where('wr_hr.id',$this->input->post('work_hour_id'));
          return  $this->db->get()->row();
      }else{
           return  $this->db->get()->result();
      }
       
      
          
    }
    
    public function getSpecialWR(){
        /*SELECT *,(SELECT DATE FROM tbl_wr_calendar WHERE id=calendar_id) AS day_name,
 (SELECT NAME  FROM tbl_wr_shift_type WHERE id=shift_type_id) AS shift_name
 FROM tbl_wr_special_day */
     
      $this->db->select('wr_sp_day.*,wr_cal.date as day_name,wr_cal.year,
 (SELECT NAME  FROM tbl_wr_shift_type WHERE id=shift_type_id) AS shift_name')
              ->from('tbl_wr_special_day as wr_sp_day')
              ->join('tbl_wr_calendar wr_cal','wr_sp_day.calendar_id=wr_cal.id')
              ->order_by('wr_sp_day.order_by','ASC')
              ->order_by('wr_sp_day.order_by','ASC');
      if($this->input->post('country_id')){
          $this->db->where('wr_sp_day.country_id',$this->input->post('country_id'));
      }
      if($this->input->post('region_id')){
          $this->db->where('wr_sp_day.country_region_id',$this->input->post('region_id'));
      }
      if($this->input->post('skill_id')){
          $this->db->where('wr_sp_day.skill_id',$this->input->post('skill_id'));
      }
       if($this->input->post('level_id')){
          $this->db->where('wr_sp_day.level_id',$this->input->post('level_id'));
      }
      if($this->input->post('calendar_year')){
          $this->db->where('wr_cal.year',$this->input->post('calendar_year'));
      }
      if($this->input->post('special_wr_id')){
          $this->db->where('wr_sp_day.id',$this->input->post('special_wr_id'));
          return  $this->db->get()->row();
      }else{
           return  $this->db->get()->result();
      }
       
      
          
    }
    
    public function checkSpecialRate(){
      $this->db->select('count(*) total')->from('tbl_wr_special_day')
                ->where(array('country_id'=>$this->input->post('sp_country_id'),
                    'country_region_id'=>$this->input->post('sp_region_id'),
                    'skill_id'=>$this->input->post('sp_skill_id'),
                    'level_id'=>$this->input->post('sp_level_id'),
                    'calendar_id'=>$this->input->post('sp_calendar_id'),
                    'time_from'=>$this->input->post('sp_time_from'),
                    'time_to'=>$this->input->post('sp_time_to')
                ));
       if($this->input->post('hidden_id') && $this->input->post('hidden_id') != ''){
           $this->db->where('id <>',$this->input->post('hidden_id'));
       }
                $check_special_rate =  $this->db->get()->row();
               
        
        if($check_special_rate->total > 0){
            return TRUE;
        }else{
            
            return FALSE;
        }
    
    }
    
     public function checkOvertimeRate(){
       $this->db->select('count(*) total')->from('tbl_wr_overtime')
                ->where(array('country_id'=>$this->input->post('sp_country_id'),
                    'country_region_id'=>$this->input->post('sp_region_id'),
                    'skill_id'=>$this->input->post('sp_skill_id'),
                    'level_id'=>$this->input->post('sp_level_id'),
                    'interval_id'=>$this->input->post('p_interval'),
                    'day_id'=>$this->input->post('p_day'),
                    'threshold_hour'=>$this->input->post('threshold_hour')
                ));
       if($this->input->post('hidden_id') && $this->input->post('hidden_id') != '' ){
           $this->db->where('id <>',$this->input->post('hidden_id'));
       }
               $check_special_rate =  $this->db->get()->row();
              //echo $this->db->last_query();
        
        if($check_special_rate->total > 0){
            return TRUE;
        }else{
            
            return FALSE;
        }
    
    }
    
     public function checkBreakNotTakenRate(){
       $this->db->select('count(*) total')->from('tbl_wr_bk_not_taken')
                ->where(array('country_id'=>$this->input->post('sp_country_id'),
                    'country_region_id'=>$this->input->post('sp_region_id'),
                    'skill_id'=>$this->input->post('sp_skill_id'),
                    'level_id'=>$this->input->post('sp_level_id'), 
                    'day_id'=>$this->input->post('p_day'),
                    'shift_type_id'=>$this->input->post('sp_shift_type_id')
                ));
       if($this->input->post('hidden_id') ){
           $this->db->where('id <>',$this->input->post('hidden_id'));
       }
               $check_special_rate =  $this->db->get()->row();
        
        if($check_special_rate->total > 0){
            return TRUE;
        }else{
            
            return FALSE;
        }
    
    }
    
     public function checkWorkHourRate(){
        $this->db->select('count(*) total')->from('tbl_wr_work_hour')
                ->where(array('country_id'=>$this->input->post('sp_country_id'),
                    'country_region_id'=>$this->input->post('sp_region_id'),
                    'skill_id'=>intval($this->input->post('sp_skill_id')),
                    'level_id'=>intval($this->input->post('sp_level_id')), 
                    'day_id'=>$this->input->post('p_day'),
                    'shift_type_id'=>$this->input->post('sp_shift_type_id')
                ));
       if($this->input->post('hidden_id')){
           $this->db->where('id <>',$this->input->post('hidden_id'));
       }
         
       $check_special_rate = $this->db->get()->row();
        
               
           
        
        if($check_special_rate->total > 0){
            return TRUE;
        }else{
            
            return FALSE;
        }
    
    }
    
    

}
