<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Wage_rate extends Admin_Controller {
   
   public function __construct()
	{
		parent::__construct();
		 $this->template->set_layout('admin/default');
                 $this->load->model('master_data/model_master_data');
                 $this->load->model('model_master_data_view');
                 $this->load->model('model_wage_rate');
               
            
	}
   public function index(){
      
           $data = array();
	   $data['page_url'] = "admin/master_data/industry";
	   $data['page_action'] = $page_action;
           $data['page_title'] = "Wage Rates";
           $countries = $this->model_master_data_view->getCountry();
           $regions = $this->model_master_data_view->getRegion();
           
           $skill = $this->model_master_data_view->getSkillSubskill($skill_id=NULL,$level_id=NULL,'skill');
           $years = $this->model_master_data_view->getCalendarYear('year');
           
           $shift_type = $this->model_master_data_view->getWRshiftType(NULL,1);
           $special_wage_rate_rule = $this->model_wage_rate->getSpecialWR();
           $overtime_wage_rate_rule = $this->model_wage_rate->getOvertimeWR();
           $breaks_not_taken_wage_rate_rule = $this->model_wage_rate->getBreakNotTakenWR();
           $work_hour_rate_rule = $this->model_wage_rate->getWorkHourWR();
           
          // echo $this->db->last_query();exit;
           
         //  echo $this->db->last_query();exit;
           $shift_details =$this->model_master_data_view->getWRshiftType(NULL,1);
           if($this->input->post('calendar_year')){
               $year = $this->input->post('calendar_year');
           }else{
               $year = date('Y');
           }
           $calendar_day = $this->model_master_data_view->getCalendarYear($year);
           
        
         
           $data['skills'] = $skill;
           $data['days'] = $days;
           $data['countries'] = $countries;
           $data['regions'] = $regions;
           $data['years'] = $years;
           $data['shift_type'] = $shift_type;
           $data['special_wage_rate_rule'] = $special_wage_rate_rule;
           $data['overtime_wage_rate_rule'] = $overtime_wage_rate_rule;
           $data['breaks_not_taken_wage_rate_rule'] = $breaks_not_taken_wage_rate_rule;
           $data['work_hour_rate_rule'] = $work_hour_rate_rule;
           $data['shift_details']=$shift_details;
           $data['calendar_days'] = $calendar_day;
           $data['selected_year'] = $year;
           
           $this->template->build('wage_rate',$data);
       
   }
        
   /**   
    * Uses to fetch subskill of  skill (Ajax Call)
    */     
   public function get_subskill(){
       $skill_id = $this->input->post('skill_id');
       $level = '<option value="">All</option>';
       if(!empty($skill_id)){
       $skill_level = $this->model_master_data_view->getSkillSubskill($skill_id,NULL,'level');
       $sel_level_id = $this->input->post('level_id'); 
       
       if($skill_level){
           foreach($skill_level as $sl){
               $sel = '';
               if($sel_level_id == $sl->level_id)
                   $sel='selected';
             $level .= '<option value="'.$sl->level_id.'" '.$sel.' >'.$sl->level_name.'</option>';  
           }
       }
       }
       echo $level;exit;
  
   }
   /**   
    * Uses to fetch holiday calendar day of  year (Ajax Call)
    */     
   public function get_caledardays(){
       $country_id = $this->input->post('country_id');
       $state = $this->input->post('region_id');
       $year = $this->input->post('year');
       $target_destination = $this->input->post('target');
       $calendar_day = $this->model_master_data_view->getCalendarYear($year);
       
//      / echo $this->db->last_query();exit;
        
       $calendar_day_option = "";
       
       $calendar_day_option = '<option value="">Select day</option>';
       $calendar_day_dates = array();
       if($calendar_day){
           foreach($calendar_day as $days){
               if($target_destination == 'calendar_day_dates'){
                   $calendar_day_dates[] = date('d M',strtotime($days->date));
               }else{
             $calendar_day_option .= '<option value="'.$days->id.'">('.date('d M',strtotime($days->date)).') '.$days->name.'</option>';  
               }
             
               }
       }
       if($target_destination == 'calendar_day_dates'){
       echo implode(',',$calendar_day_dates);exit;
       }else{
       echo $calendar_day_option;exit;
       }
  
   }
   
   public function saveSpecialRate(){
     //check validation
      $error_msg = array();
      //check unique
      $_POST['wr_special_rule_id'] = $this->input->post('hidden_id');
      if($this->model_wage_rate->checkSpecialRate()){
          $error_msg['status'] = 0;
          $error_msg['msg'] = 'Data already exist for the country,state,skill,subskill,calendar day,from and to';
      }else{
      
            if($this->input->post('hidden_id')){
                $action = 'edit';
            }else{
                $action = 'insert';
            }
            if($this->model_wage_rate->s_wr_rule_add_edit($action)){
                 $error_msg['status'] = 1;
                 $error_msg['msg'] = 'Saved successfully';
            }else{
                $error_msg['status'] = 0;
                 $error_msg['msg'] = 'Error occured while saving data';
            }
      }
     echo json_encode($error_msg);
    exit;
   }
   
   
   
   public function removeWRRule(){
       $load_wr = $this->input->post('load_wr');
       $data_id = $this->input->post('data_id');
       if($load_wr == 'special_rate'){
       $query = $this->db->query("CALL sp_removeWRRule('tbl_wr_special_day','{$data_id}')");
       }else if($load_wr == 'overtime_rate'){
           $query = $this->db->query("CALL sp_removeWRRule('tbl_wr_overtime','{$data_id}')");
       }else if($load_wr == 'breaks_not_taken'){
           $query = $this->db->query("CALL sp_removeWRRule('tbl_wr_bk_not_taken','{$data_id}')");
       }
       else if($load_wr == 'work_hours'){
           $query = $this->db->query("CALL sp_removeWRRule('tbl_wr_work_hour','{$data_id}')");
       }
       $response = array();
          $this->db->freeDBResource();
                 if($query)
                 {
                     $result = $query->row();
                    
                     //set delete message
                     $response['status'] = $result->status;
                     $response['msg'] = $this->model_master_data->get_master_data_mapping_delete_status($result);
                 }
                 else
                 {
                     $response['status'] = 0;
                     $response['msg'] = 'Unknown error.';
                     
                 }    
             echo json_encode($response);exit;   
       }
       
       public function saveOvertimeRate(){
           
     //check validation
      $error_msg = array();
      //check unique
      $_POST['overtime_wr_id'] = $this->input->post('hidden_id');
      if($this->model_wage_rate->checkOvertimeRate()){
          $error_msg['status'] = 0;
          $error_msg['msg'] = 'Data already exist for the country,state,skill,subskill,interval,day and threshold hour';
      }else{ 
      
            if($this->input->post('hidden_id')){
                $action = 'edit';
            }else{
                $action = 'insert';
            }
            
            if($this->model_wage_rate->overtime_wr_rule_add_edit($action)){
                 $error_msg['status'] = 1;
                 $error_msg['msg'] = 'Saved successfully';
            }else{
                $error_msg['status'] = 0;
                 $error_msg['msg'] = 'Error occured while saving data';
            }
     }
     echo json_encode($error_msg);
    exit;
   }
    public function saveBreakNotTaken(){
           
     //check validation
      $error_msg = array();
      //check unique
      $_POST['break_not_taken_rule_id'] = $this->input->post('hidden_id');
      if($this->model_wage_rate->checkBreakNotTakenRate()){
          $error_msg['status'] = 0;
          $error_msg['msg'] = 'Data already exist for the country,state,skill,subskill,day and shift';
      }else{ 
      
            if($this->input->post('hidden_id')){
                $action = 'edit';
            }else{
                $action = 'insert';
            }
            
            if($this->model_wage_rate->breaks_not_taken_wr_rule_add_edit($action)){
                 $error_msg['status'] = 1;
                 $error_msg['msg'] = 'Saved successfully';
            }else{
                $error_msg['status'] = 0;
                 $error_msg['msg'] = 'Error occured while saving data';
            }
     }
     echo json_encode($error_msg);
    exit;
   }
   
    public function saveWorkHour(){
           
     //check validation
      $error_msg = array();
      //check unique
      $_POST['work_hour_rule_id'] = $this->input->post('hidden_id');
       
       if($this->model_wage_rate->checkWorkHourRate()){
          $error_msg['status'] = 0;
          $error_msg['msg'] = 'Data already exist for the country,state,skill,subskill, day and shift';
      }else{ 
      
            if($this->input->post('hidden_id')){
                $action = 'edit';
            }else{
                $action = 'insert';
            }
            
            if($this->model_wage_rate->work_hour_wr_rule_add_edit($action)){
                 $error_msg['status'] = 1;
                 $error_msg['msg'] = 'Saved successfully';
            }else{
                $error_msg['status'] = 0;
                 $error_msg['msg'] = 'Error occured while saving data';
            }
     }
     echo json_encode($error_msg);
    exit;
   }
   
   
    public function copyWRRule(){
         
     //check validation
      $error_msg = array();
      //check unique
    
            if($this->model_wage_rate->copy_wr_rule($action)){
                 $error_msg['status'] = 1;
                 $error_msg['msg'] = 'Saved successfully';
            }else{
                $error_msg['status'] = 0;
                 $error_msg['msg'] = 'Error occured while saving data';
            }
     
     echo json_encode($error_msg);
    exit;
   }
   
   
   
   
   /**
    * Change wr order 
    */
      function changeWRTableOrder(){
       $table_master = $this->input->post('master_data');
       $data_table_id_index = json_decode($this->input->post('tbl_id'));
       if(count($data_table_id_index)>0){
           foreach($data_table_id_index as $id_index){
               $data = array();
               if($table_master == 'special_rate'){
                   
                   $data  = array('order_by'=>$id_index->index);
                   $this->db->where('id',$id_index->data_id);
                   $this->db->update('tbl_wr_special_day',$data);
                    
               }
               if($table_master == 'overtime'){
                   
                   $data  = array('order_by'=>$id_index->index);
                   $this->db->where('id',$id_index->data_id);
                   $this->db->update('tbl_wr_overtime',$data);
                    
               }
               if($table_master == 'breaks_not_taken'){
                   
                   $data  = array('order_by'=>$id_index->index);
                   $this->db->where('id',$id_index->data_id);
                   $this->db->update('tbl_wr_bk_not_taken',$data);
                    
               }
                if($table_master == 'work_hours'){
                   
                   $data  = array('order_by'=>$id_index->index);
                   $this->db->where('id',$id_index->data_id);
                   $this->db->update('tbl_wr_work_hour',$data);
                   echo $this->db->last_query();
                    
               }
               
           }
       }
       echo 1;
   }
   
   /*
    * WR form loader
    */
   public function loadWRForm(){
        
           $countries = $this->model_master_data_view->getCountry();
           $regions = $this->model_master_data_view->getRegion();
           $skill = $this->model_master_data_view->getSkillSubskill($skill_id=NULL,$level_id=NULL,'skill');
            $shift_type = $this->model_master_data_view->getWRshiftType(NULL,1);
           $data['skills'] = $skill;
           $data['countries'] = $countries;
           $data['regions'] = $regions;
           $data['shift_type'] = $shift_type;
           if($this->input->post('load_wr')== 'special_rate' || $this->input->post('load_wr')== 'special_rate_copy'){
           
           $years = $this->model_master_data_view->getCalendarYear('year');
           //echo $this->db->last_query();
          
           $_POST['special_wr_id']=$this->input->post('data_id');
           $special_wage_rate_rule = $this->model_wage_rate->getSpecialWR();
           //selected country,region,skill,region
           $data['selected_counry_id'] = $this->input->post('country_id');
           $data['selected_region_id'] = $this->input->post('region_id');
           $data['selected_skill_id'] = $this->input->post('skill_id');
           $data['selected_level_id'] = $this->input->post('level_id');
           
           $sub_skill =FALSE;
           $calendar_days = FALSE;
           
           if($special_wage_rate_rule){
           $sub_skill = $this->model_master_data_view->getSkillSubskill($special_wage_rate_rule->skill_id,$level_id=NULL,'subskill');
           
            //selected country,region,skill,region
           $data['selected_counry_id'] = $special_wage_rate_rule->country_id;
           $data['selected_region_id'] = $special_wage_rate_rule->country_region_id;
           $data['selected_skill_id'] = $special_wage_rate_rule->skill_id;
           $data['selected_level_id'] = $special_wage_rate_rule->level_id;
               
          //get calendar day
            $country_id = $special_wage_rate_rule->country_id;
            $state = $special_wage_rate_rule->country_region_id;
            $year = $special_wage_rate_rule->year;
           $calendar_day = $this->model_master_data_view->getCalendarYear($year); 
           
           }else{
               //selected country,region,skill,region
           $data['selected_counry_id'] = $this->input->post('country_id');
           $data['selected_region_id'] = $this->input->post('region_id');
           $data['selected_skill_id'] = $this->input->post('skill_id');
           $data['selected_level_id'] = $this->input->post('level_id');
            if($this->input->post('skill_id')){
           $sub_skill = $this->model_master_data_view->getSkillSubskill($this->input->post('skill_id'),$level_id=NULL,'subskill');
            }
           }
           $data['calendar_days'] = $calendar_day;
           $data['skills'] = $skill;
           $data['days'] = $days;
           $data['countries'] = $countries;
           $data['regions'] = $regions;
           $data['years'] = $years;
         
           $data['sub_skills'] = $sub_skill;
           $data['special_wage_rate_rule'] = $special_wage_rate_rule;
       $this->load->view('special_wage_rate_form',$data);
       }else if($this->input->post('load_wr') == 'overtime_rate' || $this->input->post('load_wr') == 'overtime_rate_copy'){
           
           $_POST['overtime_wr_id']=$this->input->post('data_id');
           $intervals = $this->model_master_data_view->getWRInterval();
           $days = $this->model_master_data_view->getWRDay();
           $data['intervals'] = $intervals;
           $data['days'] = $days;
           $overtime_wage_rate_rule = $this->model_wage_rate->getOvertimeWR();
     
           $data['overtime_wage_rate_rule'] = $overtime_wage_rate_rule;
          
           
           $sub_skill =FALSE;
          
           if($overtime_wage_rate_rule){
           $sub_skill = $this->model_master_data_view->getSkillSubskill($overtime_wage_rate_rule->skill_id,$level_id=NULL,'subskill');
           //selected country,region,skill,region
           $data['selected_counry_id'] = $overtime_wage_rate_rule->country_id;
           $data['selected_region_id'] = $overtime_wage_rate_rule->country_region_id;
           $data['selected_skill_id'] = $overtime_wage_rate_rule->skill_id;
           $data['selected_level_id'] = $overtime_wage_rate_rule->level_id;
           
           }else{
               //selected country,region,skill,region
           $data['selected_counry_id'] = $this->input->post('country_id');
           $data['selected_region_id'] = $this->input->post('region_id');
           $data['selected_skill_id'] = $this->input->post('skill_id');
           $data['selected_level_id'] = $this->input->post('level_id');
            if($this->input->post('skill_id')){
           $sub_skill = $this->model_master_data_view->getSkillSubskill($this->input->post('skill_id'),$level_id=NULL,'subskill');
            }
           }
            $data['sub_skills'] = $sub_skill;
           
           $this->load->view('overtime_rate_form',$data);
       }else if($this->input->post('load_wr')=='breaks_not_taken' || $this->input->post('load_wr')=='breaks_not_taken_copy'){
           
           $_POST['breaks_not_taken_wr_id']=$this->input->post('data_id');
            $breaks_not_taken_wage_rate_rule = $this->model_wage_rate->getBreakNotTakenWR();
            
           $days = $this->model_master_data_view->getWRDay();
            if($breaks_not_taken_wage_rate_rule){
           $sub_skill = $this->model_master_data_view->getSkillSubskill($breaks_not_taken_wage_rate_rule->skill_id,$level_id=NULL,'subskill');
           
           //selected country,region,skill,region
           $data['selected_counry_id'] = $breaks_not_taken_wage_rate_rule->country_id;
           $data['selected_region_id'] = $breaks_not_taken_wage_rate_rule->country_region_id;
           $data['selected_skill_id'] = $breaks_not_taken_wage_rate_rule->skill_id;
           $data['selected_level_id'] = $breaks_not_taken_wage_rate_rule->level_id;
           }else{
               //selected country,region,skill,region
           $data['selected_counry_id'] = $this->input->post('country_id');
           $data['selected_region_id'] = $this->input->post('region_id');
           $data['selected_skill_id'] = $this->input->post('skill_id');
           $data['selected_level_id'] = $this->input->post('level_id');
            if($this->input->post('skill_id')){
           $sub_skill = $this->model_master_data_view->getSkillSubskill($this->input->post('skill_id'),$level_id=NULL,'subskill');
            }
           }
            $data['sub_skills'] = $sub_skill;
          
           $data['days'] = $days;
           $data['breaks_not_taken_wage_rate_rule'] = $breaks_not_taken_wage_rate_rule;
           $this->load->view('break_not_taken',$data); 
       }else if($this->input->post('load_wr')=='work_hours' || $this->input->post('load_wr')=='work_hours_copy' ){
           
           $_POST['work_hour_id']=$this->input->post('data_id');
            $work_hour_wage_rate_rule = $this->model_wage_rate->getWorkHourWR();
           
           $days = $this->model_master_data_view->getWRDay();
            if($work_hour_wage_rate_rule){
           $sub_skill = $this->model_master_data_view->getSkillSubskill($work_hour_wage_rate_rule->skill_id,$level_id=NULL,'subskill');
            //selected country,region,skill,region
           $data['selected_counry_id'] = $work_hour_wage_rate_rule->country_id;
           $data['selected_region_id'] = $work_hour_wage_rate_rule->country_region_id;
           $data['selected_skill_id'] = $work_hour_wage_rate_rule->skill_id;
           $data['selected_level_id'] = $work_hour_wage_rate_rule->level_id;
           
           }else{
               //selected country,region,skill,region
           $data['selected_counry_id'] = $this->input->post('country_id');
           $data['selected_region_id'] = $this->input->post('region_id');
           $data['selected_skill_id'] = $this->input->post('skill_id');
           $data['selected_level_id'] = $this->input->post('level_id');
            if($this->input->post('skill_id')){
           $sub_skill = $this->model_master_data_view->getSkillSubskill($this->input->post('skill_id'),$level_id=NULL,'subskill');
            }
           }
            $data['sub_skills'] = $sub_skill;
          
           $data['days'] = $days;
           $data['work_hour_wage_rate_rule'] = $work_hour_wage_rate_rule;
           $this->load->view('work_hour_form',$data); 
       }else if($this->input->post('load_wr')=='copy_rule'){
            
            $skill = $this->model_master_data_view->getSkillSubskill($skill_id=NULL,$level_id=NULL,'not_configured_skill');
            $skill_detail =false;
            $level_detail =false;
            $sub_skill=false;
            if($this->input->post('skill_id')){
            $skill_detail = $this->model_master_data_view->getSkillSubskill(intval($this->input->post('skill_id')),$level_id=NULL,'skill');
            
             
            }
             if($this->input->post('level_id')){
            $level_detail = $this->model_master_data_view->getSkillSubskill(NULL,intval($this->input->post('level_id')),'level');
             }
             if($this->input->post('skill_id')){
            $sub_skill = $this->model_master_data_view->getSkillSubskill(intval($this->input->post('skill_id')),$level_id=NULL,'not_configured_level');
             }
      
            $data['skills'] = $skill;
            $data['sub_skills'] = $sub_skill;
            $data['skill_name'] = ($skill_detail)?$skill_detail->skill_name:'All';
            $data['level_name'] = ($level_detail)?$level_detail->level_name:'All';
            
           $this->load->view('copy_rule',$data); 
       }
       
       
   }
   
   public function getShiftDetail(){
       $shift_type_id = $this->input->post('shift_id');
       $shift_type = $this->model_master_data_view->getWRshiftType($shift_type_id,1);
         
        $response = array();
        if($shift_type){
            $from_to = $this->model_master_data->formatTime($shift_type->time_from).'-'.$this->model_master_data->formatTime($shift_type->time_to);
            $response['status'] = 1;
            $response['msg'] = $from_to;
          }else{
              $response['status'] = 0;
            $response['msg'] = 'No Record Found';
          }
         echo  json_encode($response);exit;
   }
 
   
}