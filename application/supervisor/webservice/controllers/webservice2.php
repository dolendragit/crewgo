<?php

namespace service;
if (!defined('BASEPATH')) {exit('No direct script access allowed');}
include_once APPPATH . 'customer/webservice/controllers/webservice2.php';



class Webservice2 extends \Webservice2
{


    public $_data = array();

    public function __construct()
    {
        
        parent::__construct();
        die('here');
        $this->userGroup =  SUPERVISOR;
        $this->load->helper('form');
        $this->load->helper('ws_helper');
        $this->load->model('webservice_model');
        $this->load->model('supervisor_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->ion_auth->set_error_delimiters('', '');
    }

    public function _remap($method, $params = array())
    {
        $method = $method;
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        $this->error();
    }

    public function index()
    {
        
        redirect('super_developer_view');
    }

    public function error()
    {
        $this->load->view('error');
    }

    public function register()
    {
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
            if ($this->form_validation->run() == false) {

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
                'created_on' => date('Y-m-d H:i:s'),

            );
            $res = $this->supervisor_model->check_if_customer($email);
            if (!empty($res)) {
                return $this->response('0004');
            }
            if ($user_id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array(CUSTOMER))) {

                $this->send_email($user_id, $email, $activation_code, $name);
                $this->webservice_model->updateWhere(array('id' => $user_id), array('active' => 0), 'tbl_user');
                $code = '0001';
                $return = array('user_id' => $user_id);
            } else {
                $code = '0000';
                $return = array('msg' => $this->ion_auth->errors());
            }

            return $this->response($code, $return);
        } else {
            $this->load->view('register', array('title' => 'Registration'));
        }
    }

    public function send_email($user_id, $email, $activation_code, $username)
    {
        $this->load->library('email');

        $to = $email;
        $subject = "Email confirmation";
        $data = array(
            'username' => $username,
            'user_id' => $user_id,
            'activation_code' => $activation_code,
        );

        $message = $this->load->view("email_template/email_confirmation", $data, true);

        $from = "info@cruva.com";
        $this->email->to($to)
            ->from($from, "CREWGO")
            ->subject($subject)
            ->message($message);

        $this->email->send();

    }

    public function activate($user_id, $activation_code)
    {
        $identity = (int)$user_id;
        $checkuserid = $this->db->query("select id from tbl_user where id = '$identity' and activation_code = '$activation_code'");
        if ($checkuserid->num_rows() > 0) {
            $this->db->update('tbl_user', array('active' => '1', 'activation_code' => '', 'email_verified' => '1'), array('id' => $user_id));
            $user_email = $this->db->select('email,name')->from('tbl_user')->where("id = '$user_id'")->get();
            $user_email = $user_email->row();
            $this->load->library('email');

            $to = $user_email->email;
            $subject = "Welcome to CREWGO";

            $message = $this->load->view('email_template/activation_template', array('username' => $user_email->name), true);

            $from = "info@cruva.com";
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

    public function login()
    {
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
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $encrypted_password = $this->ion_auth->hash_password($password);

            $deviceid = $this->input->post('device_id');
            $deviceType = $this->input->post('device_type');
            $hashCode = $this->webservice_model->hashCode();

            if ($this->ion_auth->login($email, $password, false, CUSTOMER) == true) {
                $query = $this->db->query("CALL sp_Login_new(?,?,?,?,?)", array($email, $encrypted_password, $deviceid, $hashCode, $deviceType));
                if ($query->num_rows() == 0) {
                    return $this->response('0000');
                }
                $result = $query->row();
                $this->db->freeDBResource();
                if ($result->status == 1) {
                    $this->_lhc_options($result);
                    /*if($result->register_from == 'LHC'){
                $this->_lhc_options($result);
                }
                else{
                $this->_return_login_details($result);
                }*/
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

    public function _return_login_details($result = "", $array = false)
    {

        $return = array(
            'user_info' => array(
                'user_id' => $result->user_id,
                'hash_code' => $result->hash_code,
                'name' => $result->name,
                'profile_image' => get_customer_profile_image($result->profile_image),
                'email' => $result->email,
                'authentication_key' => $result->key
            ),
            'lhcs' => array(),
            'is_crewgo' => "0"
        );
        if ($array) {
            return $return['user_info'];
        }
        return $this->response('0001', $return);
    }

    public function _lhc_options($result = "")
    {
        $res = $this->supervisor_model->get_lhc_customer_association($result->user_id);
        $user_info = $this->_return_login_details($result, true);

        if ($res->count > 0) {
            $lhcs = $res->lhcs;

            $ret = array(
                'lhcs' => $lhcs,
                'user_info' => $user_info,
            );
            $is_crewgo = "1";
            if ($result->register_from == 'LHC') {
                $is_crewgo = "0";
            }
            $ret['is_crewgo'] = $is_crewgo;
            return $this->response('0001', $ret);
        } else {
            $lhcs = array();

            $ret = array(
                'lhcs' => $lhcs,
                'user_info' => $user_info,
            );
            $is_crewgo = "0";
            if ($result->register_from == 'APP') {
                $is_crewgo = "1";
            }
            $ret['is_crewgo'] = $is_crewgo;
            return $this->response('0001', $ret);
            //$this->_return_login_details($result);
        }
    }

    public function set_lhc_user()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('lhc_user_id', 'LHC User ID', 'trim|required');

            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $lhc_user_id = $this->input->post('lhc_user_id');
            $res = $this->supervisor_model->check_lhc_customer_association($lhc_user_id, $user->id);
            if ($res) {
                $lhc = $this->supervisor_model->set_lhc_customer_association($lhc_user_id, $user->id);
                if ($lhc) {
                    return $this->response('0013');
                }
                return $this->response('0015');
            } else {
                return $this->response('0015');
            }
        } else {
            $this->load->view('set_lhc_user', array('title' => 'Set LHC User'));
        }
    }

    public function get_lhc_options()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $res = $this->supervisor_model->get_lhc_customer_association($user_id);
            if ($res->count > 0) {
                $lhcs = $res->lhcs;
                return $this->response('0001', array('lhcs' => $lhcs));
            } else {
                return $this->response('0017');
            }
        } else {
            $this->load->view('simple', array('title' => 'Unset LHC User'));
        }
    }

    public function unset_lhc_user()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $this->webservice_model->updateWhere(array('user_id' => $user_id), array('lhc_user_id' => 0), 'keys');
            return $this->response('0001');
        } else {
            $this->load->view('simple', array('title' => 'Unset LHC User'));
        }
    }

    public function logout()
    {

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

    public function update_device_info()
    {
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

            if ($this->form_validation->run() == false) {
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

    public function fb_login()
    {
        if ($this->input->post()) {

            $this->form_validation->set_rules('access_token', 'Access Token', 'required|trim|xss_clean');
            $this->form_validation->set_rules('device_id', 'Device Id', 'required|trim|xss_clean');
            $this->form_validation->set_rules('device_type', 'Device Type', 'required|trim|xss_clean');

            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }

            $this->load->library('Facebook');
            $access_token = $this->input->post('access_token');
            $this->facebook->setAccessToken($access_token);

            $userId = $this->facebook->getUser();
            if ($userId == 0) {
                return $this->response('0000', array("msg" => "Invalid authentication."));
            } else {
                $user = $this->facebook->api('/me?fields=email,name,first_name,last_name');
                if (isset($user['id']) && $user['id'] != '') {
                    $full_name = $user['name'];
                    $email = $user['email'];
                    $facebook_id = $user['id'];
                    $result = $this->supervisor_model->check_customer_fb($facebook_id, $email);
                    if (!empty($result)) {
                        return $this->auto_login($email, $result->password);
                    } else {
                        $response = array();
                        $response['name'] = $user['name'];
                        $response['email'] = $user['email'];
                        $response['access_token'] = $access_token;
                        return $this->response('0005', $response);
                    }
                } else {
                    return $this->response('0025');
                }
            }
        } else {
            $this->load->view('fb_login', array('title' => 'Login/Signup with facebook'));
        }
    }

    public function fb_register()
    {

        if ($this->input->post()) {
            $this->form_validation->set_rules('access_token', 'Access Token', 'required|trim|xss_clean');
            $this->_validate_registration();
            $this->form_validation->set_rules('email', 'email', 'required|trim|xss_clean|valid_email');

            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $access_token = $this->input->post('access_token');
            $this->load->library('Facebook');
            $this->facebook->setAccessToken($access_token);

            $userId = $this->facebook->getUser();
            if ($userId == 0) {
                return $this->response('0000', array("msg" => "Invalid authentication."));
            } else {
                $user = $this->facebook->api('/me?fields=email,name,first_name,last_name');
                if (isset($user['id']) && $user['id'] != '') {
                    $full_name = $user['name'];
                    $email = $user['email'];
                    $facebook_email = $user['email'];
                    $facebook_id = $user['id'];
                    $result = $this->supervisor_model->check_customer_fb($facebook_id, $email);

                    if (!empty($result)) {
                        return $this->response('0004');
                    } else {
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
                            'created_on' => date('Y-m-d H:i:s'),
                        );

                        $password = $this->webservice_model->hashCode(8);
                        $res = $this->supervisor_model->check_if_customer($email);
                        if (!empty($res)) {
                            $id = $res->id;
                            $this->ion_auth->update($id, $additional_data);
                            if (empty($facebook_email)) {
                                $this->webservice_model->updateWhere(array('id' => $id), array('active' => 1), 'tbl_user');
                            } else {
                                $this->webservice_model->updateWhere(array('id' => $id), array('active' => 0), 'tbl_user');
                                $this->send_email($id, $email, $activation_code, $name);
                            }
                            $response = array('user_id' => $id);
                            return $this->response('0001', $response);
                        } else {
                            if ($id = $this->ion_auth->register($email, $password, $email, $additional_data, array(CUSTOMER))) {
                                $this->webservice_model->updateWhere(array('id' => $id), array('active' => 0), 'tbl_user');
                                if (empty($facebook_email)) {
                                    $this->webservice_model->updateWhere(array('id' => $id), array('active' => 1), 'tbl_user');
                                } else {
                                    $this->webservice_model->updateWhere(array('id' => $id), array('active' => 0), 'tbl_user');
                                    $this->send_email($id, $email, $activation_code, $name);
                                }
                                $response = array('user_id' => $id);
                                return $this->response('0001', $response);
                            } else {
                                return $this->response('0000');
                            }
                        }
                    }

                } else {
                    return $this->response('0025');
                }
            }
        } else {
            $this->load->view('fb_register', array('title' => 'Login/Signup with facebook'));
        }
    }

    public function google_login()
    {
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
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $google_user_id = $this->input->post('google_id');
            $email = $this->input->post('email');

            $res = $this->supervisor_model->check_google_login($google_user_id, $email);
            if ($res) {
                return $this->auto_login($email, $res->password);
            } else {
                $response = array();
                $response['email'] = $this->input->post('email');
                $response['google_id'] = $this->input->post('google_id');
                return $this->response('0009', $response);
            }

        } else {
            $this->load->view('google_login', array('title' => 'Login/Signup with google'));
        }
    }

    public function google_register()
    {

        if ($this->input->post()) {
            $this->form_validation->set_rules('google_id', 'Google ID', 'required|trim|xss_clean');
            //$this->form_validation->set_rules('google_flag', 'Google Flag','required|trim|xss_clean');
            $this->_validate_registration();
            //$this->form_validation->set_rules('email', 'email','required|trim|xss_clean|valid_email|unique[tbl_user.email]');

            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $google_id = $this->input->post('google_id');
            $email = $this->input->post('email');
            if ($google_id == 0 or empty($google_id)) {
                return $this->response('0000', array("msg" => "Invalid authentication."));
            } else {
                //$result = $this->supervisor_model->getOneWhere(array('google_id' => $google_id,'email'=>$email),'tbl_user');
                $this->db->select('u.id')->from('tbl_user as u');
                $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
                $this->db->where('u.google_id', $google_id);
                $this->db->where('u.email', $email);
                $this->db->where('ug.group_id', CUSTOMER);
                $result = $this->db->get()->row();

                if (!empty($result)) {
                    return $this->response('0004');
                } else {
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
                        'created_on' => date('Y-m-d H:i:s'),

                    );
                    $password = $this->webservice_model->hashCode(8);

                    $res = $this->supervisor_model->check_if_customer($email);

                    if (!empty($res)) {
                        $id = $res->id;
                        $this->ion_auth->update($id, $additional_data);
                        $this->webservice_model->updateWhere(array('id' => $id), array('active' => 0), 'tbl_user');
                        $this->send_email($id, $email, $activation_code, $name);
                        $response = array('user_id' => $id);
                        return $this->response('0001', $response);
                    } else {
                        if ($id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array(CUSTOMER))) {
                            $this->webservice_model->updateWhere(array('id' => $id), array('active' => 0), 'tbl_user');
                            $this->send_email($id, $email, $activation_code, $name);
                            $response = array('user_id' => $id);
                            return $this->response('0001', $response);
                        } else {
                            return $this->response('0000');
                        }
                    }
                }

            }
        } else {
            $this->load->view('google_register', array('title' => 'Register with Google'));
        }
    }

    public function forgot_password()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean|max_length[100]');
            if ($this->form_validation->run() != true) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $this->load->library('email');
            $identity = $this->input->post('email');

            $query = $this->db->query("CALL sp_checkuser_new(?)", array($identity));
            $this->db->freeDBResource($this->db->conn_id);
            $result = $query->row();
            $res = $this->supervisor_model->check_if_customer($identity);
            if (!$res) {
                return $this->response('0012');
            }
            if ($result->status == 1) {
                $user = $this->db->where('email', $identity)->get('tbl_user')->row();
                if ($user->active != '1') {
                    return $this->response("0000", array("msg" => "User is Inactive"));
                }
                $token = $this->webservice_model->hashCode(20);

                $this->db->query("CALL sp_assigntoken_new(?,?)", array($identity, $token));
                $this->db->freeDBResource($this->db->conn_id);
                $url = base_url("customer/webservice/reset_password/{$result->id}/{$token}");
                $to = $identity;
                $subject = "Password Reset";

                $emailData = array();
                $emailData['forgot_link'] = anchor($url, 'click here');
                $emailData['username'] = $result->username;
                $message = $this->load->view('email_template/forgot_password_template', $emailData, true);

                $from = 'info@cruva.com';

                $this->email->to($to)
                    ->from($from, 'CREWGO')
                    ->subject($subject)
                    ->message($message);

                if ($this->email->send()) {
                    return $this->response('0001', array("msg" => 'Please check your email to reset password.'));
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

    public function reset_password($user_id, $token)
    {
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
                    'type' => 'password',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                );
            } else {
                $user = $this->ion_auth->user($user_id)->row();
                $identity = $user->email;

                $new_pass = $this->input->post('new');

                if ($this->ion_auth->reset_password($identity, $new_pass)) {
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

    public function google_test()
    {
        $this->load->library('google');
        $url = $this->google->get_authUrl();
        dd($url);
    }

    public function google_token()
    {

        $this->load->library('google');
        $url = $this->google->get_accessToken();
        dd($url);

    }

    public function stripe()
    {

        $this->load->library('stripe');
        $myCard = array(
            'number' => '4242424242424242',
            'exp_month' => 8,
            'exp_year' => 2018,
            "cvc" => "314",
        );
        $amount = "2500"; //amount is on cents. Ex. 2500 for $. 25
        $s = $this->stripe->charge($myCard, $amount);

        debug($s);

    }

    public function create_token()
    {
        $myCard = array(
            'number' => '4242424242424242',
            'exp_month' => 8,
            'exp_year' => 2018,
            "cvc" => "314",
        );
        $this->load->library('stripe');
        $s = $this->stripe->create_token($myCard);
    }

    public function create_customer()
    {
        $myCard = array(
            'number' => '4242424242424242',
            'exp_month' => 8,
            'exp_year' => 2018,
            "cvc" => "314",
        );
        $this->load->library('stripe');
        $s = $this->stripe->create_customer($myCard, 8);
        debug($s);
    }

    public function charge_by_customer_id()
    {
        $this->load->library('stripe');
        $s = $this->stripe->charge_by_customer_id($customer_id = "cus_9GTL0OA937fEv5", $amount = 1000);
        debug($s);
    }

    public function change_password()
    {
        if ($this->input->post()) {
            $hValidate = $this->webservice_model->validateHeaderRequest();
            if ($hValidate === true) {
                //continue...
            } else {
                return $this->response('0000', array("msg" => $hValidate));
            }
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $user_id = $user->id;
            $this->load->library('form_validation');

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
            $user = $this->ion_auth->user($user_id)->row();
            $identity = $user->email;

            //check if old password is correct or not?
            $encrypted_password = $this->webservice_model->encryptPassword($user_id, $this->input->post('old_password'));

            $query = $this->db->query("CALL sp_checkOldPass_new('{$identity}','{$encrypted_password}')");
            $this->db->freeDBResource($this->db->conn_id);
            $result = ($query->num_rows() > 0) ? $query->row() : false;
            if ($result && ($result->status == 1)) {
                //continue...
            } else {
                return $this->response("0000", array("msg" => "Old password incorrect"));
            }
            $new_pass = $this->input->post('new_password');
            if ($this->ion_auth->reset_password($identity, $new_pass)) {
                return $this->response("0001", array("msg" => "Password successfully changed"));
            } else {
                return $this->response("0000");
            }
        } else {
            $this->load->view('change_password', array('title' => 'Change Password'));
        }
    }

    public function auto_login($email, $encrypted_password)
    {

        $device_id = $this->input->post('device_id');
        $device_type = $this->input->post('device_type');
        $hashCode = $this->webservice_model->hashCode();

        $query = $this->db->query("CALL sp_Login_new('{$email}','{$encrypted_password}','{$device_id}','{$hashCode}','{$device_type}')");
        $this->db->freeDBResource($this->db->conn_id);

        if ($query->num_rows() == 0) {
            return $this->response('0000');
        }
        $result = $query->row();

        if ($result->status == 1) {
            return $this->_lhc_options($result);
        } elseif ($result->status == 2) {
            return $this->response('0007');
        } else {
            return $this->response('0002');
        }
    }

    public function mail_test()
    {
        $data = array(
            'username' => 'aaaa',
            'user_id' => 'uid',
            'activation_code' => 'ac',
        );

        $message = $this->load->view("email_template/email_confirmation", $data);
    }

    public function _validate_registration()
    {

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

    // get the list of teams with members
    public function get_team_list()
    {
        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $job_id = $this->input->post('job_id', TRUE);
            $response = $this->supervisor_model->get_team_list($job_id);
            if($response == false){
                return $this->response('0024');
            }
            return $this->response('0001', array('team' => $response));

        } else {
            $this->load->view('job/team_view', array('title' => 'Team List'));
        }
    }

    // get the list of members assigned in job. 
    public function get_members_in_job()
    {
        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $job_id = $this->input->post('job_id', TRUE);
            $this->form_validation->set_rules('job_id', 'Job ID', 'trim|required|is_natural_no_zero');
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $response = $this->supervisor_model->get_job_members($job_id);
            return $this->response('0001', array('members' => $response));
        } else {
            $this->load->view('job/team_view', array('title' => 'Members List'));
        }
    }

    // get the list of members assigned in job. 
    public function get_team_members()
    {
        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $job_id = $this->input->post('job_id', TRUE);
            $this->form_validation->set_rules('job_id', 'Job ID', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('team_id', 'Team ID', 'trim|required|is_natural_no_zero');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $team_id = $this->input->post('team_id', TRUE);

            $response = $this->supervisor_model->get_job_members($job_id, $team_id);
            return $this->response('0001', array('members' => $response));
        } else {
            $this->load->view('job/team_members_view', array('title' => 'Team List'));
        }
    }

    public function addedit_team()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }

            $staffs = $this->input->post('staffs', TRUE);
            $staffs = str_replace('%2C', ',', $staffs);
            $staffs = trim($staffs, ',');

            $staff_array = explode(',', $staffs);
            foreach ($staff_array as $key => $value) {
                if (!is_numeric($value) OR $value < 1) {
                    return $this->response('0023', array('msg' => 'Invalid Staffs Inforamtion'));
                }
            }

            $this->form_validation->set_rules(
                array(
                    array('field' => 'team_name', 'label' => 'Team Name', 'rules' => 'required|trim|xss_clean'),
                    array('field' => 'job_id', 'label' => 'Address', 'rules' => 'trim|required|xss_clean'),
                    array('field' => 'staffs', 'label' => 'Staffs', 'rules' => 'trim|required|xss_clean'),
                )
            );

            $operation = $this->input->post('operation', TRUE);
            if ($operation == 'edit') {
                $this->form_validation->set_rules('team_id', 'Team ID', 'trim|required|xss_clean');
            }
            if ($this->form_validation->run() === TRUE) {
                if ($operation == 'add') {
                    if ($this->supervisor_model->create_team($user->id)) {
                        return $this->response('0001', array('msg' => 'Team created successfully'));
                    } else {
                        return $this->response('0000', array('msg' => 'Unable to create team.'));
                    }
                } else if ($operation == 'edit') {
                    if ($this->supervisor_model->update_team($user->id)) {
                        return $this->response('0001', array('msg' => 'Team updated successfully'));
                    } else {
                        return $this->response('0000', array('message' => 'Invalid Operation!'));
                    }
                }

            } else {
                return $this->response('0000', array('msg' => validation_errors()));

            }
        } else {
            $this->load->view('job/add_team', array('title' => 'Add/Edit Team'));
        }
    }

    public function delete_team()
    {        
       if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            
            $this->form_validation->set_rules('team_id', 'Team ID', 'trim|required|is_natural_no_zero');
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $team_id = $this->input->post('team_id', true);
            if ($this->supervisor_model->delete_team_byid($team_id)) {
                return $this->response('0001', array('message' => 'Team reamoved successfully'));
            } else {
                return $this->response('0000', array('message' => 'Team could not removed.'));
            }
        } else {
            $this->load->view('job/team_delete', array('title' => 'Delete Team'));
        }
    }

    public function get_team_lead()
    {
        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('job_id', 'Job ID', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('team_id', 'Team ID', 'trim|required|is_natural_no_zero');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $job_id = $this->input->post('job_id', TRUE);
            $team_id = $this->input->post('team_id', TRUE);

            $response = $this->supervisor_model->get_job_members($job_id, $team_id);
            return $this->response('0001', array('members' => $response));
        } else {
            $this->load->view('job/team_members_view', array('title' => 'Team Lead'));
        }

    }

    public function add_team_lead()
    {

        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('team_id', 'Team ID', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('staff_id', 'Staff ID', 'trim|required|is_natural_no_zero');
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $update = $this->supervisor_model->update_team_lead();

            if ($update) {
                return $this->response('0001');

            } else {
                return $this->response('0000', array('msg' => 'Unable to update team lead information.'));
            }
        } else {
            $this->load->view('job/update_team_lead', array('title' => 'Team Lead'));
        }

    }

    public function get_sent_messages()
    {
        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('job_id', 'Job ID', 'trim|required|is_natural_no_zero');
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $job_id = $this->input->post('job_id', TRUE);
            $offset = $this->input->post('next_offset') ? $this->input->post('next_offset') : 0;
            $per_page = 10;
            $message = $this->supervisor_model->get_sent_message($job_id, $user->id, $per_page, $offset);
            if( !empty( $message ) ){
                foreach ($message as $k => $msg) {
                    foreach ($msg['message'] as $j => $m ) {
                        $message[$k]['message'][$j]->to_user_id = json_decode( $m->to_user_id ); 
                    }
                }
            }
            $response['message'] = $message;
            $total_messages = $this->supervisor_model->get_sent_message_num($job_id, $user->id);
            if(($offset + $per_page) >= $total_messages){
                $response['is_last_offset'] = true;
            } else {
                $response['is_last_offset'] = false;
            }

            $response['next_offset'] = ($offset + $per_page);

            return $this->response('0001', $response);
        } else {
            $this->load->view('job/message_list', array('title' => 'Message List'));
        }
    }

    public function get_received_messages()
    {
        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules('job_id', 'Job ID', 'trim|required|is_natural_no_zero');
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $job_id = $this->input->post('job_id', TRUE);
            $offset = $this->input->post('next_offset') ? $this->input->post('next_offset') : 0;
            $per_page = 10;
            $message = $this->supervisor_model->get_received_message($job_id, $user->id, $per_page, $offset);
            $response['message'] = $message;
            $total_messages = $this->supervisor_model->get_received_message_num($job_id, $user->id);

            if(($offset + $per_page) >= $total_messages){
                $response['is_last_offset'] = true;
            } else {
                $response['is_last_offset'] = false;
            }

            $response['next_offset'] = ($offset + $per_page);

            return $this->response('0001', $response);
        } else {
            $this->load->view('job/message_list', array('title' => 'Message List'));
        }
    }

    public function all_teams_and_staffs($job_id = '')
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if (!empty($key)) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            if ($job_id == '' || !is_numeric($job_id) || $job_id == 0) {
                return $this->response('0000', array('Invalid job ID.'));
            }
            $response['options'] = $this->supervisor_model->get_teams_and_staffs($job_id);
            return $this->response('0001', $response);
        }
        return $this->response('0003');
    }

    public function create_message()
    {
        if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }

        $raw_message = file_get_contents("php://input");
        $this->load->library('My_jsonschema_validator');
        if (!$this->my_jsonschema_validator->validate('file://' . FCPATH . 'assets/schemas/message/create_message.json', $raw_message)){
            return $this->response('0032', array('validation_errors' => $this->my_jsonschema_validator->error));
        }
        $message = json_decode($raw_message, true);
       // print_r($message); exit;
        $send = $this->supervisor_model->send_message($user->id, $message);

        if ($send) {
            return $this->response('0001');
        } else {
            return $this->response('0000', array('msg' => 'Could not able to send message.'));
        }


    }

    public function view_reply()
    {
        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $message_id = $this->input->post('message_id', TRUE);
            $this->form_validation->set_rules('message_id', 'Message ID', 'trim|required|is_natural_no_zero');
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $response['message'] = $this->supervisor_model->get_message_reply($message_id);
            return $this->response('0001', $response);
        } else {
            $this->load->view('job/message_details', array('title' => 'View Reply'));
        }
    }

    public function reply_message()
    {
        if ($this->input->post()) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $this->form_validation->set_rules(array(
                    array('field' => 'message_id', 'label' => 'Message ID', 'rules' => 'trim|xss_clean|required|is_natural_no_zero'),
                    array('field' => 'message', 'label' => 'Message Text', 'rules' => 'trim|xss_clean|required|max_length[512]')
                )
            );
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $this->load->model('staff_model');
            if ($this->supervisor_model->send_reply($user->id) > 0) {
                return $this->response('0001', array(), STAFF);
            } else
                return $this->response('0000', array(), STAFF);

        } else {
            $this->load->view('job/send_reply', array('title' => 'Send Reply'));
        }
    }

    public function delete_message($msg_id=0)
    {
         if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
        if( $_SERVER['REQUEST_METHOD']!=="DELETE"){
            return $this->response('0003');
        }

        if (!is_numeric($msg_id) || $msg_id == 0) {
            return $this->response('0000', array('msg' => 'Invalid Message ID'));
        }
        $del = $this->supervisor_model->delete_message($msg_id);
        if ($del) {
            return $this->response('0001');
        } else
            return $this->response('0000');
    }

// END Class  
}
