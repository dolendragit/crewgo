<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_model extends CI_Model {

	public function __construct()
	{

		parent::__construct();

	}	

	/**
	 * get list of completed jobs for repetation
	 * @return object/bool
	 * returns list of jobs which are eligible for repetation, if no jobs reutrns false. 
	 */
	function get_scheduling_jobs(){

		$query = $this->db->select('js.*')
		->from('tbl_job_scheduler js')
		->join('tbl_job j', 'js.job_id = j.id')
		->where('job_id', 9)
		//->where(array('j.status'=>3, 'js.start_date <='=>gmdate('Y-m-d')))
		->order_by('entered_date', 'DESC')
		->limit(10)
		->get();
		if($query->num_rows() > 0){
			return $query->result();
		}
		return false;
	}

	function get_job_info($job_id){
		$query = $this->db->get_where('tbl_job', array('id'=>$job_id));
		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}

	/**
	 * copy jobs and job detail from scheduled job and create new job with associated jonb details.
	 * @param  int $job_id 
	 * @return bool    true/false
	 */
	function copy_job_data($job_id, $schedule_type='W', $schedule_rate=1 ){
		
		$this->db->trans_start();
		// should i regenerate job quote number?
		$this->db->query('CREATE TEMPORARY TABLE tmp_tbl_job SELECT * FROM tbl_job WHERE id ='.$job_id);
		$this->db->query('UPDATE tmp_tbl_job SET id = NULL, status = 0, shift_status = 4');
		$this->db->query('INSERT INTO tbl_job SELECT * FROM tmp_tbl_job');
		$insert_id = $this->db->insert_id();
		$this->db->query('DROP TEMPORARY TABLE IF EXISTS tmp_tbl_job');
		
		if($insert_id){
			// get the job detail info and put into copied job. 
			$query = $this->db->get_where('tbl_job_detail jd', array('job_id'=>$job_id));
			if($query->num_rows() > 0){  // job detail start
				$job_details = $query->result();
				foreach ($job_details as $key => $jd) {

					if($schedule_type =='W') { 

					$start_week_day = date('l', strtotime($jd->start_time));
					$start_time = date('H:i:s', strtotime($jd->start_time));
					$start_date = date('Y-m-d', strtotime($jd->start_time));
					$new_start_date = date('Y-m-d', strtotime($start_date.' +'.$schedule_rate.' '.$start_week_day));
					$new_start_datetime = $new_start_date.' '.$start_time;
					$new_start_datetime = date('Y-m-d H:i:s', strtotime($new_start_datetime));
					$time_diff =  strtotime($new_start_datetime) - strtotime($jd->start_time);
					$new_end_datetime = date('Y-m-d H:i:s', (strtotime($jd->end_time)+$time_diff));

				} else {

					$new_start_datetime = gmdate('Y-m-d H:i:s');
					$time_diff =  strtotime($new_start_datetime) - strtotime($jd->start_time);
					$new_end_datetime = date('Y-m-d H:i:s', (strtotime($jd->end_time)+$time_diff));
				}

					$jd_row = array(
						'job_id' 			=> $insert_id,
						'skill_id' 			=> $jd->skill_id,
						'level_id' 			=> $jd->level_id,
						'required_number' 	=> $jd->required_number,
						'start_time' 		=> $new_start_datetime,
						'end_time'			=> $new_end_datetime,
						'hourly_rate' 		=> $jd->hourly_rate,
						'total_hour' 		=> $jd->total_hour,
						'total_cost' 		=> $jd->total_cost,
						'qualification'		=> $jd->qualification,
						'updated_date' 		=> $jd->updated_date,
						'updated_by' 		=> $jd->updated_by,
						'has_peak_price' 	=> $jd->has_peak_price,
						'peak_price' 		=> $jd->peak_price,
						'shift_status' 		=> 4,
						);

					$this->db->insert('tbl_job_detail', $jd_row);
					$new_jd_id = $this->db->insert_id();
					// add job break data
					$break_query = $this->db->get_where('tbl_job_detail_break', array('job_detail_id'=>$jd->id));
					if($break_query->num_rows() > 0){
						$breaks = $break_query->result();
						$insert_break = array();
						foreach ($breaks as $break) {
							$break_start_time = date('Y-m-d H:i:s', (strtotime($break->start_time)+$time_diff));
							$break_end_time = date('Y-m-d H:i:s', (strtotime($break->end_time)+$time_diff));

							$break_row = array(
								'job_detail_id' => $new_jd_id,
								'start_time' => $break_start_time,
								'end_time' => $break_end_time,
								'entered_date' => $break->entered_date,
								'entered_by' => $break->entered_by,
								'updated_date' => $break->updated_date,
								'updated_by' => $break->updated_by						
							);
							$insert_break[] = $break_row;
						}
						// insert break data into batch
						$this->db->insert_batch('tbl_job_detail_break', $insert_break);
					}
					
				}
			}

			// copy job qualification and insert new data 
			$qualification_query = $this->db->get_where('tbl_job_qualification', array('job_id'=>$job_id));
			if($qualification_query->num_rows() > 0){
				$qualification = $qualification_query->result();
				$insert_qualification = array();
				foreach ($qualification as $q) {
					$q_row = array(
						'job_id'		=>$insert_id,
						'skill_id'		=>$q->skill_id,
						'level_id'		=>$q->level_id,
						'qualification_id'=>$q->qualification_id,
						'created_at'	=>$q->created_at
					);
					$insert_qualification[] = $q_row;
				}
				$this->db->insert_batch('tbl_job_qualification', $insert_qualification);

			}

			// copy job induction and insert new data 
			$induction_query = $this->db->get_where('tbl_job_induction', array('job_id'=>$job_id));
			if($induction_query->num_rows() > 0){
				$induction = $induction_query->result();
				$insert_induction = array();
				foreach ($induction as $ind) {
					$i_row = array(
						'email'			=>$ind->email,
						'name'			=>$ind->name,
						'provider'		=>$ind->provider,
						'valid_induction' =>$ind->valid_induction,
						'job_id'		=>$insert_id,
						'quote_number'	=>$ind->quote_number,
						'induction_number'	=>$ind->induction_number,
						'url'			=>$ind->url,
						'contact_number'=>$ind->contact_number,
						'other_detail'	=>$ind->other_detail,

					);
					$insert_induction[] = $i_row;
				}
				$this->db->insert_batch('tbl_job_induction', $insert_induction);
			}

			$this->db->trans_complete();

			if($this->db->trans_status()===false)
				return false;

			return $insert_id;
		}
	} 

	function update_job_schedule($id, $new_jobid, $occurance){
		$occurance++;
		$this->db->where('id', $id);
		$this->db->update('tbl_job_scheduler', array('created_occurance' =>$occurance ));

		$insert_data = array(
			'job_scheduler_id' 	=> $id,
			'job_id'			=> $new_jobid,
			'created_date'		=> gmdate('Y-m-d H:i:s')
		);
		$this->db->insert('tbl_job_scheduler_activity', $insert_data);
	}

	function jobs_last_scheduled_info($scheduler_id){
		$query = $this->db->where('job_scheduler_id', $scheduler_id)
		->order_by('id', 'DESC')
		->limit(1)
		->get('tbl_job_scheduler_activity');

		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}
}

/* End of file cron_model.php */
/* Location: ./application/models/cron_model.php */