<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Staff extends Web_Service_Controller {

	public $_data = array();

    public $_user_id;
    public $_lhc_user_id = 0 ;
    public $_lhc_selected = 0 ;
    public $_response = array();

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('ws_helper');
        $this->load->model('staffs_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->ion_auth->set_error_delimiters('', '');
    }

    public function _remap($method, $params = array()) {
 
        $method = $method;
        if (method_exists($this, $method)) {
            if ($this->input->post()) {
                if (!($user = $this->_can_access())) {
                    return $this->response('0006');
                }
                $this->_user_id = $user->id;

                if($user->lhc_user_id != 0 && !empty($user->lhc_user_id)){
                    $this->_lhc_user_id = $user->lhc_user_id;
                    $this->_lhc_selected = 1;
                }
                
            }
            return call_user_func_array(array($this, $method), $params);
        }
        return $this->response('0000');
    }

    public function index() {
        redirect('developer_view');
    }

    public function get_job_staffs() {

        if ($this->input->post()) {
            $user_id = $this->_user_id;
            $this->form_validation->set_rules('job_id','Job ID','trim|required|numeric|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $job_id = $this->input->post('job_id');

            if(!$this->is_job_assigned($job_id)){
                return $this->response('0020');
            }

            $res = $this->staffs_model->getJobStaffs($job_id);
            $this->_response['staffs'] = $res;
            return $this->response('0001', $this->_response);

        } 
        else {
            $this->load->view('job/job_detail', array('title' => 'Job Staff'));
        }
    }

    public function get_staff_detail() {

        if ($this->input->post()) {
            $user_id = $this->_user_id;
            $this->form_validation->set_rules('staff_id','Staff ID','trim|required|numeric|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $staff_id = $this->input->post('staff_id');

            $res = $this->staffs_model->getStaffInfo($staff_id);
            $this->_response['staff'] = $res;
            return $this->response('0001', $this->_response);

        } 
        else {
            $this->load->view('job/get_by_id', array('title' => 'Staff Detail', 'name' => 'Staff Id', 'id' => 'staff_id'));
        }

    }




    public function track_job_staffs() {

        if ($this->input->post()) {
            $user_id = $this->_user_id;
            $this->form_validation->set_rules('job_id','Job ID','trim|required|numeric|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $job_id = $this->input->post('job_id');

            if(!$this->is_job_assigned($job_id)){
                return $this->response('0020');
            }
            $this->_get_job_info($job_id);
            $res = $this->staffs_model->getJobStaffs($job_id,0,TRUE);
  
            $this->_response['staffs'] = $res;
            return $this->response('0001', $this->_response);

        } 
        else {
            $this->load->view('job/job_detail', array('title' => 'Track Job Staffs'));
        }
    }


    public function track_single_staff() {

        if ($this->input->post()) {
            $user_id = $this->_user_id;
            $this->form_validation->set_rules('job_id','Job ID','trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('staff_id','Staff ID','trim|required|numeric|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $job_id = $this->input->post('job_id');
            $staff_id = $this->input->post('staff_id');

            if(!$this->is_job_assigned($job_id)){
                return $this->response('0020');
            }

            $res = $this->staffs_model->getJobStaffs($job_id,$staff_id,TRUE);
            $this->_get_job_info($job_id,$staff_id);
            $this->_response['staff'] = $res;
            return $this->response('0001', $this->_response);

        } 
        else {
            $this->load->view('job/job_staff', array('title' => 'Track Single Staff'));
      
        }
    }

    public function _get_job_info($job_id="",$staff_id=""){
        $jobTime = $this->staffs_model->getJobInfo($job_id);
        $jobInfo = array();
        $jobInfo['job_id'] = $jobTime->job_id;
        $jobInfo['start_time'] = strtotime($jobTime->start_time);
        $jobInfo['end_time'] = strtotime($jobTime->end_time);
        $jobInfo['job_location'] = array('latitude'=> $jobTime->latitude , 'longitude' => $jobTime->longitude );
        $jobInfo['perimeter'] = "100";
        //$perimeter = $this->staffs_model->getOneWhere(array('slug' => 'staff_leaves_site'),'tbl_notification_settings');
        //$perimeter = isset($perimeter->allowed_distance) ? $perimeter->allowed_distance : "50";
       
        $period = new DatePeriod(
             new DateTime($jobTime->start_time),
             new DateInterval('PT'.STAFF_TRACK_INTERVAL.'M'),
             new DateTime($jobTime->end_time)
        );
        $periods = array();
        foreach ($period as $dt) {
           $periods[] = $dt;
        }
        $jobInfo['currnet_interval'] = $this->_getCurrnetInterval($periods,$jobInfo['end_time']);
        $jobInfo['interval'] = count($periods);
      
        $this->_response['jobInfo'] = $jobInfo;
    }

    function _getCurrnetInterval($periods="",$end_time="") {
        $currnet_time = time();
        if($currnet_time > $end_time)
            return count($periods);
        
        foreach ($periods as $k => $p) {
           if (strtotime($p->format("Y-m-d H:i")) > $currnet_time) {
                return $k+1;
           }
        } 
    }
   
}
