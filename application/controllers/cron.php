<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends MX_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->model('cron_model');

	}

	public function index()
	{
		// do your magic here...
	}

	/**
	 * repeat the jobs which are shheduled to be repeated.
	 * @return none
	 */
	public function repeat_job(){

		$jobs = $this->cron_model->get_scheduling_jobs();
		//echo '<pre>'; print_r($jobs); exit;
		if($jobs){
			foreach ($jobs as $key => $job) {
				if( ( $job->required_occurance > 0 && $job->required_occurance > $job->created_occurance) ||($job->end_date != '0000-00-00' && strtotime( $job->end_date ) > strtotime( date('Y-m-d') ) ) ){

					if($job->schedule_type=='W'){
						$min_days = 7;
						if($job->schedule_rate && $job->schedule_rate > 0){
							$min_days = 7 * $job->schedule_rate;
						}
						$min_days_timestamp = $min_days*24*60*60;
						$last_schedule = $this->cron_model->jobs_last_scheduled_info($job->id);

						// get job last started date time
						if($last_schedule){
							$min_sart_timestamp = strtotime($last_schedule->created_date);
							$min_sart_timestamp = $min_sart_timestamp+$min_days_timestamp;

						} else {
							$min_sart_timestamp = strtotime($job->start_date);
						}

						$current_timestamp = time();

						//check if the job is ready for the repeat.
						if($min_sart_timestamp <= $current_timestamp){
							// repeat the current job. copy existing job and get new job id 
							$new_jobid = $this->cron_model->copy_job_data($job->job_id, $job->schedule_type, $job->schedule_rate);

							if($new_jobid){
								//increment the created occrance and insert activity to tbl_job_scheduler_activity
								$this->cron_model->update_job_schedule($job->id, $new_jobid, $job->created_occurance);
							}
							
						}

					}

				}
			} 
		}

	}
}

/* End of file cron.php */
