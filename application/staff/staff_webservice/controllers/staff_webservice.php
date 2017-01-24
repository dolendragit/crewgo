<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Staff_webservice extends Web_Service_Controller
{

    public $data = array();
    private $userTimezone;
    const NORMAL_LOGIN = 1, GOOGLE_LOGIN = 2, FACEBOOK_LOGIN = 3;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('webservice_model');
//        $this->_settings();
    }

    private function _settings()
    {
        $this->userTimezone = $this->webservice_model->get_user_timezone();
//        date_timezone_set($this->userTimezone);
        date_default_timezone_set($this->userTimezone);
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
        redirect('staff_developer_view');
    }

    public function error()
    {
        $this->load->view('error');
    }

    function register()
    {
        $this->load->model('staff_model');
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'name', 'label' => 'Name', 'rules' => 'required|trim'),
                array('field' => 'address', 'label' => 'Address', 'rules' => 'trim|required'),
                array('field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|xss_clean|valid_email'),
                array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),
                array('field' => 'phone_number', 'label' => 'Mobile Number', 'rules' => 'trim|required'),
                array('field' => 'skill', 'label' => 'Skill', 'rules' => 'trim|required|valid_json'),
            );
            $registerType = $this->input->post('register_type');
            if ($registerType == self::FACEBOOK_LOGIN) {
                array_push($validationRules, array('field' => 'access_token', 'label' => 'Facebook access Token', 'rules' => 'trim|required'));
            } elseif ($registerType == self::GOOGLE_LOGIN) {
                array_push($validationRules, array('field' => 'google_id', 'label' => 'Google Id', 'rules' => 'trim|required'));
            } else {
                array_push($validationRules, array('field' => 'register_type', 'label' => 'Register Type', 'rules' => "trim|required|integer|one_of[" . self::NORMAL_LOGIN . "," . self::GOOGLE_LOGIN . "," . self::FACEBOOK_LOGIN . "]"));
            }
            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');
            $this->ion_auth->set_error_delimiters('', '');

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
            $skills = json_decode($this->input->post('skill'), TRUE);
            $activation_code = sha1(md5(microtime()));
            $staffData = array(
                'full_address' => $address,
            );
            $addedCustomerId = FALSE;

            // for normal register
            if ($registerType == self::NORMAL_LOGIN) {
                $additional_data = array(
                    'name' => $name,
                    'full_address' => $address,
                    'phone_number' => $phone_number,
                    'activation_code' => $activation_code,
                    'active' => 0
                );
                if (empty($this->staff_model->check_if_staff($email))) {
                    if ($user_id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array(STAFF))) {
                        $addedCustomerId = $user_id;
                        //add skill for each selection
                        $skillId = $subSkillId = 0;
                        foreach ($skills as $skill) {
                            $skillId = $skill['id'];
                            foreach ($skill['levels'] as $subSkill) {
                                $subSkillId = $subSkill['id'];
                                $this->db->query("call sp_staff_add_skill(?,?,?)", array($user_id, $skillId, $subSkillId));
                            }
                        }
                        $this->send_email($user_id, $email, $activation_code, $name);
                        $this->webservice_model->updateWhere(array('id' => $user_id), array('active' => 0), 'tbl_user');
                        $code = '0011';
                        $return = array('user_id' => $user_id);
                    } else {
                        $code = '0000';
                        $return = array('msg' => $this->ion_auth->errors());
                    }
                } else {
                    $code = '0003';
                }
            } // for google registration
            elseif ($registerType == self::GOOGLE_LOGIN) {
                $google_id = $this->input->post('google_id');
                $email = $this->input->post('email');
                if ($google_id == 0 OR empty($google_id)) {
                    $code = '0004';
                } else {
//                    $result = $this->staff_model->getOneWhere(array('google_id' => $google_id, 'email' => $email), 'tbl_user');
                    $result = $this->staff_model->check_google_login($google_id, $email);
                    if ($result) {
                        $code = '0003';
                    } else {
                        $name = trim($this->input->post('name'));
                        $address = $this->input->post('address');
                        $email = trim($this->input->post('email'));
                        $phone_number = trim($this->input->post('phone_number'));
                        $additional_data = array(
                            'name' => $name,
                            'full_address' => $address,
                            'phone_number' => $phone_number,
                            'login_from' => 'GMAIL',
                            'google_id' => $google_id,
                        );
                        $password = $this->input->post('password');

                        if ($id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array(STAFF))) {
                            $addedCustomerId = $id;
                            //remove previous selection for skill and subskill
                            $this->db->where('staff_user_id', $id);
                            $this->db->delete('tbl_staff_skill');
                            //add skill for each selection
                            $skillId = $subSkillId = 0;
                            foreach ($skills as $skill) {
                                $skillId = $skill['id'];
                                foreach ($skill['levels'] as $subSkill) {
                                    $subSkillId = $subSkill['id'];
                                    $this->db->query("call sp_staff_add_skill(?,?,?)", array($id, $skillId, $subSkillId));
                                }
                            }
                            $this->webservice_model->updateWhere(array('id' => $id), array('active' => 1), 'tbl_user');
                            $response = array('user_id' => $id);
                            return $this->response('0001', $response, STAFF);
                        } else {
                            $code = '0000';
                            $return = array('msg' => $this->ion_auth->errors());
                        }
                    }
                }
            } //for Facebook login
            elseif ($registerType == self::FACEBOOK_LOGIN) {
                $access_token = $this->input->post('access_token');
                $this->load->library('Facebook');
                $this->facebook->setAccessToken($access_token);
                $userId = $this->facebook->getUser();
                if ($userId == 0) {
                    $code = '0005';
                } else {
                    $user = $this->facebook->api('/me?fields=email,name,first_name,last_name');
                    $user['email'] = isset($user['email']) ? $user['email'] : '';
                    $sameEmailAsFbAPI = ($user['email'] == $email);
                    $facebook_id = $user['id'];
                    $query = $this->db->query("CALL sp_staff_check_fb_registered('{$email}', '{$facebook_id}')");
                    $this->db->freeDBResource($this->db->conn_id);
                    $result = ($query->num_rows() > 0) ? $query->row() : FALSE;

                    if ($result && ($result->status == 1)) {
                        $code = '0003';
                    } else {
                        $additional_data = array(
                            'name' => $name,
                            'full_address' => $address,
                            'phone_number' => $phone_number,
                            'login_from' => 'FB',
                            'facebook_id' => $facebook_id,
                            'active' => 0,
                        );
                        $password = $this->input->post('password');
                        if (!$sameEmailAsFbAPI) {
                            $additional_data['activation_code'] = $activation_code;
                        }
                        if ($id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array(STAFF))) {
                            $addedCustomerId = $id;
                            //remove previous selection for skill and subskill
                            $this->db->where('staff_user_id', $id);
                            $this->db->delete('tbl_staff_skill');
                            //add skill for each selection
                            $skillId = $subSkillId = 0;
                            foreach ($skills as $skill) {
                                $skillId = $skill['id'];
                                foreach ($skill['levels'] as $subSkill) {
                                    $subSkillId = $subSkill['id'];
                                    $this->db->query("call sp_staff_add_skill(?,?,?)", array($id, $skillId, $subSkillId));
                                }
                            }
                            $response = array('user_id' => $id);
                            if ($sameEmailAsFbAPI) {
                                $this->webservice_model->updateWhere(array('id' => $id), array('active' => 1), 'tbl_user');
                                return $this->response('0001', $response, STAFF);
                            } else {
                                $this->send_email($id, $email, $activation_code, $name);
                                return $this->response('0011', $response, STAFF);
                            }
                        } else {
                            $code = '0000';
                            $return = array('msg' => $this->ion_auth->errors());
                        }
                    }
                }
            }
            if ($addedCustomerId) {//add staff data on tbl_staff_add_info table
                $staffData['staff_user_id'] = $addedCustomerId;
                $this->staff_model->add_staff_detail($staffData, $addedCustomerId);
            }
            return $this->response($code, $return, STAFF);
        } else {
            $skills = json_encode($this->staff_model->get_skills());
            $this->load->view('register', array('title' => 'Registration', 'skills' => $skills));
        }
    }

    function send_email($user_id, $email, $activation_code, $username)
    {
        $this->load->library('email');
        $to = $email;
        $subject = "Email confirmation";
        $data = array(
            'username' => $username,
            'user_id' => $user_id,
            'activation_code' => $activation_code
        );
        $message = $this->load->view("email_template/email_confirmation", $data, TRUE);
        $from = "info@cruva.com";
        $this->email->to($to)
            ->from($from, "CREWGO")
            ->subject($subject)
            ->message($message);
        $this->email->send();
    }

    function activate($user_id, $activation_code)
    {
        $identity = (int)$user_id;
        $checkuserid = $this->db->query("select id from tbl_user where id = '$identity' and activation_code = '$activation_code'");
        if ($checkuserid->num_rows() > 0) {
            $this->db->update('tbl_user', array('active' => '1', 'activation_code' => ''), array('id' => $user_id));
            $user_email = $this->db->select('email,name')->from('tbl_user')->where("id = '$user_id'")->get();
            $user_email = $user_email->row();
            $this->load->library('email');
            $subject = "Welcome to CREWGO";
            $message = $this->load->view('email_template/activation_template', array('name' => $user_email->name), TRUE);
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

    function login()
    {
        if ($this->input->post()) {
            $this->load->model('staff_model');
            $return = array();
            $code = '';
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'device_id', 'label' => 'Device Id', 'rules' => 'trim|required'),
                array('field' => 'device_type', 'label' => 'Device Type', 'rules' => 'required|trim'),
            );
            $loginType = $this->input->post('login_type');
            if ($loginType == self::NORMAL_LOGIN) {
                array_push($validationRules, array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required'), array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'));
            } elseif ($loginType == self::FACEBOOK_LOGIN) {
                array_push($validationRules, array('field' => 'access_token', 'label' => 'Facebook access Token', 'rules' => 'trim|required'));
            } elseif ($loginType == self::GOOGLE_LOGIN) {
                array_push($validationRules, array('field' => 'email', 'label' => 'Email', 'rules' => 'required|trim'));
                array_push($validationRules, array('field' => 'google_id', 'label' => 'Google Id', 'rules' => 'trim|required'));
            } else {
                array_push($validationRules, array('field' => 'login_type', 'label' => 'Login Type', 'rules' => "trim|required|integer|one_of[" . self::NORMAL_LOGIN . "," . self::GOOGLE_LOGIN . "," . self::FACEBOOK_LOGIN . "]"));
            }

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');
            $this->ion_auth->set_error_delimiters('', '');
            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            // for normal login
            if ($loginType == self::NORMAL_LOGIN) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $deviceid = $this->input->post('device_id');
                $deviceType = $this->input->post('device_type');
                $hashCode = $this->webservice_model->hashCode();
                if ($this->ion_auth->login($email, $password, FALSE, STAFF) == TRUE) {
                    $query = $this->db->query("CALL sp_Login_staff(?,?,?,?)", array($email, $deviceid, $hashCode, $deviceType));
                    $result = $query->row();
                    $return = array(
                        'user_info' => array(
                            'user_id' => $result->user_id,
                            'name' => $result->name,
                            'image' => (($result->profile_image and file_exists(FCPATH . $result->profile_image)) ? base_url($result->profile_image) : ''),
                            'phone' => $result->phone_number,
                            'email' => $result->email,
                            'authentication_key' => $result->key
                        )
                    );
                    $code = '0001';
                } else {
                    $query = $this->db->query("CALL sp_is_user_active(?)", array($email));
                    $result = $query->row();
                    if ($result->active == 1) {
                        //username password don't match
                        $code = '0006';
                    } elseif ($result->active == 2) {
                        $code = '0008';
                    } else {
                        //user not verified
                        $code = '0007';
                    }
                }
            } // for Google login
            elseif ($loginType == self::GOOGLE_LOGIN) {
                $google_user_id = $this->input->post('google_id');
                $email = $this->input->post('email');
                $res = $this->staff_model->check_google_login($google_user_id, $email);
                if ($res) {
                    $this->webservice_model->updateWhere(array('email' => $email), array('active' => 1), 'tbl_user');
                    return $this->auto_login($email);
                } else {
                    $response = array();
                    $response['email'] = $this->input->post('email');
                    $response['google_id'] = $this->input->post('google_id');
                    return $this->response('0008', $response, STAFF);
                }

            } //for Facebook login
            elseif ($loginType == self::FACEBOOK_LOGIN) {
                $this->load->library('Facebook');
                $access_token = $this->input->post('access_token');
                $this->facebook->setAccessToken($access_token);
                $userId = $this->facebook->getUser();
                if ($userId == 0) {
                    $code = '0005';
                } else {
                    $user = $this->facebook->api('/me?fields=email,name,first_name,last_name');
                    if (isset($user['email']) && $user['email'] != '') {
                        $email = $user['email'];
                        $facebook_id = $user['id'];
                        $query = $this->db->query("CALL sp_staff_check_fb_registered('{$email}', '{$facebook_id}')");
                        $this->db->freeDBResource($this->db->conn_id);
                        $result = ($query->num_rows() > 0) ? $query->row() : FALSE;

                        if ($result && ($result->status == 1)) {
                            $this->webservice_model->updateWhere(array('email' => $email), array('active' => 1), 'tbl_user');
                            return $this->auto_login($email);
                        } else {
                            $response = array();
                            $response['name'] = $user['name'];
                            $response['email'] = $user['email'];
                            $response['access_token'] = $access_token;
                            $code = '0008';
                        }
                    } else {
                        $code = '0005';
                    }
                }
            }
            return $this->response($code, $return, STAFF);

        } else {
            $this->load->view('login', array('title' => 'Login'));
        }
    }

    /**
     * Method to logout user
     */
    public function logout()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
