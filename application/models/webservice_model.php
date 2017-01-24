<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Webservice_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function validateHeaderRequest($validateFields = array())
    {
        $headers = apache_request_headers();
        $key = (trim(@$headers['Authentication-Key']));

        $myFields = array('1' => $key);
        $myFieldsName = array('1' => "Authentication Key");

        $errMessage = "";

        if (!empty($validateFields)) {
            foreach ($validateFields as $each) {
                if (isset($myFields[$each]) && $myFields[$each] != '') {
                    //continue to validate next field if found.
                } else {
                    $errMessage .= $myFieldsName[$each] . " field is required.\n <br />";
                }
            }
        }

        if ($errMessage == "")
            return TRUE;
        else
            return $errMessage;
    }

    function hashCode($length = 32)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function encryptPassword($staffId, $password)
    {
        if (empty($password)) {
            return FALSE;
        }
        //return sha1($password);
        return $this->ion_auth->hash_password_db_send($staffId, $password);
    }


    function checkLogin()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        $query = $this->db->query("CALL sp_checkUserLoggedIn_new('{$key}')");
        $this->db->freeDBResource($this->db->conn_id);

        return $query->row()->status == 1 ? $query->row() : FALSE;
    }

    function checkSupervisorOrCustomer()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        $query = $this->db->query("CALL sp_checkUserLoggedIn('{$key}')");
        $this->db->freeDBResource($this->db->conn_id);

        return $query->row()->status == 1 ? $query->row() : FALSE;
    }

    function checkJob($user_id, $group_id, $job_id)
    {
        $this->db->select('*')->from('tbl_job');
        $this->db->where('id', $job_id);
        if ($group_id == CUSTOMER) {
            $this->db->where('customer_user_id', $user_id);
        } elseif ($group_id == SUPERVISOR) {
            $this->db->where('supervisor_user_id', $user_id);
        }
        $res = $this->db->get()->row();
        return $res;
    }

    function get_user_detail($user_id)
    {
        $query = $this->db->select('users.*')
            ->from('users')
            ->where("users.id = '$user_id'")
            ->get();
        return $query->row();
    }

    public function get_user_timezone()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        $user = $this->db->select('tu.id, timezone')
            ->join('keys as k', 'k.user_id = tu.id', 'inner')
            ->where(array(
                'k.key' => $key
            ))
            ->get('tbl_user as tu')->row();
        return (isset($user->timezone) ? $user->timezone : DEFAULT_TIMEZONE);
    }

    function get_admin_settings($select = array('*'))
    {
        $query = $this->db->select($select)->from('tbl_con_general_setting')->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }


}
