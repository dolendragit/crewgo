<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Web_Service_Controller{

    public $_data = array();
    public $_user_id;
    public $_response = array();
    public $protected_methods = array('detail');
    private $_offset = 0 ;

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('ws_helper');
        $this->load->model(array('webservice_model','supervisor_model','customer_model'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->ion_auth->set_error_delimiters('', '');
    }

    public function _remap($method, $params = array()) {
        $method = $method;
        if (method_exists($this, $method)) {
            if ($this->input->post() OR in_array($method, $this->protected_methods)) {
                if (!($user = $this->is_supervisor())) {
                    return $this->response('0006');
                }
                $this->_user_id = $user->id;    
            }
            return call_user_func_array(array($this, $method), $params);
        }
        return $this->response('0000');
    }

    /** Returns Object of Supervisor Profile details
     * @return mixed
     */

    public function detail(){
   
            $details = $this->customer_model->get_customer_profile($this->_user_id);
            $details->profile_image = getProfileImage($details->profile_image);

            $response['details'] = $details;
            $response['availability'] = $this->db->select('day_0,day_1,day_2,day_3,day_4,day_5,day_6')->from('tbl_supervisor_availability')->where('supervisor_user_id',$this->_user_id)->get()->result();
            $this->_response['userInfo'] = $response;
            return $this->response('0001', $this->_response);
        
    }

     /** Method for Edit Profile
     * @return mixed
     */

    public function edit()
    {
        if ($this->input->post()) {

            $user_id = $this->_user_id;
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('phone', 'Phone', 'required|trim|xss_clean');
            $this->form_validation->set_rules('profile_image', 'Profile Image', 'xss_clean');
            $this->form_validation->set_rules('availability', 'Availability', 'xss_clean|trim');

            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $availability =  $this->input->post('availability');
            $availabilityArr = array();
            if(!empty($availability)){
                $availabilityArr = explode(',', $availability);
                if(count($availabilityArr) != 7){
                     return $this->response('0022');
                }
            }
        
            $name = $this->input->post('name');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $phone_number = $this->input->post('phone');
            $profile_image = $this->input->post('profile_image');

            $config['upload_path'] = ASSETSPATH.'/images/profile_image/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2000;
            $config['max_width'] = 5000;
            $config['max_height'] = 5000;
            if (!empty($profile_image)) {
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('profile_image')) {
                    return $this->response('0000', array("msg" => $this->upload->display_errors()));
                } else {
                    $data = $this->upload->data();
                    $profile_image = $data['file_name'];
                }
            }
            $user_data = array(
                'name' => $name,
                'full_address' => $address,
                'phone_number' => $phone_number,
            
            );
            if (!empty($profile_image)) {
                $user_data['profile_image'] = $profile_image;
            }
            $this->ion_auth->update($this->_user_id, $user_data);
            if(!empty($availabilityArr)){
                $dayArr = array();
                foreach ($availabilityArr as $k=>$v) {
                    $dayArr['day_'.$k] = $v == 0 ? 0 : 1;
                }
                if(!empty($dayArr)){
                    $where = array('supervisor_user_id' => $this->_user_id);
                    $this->webservice_model->updateOrInsert('tbl_supervisor_availability', $dayArr, $where);
                }
            }
            $this->response('0001');

        } else {
            $this->load->view('profile/edit', array('title' => 'Edit Profile Detail'));
        }

    }
    
    /**
     * Get Timesheet data for Supervisor
     */
    public function timesheet(){
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if ($this->input->post() || !empty($key)) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                array(
                    array('field' => 'job_id', 'label' => 'Job Id', 'rules' => 'trim|required|integer'),
                )
            );
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $timeSheets = $this->supervisor_model->get_timesheet($user->id, $this->input->post('job_id'));
            return $this->response('0001', array('timesheet' => $timeSheets));
        }
        else {
            $this->load->view('profile/get_timesheet', array('title' => 'Get Timesheet'));
        }

    }
}