//        $headerValidate = $this->webservice_model->validateHeaderRequest(array(1));
//        if ($headerValidate === TRUE) {
//            //continue...
//            $headers = apache_request_headers();
//            $key = $headers['authentication_key'];
//        } else {
//            return $this->response('0000', array("msg" => $headerValidate), STAFF);
//        }
        $query = $this->db->query("CALL sp_Logout(?)", array($key));
        $result = $query->row();
        if ($result->status == 1) {
            return $this->response('0001');
        } else {
            return $this->response('0000');
        }
    }

    function update_device_info()
    {
        if ($this->input->post()) {
            $headerValidate = $this->webservice_model->validateHeaderRequest(array(1));
            if ($headerValidate === TRUE) {
                //continue...
                $headers = apache_request_headers();
                $key = $headers['authentication_key'];
            } else {
                return $this->response('0000', array("msg" => $headerValidate));
            }

            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }

            $this->load->library('form_validation');
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

    function forgot_password()
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

            if ($result->status == 1) {
                $token = $this->webservice_model->hashCode(20);
                $this->db->query("CALL sp_assigntoken_new(?,?)", array($identity, $token));
                $this->db->freeDBResource($this->db->conn_id);
                $url = base_url("staff/staff_webservice/reset_password/{$result->id}/{$token}");
                $to = $identity;
                $subject = "Password Reset";
                $emailData = array();
                $emailData['forgot_link'] = anchor($url, 'Click here');
                $emailData['name'] = $result->name;
                $message = $this->load->view('email_template/forgot_password_template', $emailData, TRUE);
                $from = 'info@cruva.com';
                $this->email->to($to)
                    ->from($from, 'CREWGO')
                    ->subject($subject)
                    ->message($message);
                if ($this->email->send()) {
                    return $this->response('0001', array('msg' => 'We just sent you a url link in your email to reset your password. Please open the link and change your password.'));
                } else {
                    return $this->response('0010', array(), STAFF);
                }
            } else {
                return $this->response('0008', array(), STAFF);
            }
        } else {
            $this->load->view('forgot_password_request', array('title' => 'Forgot Password'));
        }
    }

    function reset_password($user_id, $token)
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
                return $this->response('0002', array(), STAFF);
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
                return $this->response("0009", array(), STAFF);
            }
            $new_pass = $this->input->post('new_password');
            if ($this->ion_auth->reset_password($identity, $new_pass)) {
                return $this->response("0001", array("msg" => "Password successfully changed"), STAFF);
            } else {
                return $this->response("0000");
            }
        } else {
            $this->load->view('change_password', array('title' => 'Change Password'));
        }
    }


    public function auto_login($email)
    {
        $hValidate = $this->webservice_model->validateHeaderRequest();
        if ($hValidate === TRUE) {
            $deviceid = $this->input->post('device_id');
            $deviceType = $this->input->post('device_type');

        } else {
            return $this->response('0000', array("msg" => $hValidate));
        }
        $hashCode = $this->webservice_model->hashCode();
        $query = $this->db->query("CALL sp_Login_staff(?,?,?,?)", array($email, $deviceid, $hashCode, $deviceType));
        $result = $query->row();
        if (isset($result->status) && $result->status == '1') {
            $return = array(
                'user_info' => array(
                    'user_id' => $result->user_id,
                    'name' => $result->name,
                    'image' => (($result->profile_image and file_exists(FCPATH . $result->profile_image)) ? base_url($result->profile_image) : ''),
                    'phone' => $result->phone_number,
                    'email' => $result->email,
                    'authentication_key' => $result->key
                )
            );
            $code = '0001';
        } else {
            $code = '0008';
        }
        return $this->response($code, $return, STAFF);

    }

    /**
     * Get Skill and Subskill
     */
    public function get_skills()
    {
        $this->load->model('staff_model');
        $return['skills'] = $this->staff_model->get_skills();
        $this->db->freeDBResource($this->db->conn_id);
        return $this->response('0001', $return);
    }

    /**
     * Get Profile detail for staff
     */
    public function get_profile()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if ($this->input->post() || !empty($key)) {
//        $headers = apache_request_headers();
//        $key = @trim($headers['Authentication-Key']);
//        if ($this->input->post() || isset($key)) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }

            $query = $this->db->query("CALL sp_staff_get_profile({$user->id})");
            $return['profile_detail'] = $query->row();
            $this->db->freeDBResource($this->db->conn_id);
            return $this->response('0001', $return);

        } else {
            $this->load->view('get_profile', array('title' => 'Get Profile'));
        }
    }

    /**
     * Edit profile detail for Staff
     */
    public function edit_profile()
    {
        if ($this->input->post()) {
            $hValidate = $this->webservice_model->validateHeaderRequest();
            if ($hValidate === true) {
            } else {
                return $this->response('0000', array("msg" => $hValidate));
            }
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }

            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|required|xss_clean|max_length[150]'),
                array('field' => 'address', 'label' => 'Address', 'rules' => 'trim|required|xss_clean|max_length[150]'),
                array('field' => 'phone_number', 'label' => 'Phone Number', 'rules' => 'trim|required|max_length[20]'),
                array('field' => 'auto_bookable', 'label' => 'Auto Bookable', 'rules' => 'trim|required|one_of["0","1"]'),
                array('field' => 'brief_description', 'label' => 'Brief Description', 'rules' => 'trim|required|xss_clean|max_length[500]'),
                array('field' => 'suburbs', 'label' => 'Prefered Suburbs', 'rules' => 'trim|required|valid_json'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $this->load->model('staff_model');
            // ***** on progress *****

        } else {
            $this->load->view('edit_profile', array('title' => 'Edit Profile', 'suburbs' => '[{"id":"1"},{"id":"2"}]'));
        }
    }

    /**
     * Get list of available suburbs
     */
    public function get_suburbs()
    {
        $this->load->model('staff_model');
        $this->db->select('id, postcode, suburb, state');
        $suburbs = $this->staff_model->getAllRows('tbl_postcodes_geo');
        $return['suburbs'] = ($suburbs);
        $this->db->freeDBResource($this->db->conn_id);
        return $this->response('0001', $return, STAFF);
    }

    /**
     * Get list of jobs
     * @return mixed
     */
    public function get_job_alerts()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'offset', 'label' => 'Offset', 'rules' => 'trim|integer|required'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $this->load->model('staff_model');
            $return['jobs'] = $this->staff_model->get_job_alerts($user->id);
            $returnJobs = array();
            if (count($return['jobs'])) {
                foreach ($return['jobs'] as $job) {
                    $returnJobs[$job->start_date][] = $job;
                }
            }
            $jobAlertArrayLimit = 1000;
            $jobAlertArrayOffset = $this->input->post('offset');
            $nextJobAlertArrayOffset = NULL;
            if (count($returnJobs) > ($jobAlertArrayOffset + $jobAlertArrayLimit))
                $nextJobAlertArrayOffset = (int)$jobAlertArrayOffset + $jobAlertArrayLimit;
            $returnJobs = array_slice($returnJobs, $jobAlertArrayOffset, $jobAlertArrayLimit);
            $return['jobs'] = array_values($returnJobs);
            $return['next_offset'] = $nextJobAlertArrayOffset;
            return $this->response('0001', $return, STAFF);

        } else {
            $this->load->view('get_job_alerts', array('title' => 'Get Job Alerts'));
        }
    }

    /**
     * Get job detail for Staff
     */
    public function get_job_alert_detail()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_alert_id', 'label' => 'Job Alert Id', 'rules' => 'trim|required|integer'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }

            $this->load->model('staff_model');
            $return['job'] = $this->staff_model->get_job_alert_detail($user->id, $this->input->post('job_alert_id'));
            if ($return['job'] == FALSE) {
                return $this->response('0012', array(), STAFF);
            }
            return $this->response('0001', $return, STAFF);


        } else {
            $this->load->view('get_job_alert_detail', array('title' => 'Get Job Alert Detail'));
        }

    }

    /**
     * Respond to Job Alert
     */
    public function respond_job_alert()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_alert_id', 'label' => 'Job Alert Id', 'rules' => 'trim|required|integer'),
                array('field' => 'response_flag', 'label' => 'Response Flag', 'rules' => 'trim|required|integer|one_of[1,2,3]'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();

            $this->load->model('staff_model');
            $code = $this->staff_model->job_alert_response($user->id, $this->input->post());
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('respond_job_alert', array('title' => 'Get Job Alert Detail'));
        }
    }

    /**
     * Get Accepted Jobs list
     */
    public function get_accepted_jobs()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'offset', 'label' => 'Offset', 'rules' => 'trim|integer|required'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $this->load->model('staff_model');
            $return['jobs'] = $this->staff_model->get_accepted_jobs_list($user->id);
            $returnJobs = array();
            if (count($return['jobs'])) {
                foreach ($return['jobs'] as $job) {
                    $returnJobs[$job->start_date][] = $job;
                }
            }
            $jobAcceptedArrayLimit = 1000;
            $jobAcceptedArrayOffset = $this->input->post('offset');
            $nextJobAcceptedArrayOffset = NULL;
            if (count($returnJobs) > ($jobAcceptedArrayOffset + $jobAcceptedArrayLimit))
                $nextJobAcceptedArrayOffset = (int)$jobAcceptedArrayOffset + 1;
            $returnJobs = array_slice($returnJobs, $jobAcceptedArrayOffset, $jobAcceptedArrayLimit);
            $return['jobs'] = array_values($returnJobs);
            $return['next_offset'] = $nextJobAcceptedArrayOffset;
            return $this->response('0001', $return, STAFF);

        } else {
            $this->load->view('get_accepted_jobs', array('title' => 'Get Accepted Jobs List'));
        }

    }

    /**
     * Get Job starting on next 10min till end
     */
    public function get_ongoing_job()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if ($this->input->post() || !empty($key)) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }

            $this->load->model('staff_model');
            $return = array();
            if ($job = $this->staff_model->get_ongoing_job($user->id, '10')) {
                $return['ongoing_job'] = $job;
                $code = '0001';
            } else {
                $code = '0020';
            }
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('get_ongoing_job', array('title' => 'Get Ongoing Job'));
        }
    }

    /**
     * Set job site location by Staff
     */
    public function set_job_site()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_staff_id', 'label' => 'Job Staff ID', 'rules' => 'trim|required|integer'),
                array('field' => 'job_site_lat_lng', 'label' => 'Job Site Latitude Longitude', 'rules' => 'trim|required|valid_lat_lng'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();

            $this->load->model('staff_model');
            if ($this->staff_model->set_job_site($user->id, $this->input->post('job_staff_id'), explode(',', $this->input->post('job_site_lat_lng'))))
                $code = '0001';
            else
                $code = '0000';
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('set_job_site', array('title' => 'Set Job Site'));
        }
    }

    /**
     * JOb Check In for Staff
     */
    public function job_check_in()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_detail_id', 'label' => 'Job Detail ID', 'rules' => 'trim|required|integer'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();

            $this->load->model('staff_model');
            $code = $this->staff_model->job_check_in($user->id, $this->input->post('job_detail_id'), date('Y-m-d H:i:s'));
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('job_check_in', array('title' => 'Job Check In'));
        }
    }

    /**
     * JOb Check In for Staff
     */
    public function checked_in_job_detail()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_detail_id', 'label' => 'Job Detail ID', 'rules' => 'trim|required|integer'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();

            $this->load->model('staff_model');
            $job = $this->staff_model->get_checked_in_job_detail($user->id, $this->input->post('job_detail_id'));
            if ($job) {
                $return['job'] = $job;
                $code = '0001';
            } else
                $code = '0000';
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('job_check_in', array('title' => 'Checked-in Job Detail'));
        }
    }


    /**
     * Delete Accepted job
     */
    public function staff_job_delete()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_detail_id', 'label' => 'Job Detail id', 'rules' => 'trim|required|integer'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();
            $this->load->model('staff_model');
            if ($this->staff_model->staff_job_delete($user->id, $this->input->post('job_detail_id')))
                $code = '0001';
            else
                $code = '0000';
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('staff_job_delete', array('title' => 'Delete Staff Job'));
        }

    }

    public function get_job_detail()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_detail_id', 'label' => 'Job Detail id', 'rules' => 'trim|required|integer'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();
            $this->load->model('staff_model');
            if ($return['job'] = $this->staff_model->get_job_detail($user->id, $this->input->post('job_detail_id')))
                $code = '0001';
            else
                $code = '0012';
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('staff_job_delete', array('title' => 'Get Accepted Job Detail'));
        }

    }

    /**
     * Complete ongoing job
     */
    public function complete_job()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_detail_id', 'label' => 'Job Detail id', 'rules' => 'trim|required|integer'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();
            $this->load->model('staff_model');
            $code = $this->staff_model->complete_job($user->id, $this->input->post('job_detail_id'));
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('complete_job', array('title' => 'Complete Job'));
        }

    }

    /**
     * Track Staff Location
     */
    public function post_staff_location()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_detail_id', 'label' => 'Job Detail id', 'rules' => 'trim|required|integer'),
                array('field' => 'job_site_lat_lng', 'label' => 'Staff Current Latitude Longitude', 'rules' => 'trim|required|valid_lat_lng'),
                array('field' => 'is_staff_away', 'label' => 'Is staff away', 'rules' => "trim|required|integer|one_of[0,1]"),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();
            $this->load->model('staff_model');
            $code = $this->staff_model->post_staff_location($user->id, $this->input->post('job_detail_id'), explode(',', $this->input->post('job_site_lat_lng')), (bool)$this->input->post('is_staff_away'));
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('post_staff_location', array('title' => 'Post Staff Location'));
        }
    }

    /**
     * Start job break
     */
    public function post_start_job_break()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_staff_id', 'label' => 'Job Staff id', 'rules' => 'trim|required|integer'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();
            $this->load->model('staff_model');
            $code = $this->staff_model->post_start_job_break($user->id, $this->input->post('job_staff_id'), date('Y-m-d H:i:s'));
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('post_start_job_break', array('title' => 'Post Start Job Break'));
        }
    }

    /**
     * Stop job break
     */
    public function post_stop_job_break()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->library('form_validation');
            $validationRules = array(
                array('field' => 'job_staff_id', 'label' => 'Job Staff id', 'rules' => 'trim|required|integer'),
            );

            $this->form_validation->set_rules($validationRules);
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $return = array();
            $this->load->model('staff_model');
            $code = $this->staff_model->post_stop_job_break($user->id, $this->input->post('job_staff_id'), date('Y-m-d H:i:s'));
            return $this->response($code, $return, STAFF);
        } else {
            $this->load->view('post_stop_job_break', array('title' => 'Post Start Job Break'));
        }
    }


    public function timesheet()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if ($this->input->post() || !empty($key)) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0006');
            }
            $this->load->model('staff_model');
            $timeSheets = $this->staff_model->get_timesheet($user->id);
            return $this->response('0001', array('timesheet' => $timeSheets));
        } else {
            $this->load->view('get_timesheet', array('title' => 'Get Timesheet'));
        }

    }

    // get staff messages 
    public function get_all_messages()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if ($this->input->post() || !empty($key)) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->model('staff_model');
            $response['user'] = ($user->id);

            $response['sent_message'] = $this->staff_model->get_sent_message($user->id);
            $response['received_message'] = $this->staff_model->get_received_message($user->id);
            return $this->response('0001', $response, STAFF);

        } else {
            $this->load->view('message/message_list', array('title' => 'Message List'));
        }
    }

    // get staff messages 
    public function get_sent_messages()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if ($this->input->post() || !empty($key)) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->model('staff_model');
            $offset = $this->input->post('next_offset') ? $this->input->post('next_offset') : 0;
            $per_page = 10;
            $response['user'] = ($user->id);
            $response['sent_message'] = $this->staff_model->get_sent_message($user->id, $per_page, $offset);

            $total_messages = $this->staff_model->get_sent_message_num($user->id);
           
            if (($offset + $per_page) >= $total_messages) {
                $response['is_last_offset'] = true;
            } else {
                $response['is_last_offset'] = false;
            }

            $response['next_offset'] = ($offset + $per_page);

            return $this->response('0001', $response, STAFF);

        } else {
            $this->load->view('message/message_list', array('title' => 'Sent Message List'));
        }
    }

    // get staff messages 
    public function get_received_messages()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if ($this->input->post() || !empty($key)) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->load->model('staff_model');
            $response['user'] = ($user->id);
            $offset = $this->input->post('next_offset') ? $this->input->post('next_offset') : 0;
            $per_page = 10;
            $response['received_message'] = $this->staff_model->get_received_message($user->id, $per_page, $offset );

            $total_messages = $this->staff_model->get_received_message_num($user->id);

            if (($offset + $per_page) >= $total_messages) {
                $response['is_last_offset'] = true;
            } else {
                $response['is_last_offset'] = false;
            }

            $response['next_offset'] = ($offset + $per_page);
            return $this->response('0001', $response, STAFF);

        } else {
            $this->load->view('message/message_list', array('title' => 'Received Message List'));
        }
    }

    public function message_details()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->form_validation->set_rules('message_id', 'Message ID', 'trim|xss_clean|required|is_natural_no_zero');
            if ($this->form_validation->run() === false) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }

            $msg_id = $this->input->post('message_id', true);
            $this->load->model('staff_model');
            $response['message'] = $this->staff_model->get_message_details($msg_id, $user->id);
            if (!empty($response)) {
                return $this->response('0001', $response, STAFF);
            } else
                return $this->response('0000', array('msg' => 'No data found!'), STAFF);

        } else {
            $this->load->view('message/message_details', array('title' => 'Message Detail'));
        }
    }

    public function view_reply()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $message_id = $this->input->post('message_id', TRUE);
            $this->form_validation->set_rules('message_id', 'Message ID', 'trim|required|is_natural_no_zero');
            if ($this->form_validation->run() == FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }
            $this->load->model('staff_model');
            $response['main_message'] = $this->staff_model->get_message_details($message_id);
            $response['replies'] = $this->staff_model->get_message_reply($message_id);
            return $this->response('0001', $response);
        } else {
            $this->load->view('message/message_details', array('title' => 'View Reply'));
        }
    }

    public function reply_message()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
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
            if ($this->staff_model->send_reply($user->id) > 0) {
                return $this->response('0001', array(), STAFF);
            } else
                return $this->response('0000', array(), STAFF);

        } else {
            $this->load->view('message/send_reply', array('title' => 'Send Reply'));
        }
    }

    public function get_job_induction()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->form_validation->set_rules('job_id', 'Job ID', 'trim|required|is_natural_no_zero');
            if ($this->form_validation->run() === false) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $this->load->model('staff_model');

            $job_id = $this->input->post('job_id', true);
            $return = $this->staff_model->get_staff_job_induction($job_id, $user->id);
            if ($return == false) {
                return $this->response('0027', array(), STAFF);
            }
            return $this->response('0001', array('induction' => $return), STAFF);

        } else {
            $this->load->view('job/job_induction', array('title' => 'Job Induction'));
        }
    }

    /**
     * Get calendar data based on date for staff availability and job listing
     */
    public function get_job_calendar()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->form_validation->set_rules('start_timestamp', 'Start Timestamp', 'trim|required|integer');
            $this->form_validation->set_rules('end_timestamp', 'End Timestamp', 'trim|required|integer');
            if ($this->form_validation->run() === false) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $this->load->model('staff_model');

            $return = $this->staff_model->get_job_calendar($user->id, $this->input->post('start_timestamp'), $this->input->post('end_timestamp'));
            if (empty($return)) {
                return $this->response('0000', array(), STAFF);
            }
            return $this->response('0001', array('calendar' => $return), STAFF);

        } else {
            $this->load->view('job/get_job_calendar', array('title' => 'Get Job Calendar'));
        }
    }

    public function post_staff_unavailability()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->form_validation->set_rules('timestamps', 'Unavailability Timestamp Json', 'trim|required|valid_json');
            if ($this->form_validation->run() === false) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $this->load->model('staff_model');
            $code = $this->staff_model->post_staff_unavailability($user->id, $this->input->post('timestamps'));
            return $this->response($code, array(), STAFF);

        } else {
            $this->load->view('job/post_staff_unavailability', array('title' => 'Post Staff Unavailability'));
        }
    }

    public function get_staff_unavailability()
    {
        if ($this->input->post()) {
            if (!($user = $this->is_logged_in())) {
                return $this->response('0002', array(), STAFF);
            }
            $this->form_validation->set_rules('start_timestamp', 'Start Timestamp', 'trim|required|integer');
            if ($this->form_validation->run() === false) {
                return $this->response('0000', array('msg' => validation_errors()), STAFF);
            }
            $this->load->model('staff_model');

            $return = $this->staff_model->get_staff_unavailability($user->id, $this->input->post('start_timestamp'));
            return $this->response('0001', array('unavailability' => $return), STAFF);

        } else {
            $this->load->view('job/get_staff_unavailability', array('title' => 'Get Staff Unavailability'));
        }
    }

}
