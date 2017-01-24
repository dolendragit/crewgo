<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * @property  input
 * @property  session
 */
class Model_message extends MY_Model
{

    public function __construct()
    {
      parent::__construct();

  }

       /**
       * Get all message details, sender details and receiver details 
       * @param int $result
       */
       public function get_all_messages($limit=0, $offset=0)
       {
            // staff search condition
            if( !empty($_POST['columns'][3]['search']['value']) ){ 
                $msg_arr = array();
                $query = $this->db->select('u.name,mrr.job_message_id')
                ->from('tbl_staff_message_read_reply mrr')
                ->join('tbl_user u', 'mrr.user_id = u.id')
                ->join('tbl_user_group ug', 'mrr.user_id = ug.user_id')
                ->where(array('ug.group_id'=>4, 'mrr.is_reply'=>0))
                ->like('u.name', $_POST['columns'][3]['search']['value'])
                ->group_by('mrr.job_message_id')
                ->get();
               // echo  $this->db->last_query(); exit;
                if ($query->num_rows() > 0) {
                    $msg = $query->result();
                    foreach ($msg as $k => $msg) {
                        $msg_arr[$k] = $msg->job_message_id;
                    }
                } else {
                    // prevent possible error in where in condition
                    $msg_arr[0]=0;
                }
            }

            // mobile num search condition
            if( !empty($_POST['columns'][0]['search']['value']) ){ 
                $mob_arr = array();
                $query = $this->db->select('u.name,mrr.job_message_id')
                ->from('tbl_staff_message_read_reply mrr')
                ->join('tbl_user u', 'mrr.user_id = u.id')
                ->join('tbl_user_group ug', 'mrr.user_id = ug.user_id')
                ->where(array('ug.group_id'=>4, 'mrr.is_reply'=>0))
                ->like('u.phone_number', $_POST['columns'][0]['search']['value'])
                ->group_by('mrr.job_message_id')
                ->get();
               // echo  $this->db->last_query(); exit;
                if ($query->num_rows() > 0) {
                    $mob_staffs = $query->result();
                    foreach ($mob_staffs as $k => $mob) {
                        $mob_arr[$k] = $mob->job_message_id;
                    }
                } else {
                    // prevent possible error in where in condition
                    $mob_arr[0]=0;
                }
                $mob_arr = join(',', $mob_arr); 
            }
 
            $this->db->select('jm.id as msg_id, jm.upper_message_id, lhc.name as lhc_name, jm.entered_date, jm.job_id, custm.name as customer, jm.message')
            ->from('tbl_job_message jm')
            ->join('tbl_job j', 'jm.job_id = j.id', 'left')
            ->join('tbl_user lhc', 'j.lhc_user_id = lhc.id', 'left')
            ->join('tbl_user custm', 'jm.entered_by = custm.id', 'left')
            ->where('jm.upper_message_id', '0');

            if( !empty($_POST['columns'][0]['search']['value']) ){   
                // search by Mobile number
                $where = "custm.phone_number LIKE '%".$_POST['columns'][0]['search']['value']."%' ESCAPE '!'"; 
                if($mob_arr !=0)
                    $where = $where." OR 'jm.id' IN ($mob_arr)";
                
               $this->db->where($where);
            }
            if( !empty($_POST['columns'][1]['search']['value']) ){ 
                // search by job id
                $this->db->where('jm.job_id', $_POST['columns'][1]['search']['value']);

            }
            if( !empty($_POST['columns'][2]['search']['value']) ){ 
                // client search
                $this->db->where('jm.job_id', $_POST['columns'][2]['search']['value']);
            }

            if( !empty($_POST['columns'][3]['search']['value']) ){ 
              // staff search
              $this->db->where_in('jm.id', $msg_arr);
            }
            if( !empty($_POST['columns'][4]['search']['value']) ){ 
                // date from
                $this->db->where('DATE(jm.entered_date) >=', $_POST['columns'][4]['search']['value']);
            }
            if( !empty($_POST['columns'][5]['search']['value']) ){ 
                // date to
                $this->db->where('DATE(jm.entered_date) <=', $_POST['columns'][5]['search']['value']);
            }

            if($limit > 0){
                $this->db->limit($limit, $offset);
            }
            $query = $this->db->get();
            if($query->num_rows() > 0){
                return $query->result();
            }
            return false;
    }

    public function get_total_messages(){
        $this->db->select('jm.id as msg_id, jm.upper_message_id, lhc.name as lhc_name, jm.entered_date, jm.job_id, custm.name as customer, jm.message')
        ->from('tbl_job_message jm')
        ->join('tbl_job j', 'jm.job_id = j.id', 'left')
        ->join('tbl_user lhc', 'j.lhc_user_id = lhc.id', 'left')
        ->join('tbl_user custm', 'jm.entered_by = custm.id', 'left')
        ->where('jm.upper_message_id', '0');
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
      * Get all replies of a particular message 
      * @param int msg_id
      * @return mixed
      */
    public function get_message_replies($msg_id = 0){
      if($msg_id > 0){
        $query = $this->db->select('jm.id as msg_id, jm.upper_message_id, jm.entered_date, jm.job_id, custm.name as customer, staff.name as staff, jm.message')
        ->from('tbl_job_message jm')
        ->join('tbl_user custm', 'jm.to_user_id = custm.id', 'left')
        ->join('tbl_user staff', 'jm.entered_by = staff.id', 'left')
        ->where('jm.upper_message_id', $msg_id)
        ->get();
        if($query->num_rows() > 0){
          return $query->result();
        }
    }
    return false;
    }

  /**
   * Get message receiver staff's information 
   * @param  integer
   * @return mixed
   */
  public function get_receiver_staffs($msg_id=0){
        if($msg_id > 0){
            $query = $this->db->select('u.name as staff_name, mrr.user_id as staff_id')
            ->from('tbl_staff_message_read_reply mrr')
            ->join('tbl_user u', 'mrr.user_id = u.id')
            ->join('tbl_user_group ug', 'u.id = ug.user_id')
            ->where(array('mrr.is_reply' => 0, 'mrr.job_message_id'=>$msg_id, 'ug.group_id' => 4))
            ->group_by('mrr.user_id')
            ->get();
            // echo $this->db->last_query(); exit;
            if ($query->num_rows() > 0) {
            return $query->result();
            }
            return false;
        }
    }

  /**
   * Get customer information
   * @param  string
   * @return mixed
   */
  public function get_all_customers($ret = 'obj'){
        $query = $this->db->select('u.id as customer_id, u.name as customer_name')
        ->from('tbl_user u')
        ->join('tbl_user_group ug', 'u.id = ug.user_id')
        ->where(array( 'ug.group_id'=>3, 'u.active'=>1))
        ->get();
        if($query->num_rows() > 0){
          if($ret = 'obj')
            return $query->result();
        return $query->result_array();
        }
        return false;
    }

}
