<?php 
/**
* Model_master_setup
* This model contains business logics for Master_setup controller
*/
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Model_master_setup extends MY_Model 
{

	public function __construct() 
	{
		parent::__construct();

	}

	// Retrives all notification templates
	function get_notification(){
		$query = $this->db->get('tbl_notification_settings');
		if($query->num_rows() > 0){
			return $query->result();
		}  
		return false;
	}

	// Get groups information for specific notification
	function groups_notificaiton($id=0){
		$this->db->select('ng.id, g.name, ng.value')->from('tbl_notification_group ng');
		$this->db->join('tbl_group g', 'ng.group_id = g.id');
		$this->db->where('ng.notification_id', $id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		}
		return false;
	}

	// updates notification records
	function update_notification(){
		$notification_group = $this->input->post('group', TRUE);
		$message = $this->input->post('message', TRUE);
		$notification_time = $this->input->post('notification_time', TRUE);
		$allowed_distance = $this->input->post('allowed_distance', TRUE);

		$not_array = array();
		$return = 1;

		$this->db->trans_start();
		foreach ($message as $key => $msg) {

			$not_array['message']=$message[$key];

			if(isset($notification_time[$key]))
				$not_array['notification_time'] = $notification_time[$key];

			if (isset($allowed_distance[$key])) 
				$not_array['allowed_distance'] = $allowed_distance[$key];

			// update notificaiton_setings table
			$this->db->where('id', $key);
			$this->db->update('tbl_notification_settings', $not_array);

			$this->db->where('notification_id', $key);
			$this->db->update('tbl_notification_group', array('value'=>'0'));

			if(isset($notification_group[$key]) && !empty($notification_group[$key])){
				foreach ($notification_group[$key] as $ngk => $ngv) {

					// updates notification group information
					$this->db->where('id', $ngk);
					$this->db->update('tbl_notification_group', array('value'=>$ngv));
				}

			}	 		
		}
		if($this->db->trans_status() == FALSE)
			$return = 0;

		$this->db->trans_complete();

		return $return;
	}

	public function get_admin_settings(){
		$settings = $this->db->select('*')
		->from('tbl_con_general_setting')
		->get()->row();

		return $settings;
	}

	public function get_all_countries(){
		$query = $this->db->select('id, name, lhc_available')->from('tbl_country')->get();
		if($query->num_rows() > 0){
			return $query->result();
		}
		return false;
	}

	public function get_all_industries(){
		$query = $this->db->select('id, name, lhc_restriction')->from('tbl_con_industry')->get();
		if($query->num_rows() > 0){
			return $query->result();
		}
		return false;
	}
	public function get_selected_industries(){
		$query = $this->db->select('id, name, lhc_restriction')->from('tbl_con_industry')->where('lhc_restriction', 'yes')->get();
		if($query->num_rows() > 0){
			return $query->result();
		}
		return false;
	}
	public function update_settings(){
		$data = array(
			'quote_expiry_time' => $this->input->post('quote_expiry_time', true),
			'quote_cleanup_time' => $this->input->post('quote_cleanup_time', true), 
			'short_notice_period' => $this->input->post('short_notice_period', true), 
			'max_booking_time_type' => $this->input->post('max_booking_time_type', true), 
			'max_booking_time' => $this->input->post('max_booking_time', true), 
			'ic_timesheet_completion' => $this->input->post('ic_timesheet_completion', true), 
			'ic_task_completion' => $this->input->post('ic_task_completion', true), 
			'publish_job_before' => $this->input->post('publish_job_before', true), 
			'allow_lhc_to_replace_staff' => $this->input->post('allow_lhc_to_replace_staff', true),
			'pay_period_type' => $this->input->post('pay_period_type', true), 
			'pay_week_day' => $this->input->post('pay_week_day', true),  
			'remove_potential_staff_day' => $this->input->post('remove_potential_staff_day', true), 
			'remove_potential_staff_week' => $this->input->post('remove_potential_staff_week', true),
			'remove_potential_staff_fortnight' => $this->input->post('remove_potential_staff_fortnight', true),
			'updated_date' => date('Y-m-d H:i:s'), 
			'updated_by' => $this->session->userdata('user_id'), 
			);

		if($this->db->update('tbl_con_general_setting', $data)){
			return true;
		}
		return false;
	}

	public function update_available_countries(){
		$countries = $this->input->post('available_countries', true);
		$this->db->trans_start();
		if(!empty($countries)){
			$this->db->update('tbl_country', array('lhc_available'=>'no'));
			$this->db->where_in('id', $countries);
			$this->db->update('tbl_country', array('lhc_available'=>'yes'));
		}
		$this->db->trans_complete();
		if($this->db->trans_status()===false){
			return false;
		}
		return true;
	}

	public function add_restrict_industries(){
	 	$this->db->where('id', $this->input->post('restrict_industry', true));
	 	if( $this->db->update('tbl_con_industry', array('lhc_restriction'=>'yes')) ){
	 		return true;
	 	} 
	 	return false;
	}
	
	public function remove_restrict_industry($industry_id){
	 	$this->db->where('id', $this->input->post('id', true));
	 	if( $this->db->update('tbl_con_industry', array('lhc_restriction'=>'no')) ){
	 		return true;
	 	} 
	 	return false;
	}
}

?>