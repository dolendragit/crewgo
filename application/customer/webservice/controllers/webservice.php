<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Webservice extends Web_Service_Controller {
    
	public $_data = array();

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
            return call_user_func_array(array($this, $method), $params);
        }
        $this->error();
    }

    public function index() {
        redirect('developer_view');
    }

    public function error() {
        $this->load->view('error');
    }

    function register() {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'name', 'label' => 'Name', 'rules' => 'required|trim'),
                        array('field' => 'address', 'label' => 'Address', 'rules' => 'trim|required'),
                        array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email'),
                        array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),
                        array('field' => 'phone_number', 'label' => 'Mobile Number', 'rules' => 'trim|required'),
                    )
            );
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

            $additional_data = array(
            	'name' => $name,
                'full_address' => $address,
                'phone_number' => $phone_number,
                'activation_code' => $activation_code,
                'active' => 0,
                'created_on' => date('Y-m-d H:i:s')
                
            );
            $res = $this->customer_model->check_if_customer($email);
            if(!empty($res)){
                return $this->response('0004');
            }
            if ($user_id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array(CUSTOMER))) {

                $this->send_email($user_id, $email, $activation_code, $name);
                $this->webservice_model->updateWhere(array('id' => $user_id), array('active' => 0), 'tbl_user');
                $code = '0001';
                $return = array('user_id' => $user_id);
            } 
            else {
                $code = '0000';
                $return = array('msg' => $this->ion_auth->errors());
            }
    

            return $this->response($code, $return);
        } else {
            $this->load->view('register', array('title' => 'Registration'));
        }
    }

    function send_email($user_id, $email, $activation_code, $username) {
        $this->load->library('email');

        $to = $email;
        $subject = "Email confirmation";
        $data = array(
            'username' => $username,
            'user_id' => $user_id,
            'activation_code' => $activation_code
        );


        $message = $this->load->view("email_template/email_confirmation", $data, TRUE);

        $from = "info@crewgo.com";
        $this->email->to($to)
                ->from($from, "CREWGO")
                ->subject($subject)
                ->message($message);

        $this->email->send();
       
    }

    function activate($user_id, $activation_code) {
        $identity = (int) $user_id;
        $checkuserid = $this->db->query("select id from tbl_user where id = '$identity' and activation_code = '$activation_code'");
        if ($checkuserid->num_rows() > 0) {
            $this->db->update('tbl_user', array('active' => '1','activation_code' => '','email_verified' => '1'), array('id' => $user_id));
            $user_email = $this->db->select('email,name')->from('tbl_user')->where("id = '$user_id'")->get();
            $user_email = $user_email->row();
            $this->load->library('email');

            $to = $user_email->email;
            $subject = "Welcome to CREWGO";

            $message = $this->load->view('email_template/activation_template', array('name' => $user_email->name), TRUE);

            $from = "info@crewgo.com";
            $this->email->to($user_email->email)
                    ->from($from, "CREWGO")
                    ->subject($subject)
                    ->message($message);

            $this->email->send();
             echo $content = "<p>Your account is validated now.Thank you</p>";
        } else {

            echo $content = "<p>Invalid Link</p>";
        }
    }

    function login() {
        if ($this->input->post()) {
            $return = array();
            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required'),
                        array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),
                        array('field' => 'device_id', 'label' => 'Device Id', 'rules' => 'trim|required'),
                        array('field' => 'device_type', 'label' => 'Device Type', 'rules' => 'required|trim'),
                    )
            );
            $this->form_validation->set_error_delimiters('', '');
            $this->ion_auth->set_error_delimiters('', '');
            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $encrypted_password = $this->ion_auth->hash_password($password);

            $deviceId = $this->input->post('device_id');
            $deviceType = $this->input->post('device_type');
            $hashCode = $this->webservice_model->hashCode();

            if ($this->ion_auth->login($email, $password, FALSE, CUSTOMER) == TRUE) {
                $query = $this->db->query("CALL sp_tokenLogin(?,?,?,?,?,?)", array($email, $encrypted_password, $deviceId, $hashCode, $deviceType,CUSTOMER));
                if ($query->num_rows() == 0) {
                    return $this->response('0000');
                }
                $result = $query->row();
                $this->db->freeDBResource();
                if ($result->status == 1) {
                    $this->_lhc_options($result);
                } elseif ($result->status == 0) {
                    return $this->response('0012');
                } else {
                    return $this->response('0012');
                }
            } else {
                return $this->response('0000', array('msg' => $this->ion_auth->errors()));
            }
        } else {
            $this->load->view('login', array('title' => 'Login'));
        }
    }

    public function _return_login_details($result="",$array=false){
        $payment = $this->webservice_model->getOneWhere(array('customer_user_id' => $result->user_id),'tbl_customer_payment_info');
        $payment_flag = empty($payment) ? false : true;
        $return = array(
                    'user_info' => array(
                        'user_id' => $result->user_id,
                        'hash_code' => $result->hash_code,
                        'name' => $result->name,
                        'profile_image' => get_customer_profile_image($result->profile_image),
                        'email' => $result->email,
                        'authentication_key' => $result->key,
                        'phone_number' => $result->phone_number,
                        'full_address' => $result->full_address,
                        'setup_payment' => $payment_flag
                    ),
                    'lhcs' => array(),
                    'is_crewgo' => "0"
                );
        if($array){
            return $return['user_info'];
        }
        return $this->response('0001', $return);
    }

    public function _lhc_options($result=""){
        $res = $this->customer_model->get_lhc_customer_association($result->user_id);
        $user_info = $this->_return_login_details($result,TRUE);

        if($res->count > 0){
            $lhcs = $res->lhcs;
            
            $ret = array(
                'lhcs' => $lhcs,
                'user_info' => $user_info
            );
            $is_crewgo = "1";
            if($result->register_from == 'LHC'){
                $is_crewgo = "0";
            }
            $ret['is_crewgo'] = $is_crewgo;
            return $this->response('0001', $ret);
        }
        else{
            $lhcs = array();
            
            $ret = array(
                'lhcs' => $lhcs,
                'user_info' => $user_info
            );
            $is_crewgo = "0";
            if($result->register_from == 'APP'){
                $is_crewgo = "1";
            }
            $ret['is_crewgo'] = $is_crewgo;
            return $this->response('0001', $ret);
            //$this->_return_login_details($result);
        }
    }

    public function set_lhc_user(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('lhc_user_id', 'LHC User ID', 'trim|required');

            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $lhc_user_id = $this->input->post('lhc_user_id');
            $res = $this->customer_model->check_lhc_customer_association($lhc_user_id,$user->id);
            if($res){
                $lhc = $this->customer_model->set_lhc_customer_association($lhc_user_id,$user->id);
                if($lhc){
                    return $this->response('0001');
                }
                return $this->response('0015');
            }
            else{
                return $this->response('0015');
            }
        } 
        else {
            $this->load->view('set_lhc_user', array('title' => 'Set LHC User'));
        }
    }

    public function get_lhc_options(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $res = $this->customer_model->get_lhc_customer_association($user_id);
            if($res->count > 0){
                $lhcs = $res->lhcs;
                return $this->response('0001', array('lhcs' => $lhcs));
            }
            else{
                return $this->response('0017');
            }
        } 
        else {
            $this->load->view('simple', array('title' => 'Unset LHC User'));
        }
    }


    public function unset_lhc_user(){
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $headers = apache_request_headers();
            $key = @$headers['Authentication-Key'];
            $this->webservice_model->updateWhere(array('key' => $key), array('lhc_user_id' => 0), 'keys');
            return $this->response('0001');
        } 
        else {
            $this->load->view('simple', array('title' => 'Unset LHC User'));
        }
    }
    
    public function logout() {

        if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
        }
        $headers = apache_request_headers();
        $key = @$headers['Authentication-Key'];
        $query = $this->db->query("CALL sp_Logout(?)", array($key));
        $result = $query->row();
        if ($result->status == 1) {
            return $this->response('0001');
        } else {
            return $this->response('0000');
        }
    }
    
    function update_device_info() {
        if ($this->input->post()) {
        
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'device_name', 'label' => 'Device Name', 'rules' => 'required|trim'),
                        array('field' => 'device_model', 'label' => 'Device Model', 'rules' => 'required|trim'),
                        array('field' => 'os_version', 'label' => 'OS Version', 'rules' => 'trim|required'),
                        array('field' => 'device_token', 'label' => 'Device Token', 'rules' => 'trim|required'),
                    )
            );
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $device_name = $this->input->post('device_name');
            $device_model = $this->input->post('device_model');
            $device_type = $this->input->post('device_type');
            $os_version = $this->input->post('os_version');
            $device_token = $this->input->post('device_token');
            
            $query = $this->db->query("CALL sp_updateDeviceInfo(?,?,?,?,?,?,?)", array($user->id, $user->device_id, $device_name, $device_model, $os_version, $device_token, $device_type));
            $result = $query->row();
            $this->db->freeDBResource($this->db->conn_id);
            if ($result->status == 1) {
                return $this->response('0001');
            } else {
                return $this->response('0016');
            }
        } else {
            $this->load->view('update_device_info', array('title' => 'Update Device Info'));
        }
    }


    public function fb_login() {
        if ($this->input->post()) {

            $this->form_validation->set_rules('access_token', 'Access Token','required|trim|xss_clean');  
            $this->form_validation->set_rules('device_id', 'Device Id','required|trim|xss_clean');  
            $this->form_validation->set_rules('device_type', 'Device Type','required|trim|xss_clean');  

            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array("msg" => validation_errors()));
            }

            $this->load->library('Facebook');
            $access_token = $this->input->post('access_token');
            $this->facebook->setAccessToken($access_token);

            $userId = $this->facebook->getUser();
            if ($userId == 0){
                return $this->response('0000', array("msg" => "Invalid authentication."));
            } 
            else{
                $user = $this->facebook->api('/me?fields=email,name,first_name,last_name');
                if (isset($user['id']) && $user['id'] != '') {
                    $full_name = $user['name'];
                    $email = $user['email'];
                    $facebook_id = $user['id'];
                    $result = $this->customer_model->check_customer_fb($facebook_id,$email);     
                    if (!empty($result)) {
                        return $this->auto_login($email, $result->password);
                    }
                    else {
                        $response = array();
                        $response['name'] = $user['name'];
                        $response['email'] = $user['email'];
                        $response['access_token'] = $access_token;
                        return $this->response('0005',$response);
                    }
                } 
                else {
                    return $this->response('0025');
                }
            }
        } 
        else {
            $this->load->view('fb_login', array('title' => 'Login/Signup with facebook'));
        }
    }

    public function fb_register(){

        if ($this->input->post()) {
            $this->form_validation->set_rules('access_token', 'Access Token','required|trim|xss_clean');
            $this->_validate_registration();
            $this->form_validation->set_rules('email', 'email','required|trim|xss_clean|valid_email');

            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $access_token = $this->input->post('access_token');
            $this->load->library('Facebook');
            $this->facebook->setAccessToken($access_token);

            $userId = $this->facebook->getUser();
            if ($userId == 0) {
                return $this->response('0000', array("msg" => "Invalid authentication."));
            } 
            else{
                $user = $this->facebook->api('/me?fields=email,name,first_name,last_name');
                if (isset($user['id']) && $user['id'] != '') {
                    $full_name = $user['name'];
                    $email = $user['email'];
                    $facebook_email = $user['email'];
                    $facebook_id = $user['id'];
                    $result = $this->customer_model->check_customer_fb($facebook_id,$email);  

                    if (!empty($result)) {
                        return $this->response('0004');
                    }
                    else{
                        $name = trim($this->input->post('name'));
                        $address = $this->input->post('address');
                        $email = trim($this->input->post('email'));
                        $username = $name;
                        $password = $this->input->post('password');
                        $phone_number = trim($this->input->post('phone_number'));
                        $activation_code = sha1(md5(microtime()));
                        $additional_data = array(
                            'name' => $name,
                            'full_address' => $address,
                            'phone_number' => $phone_number,
                            'login_from' => 'FB',
                            'facebook_id' => $facebook_id,
                            'activation_code' => $activation_code,
                            'created_on' => date('Y-m-d H:i:s')
                        );

                        $password = $this->webservice_model->hashCode(8); 
                        $res = $this->customer_model->check_if_customer($email);
                        if(!empty($res)){
                            $id = $res->id;
                            $this->ion_auth->update($id,$additional_data);
                            if(empty($facebook_email)){
                                $this->webservice_model->updateWhere(array('id' => $id ), array('active' => 1),'tbl_user');
                            }
                            else{
                                $this->webservice_model->updateWhere(array('id' => $id ), array('active' => 0),'tbl_user');
                                $this->send_email($id, $email, $activation_code, $name);
                            }
                            $response = array('user_id' => $id);
                            return $this->response('0001',$response);
                        }
                        else{
                            if ($id = $this->ion_auth->register($email, $password, $email, $additional_data, array(CUSTOMER))) {
                                $this->webservice_model->updateWhere(array('id' => $id ), array('active' => 0),'tbl_user');
                                if(empty($facebook_email)){
                                    $this->webservice_model->updateWhere(array('id' => $id ), array('active' => 1),'tbl_user');
                                }
                                else{
                                    $this->webservice_model->updateWhere(array('id' => $id ), array('active' => 0),'tbl_user');
                                    $this->send_email($id, $email, $activation_code, $name);
                                }
                                $response = array('user_id' => $id);
                                return $this->response('0001',$response);
                            } 
                            else{
                                return $this->response('0000');
                            }
                        }
                    }
                   
                } 
                else {
                    return $this->response('0025');
                }
            }
        } 
        else {
            $this->load->view('fb_register', array('title' => 'Login/Signup with facebook'));
        }
    }

     public function google_login() {
        if ($this->input->post()) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|valid_email'),
                        array('field' => 'device_id', 'label' => 'Device Id', 'rules' => 'required|trim'),
                        array('field' => 'device_type', 'label' => 'Device Type', 'rules' => 'trim|required'),
                        array('field' => 'google_id', 'label' => 'User Id', 'rules' => 'trim|required'),
                        //array('field' => 'google_flag', 'label' => 'Flag', 'rules' => 'trim|required'),
                    )
            );
            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $google_user_id = $this->input->post('google_id');
            $email = $this->input->post('email');

            $res = $this->customer_model->check_google_login($google_user_id,$email);
            if($res){
                 return $this->auto_login($email,$res->password);
            }
            else {
                $response = array();
                $response['email'] = $this->input->post('email');
                $response['google_id'] = $this->input->post('google_id');
                return $this->response('0009',$response);
            }

        } else {
            $this->load->view('google_login', array('title' => 'Login/Signup with google'));
        }
    }


    public function google_register(){

        if ($this->input->post()) {
            $this->form_validation->set_rules('google_id', 'Google ID','required|trim|xss_clean');
            //$this->form_validation->set_rules('google_flag', 'Google Flag','required|trim|xss_clean');
            $this->_validate_registration();
            //$this->form_validation->set_rules('email', 'email','required|trim|xss_clean|valid_email|unique[tbl_user.email]');

            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $google_id = $this->input->post('google_id');
            $email = $this->input->post('email');
            if ($google_id == 0 OR empty($google_id)) {
                return $this->response('0000', array("msg" => "Invalid authentication."));
            } 
            else{
                //$result = $this->customer_model->getOneWhere(array('google_id' => $google_id,'email'=>$email),'tbl_user');
                $this->db->select('u.id')->from('tbl_user as u');
                $this->db->join('tbl_user_group as ug','u.id = ug.user_id');
                $this->db->where('u.google_id',$google_id); 
                $this->db->where('u.email',$email); 
                $this->db->where('ug.group_id',CUSTOMER); 
                $result = $this->db->get()->row();

                if (!empty($result)) {
                    return $this->response('0004');
                }
                else{
                    $name = trim($this->input->post('name'));
                    $address = $this->input->post('address');
                    $email = trim($this->input->post('email'));
                    $username = $name;
                    $password = $this->input->post('password');
                    $phone_number = trim($this->input->post('phone_number'));
                    $activation_code = sha1(md5(microtime()));
                    $additional_data = array(
                        'name' => $name,
                        'full_address' => $address,
                        'phone_number' => $phone_number,
                        'login_from' => 'GMAIL',
                        'google_id' => $google_id,
                        'activation_code' => $activation_code,
                        'created_on' => date('Y-m-d H:i:s')

                      
                    );
                    $password = $this->webservice_model->hashCode(8); 

                    $res = $this->customer_model->check_if_customer($email);

                    if(!empty($res)){
                        $id = $res->id;
                        $this->ion_auth->update($id,$additional_data);
                        $this->webservice_model->updateWhere(array('id' => $id ), array('active' => 0),'tbl_user');
                        $this->send_email($id, $email, $activation_code, $name);
                        $response = array('user_id' => $id);
                        return $this->response('0001',$response);
                    }
                    else{
                        if ($id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array(CUSTOMER))) {
                            $this->webservice_model->updateWhere(array('id' => $id ), array('active' => 0),'tbl_user');
                            $this->send_email($id, $email, $activation_code, $name);
                            $response = array('user_id' => $id);
                            return $this->response('0001',$response);
                        } 
                        else{
                            return $this->response('0000');
                        }
                    }
                }
               
                
            }
        } 
        else {
            $this->load->view('google_register', array('title' => 'Register with Google'));
        }
    }


    function forgot_password() {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean|max_length[100]');
            if ($this->form_validation->run() != true) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            
            $identity = $this->input->post('email');
            $query = $this->db->query("CALL sp_checkUserByGroup(?,?)", array($identity,CUSTOMER));
            $this->db->freeDBResource($this->db->conn_id);
            $result = $query->row();

            if ($result->status == 1) {
                if($result->active != '1' ){
                    return $this->response("0000", array("msg" => "User is Inactive"));
                }
                $token = $this->webservice_model->hashCode(20);

                $this->db->query("CALL sp_assignTokenById(?,?)", array($result->id, $token));
                $this->db->freeDBResource($this->db->conn_id);
                $url = base_url("customer/webservice/reset_password/{$result->id}/{$token}");
                $to = $identity;
                $subject = "Password Reset";

                $emailData = array();
                $emailData['forgot_link'] = anchor($url, 'click here');
                $emailData['name'] = $result->name;
                $message = $this->load->view('email_template/forgot_password_template', $emailData, TRUE);
                $from = 'info@crewgo.com';

                $this->load->library('email');
                $this->email->to($to)
                        ->from($from, 'CREWGO')
                        ->subject($subject)
                        ->message($message);

                if ($this->email->send()) {
                    return $this->response('0001',array("msg" => 'Please check your email to reset password.'));
                } else {
                    return $this->response('0000');
                }
            } else {
                return $this->response('0014');
            }
        } else {
            $this->load->view('forgot_password_request', array('title' => 'Forgot Password'));
        }
    }

    function reset_password($user_id, $token) {
        $user = $this->db->where('forgotten_password_code', $token)->where('id', $user_id)->get('tbl_user')->row(); //pass the code to profile

        if (is_object($user)) {

            $this->load->library(array('session'));
            $this->load->library('form_validation');
            $this->form_validation->set_rules('new', 'New Password', 'required|min_length[4]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', "Confirm Password", 'required');

            if ($this->form_validation->run() == false) {
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
                $this->data['min_password_length'] = 6;
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password'
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password'
                );
            } else {
                $user = $this->ion_auth->user($user_id)->row();
                $identity = $user->email;

                $password = $this->input->post('new');
                $password = $this->ion_auth->hash_password($password);
                $res = $this->customer_model->reset_password($user_id,$password);
                if ($res) {
                    $this->data['content'] = "Your password has been changed. You can now close this window.";
                } else {
                    $this->data['content'] = "<p>Sorry, your link couldn't be validated. Please ask for new link.</p>";
                    $this->data['content'] .= "<p>You can now close this window.</p>";
                }
            }
        } else {

            $this->data['content'] = "<p>Sorry, your link couldn't be validated. Please ask for new link.</p>";
            $this->data['content'] .= "<p>You can now close this window.</p>";
        }
        $this->load->view('forgot_password_change', $this->data);
    }
  
    public function make_payment(){
        if ($this->input->post()) {
    
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                array(
                    array('field' => 'amount', 'label' => 'Amount', 'rules' => 'trim|required|numeric|xss_clean'),
                    array('field' => 'customer_id', 'label' => 'Customer ID', 'rules' => 'trim|required|xss_clean'),

                )
            );
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $amount = $this->input->post('amount');
            $customer_id = $this->input->post('customer_id');
      
       
            $this->load->library('stripe');
            $res = $this->stripe->charge_by_customer_id($customer_id,$amount);

            if($res->status =='error'){
                return $this->response("0000", array("msg" => $res->message));
            }
            elseif($res->status == 'success'){
                //  $data = array(
                //     'id'  => $res->id,
                //     'amount'  => $res->amount ,  
                //     'customer'  => $res->customer ,
                //     'created' => $res->created ,  
                // );
                       
                // return $this->response("0001", $data);
                $data = array(
                    'user_id'  => $user_id,
                    'transaction_id'  => $res->id ,  
                    'amount'  => $res->amount ,
                    'created_at' => date('Y-m-d H:i:s'),  
                );
                $id = $this->webservice_model->insertRow($data,'payments');
                if($id){
                    return $this->response('0001', $data);
                }
                else{
                    return $this->response("0000", array("msg" => "something went wrong"));
                }
            }
    
            
            
        } 
        else {
            $this->load->view('make_payment', array('title' => 'Make Payment'));
        }
    }

    public function payment_history(){
        if ($this->input->post()) {
            $offset = 0 ;
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                array(
                    array('field' => 'offset', 'label' => 'Amount', 'rules' => 'trim|required|numeric|xss_clean'),
                )
            );
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $offset = $this->input->post('offset');
            $this->db->select('*')->from('payments');
            $this->db->where('user_id',$user_id);
            $this->db->limit(10, $offset);
            $res = $this->db->get()->result();
     
            if($res){
                return $this->response('0001', array('payments' => $res));
            }
            else{
                return $this->response("0000", array("msg" => "No Records Found"));
            }
            
        } 
        else {
            $this->load->view('payment_history', array('title' => 'Make Payment'));
        }
    }

    public function google_test(){
        $this->load->library('google');
        $url = $this->google->get_authUrl();
        dd($url);
    }

    public function google_token(){

        $this->load->library('google');
        $url = $this->google->get_accessToken();
        dd($url);


    }


    public function stripe(){

       $this->load->library('stripe');
            $myCard = array(
                'number' => '4242424242424242', 
                'exp_month' => 8, 
                'exp_year' => 2018,
                "cvc" => "314"
            );
            $amount = "2500"; //amount is on cents. Ex. 2500 for $. 25
            $s = $this->stripe->charge($myCard,$amount);
        
        debug($s);


    }

    public function create_token(){
         $myCard = array(
                'number' => '4242424242424242', 
                'exp_month' => 8, 
                'exp_year' => 2018,
                "cvc" => "314"
            );
        $this->load->library('stripe');
        $s = $this->stripe->create_token($myCard);
    }

     public function create_customer(){
        $myCard = array(
                'number' => '4242424242424242', 
                'exp_month' => 8, 
                'exp_year' => 2018,
                "cvc" => "314"
            );
        $this->load->library('stripe');
        $s = $this->stripe->create_customer($myCard,8);
        debug($s);
     }

     public function charge_by_customer_id(){
        $this->load->library('stripe');
        $s = $this->stripe->charge_by_customer_id($customer_id="cus_9GTL0OA937fEv5",$amount=1000);
        debug($s);
     }

    public function change_password(){
        if ($this->input->post()) {
	        if (!($user = $this->is_logged_in())) {
	            return $this->response('0006');
	        }
	        $user_id = $user->id;
	       
	        $this->form_validation->set_rules(
    	        array(
    	        array('field' => 'old_password', 'label' => 'Old Password', 'rules' => 'trim|required|xss_clean'),
    	        array('field' => 'new_password', 'label' => 'New Password', 'rules' => 'trim|required|xss_clean'),
    	        array('field' => 'confirm_password', 'label' => 'Confirm Password', 'rules' => 'trim|required|xss_clean|matches[new_password]'),
    	        )
	        );
	        if ($this->form_validation->run($this) === false) {
	           return $this->response('0000', array("msg" => validation_errors()));
	        }
	        $oldpassword = $this->ion_auth->hash_password_db_send($user->id,$this->input->post('old_password'));
            if($user->password != $oldpassword ){
	           return $this->response("0000", array("msg" => "Old password incorrect"));
	        }
            $password = $this->ion_auth->hash_password($this->input->post('new_password'));
            $res = $this->customer_model->reset_password($user_id,$password);
	        if ($res) {
	           return $this->response("0001",array("msg" => "Password successfully changed"));
	        } 
            else {
	           return $this->response("0000");
	        }
        } 
        else {
        $this->load->view('change_password', array('title' => 'Change Password'));
        }
    }


     public function auto_login($email, $encrypted_password) {

        $deviceId = $this->input->post('device_id');
        $deviceType = $this->input->post('device_type');
        $hashCode = $this->webservice_model->hashCode();
        $query = $this->db->query("CALL sp_tokenLogin(?,?,?,?,?,?)", array($email, $encrypted_password, $deviceId, $hashCode, $deviceType,CUSTOMER));
        $this->db->freeDBResource($this->db->conn_id);

        if ($query->num_rows() == 0) {
            return $this->response('0000');
        }
        $result = $query->row();

        if ($result->status == 1) {
            return $this->_lhc_options($result);
        } 
        elseif ($result->status == 2) {
            return $this->response('0007');
        } 
        else {
            return $this->response('0002');
        }
    }

    function mail_test(){
           $data = array(
            'username' => 'aaaa',
            'user_id' => 'uid',
            'activation_code' => 'ac'
        );


        $message = $this->load->view("email_template/email_confirmation", $data);
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

    function test_method(){
        return $this->response('0002');
    }
 
}
