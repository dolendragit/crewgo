<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Job extends Web_Service_Controller {

	public $_data = array();

    const SCHEDULE_WEEKLY = 'W';

    public $_user_id;
    public $_lhc_user_id = 0 ;
    public $_lhc_selected = 0 ;
    public $_response = array();

    private $_offset = 0 ;

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('ws_helper');
        $this->load->model('webservice_model');
        $this->load->model('customer_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->ion_auth->set_error_delimiters('', '');
    }

    public function _remap($method, $params = array()) {
        $method = $method;
        if (method_exists($this, $method)) {
            if ($this->input->post()) {
                if (!($user = $this->is_logged_in())) {
                    return $this->response('0006');
                }
                $this->_user_id = $user->id;

                if(/*$user->register_from == 'LHC' &&*/ $user->lhc_user_id != 0 && !empty($user->lhc_user_id)){
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
   
    public function add_job_test(){
        if ($this->input->post()) {
            
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->add_job(1);
            
        } 
        else {
            $raw_data = job_schema();
            $this->load->view('job/add_job', array('title' => 'Add Job','raw_data' => $raw_data,'url' => base_url(MODULE.'/webservice/job/add_job_test')));
        }
    }

    function add_job($test=0) {
        if (!($user = $this->is_logged_in())) {
            return $this->response('0006');
        }

        $user_id = $user->id;

        $raw_data = file_get_contents('php://input');
        $raw_data_test = job_schema();
        if($test == 1){
            $raw_data = $raw_data_test;
        }

        $file = 'data.txt';
        $current = "\n" . $raw_data . "\r\n";
        file_put_contents($file, $current, FILE_APPEND | LOCK_EX);

        $data = json_decode($raw_data, TRUE);

        if (!$data)
            return $this->response('0018');

        $this->load->library('My_jsonschema_validator');
        if (!$this->my_jsonschema_validator->validate('file://' . FCPATH . 'assets/schemas/job/add_schema.json', $raw_data)){
            return $this->response('0032', array('validation_errors' => $this->my_jsonschema_validator->error));
        }

        else{

            if (!is_array($data['skills'])) {
                return $this->response('0018');
            }
            else{
                $quote_id = $this->customer_model->generate_quote_id();

                $address = $data['job_full_address'];
                $address = http_build_query(array('address' => $address), '', '&amp;');
                $url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&components=country:AU&".$address;
          
                $res = @file_get_contents($url);
                $res = @json_decode($res);
                
                $postal_code = "";
                if(isset($res->results[0]->address_components)){
                    $postal_code = $this->extract_data($res->results[0]->address_components, "postal_code");
                }

                $job = array(
                   
                    'job_full_address'  => $data['job_full_address'],
                    //'job_street'  => $data['job_street'],
                    'job_postcode_id'    => $postal_code,
                        
                    'meeting_full_address'  => $data['meeting_full_address'],  
                    //'meeting_street'  => $data['meeting_street'],     
                    //'meeting_postcode_id'  => $data['meeting_postcode_id'],
                    'meeting_lat'  => $data['meeting_lat'],
                    'meeting_long'  => $data['meeting_long'],    

                    
                    'quote_id' => $quote_id,
                    'description'  => $data['notes'],   
                    'customer_user_id'   => $user_id,   
                    'entered_date'    => date('Y-m-d H:i:s'),
                    'entered_by'  => $user_id
                );

                if($this->_lhc_selected){
                    $job['lhc_user_id'] = $this->_lhc_user_id;
                }
                
                $job_id = $this->webservice_model->insertRow($job,'tbl_job');
                if(!$job_id){
                    return $this->response('0000',array('msg' => 'Server Error'));
                }
                else{
                    foreach ($data['skills'] as $s) {
                        $start_time = $s['start_time'];
                        $end_time = $s['end_time'];
                        $skill_id = $s['skill_id'];
                        $level_id = $s['level_id'];
                        $quantity = $s['quantity'];

                        $total_hour = strtotime($end_time) - strtotime($start_time);
                        $total_hour = $total_hour/(60*60);
                        $hourly_rate = 0; //$this->customer_model->get_hourly_rate($skill_id,$level_id);
                        $skill_array = array(
                            'job_id'  => $job_id,
                            'skill_id' =>  $skill_id, 
                            'level_id' => $level_id,  
                            'required_number'  => $quantity,
                            'start_date' => date('Y-m-d',strtotime($s['date'])),           
                            'start_time' =>  date('H:i:s',strtotime($start_time)),   //$s['start_time'],         
                            'end_time'=> date('H:i:s',strtotime($end_time)), //  $s['end_time']
                            'total_hour'=> $total_hour,
                            //'hourly_rate' => $hourly_rate,
                            //'total_cost' => $hourly_rate * $total_hour * $quantity
                        );
                        $job_detail_id = $this->webservice_model->insertRow($skill_array,'tbl_job_detail');

                        $breaks = $s['breaks'];
                        if(is_array($breaks) && !empty($breaks)){
                            foreach ($breaks as $b) {
                                $break_array = array(
                                    'job_detail_id' => $job_detail_id ,  
                                    'start_time' => $b['start_time'],     
                                    'end_time'  => $b['end_time'],  
                                    'entered_date'   => date('Y-m-d H:i:s'), 
                                    'entered_by' => $user_id
                                );
                                $break_id = $this->webservice_model->insertRow($break_array,'tbl_job_detail_break');
                            }
                        }
                    }
                }
                $this->_response = $this->customer_model->getJobDetail($job_id,$user_id);

                return $this->response('0001',$this->_response);
            }
        }  
    }

    public function edit_job() {
    
        $user_id = $this->_user_id;
        $raw_data = file_get_contents('php://input');

        $file = time().'.txt';
        $current = "\n" . $raw_data . "\r\n";
        file_put_contents($file, $current, FILE_APPEND | LOCK_EX);
        $data = json_decode($raw_data, TRUE);

        if (!$data)
            return $this->response('0018');

        $this->load->library('My_jsonschema_validator');
        if (!$this->my_jsonschema_validator->validate('file://' . FCPATH . 'assets/schemas/job/edit_schema.json', $raw_data)){
            return $this->response('0032', array('validation_errors' => $this->my_jsonschema_validator->error));
        }
        else{
            if (!is_array($data['skills'])) {
                return $this->response('0018');
            }
            else{
        
                $job_id = $data['job_id'];
                if(empty($job_id)){
                    $this->_response['msg'] = "Invalid Job Id";
                    return $this->response('0000',$this->_response);
                }
                $address = $data['job_full_address'];
                $address = http_build_query(array('address' => $address), '', '&amp;');
                $url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&components=country:AU&".$address;
          
                $res = @file_get_contents($url);
                $res = @json_decode($res);
                
                $postal_code = "";
                if(isset($res->results[0]->address_components)){
                    $postal_code = $this->extract_data($res->results[0]->address_components, "postal_code");
                }
                $job = array(
                    'job_full_address'  => $data['job_full_address'],
                    'job_postcode_id'    => $postal_code,
                        
                    'meeting_full_address'  => $data['meeting_full_address'],  
                    'meeting_lat'  => $data['meeting_lat'],
                    'meeting_long'  => $data['meeting_long'],    

                    'description'  => $data['notes'],   
                    'customer_user_id'   => $user_id,   
                    'updated_date'    => date('Y-m-d H:i:s'),
                    'updated_by'  => $user_id
                );

                if($this->_lhc_selected){
                    $job['lhc_user_id'] = $this->_lhc_user_id;
                }
                
                $this->webservice_model->updateWhere(array('id' => $job_id), $job, 'tbl_job');
                if(!$job_id){
                    return $this->response('0000',array('msg' => 'Server Error'));
                }
                else{
                    $this->customer_model->delete_job_detail($job_id);
                    foreach ($data['skills'] as $s) {
                        $start_time = $s['start_time'];
                        $end_time = $s['end_time'];
                        $skill_id = $s['skill_id'];
                        $level_id = $s['level_id'];
                        $quantity = $s['quantity'];

                        $total_hour = strtotime($end_time) - strtotime($start_time);
                        $total_hour = $total_hour/(60*60);
                        $skill_array = array(
                            'job_id'  => $job_id,
                            'skill_id' =>  $skill_id, 
                            'level_id' => $level_id,  
                            'required_number'  => $quantity,
                            'start_date' => date('Y-m-d',strtotime($s['date'])),           
                            'start_time' =>  date('H:i:s',strtotime($start_time)),    
                            'end_time'=> date('H:i:s',strtotime($end_time)), 
                            'total_hour'=> $total_hour,
               
                        );
                        $job_detail_id = $this->webservice_model->insertRow($skill_array,'tbl_job_detail');

                        $breaks = $s['breaks'];
                        if(is_array($breaks) && !empty($breaks)){
                            foreach ($breaks as $b) {
                                $break_array = array(
                                    'job_detail_id' => $job_detail_id ,  
                                    'start_time' => $b['start_time'],     
                                    'end_time'  => $b['end_time'],  
                                    'entered_date'   => date('Y-m-d H:i:s'), 
                                    'entered_by' => $user_id
                                );
                                $break_id = $this->webservice_model->insertRow($break_array,'tbl_job_detail_break');
                            }
                        }
                    }
                }
                $this->_response['job'] = $this->customer_model->getJobDetail($job_id,$user_id);

                return $this->response('0001',$this->_response);
            }
        }  
    }


    function get_price(){

        if (!($user = $this->is_logged_in())) {
            return $this->response('0006');
        }
        $user_id = $user->id;

        $raw_data = file_get_contents('php://input');
        $raw_data_test = get_skills_schema();

        $file = 'temp/skills.txt';
        $current = "\n" . $raw_data . "\r\n";
        file_put_contents($file, $current, FILE_APPEND | LOCK_EX);

        $data = json_decode($raw_data, TRUE);

        if (!$data)
            return $this->response('0018');

        $this->load->library('My_jsonschema_validator');
        if (!$this->my_jsonschema_validator->validate('file://' . FCPATH . 'assets/schemas/job/skill_schema.json', $raw_data)){
            return $this->response('0032', array('validation_errors' => $this->my_jsonschema_validator->error));
        }

        $total_price = 0;

        foreach ($data as $s) {
            $start_time = $s['start_time'];
            $end_time = $s['end_time'];
            $skill_id = $s['skill_id'];
            $level_id = $s['level_id'];
            $quantity = $s['quantity'];

            $total_hour = strtotime($start_time) - strtotime($end_time);
            $total_hour = $total_hour/(60*60);
            $hourly_rate = $this->customer_model->get_hourly_rate($skill_id,$level_id);
            
            $total_cost = $hourly_rate * $total_hour * $quantity;
            $total_price += $total_cost; 
        }
        return $this->response('0001', array('total' => $total_price ));
    }

    function _calculate_price($data = array()){
        if(!empty($data)){

        }
    }


    function get_jobs(){
        if ($this->input->post()) {
            $user_id = $this->_user_id;
            if($this->_lhc_selected){
                $jobs = $this->customer_model->get_jobs($user_id,$this->_lhc_user_id);
            }
            else{
                $jobs = $this->customer_model->get_jobs($user_id);
            }
            return $this->response('0001', array('jobs' => $jobs ));
        } 
        else {
            $this->load->view('job/search', array('title' => 'Jobs'));
        }
    }

    public function get_industries(){
        if ($this->input->post()) {
            $user_id = $this->_user_id;

            $term = $this->input->post('term');
            $res = $this->customer_model->get_industry($term,$user_id);
          
            return $this->response('0001', array('industries' => $res ));
        } 
        else {
            $this->load->view('job/search', array('title' => 'Industries'));
        }
    }

    public function get_skills(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('industry_id', 'Industry ID','required|trim');
        
            if($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $user_id = $this->_user_id;
            $industry_id = $this->input->post('industry_id');
            $term = $this->input->post('term');
            
            $res = $this->customer_model->get_skills($term,$industry_id);
          
            return $this->response('0001', array('skills' => $res ));
        } 
        else {
            $this->load->view('job/search_by_id', array('title' => 'Skills', 'name' => 'Industry Id', 'id' => 'industry_id'));
        }
    }

    public function get_levels(){
        if ($this->input->post()) {

            $user_id = $this->_user_id;
      
            $this->form_validation->set_rules('skill_id', 'Skill ID','required|trim');
            if($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            
            $skill_id = $this->input->post('skill_id');
            $term = $this->input->post('term');

            $res = $this->customer_model->get_levels($term,$skill_id);
          
            return $this->response('0001', array('levels' => $res ));
        } 
        else {
            $this->load->view('job/search_level', array('title' => 'Levels', 'name' => 'Skill Id', 'id' => 'skill_id'));
        }
    }


    public function get_meeting_place(){
        if ($this->input->post()) {
            $user_id = $this->_user_id;
            $term = $this->input->post('term');
            $res = $this->customer_model->get_meeting_place($term,$user_id);
            return $this->response('0001', array('places' => $res ));
        } 
        else {
            $this->load->view('job/search', array('title' => 'Places'));
        }
    }

    public function get_job_detail(){
        if ($this->input->post()) {
            $user_id = $this->_user_id;

            $this->load->library('form_validation');
            $this->form_validation->set_rules('job_id','Job ID','trim|required|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');
                $res = $this->customer_model->getJobDetail($job_id,$user_id);
                return $this->response('0001', $res);
            }

        } 
        else {
            $this->load->view('job/job_detail', array('title' => 'Job Detail'));
        }
    }


    public function add_supervisor() {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $data = $this->input->post();
           
            $this->_validate_registration();
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
    
            $return = array();

            $name = trim($this->input->post('name'));
            $address = $this->input->post('address');
            $email = trim($this->input->post('email'));
            $username = $email;
            $password = $this->input->post('password');
            $phone_number = trim($this->input->post('phone_number'));
            
            $activation_code = sha1(md5(microtime()));
            $user_id = $user->id;
            $additional_data = array(
                'name' => $name,
                //'first_name' => $name,
                'full_address' => $address,
                'phone_number' => $phone_number,
                'activation_code' => $activation_code,
                'customer_user_id' => $user_id
                
            );

            if ($this->ion_auth->email_check($email) == FALSE) {
                if ($id = $this->ion_auth->register($username, $password, $email, $additional_data, array(SUPERVISOR))) {
                    //$this->send_email($user_id, $email, $activation_code, $name);
                    $res = $this->customer_model->get_supervisor_info($id,$user_id);
                    if(!empty($res)){
                        return $this->response('0001', array('supervisor' => $res ));
                    }
                    return $this->response('0000');
                } 
                else {
                    $code = '0000';
                    $return = array('msg' => $this->ion_auth->errors());
                }
            } else {
                $code = '0004';
            }

            return $this->response($code, $return);
        } else {
            $this->load->view('add_supervisor', array('title' => 'Add Supervisor'));
        }
    }

    function update_supervisor() {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
           
            $this->_validate_registration();
            $this->form_validation->set_rules('supervisor_id', 'Id','required|trim');
            $this->form_validation->set_rules('email', 'Email','unique[tbl_user.email,id.supervisor_id]');
            
            if($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            
            $return = array();
            $id = $this->input->post('supervisor_id');
            $name = trim($this->input->post('name'));
            $address = $this->input->post('address');
            $email = trim($this->input->post('email'));
            $username = $email;
            $password = $this->input->post('password');
            $phone_number = trim($this->input->post('phone_number'));

            $check = $this->customer_model->check_valid_supervisor($id,$user->id);
            if(!$check && empty($check)){
                return $this->response('0011');
            }

            $data = array(
                'name' => $name,
                'email' => $email,
                'full_address' => $address,
                'phone_number' => $phone_number,
            ); 

            if ($this->ion_auth->update($id,$data)) {
                $info = $this->customer_model->get_supervisor_info($id,$user->id);
                $code = '0001';
                $return = array('supervisor' => $info);
            }
            else {
                $code = '0000';
                $return = array('msg' => $this->ion_auth->errors());
            } 

            return $this->response($code, $return);
        } 
        else {
            $this->load->view('update_supervisor', array('title' => 'Update Supervisor'));
        }
    }

  

    public function book_job(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|xss_clean');

            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');

                $user_id = $user->id; 
                $check = $this->customer_model->check_job($job_id,$user_id);
                if(!$check){
                    return $this->response('0020');
                }
                $job = $this->customer_model->getOneWhere(array('id' => $job_id),'tbl_job');
                $job_detail = $this->customer_model->get_job_skills($job_id);
                $this->_response['job_detail'] = $job_detail;
                if(!empty($job->booking_number)){
                    $this->_response['booking_number'] =$job->booking_number;
                }
                else{
                    $booking_number = $this->customer_model->book_job($job_id);
                    $this->_response['booking_number'] = $booking_number;
                }
                return $this->response('0001',$this->_response);
            };
        } 
        else {
            $this->load->view('job/book_job', array('title' => 'Book Job'));
        }
    }

    public function get_supervisors(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $term = $this->input->post('term');
            $offset = empty($this->input->post('offset')) ? $this->_offset : $this->input->post('offset');
            $res = $this->customer_model->get_supervisors($this->_user_id,$term,$offset);

            $pagination =  $this->_paginate($res['total'],$offset);

            $this->_response['supervisors'] = $res['res'];
            $this->_response['pagination'] = $pagination;

            return $this->response('0001',$this->_response);
        } 
        else {
            $this->load->view('job/search_with_offset', array('title' => 'Supervisors'));
        }
    }


    public function get_supervisor_detail(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('supervisor_id', 'supervisor ID','required|trim|numeric|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $user_id = $this->_user_id;
            $supervisor_id = $this->input->post('supervisor_id');
            $res = $this->customer_model->get_supervisor_info($supervisor_id,$user_id);
            if(!empty($res)){
                return $this->response('0001', array('supervisor' => $res ));

            }

            return $this->response('0030');
        } 
        else {
            $this->load->view('job/get_by_id', array('title' => 'Supervisor Detail', 'name' => 'Supervisor Id', 'id' => 'supervisor_id'));
        }
    }

    

    public function add_job_supervisor(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|xss_clean');
            $this->form_validation->set_rules('supervisor_id', 'Supervisor ID','required|trim|xss_clean');
             
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');
                $supervisor_id = $this->input->post('supervisor_id');
                $user_id = $user->id; 
                $check = $this->customer_model->check_job($job_id,$user_id);
                if(!$check){
                    return $this->response('0020');
                }
                $res = $this->customer_model->set_job_supervisors($job_id,$supervisor_id);
                return $this->response('0001');
            };
        } 
        else {
            $this->load->view('job/add_job_supervisor', array('title' => 'Add job Supervisor'));
        }
    }

    public function delete_supervisor(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('supervisor_id', 'Supervisor ID','required|trim|numeric|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $user_id = $this->_user_id;
            $supervisor_id = $this->input->post('supervisor_id');
            $res =  $this->customer_model->check_valid_supervisor($supervisor_id,$user_id);
            if(!empty($res)){
                if($this->ion_auth->delete_user($supervisor_id)){
                    return $this->response('0001');
                }
                return $this->response('0030');
            }
            return $this->response('0030');
        } 
        else {
            $this->load->view('job/get_by_id', array('title' => 'Delete Supervisor', 'name' => 'Supervisor Id', 'id' => 'supervisor_id'));
        }
    }

    function create_induction(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id; 

            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|xss_clean');
            $this->form_validation->set_rules('provider', 'Provider','required|trim|xss_clean');
            $this->form_validation->set_rules('link', 'Link','required|trim|xss_clean');
            $this->form_validation->set_rules('contact_no', 'Contact','required|trim|xss_clean');
            $this->form_validation->set_rules('other', 'Other','required|trim|xss_clean');
             
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');
                $check = $this->customer_model->get_job($job_id,$user_id);

                if(!$check OR empty($check)){
                    return $this->response('0020');
                }

                $insert_array = array(
                        'job_id' => $this->input->post('job_id'),
                        'quote_number' => $check->quote_id,
                        'induction_number' => $this->customer_model->generate_induction_number(),
                        'url' => $this->input->post('link'),
                        'contact_number' => $this->input->post('contact_no'),
                        'other_detail' => $this->input->post('other')
                    );
                $id = $this->customer_model->set_induction($insert_array);
                if($id){
                    return $this->response('0001');
                }
                else{
                    return $this->response('0000');
                }
                
            };
        } 
        else {
            $this->load->view('job/create_induction', array('title' => 'Create Induction'));
        }
    }


    function get_qualifications(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $res = $this->customer_model->get_qualifications($user_id);
          
            return $this->response('0001', array('qualifications' => $res ));
        } 
        else {
            $this->load->view('job/search', array('title' => 'Qualification'));
        }
    }

    function get_attributes(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $res = $this->customer_model->get_attributes($user_id);
          
            return $this->response('0001', array('attributes' => $res ));
        } 
        else {
            $this->load->view('job/search', array('title' => 'attributes'));
        }
    }

    function set_attributes(){

        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|xss_clean');
            $this->form_validation->set_rules('attributes', 'Attributes','required|trim|xss_clean');
             
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');
                $attributes = $this->input->post('attributes');
                $user_id = $user->id; 
                $check = $this->customer_model->check_job($job_id,$user_id);
                if(!$check){
                    return $this->response('0020');
                }
                $res = $this->customer_model->set_attributes($attributes,$job_id);
                if($res){
                    return $this->response('0001');
                }
                else{
                    return $this->response('0000');
                }
            };
        } 
        else {
            $this->load->view('job/set_attributes', array('title' => 'Set Attributes'));
        }
    }

     function add_new_qualification(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $this->form_validation->set_rules('name', 'Name','required|trim|xss_clean');
            $this->form_validation->set_rules('code', 'code','required|trim|xss_clean');
             
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $name = $this->input->post('name');
                $insert = array(
                    'name' => $name,
                    'entered_date' => date('Y-m-d H:i:s'),
                    'entered_by' => $user_id,
                    'status' => 1
                );
                $id = $this->webservice_model->insertRow($insert,'tbl_con_qualification');
                if($id){
                    return $this->response('0001', array('id'=> $id, 'name' => $name ));
                }
                else{
                    return $this->response('0000', array("msg" => 'Server Error'));
                }
            }
        } 
        else {
            $this->load->view('job/add_qualification', array('title' => ' Add Qualification'));
        }
    }

    function additional_information(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|xss_clean');
            $this->form_validation->set_rules('ppe', 'PPE','required|trim|xss_clean');
            $this->form_validation->set_rules('full_address', 'Full Address','required|trim|xss_clean');
            $this->form_validation->set_rules('street', 'Street','required|trim|xss_clean');
            $this->form_validation->set_rules('postcode_id', 'Postcode','required|trim|xss_clean');
            $this->form_validation->set_rules('job_site', 'Job Site','required|trim|xss_clean');
            $this->form_validation->set_rules('more_than_one_shift', 'More than one shift','required|trim|xss_clean');
            $this->form_validation->set_rules('presentable', 'presentable','required|trim|xss_clean');
            $this->form_validation->set_rules('transport', 'transport','required|trim|xss_clean');
             
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $ppe = $this->input->post('ppe');
                $ppe = explode(',', $ppe);
                $job_id = $this->input->post('job_id');
                $array = array(
                    'job_id'   => $job_id,
            
                    'ppe'     => serialize($ppe),
                    'full_address'  =>  $this->input->post('full_address'),
                    'street'  => $this->input->post('street'),
                    'postcode_id'  =>   $this->input->post('postcode_id'),
                    'job_site'  => $this->input->post('job_site') ? 'I':'O' ,
                    'more_than_one_shift' =>   $this->input->post('more_than_one_shift') ? 1: 0 ,
                    'presentable'   => $this->input->post('presentable') ? 1:0 ,
                    'transport'   => $this->input->post('transport')  ? 1:0 ,
                    'entered_date'   =>  date('Y-m-d H:i:s'),
                    'entered_by' => $user_id,
                    'updated_date'  =>  date('Y-m-d H:i:s'),
                    'updated_by'  => $user_id,
                );
                $id = $this->webservice_model->updateOrInsert('tbl_job_add_info',$array,array('job_id'  => $job_id));
                if($id){
                    return $this->response('0001', array("info" => $array));
                }
                return $this->response('0000');
            }
        } 
        else {
            $this->load->view('job/additional_information', array('title' => ' Additional Information'));
        }
    }

    public function generate_quote(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|xss_clean');

            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');

                $user_id = $user->id; 
                $check = $this->customer_model->check_job($job_id,$user_id);
                if(!$check){
                    return $this->response('0020');
                }
                $quote_id = $this->customer_model->generate_quote($job_id);
                $response = array();
                $response['quote_id'] = $quote_id;
                return $this->response('0001',$response);
            };
        } 
        else {
            $this->load->view('job/book_job', array('title' => 'Book Job'));
        }
    }

 
    function repeat_job(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }

            $user_id = $user->id;
            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|xss_clean');
            $this->form_validation->set_rules('schedule_type', 'Schedule','required|trim|xss_clean|one_of[' . self::SCHEDULE_WEEKLY .']');
            $this->form_validation->set_rules('schedule_rate', 'Schedule Rate','required|trim|xss_clean');
            $this->form_validation->set_rules('start_date', 'Start Date','required|trim|xss_clean');
            $this->form_validation->set_rules('required_occurance', 'Required Occurance','required|numeric|trim|xss_clean');
            $this->form_validation->set_rules('end_date', 'End Date','required|trim|xss_clean');
             
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');
                $check = $this->customer_model->check_job($job_id,$user_id);
                if(!$check){
                    return $this->response('0020');
                }
                $array = array(
                    'job_id' => $job_id,
                    'schedule_type' => $this->input->post('schedule_type'),  
                    'schedule_rate' => $this->input->post('schedule_rate'),  
                    'start_date' => date('Y-m-d',strtotime($this->input->post('start_date'))),             
                    'required_occurance' => $this->input->post('required_occurance'), 
                    'end_date' => date('Y-m-d',strtotime($this->input->post('end_date'))),                 
                    'entered_date' => date('Y-m-d'),  
                    'entered_by' => $user_id  
                );
                $id = $this->webservice_model->updateOrInsert('tbl_job_scheduler',$array,array('job_id'  => $job_id,));
                if($id){
                    return $this->response('0001');
                }
                else{
                    return $this->response('0000');
                }
            }
        } 
        else {
            $this->load->view('job/repeat_job', array('title' => 'Repeat Job'));
        }
    }

    /*function wage(){
        $query = $this->db->query("CALL sp_WRcalculation(?,?)", array(2,'asdfds'));
          // $query = $this->db->query("sp_WRcalculation(1,'sds')");
        $this->db->freeDBResource($this->db->conn_id);

        $result = ($query->num_rows() > 0) ? $query->row() : FALSE;
        dd($result);
    }*/

    public function wage(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');
                $user_id = $this->_user_id;
                $check = $this->customer_model->check_job($job_id,$user_id);
                if(!$check){
                    return $this->response('0020');
                }
                $query = $this->db->query("CALL sp_WRcalculation(?,?)", array($job_id,'str'));
                $this->db->freeDBResource();
                $result = ($query->num_rows() > 0) ? $query->row() : FALSE;
     
                if(!empty($result) && $result->STATUS == 1 ){
                    $wage = $result->total_wage;
                    $response = array();
                    $response['wage'] = $wage;
                    return $this->response('0001',$response);
                }
                return $this->response('0000');
                
            };
        } 
        else {
            $this->load->view('job/book_job', array('title' => 'Get Wage'));
        }
    }

    public function get_calendar_jobs(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('date', 'Date','required|trim|validate_date["Y-m-d"]|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $date = $this->input->post('date');
                $specific_date = $this->input->post('specific_date');
                $specific_date = empty($specific_date) ? false : true ;
                $date = date('Y-m-d',strtotime($date));
                $user_id = $this->_user_id;
                $jobs = $this->customer_model->get_jobs($user_id,$this->_lhc_user_id,$date,$specific_date);
                if(!empty($jobs)){
                    return $this->response('0001',array('jobs' => $jobs));
                }
                return $this->response('0030');
            };
        } 
        else {
            $this->load->view('job/get_calendar_jobs', array('title' => 'Calender Jobs'));
        }
    }

    public function get_peak_price(){
     if ($this->input->post()) {
            $user_id = $this->_user_id;

            $this->load->library('form_validation');
            $this->form_validation->set_rules('job_id','Job ID','trim|required|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');
                $query = $this->db->query("CALL sp_WRcalculation(?,?)", array($job_id,'str'));
                $this->db->freeDBResource();
                $result = ($query->num_rows() > 0) ? $query->row() : FALSE;
     
                if(!empty($result) && $result->STATUS == 1 ){
                    $wage = $result->total_wage;
                    $res = $this->customer_model->get_peak_activity_level();
               
                    $new = array();
                    if(!empty($res)){
                        foreach ($res as $r) {
                            $r->total = $r->peak_price_factor * $wage;
                            $new[] = $r;
                        }
                    }
                    if(empty($new)){
                        return $this->response('0030');
                    }
                    $this->_response['wage'] = $wage;
                    $this->_response['prices'] = $new;
                    return $this->response('0001',$this->_response);
                }
                return $this->response('0030');
                
            }

        } 
        else {
            $this->load->view('job/job_detail', array('title' => 'Peak Price'));
        }
    }

    public function view_book_screen(){
        if ($this->input->post()) {
            $user_id = $this->_user_id;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('job_id','Job ID','trim|required|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            else{
                $job_id = $this->input->post('job_id');
                $res = $this->customer_model->getJobDetail($job_id,$user_id);
                return $this->response('0001', $res);
            }

        } 
        else {
            $this->load->view('job/job_detail', array('title' => 'View Book Screen'));
        }
    }

    public function _validate_registration(){
        
        $this->form_validation->set_rules(
                array(
                    array('field' => 'name', 'label' => 'Name', 'rules' => 'required|trim'),
                    array('field' => 'address', 'label' => 'Address', 'rules' => 'trim|required'),
                    array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email'),
                    array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),
                    array('field' => 'phone_number', 'label' => 'Mobile Number', 'rules' => 'trim|required'),
                )
        );  
    }

    public function delete_shift(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('shift_id', 'Shift ID','required|trim|numeric|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $user_id = $this->_user_id;
         /*    $res =  $this->customer_model->get_all_jobs($user_id);
             $this->_response['res'] = $res;
             return $this->response('0001',$this->_response);*/
            $shift_id = $this->input->post('shift_id');
            $res =  $this->customer_model->validate_shift($shift_id,$user_id);
            if(!empty($res)){
                $status = $res->status;
                if($status == 0){
                    $this->customer_model->delete_shift($shift_id);
                    return $this->response('0001');
                }
                else{
        
                    return $this->response('0021');
                }
                
            }
     
            return $this->response('0030');
        } 
        else {
            $this->load->view('job/get_by_id', array('title' => 'Delete Shift', 'name' => 'Shift Id', 'id' => 'shift_id'));
        }
    }

    public function delete_job(){
        if ($this->input->post()) {
            $this->form_validation->set_rules('job_id', 'Job ID','required|trim|numeric|xss_clean');
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $user_id = $this->_user_id;
            $job_id = $this->input->post('job_id');
            $res =  $this->customer_model->validate_job($job_id,$user_id);
            if(!empty($res)){
                $status = $res->status;
                if($status == 0){
                    if($this->customer_model->delete_job($job_id)){
                        return $this->response('0001');
                    }
                    return $this->response('0021');
                }
                return $this->response('0021');
              
            }
            return $this->response('0030');
        } 
        else {
            $this->load->view('job/get_by_id', array('title' => 'Delete Job', 'name' => 'Job Id', 'id' => 'job_id'));
        }
    }

    public function extract_data($components, $type){
        for ($i=0; $i< count($components); $i++)
            for ( $j=0; $j< count($components[$i]->types); $j++)
                if ($components[$i]->types[$j]== $type) return $components[$i]->long_name;
        return "";
    }

   
   
}
