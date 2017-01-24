<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Customer_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('ion_auth'));

    }

    public function checkValidityOfEmail($email, $lhc)
    {
        $query = $this->db->select('*')
            ->from('tbl_user')
            ->join('tbl_user_group', 'tbl_user.id = tbl_user_group.user_id')
            ->where('email', $email)
            ->where('tbl_user_group.group_id', 3)
            ->get();

        $checkinAssociation = $this->db->select('*')
                                    ->from('tbl_lhc_customer_association')
                                    ->where('lhc_user_id', $lhc)
                                    ->where('customer_user_id', $query->row()->id)
                                    ->get();
       

        if (count($query->row()) > 0) {
            //check for user group
           // $restricted_groups = array('1', '2', '4', '5');
            $restricted_groups = array('1', '2', '4', '5');
            if (in_array($query->row()->group_id, $restricted_groups)) {
                return false;
            } else{
                if ($checkinAssociation->num_rows() > 0){
                    return false;
                }
                return true;
            }
        } else {
            return true;
        }

    }

    public function saveCustomer($lhc, $data)
    {
        $this->db->insert('tbl_user', $data);
        $insert_id = $this->db->insert_id();
        $this->ion_auth->add_to_group(3, $insert_id);

        $associate = array('lhc_user_id' => $lhc, 'customer_user_id' => $insert_id, 'requested_date' => date("Y-m-d"), 'status' => '1');
        $this->db->insert('tbl_lhc_customer_association', $associate);

        return true;
    }



    public function checkExistingCustomer($email)
    {
        $query = $this->db->select('*')
            ->from('tbl_user')
            ->join('tbl_user_group', 'tbl_user.id = tbl_user_group.user_id')
            ->where('email', $email)
            ->where('tbl_user_group.group_id', 3)
            ->get();




        if (count($query->row()) > 0) {
            //email available
            return $query->row()->user_id;
        }

        return false;

    }



    public function getAllCustomers($lhc)
    {
        $query = $this->db->select('tbl_user.id as id, tbl_user.name, tbl_user.full_address, tbl_user.phone_number, tbl_user.email')
                            ->from('tbl_user')
                            ->join('tbl_user_group', 'tbl_user_group.user_id = tbl_user.id')
                            ->join('tbl_lhc_customer_association', 'tbl_user.id = tbl_lhc_customer_association.customer_user_id')
                            ->where('tbl_lhc_customer_association.lhc_user_id', $lhc)
                            ->where('tbl_user_group.group_id', 3)
                            ->group_by('tbl_lhc_customer_association.customer_user_id')
                            ->get();

        return $query->result();
    }


    public function getCustomerById($id)
    {
        $query = $this->db->select('*')
                      ->from('tbl_user')
                      ->where('id', $id)
                      ->get();

        return $query->row();
    }
}
