<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Induction_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function saveInduction($induction)
    {
        $this->db->insert('tbl_con_induction_detail', $induction);
        return true;

    }

    public function getAll($id)
    {
        $query = $this->db->select('*')
            ->from('tbl_con_induction_detail')
            ->where('entered_by', $id)
            ->get();

        return $query->result();
    }

    public function findById($id)
    {
        $query = $this->db->select('*')
            ->from('tbl_con_induction_detail')
            ->where('id', $id)
            ->get();

        return $query->row();
    }


    public function updateInduction($induction, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_con_induction_detail', $induction);

       
        return true;
    }
}
