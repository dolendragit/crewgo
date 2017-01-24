<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Lhc_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function register($data = array(), $data1 = array(), $files = array(), $expiry = array(), $docid = array(), $industry)
    {

        $this->db->insert('tbl_user', $data);
        $insertId = $this->db->insert_id();

        if (count($files) > 0) {
            foreach ($files as $key => $val) {
                $insert_doc = array('lhc_user_id' => $insertId, 'doc_name' => $val, 'expiry_date' => $expiry[$key], 'doc_id' => $docid[$key]);
                $this->db->insert('tbl_lhc_doc', $insert_doc);
            }
        }

        $data1['lhc_user_id'] = $insertId;

        $user_group = array(
            'user_id'  => $insertId,
            'group_id' => '2',
        );
        $this->db->insert('tbl_user_group', $user_group);
        $this->db->insert('tbl_lhc_add_info', $data1);

        foreach ($industry as $val) {
            $data = array(
                'lhc_user_id' => $insertId,
                'industry_id' => $val,
            );
            $this->db->insert('tbl_lhc_industry', $data);
        }

        return 1;

    }

    public function changepassword($user, $password)
    {
        $data = array('password' => $password);
        $this->db->where('id', $user);
        $this->db->update('tbl_user', $data);
        return 1;
    }

    public function checkEmail($email)
    {
        $query = $this->db->get_where('tbl_user', array('email' => $email));
        if ($query->num_rows() == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public function insertCode($email, $code)
    {
        $data = array('forgotten_password_code' => $code);
        $this->db->where('email', $email);
        $this->db->update('tbl_user', $data);
        return 1;
    }

    public function checkValidity($code)
    {
        $query = $this->db->get_where('tbl_user', array('forgotten_password_code' => $code));
        if ($query->num_rows() == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public function updatePassword($encrypted, $code)
    {
        $data    = array('password' => $encrypted);
        $query   = $this->db->get_where('tbl_user', array('forgotten_password_code' => $code));
        $results = $query->row();

        $this->db->where('email', $results->email);
        $operation = $this->db->update('tbl_user', $data);

        if ($operation) {
            $data = array('forgotten_password_code' => null);
            $this->db->where('email', $results->email);
            $this->db->update('tbl_user', $data);
            return 1;
        } else {
            return 0;
        }
    }

    public function getIndustry()
    {
        $query = $this->db->select('id, name, status')
            ->from('tbl_con_industry')
            ->where('lhc_restriction', 'no')
            ->get();

        return $query->result();
    }

    public function allDetail($id)
    {
        //$this->db->join('comments', 'comments.id = blogs.id', 'left');
        $query = $this->db->select('*')
            ->from('tbl_user')
            ->join('tbl_lhc_add_info', 'tbl_lhc_add_info.lhc_user_id = tbl_user.id', 'left')
            ->where('tbl_user.id', $id)
            ->get();

        return $query->row();
    }

    public function allIndustry($id)
    {
        $query = $this->db->select('tbl_lhc_industry.industry_id')
            ->from('tbl_lhc_industry')
            ->join('tbl_con_industry', 'tbl_lhc_industry.industry_id = tbl_con_industry.id', 'left')
            ->where('tbl_lhc_industry.lhc_user_id', $id)
            ->get();

        return $query->result_array();
    }

    public function checkExistence($email)
    {
        $query = $this->db->get_where('tbl_user', array('email' => $email));
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }

    }

    public function updateProfile($id, $user, $info, $industry)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_user', $user);

        $this->db->where('lhc_user_id', $id);
        $this->db->update('tbl_lhc_add_info', $info);

        $this->db->delete('tbl_lhc_industry', array('lhc_user_id' => $id));
        foreach ($industry as $key => $val) {
            $data = array('lhc_user_id' => $id, 'industry_id' => $val);
            $this->db->insert('tbl_lhc_industry', $data);
        }

        return 1;

    }

    public function getLhcDoc()
    {
        $query = $this->db->select('id, name, status, has_expiry_date, show_in_registration')
            ->from('tbl_con_lhc_doc')
            ->where('status', 1)
            ->where('show_in_registration', 1)
            ->get();

        return $query->result();
    }

}
