<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supervisor_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Fetch team data
     * @param type $jobid
     * @return type
     */

    public function get_team_list($job_id = 0)
    {
        $team = $this->db->select('jt.id, jt.name as team_name')
            ->from('tbl_job_team jt')
            ->where('jt.job_id', $job_id)
            ->get()
            ->result();
        $team_array = array();
        if (!empty($team)) {
            foreach ($team as $key => $t) {
                $temp = array();
                $temp['team_id'] = $t->id;
                $temp['team_name'] = $t->team_name;

                $team_lead = $this->db->select('jtm.staff_user_id as staff_id, u.name as staff_name, u.profile_image as staff_image, cs.name as job, cl.name as level')
                    ->from('tbl_job_team_member jtm')
                    ->join('tbl_user u', 'jtm.staff_user_id = u.id')
                    ->join('tbl_job_staff js', 'u.id = js.staff_user_id', 'left')
                    ->join('tbl_job_detail jd', 'js.job_detail_id = jd.id', 'left')
                    ->join('tbl_con_skill cs', 'jd.skill_id = cs.id', 'left')
                    ->join('tbl_con_level cl', 'jd.level_id = cl.id', 'left')
                    // ->where('jd.job_id', $job_id) // 
                    ->where('jtm.job_team_id', $t->id)
                    ->where('jtm.is_team_lead', 1)
                    ->group_by('jtm.staff_user_id')
                    ->get()->result();
                    if(!empty($team_lead)){
                        $temp['team_lead'] = $team_lead;
                    }

                $user = $this->db->select('jtm.staff_user_id as staff_id, u.name as staff_name, u.profile_image as staff_image, cs.name as job, cl.name as level, jtm.is_team_lead')
                    ->from('tbl_job_team_member jtm')
                    ->join('tbl_user u', 'jtm.staff_user_id = u.id')
                    ->join('tbl_job_staff js', 'u.id = js.staff_user_id', 'left')
                    ->join('tbl_job_detail jd', 'js.job_detail_id = jd.id', 'left')
                    ->join('tbl_con_skill cs', 'jd.skill_id = cs.id', 'left')
                    ->join('tbl_con_level cl', 'jd.level_id = cl.id', 'left')
                    ->where('jtm.job_team_id', $t->id)
                    ->group_by('jtm.staff_user_id')
                    ->get()->result();

                if (!empty($user)) {
                    $temp['users'] = $user;
                }
                array_push($team_array, $temp);
            }
        } else {
            return false;
        }
        return $team_array;
    }

    /**
     * Fetch member data
     * @param type $jobid
     * @return type
     */

    public function get_job_members($job_id = 0, $team_id = 0)
    {
        $member = $this->db->select('js.staff_user_id as staff_id, u.name as staff_name, u.profile_image as staff_image, cs.name as job, cl.name as level')
            ->from('tbl_job_staff js')
            ->join('tbl_user u', 'js.staff_user_id = u.id')
            ->join('tbl_job_detail jd', 'js.job_detail_id = jd.id')
            ->join('tbl_con_skill cs', 'jd.skill_id = cs.id')
            ->join('tbl_con_level cl', 'jd.level_id = cl.id')
            ->where('jd.job_id', $job_id)
            ->group_by('js.staff_user_id')
            ->get()
            ->result();

        if ($team_id == 0) {
            return $member;
        }

        $team_members = $this->db->select('tm.staff_user_id')
            ->from('tbl_job_team_member tm')
            ->join('tbl_job_team jt', 'tm.job_team_id = jt.id')
            ->where('jt.job_id', $job_id)
            ->where('tm.job_team_id', $team_id)
            ->get()->result();

        $existing_ids = array();
        if (!empty($team_members)) {
            foreach ($team_members as $k => $tmem) {
                $existing_ids[$k] = $tmem->staff_user_id;
            }
        }

        $existing_members = array();
        $new_members = array();

        if (!empty($member)) {
            foreach ($member as $key => $mem) {
                if (in_array($mem->staff_user_id, $existing_ids)) {
                    array_push($existing_members, $mem);
                } else {
                    array_push($new_members, $mem);
                }
            }
        }
        $response['existing_members'] = $existing_members;
        $response['new_members'] = $new_members;
        return $response;

    }

    /**
     * Fetch member data
     * @param type $jobid
     * @return type
     */

    public function create_team($user_id)
    {

        $staffs = $this->input->post('staffs', TRUE);
        $staff_array = explode(',', $staffs);

        $team_data = array(
            'job_id' => $this->input->post('job_id', TRUE),
            'name' => $this->input->post('team_name', TRUE),
            'entered_date' => get_date_time(),
            'entered_by' => $user_id
        );
        $this->db->insert('tbl_job_team', $team_data);
        $team_id = $this->db->insert_id();

        //insert team staff's information  
        if ($team_id > 0 && !empty($staff_array[0])) {
            $team_member_data[] = array();
            foreach ($staff_array as $key => $staff) {
                if (is_numeric($staff) && $staff > 0) {
                    $temp = array(
                        'job_team_id' => $team_id,
                        'staff_user_id' => $staff,
                        'entered_date' => get_date_time(),
                        'entered_by' => $user_id
                    );
                    $team_member_data[$key] = $temp;
                }
            }
            $this->db->insert_batch('tbl_job_team_member', $team_member_data);

            return $team_id;
        }
        return FALSE;
    }


    /**
     * Fetch member data
     * @param type $jobid
     * @return type
     */

    public function update_team($user_id)
    {
        $team_id = $this->input->post('team_id', TRUE);
        $staffs = $this->input->post('staffs', TRUE);
        $staff_array = explode(',', $staffs);

        //update team information
        $team_data = array(
            'job_id' => $this->input->post('job_id', TRUE),
            'name' => $this->input->post('team_name', TRUE),
            'updated_date' => get_date_time(),
            'updated_by' => $user_id
        );
        $this->db->where('id', $team_id);
        $this->db->update('tbl_job_team', $team_data);

        if ($team_id > 0 && !empty($staff_array[0])) {

            //delete old team staff information
            $this->db->where('job_team_id', $team_id);
            $this->db->delete('tbl_job_team_member');

            //update new team staff information.
            $team_member_data[] = array();
            foreach ($staff_array as $key => $staff) {
                if (is_numeric($staff) && $staff > 0) {
                    $temp = array(
                        'job_team_id' => $team_id,
                        'staff_user_id' => $staff,
                        'entered_date' => get_date_time(),
                        'entered_by' => $user_id
                    );
                    $team_member_data[$key] = $temp;
                }
            }
            $this->db->insert_batch('tbl_job_team_member', $team_member_data);

            return $team_id;
        }
        return FALSE;
    }

    public function delete_team_byid($team_id){
        $this->db->trans_start();
        $this->db->where('job_team_id', $team_id);
        $this->db->delete('tbl_job_team_member');
        $this->db->where('id', $team_id);
        $this->db->delete('tbl_job_team');
        $this->db->trans_complete();
        if($this->db->trans_status()===true){
            return true;
        }
        return false;
    }

    public function get_team_lead($job_id = 0, $team_id=0){
    $member['staffs'] = $this->db->select('js.staff_user_id, u.name as staff_name, u.profile_image, cs.name as skill_name, cl.name as level_name')
        ->from('tbl_job_staff js')
        ->join('tbl_user u', 'js.staff_user_id = u.id')
        ->join('tbl_job_detail jd', 'js.job_detail_id = jd.id')
        ->join('tbl_job_team jt', 'jd.job_id = jt.job_id')
        ->join('tbl_job_team_member tm', 'jt.id = tm.job_team_id')
        ->join('tbl_con_skill cs', 'jd.skill_id = cs.id')
        ->join('tbl_con_level cl', 'jd.level_id = cl.id')
        ->where('jd.job_id', $job_id)
        ->where('jt.id', $team_id)
        ->where('tm.is_team_lead !=', '1')
        ->get()
        ->result();

    $member['team_lead'] = $this->db->select('js.staff_user_id, u.name as staff_name, u.profile_image, cs.name as skill_name, cl.name as level_name')
        ->from('tbl_job_staff js')
        ->join('tbl_user u', 'js.staff_user_id = u.id')
        ->join('tbl_job_detail jd', 'js.job_detail_id = jd.id')
        ->join('tbl_job_team jt', 'jd.job_id = jt.job_id')
        ->join('tbl_job_team_member tm', 'jt.id = tm.job_team_id')
        ->join('tbl_con_skill cs', 'jd.skill_id = cs.id')
        ->join('tbl_con_level cl', 'jd.level_id = cl.id')
        ->where('jd.job_id', $job_id)
        ->where('jt.id', $team_id)
        ->where('tm.is_team_lead', '1')
        ->get()
        ->row();

        return $member;
    }

    // update team lead  
    public function update_team_lead(){
        $team_id = $this->input->post('team_id', TRUE);
        $user_id = $this->input->post('staff_id', TRUE);

        $this->db->where(array('job_team_id'=>$team_id, 'is_team_lead'=>1));
        $this->db->update('tbl_job_team_member', array('is_team_lead'=>0));

        $this->db->where(array('job_team_id'=>$team_id, 'staff_user_id'=>$user_id));
        if( $this->db->update('tbl_job_team_member', array('is_team_lead'=>1)) ){
            return TRUE;
        }
        return FALSE;

    }

    public function get_sent_message($job_id=0, $user_id=0, $limit=0, $offset=0){
        $create_date = $this->db->select('DATE(entered_date) as date')
        ->from('tbl_job_message')
        ->where(array('job_id' => $job_id, 'entered_by'=>$user_id, 'upper_message_id'=>0))
        ->group_by('date')
        ->order_by('date', 'desc')
        ->limit($limit, $offset)
        ->get()->result();

        $message = array();
        foreach ($create_date as $key => $date) {
            $date_only = date( 'Y-m-d', strtotime($date->date) ); 
            $msg_data = $this->db->select('*')
            ->from('tbl_job_message')
            ->where(array('job_id' => $job_id, 'entered_by'=>$user_id, 'upper_message_id'=>0, 'DATE(entered_date)'=>$date_only))
            ->order_by('entered_date', 'desc')
            ->get()->result();

            $message[$key]['date'] = strtotime($date_only);
            $message[$key]['message'] = $msg_data;
        }
        return $message;
    }

    public function get_received_message($job_id=0, $user_id=0, $limit=0, $offset=0){
        $create_date = $this->db->select('DATE(jm.entered_date) as date')
        ->from('tbl_job_message jm')
        ->join('tbl_user u', 'jm.entered_by = u.id')
        ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
        ->where(array('jm.job_id' => $job_id, 'jm.to_user_id'=>$user_id,'sm.user_id'=>$user_id, 'jm.upper_message_id !='=>0))
        ->group_by('date')
        ->order_by('date', 'desc')
        ->limit($limit, $offset)
        ->get()->result();

        $message = array();
        foreach ($create_date as $key => $date) {
            $date_only = date( 'Y-m-d', strtotime($date->date) ); 
            $msg_data = $this->db->select('jm.id, jm.job_id, jm.title, jm.message, jm.delete_option, jm.can_reply, u.name as send_from, u.id as sender_id, sm.is_read')
            ->from('tbl_job_message jm')
            ->join('tbl_user u', 'jm.entered_by = u.id')
            ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
            ->where(array('jm.job_id' => $job_id, 'jm.to_user_id'=>$user_id, 'sm.user_id'=>$user_id, 'jm.upper_message_id !='=>0, 'DATE(jm.entered_date)'=>$date_only))
            ->order_by('jm.entered_date', 'desc')
            ->get()->result();
            $message[$key]['date'] = strtotime($date_only);
            $message[$key]['message'] = $msg_data;
        }
        return $message;
    }

    public function get_received_message_num($job_id=0, $user_id=0){
        $num_rows = $this->db->select('DATE(jm.entered_date) as date')
            ->from('tbl_job_message jm')
            ->join('tbl_user u', 'jm.entered_by = u.id')
            ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
            ->where(array('jm.job_id' => $job_id, 'jm.to_user_id'=>$user_id, 'sm.user_id'=>$user_id, 'upper_message_id'=>0))
            ->group_by('date')
            ->order_by('date', 'desc')
            ->get()->num_rows();

        return $num_rows;
    }

    public function get_sent_message_num($job_id=0, $user_id=0){
        $num_rows =  $this->db->select('DATE(entered_date) as date')
        ->from('tbl_job_message')
        ->where(array('job_id' => $job_id, 'entered_by'=>$user_id, 'upper_message_id'=>0))
        ->group_by('date')
        ->order_by('date', 'desc')
        ->get()->num_rows();
            
        return $num_rows;
    }

    public function get_teams_and_staffs($job_id){
        $receiver['staffs'] = $this->db->select('js.staff_user_id as staff_id, u.name as staff_name')
        ->from('tbl_job_staff js')
        ->join('tbl_user u', 'js.staff_user_id = u.id')
        ->join('tbl_job_detail jd', 'js.job_detail_id = jd.id')
        ->join('tbl_job_team jt', 'jd.job_id = jt.job_id')
        ->where('jd.job_id', $job_id)
        ->group_by('js.staff_user_id')
        ->get()
        ->result();

        $receiver['teams'] = $this->db->select('jt.id as team_id, jt.name as team_name')
        ->from('tbl_job_team jt')
        ->where('jt.job_id', $job_id)
        ->group_by('jt.id')
        ->get()
        ->result();
        return $receiver;
    }

    public function read_message($msg_id, $user_id){
        $data = $this->db->select('is_read')
        ->where( array('job_message_id'=>$msg_id, 'user_id'=>$user_id) )
        ->get('tbl_staff_message_read_reply')
        ->row();

        // return if data not found or already read
        if(empty($data) || $data->is_read==1){
            return false;
        }

        $this->db->where( array('job_message_id'=>$msg_id, 'user_id'=>$user_id) );
        if($this->db->update('tbl_staff_message_read_reply', array('is_read'=>1, 'read_date'=>gmdate('Y-m-d H:i:s')))){
            return true;
        }
        return false;
    }

    public function send_message($user_id = 0, $message){
        if(empty($message)){
            return false;
        }
        $job_id = $message['job_id'];
        $receivers = $message['receivers'];

         if ( $receivers['all']==0 && empty( $receivers['teams'] ) && empty( $receivers['staffs'] ) ) {
            // return false if there is no staffs or team selected
            return false; 
        }

            $staff_array = array(); // used to keep records of staffs in array
            $team_array = array(); // used to keep records of teams in array
            if ($receivers['all']==1) {
                // get list of all members to receiver user list.
                $staffs = $this->db->select('js.staff_user_id, u.name as staff_name')
                ->from('tbl_job_staff js')
                ->join('tbl_user u', 'js.staff_user_id = u.id')
                ->join('tbl_job_detail jd', 'js.job_detail_id = jd.id')
                ->where('jd.job_id', $job_id)
                ->group_by('js.staff_user_id')
                ->get()
                ->result();
                if (!empty($staffs)) {
                    foreach ($staffs as $key => $staff) {
                        array_push($staff_array, $staff->staff_user_id);
                    }
                }
            } else {     
                // get selected staffs list 
                if( !empty( $receivers['staffs'] ) ){
                    foreach ($receivers['staffs'] as $sk => $srcv) {
                        array_push($staff_array, $srcv['staff_id']);
                    }
                }
                // get the team members and store into $staff_array
                if (!empty($receivers['teams'])) {                
                    foreach ($receivers['teams'] as $k => $t) {
                        $user = $this->db->select('jtm.staff_user_id')
                        ->from('tbl_job_team_member jtm')
                        ->join('tbl_user u', 'jtm.staff_user_id = u.id')
                        ->where('jtm.job_team_id', $t['team_id'])
                        ->group_by('jtm.staff_user_id')
                        ->get()->result();

                        if (!empty($user)) {
                            foreach ($user as $key => $u) {
                            if( !in_array($u->staff_user_id, $staff_array)){ // avoids duplicated staff ids
                                array_push($staff_array, $u->staff_user_id); // push unique user id to $staff_array
                            }
                        }               
                    }
                }                
            }  
            $staff_array = array_unique($staff_array, SORT_NUMERIC); // get only unique staff ids in array.
        }

        $message_data = array(
            'job_id'        => $job_id,
            'upper_message_id' => 0,
            'to_user_id'    => json_encode($receivers), // sotre receivers information in json string
            'title'         => $message['title'],
            'message'       => $message['message'],
            'delete_option' => $message['delete_option'],
            'can_reply'     => $message['can_reply'],
            'entered_date'  => get_date_time(),
            'entered_by'    => $user_id
            );
        $this->db->trans_start();
        $this->db->insert('tbl_job_message', $message_data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            $staff_message = array();
            foreach ($staff_array as $stf) {
                $temp_msg = array(
                    'job_message_id'=> $insert_id,
                    'user_id'       => $stf,
                    'is_read'       => 0,
                    'is_reply'      => 0,
                    'is_delete'     => 0,
                    );
                $staff_message[] = $temp_msg;
            }
            // insert staff message data in batch.
           $this->db->insert_batch('tbl_staff_message_read_reply', $staff_message);
            $this->db->trans_complete();
            if ($this->db->trans_status() === false)
            {
                return false;
            }
            return $insert_id;
        }
        return false;
    }

    public function get_message_details($msg_id){
        $msg = $this->db->select('jm.id as message_id, jm.to_user_id, jm.job_id, j.job_number,pg.suburb, jm.title, jm.message, jm.delete_option, jm.can_reply, jm.entered_by')
                ->from('tbl_job_message jm')
                ->join('tbl_job j', 'jm.job_id = j.id')
                ->join('tbl_postcodes_geo pg', 'j.job_postcode_id = pg.id', 'left')
                ->where('jm.id', $msg_id)
                ->get();

        if($msg->num_rows() > 0){
            return $msg->row();
        }
        return false;
    }

    // get list of user's name from ids array
    public function get_receivers_fromids($receivers = array()){
        $staffs = $this->db->select('id as staff_id, name as staff_name')
        ->from('tbl_user')
        ->where_in('id', $receivers)
        ->get()->result();
        return $staffs;
    }

    // get list of teams's name from ids array
    public function get_teams_fromids($teams = array()){
        $teams = $this->db->select('id as team_id, name as team_name')
        ->from('tbl_job_team')
        ->where_in('id', $teams)
        ->get()->result();

        return $teams;
    }

    public function get_message_reply($msg_id, $user_id){
       $create_date = $this->db->select('DATE(entered_date) as date')
       ->from('tbl_job_message')
       ->where(array('upper_message_id'=>$msg_id))
       ->group_by('date')
       ->order_by('date', 'desc')
       ->get()->result();

       $message = array();
       foreach ($create_date as $key => $date) {
            $date_only = date( 'Y-m-d', strtotime($date->date) ); 

            $reply = $this->db->select('jm.id, jm.job_id, jm.title, jm.message, jm.delete_option, jm.can_reply, u.name as send_from, jm.entered_by as sender_id, sm.is_read')
            ->from('tbl_job_message jm')
            ->join('tbl_user u', 'jm.entered_by = u.id')
            ->join('tbl_staff_message_read_reply sm', 'jm.id = sm.job_message_id')
            ->where( array('jm.upper_message_id' => $msg_id, 'sm.user_id' => $user_id, 'DATE(jm.entered_date)'=>$date_only) )            
            ->order_by('jm.entered_date', 'desc')
            ->get()->result();
            $message[$key]['date'] = strtotime($date_only);       
            $message[$key]['message'] = $reply;
        }
    return $message;
}

    // update staff's reply
    public function send_reply($user_id = 0)
    {
        $message_id = $this->input->post('message_id', true);
        $main_msg = $this->db->select('job_id, entered_by, delete_option, can_reply')
            ->from('tbl_job_message')
            ->where('id', $message_id)
            ->get()
            ->row();

        if (empty($main_msg)) {
            return false;
        }

        $data = array(
            'job_id' => $main_msg->job_id,
            'upper_message_id' => $this->input->post('message_id', true),
            'to_user_id' => $main_msg->to_user_id,
            'to_team_id' => $main_msg->to_team_id,
            'message' => $this->input->post('message', true),
            'delete_option' => $main_msg->delete_option,
            'can_reply' => $main_msg->can_reply,
            'entered_date' => date('Y-m-d H:i:s'),
            'entered_by' => $user_id
        );
        $this->db->insert('tbl_job_message', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $read_reply_data = array(
                'job_message_id' => $insert_id,
                'user_id' => $user_id,
                'is_read' => 0,
                'is_reply' => 1,
                'is_delete' => 0
            );
            $this->db->insert('tbl_staff_message_read_reply', $read_reply_data);
            return $insert_id;
        }
        return false;
    }

    public function delete_message($msg_id = 0){
        $this->db->trans_start();

        $this->db->where('job_message_id', $msg_id);
        $this->db->delete('tbl_staff_message_read_reply');

        $this->db->where('upper_message_id', $msg_id);
        $this->db->delete('tbl_job_message');

        $this->db->where('id', $msg_id);
        $this->db->delete('tbl_job_message');

        $this->db->trans_complete();
        if($this->db->trans_status()===true){
            return TRUE;
        }
       return FALSE;
    }



    /**
     * Fetch job data
     * @param type $id
     * @return type
     */
    public function getJobDetail($job_id = "", $user_id = "")
    {
        $response = array();
        $res = $this->db->select('id,job_full_address,job_street,job_postcode_id,meeting_full_address,meeting_street,meeting_postcode_id,description,book_amount,quote_id')
            ->from('tbl_job')
            ->where('id', $job_id)
            ->where('customer_user_id', $user_id)
            ->get()->row();

        if (!empty($res)) {
            $response['job_detail'] = $res;
            $id = $res->id;
            $this->db->select('jd.id,jd.required_number,s.name as skill, l.name as level,jd.start_date,jd.total_cost')->from('tbl_job_detail as jd');
            $this->db->join('tbl_con_skill as s', 'jd.skill_id = s.id');
            $this->db->join('tbl_con_level as l', 'jd.level_id = l.id');
            $this->db->where('jd.job_id', $id);
            $skills = $this->db->get()->result();

            $job_detail_id = array();
            $total_cost = 0;
            if (!empty($skills)) {
                $skills_array = array();

                foreach ($skills as $s) {
                    $job_detail_id[] = $s->id;
                    $array = array(
                        'start_date' => $s->start_date,
                        'skill' => $s->skill,
                        'level' => $s->level,
                        'number' => $s->required_number,
                        'total_cost' => is_null($s->total_cost) ? 0 : $s->total_cost
                    );
                    $skills_array[] = $array;

                }

                $response['skills'] = $skills_array;
            }
            $response['total_amount'] = ($res->book_amount == null) ? 0 : $res->book_amount;
            if (!empty($job_detail_id)) {
                $break_deatil = $this->db->select('start_time,end_time')->from('tbl_job_detail_break')->where_in('job_detail_id', $job_detail_id)->get()->result();
                if (!empty($break_deatil)) {
                    $response['break_detail'] = $break_deatil;
                }
            }
        }
        return $response;
    }

    public function get_skills($term = "", $industry_id = "")
    {
        $this->db->select('s.id,s.name')->from('tbl_con_skill as s');
        $this->db->join('tbl_con_industry_skill as si', 's.id = si.skill_id');
        $this->db->join('tbl_con_industry as i', 'si.industry_id = i.id');
        if (!empty($term)) {
            $where = "(s. name like '$term%')";
            $this->db->where($where);
        }
        $this->db->where('si.status', 1);
        $this->db->limit(20);

        $res = $this->db->get()->result();

        return $res;
    }

    public function get_levels($term = "", $skill_id = "")
    {

        $this->db->select('l.id,l.name')->from('tbl_con_level as l');
        $this->db->join('tbl_con_skill_level as sl', 'sl.level_id = l.id');
        $this->db->where('sl.skill_id', $skill_id);
        if (!empty($term)) {
            $where = "(l. name like '$term%')";
            $this->db->where($where);
        }
        $this->db->order_by('l.name');
        $res = $this->db->get()->result();

        return $res;
    }

    public function get_supervisors($user_id = "", $term = "", $offset = 0)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS u.id, u.id,u.name,u.email,u.phone_number,u.full_address, IFNULL(u.profile_image,"") as profile_image', FALSE)->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('ug.group_id', '5');
        $this->db->where('u.customer_user_id', $user_id);
        if (!empty($term)) {
            $where = "(name like '$term%' OR email like 'term%')";
            $this->db->where($where);
        }
        //if(!empty($offset)){
        $this->db->limit(10, $offset);
        //}
        $this->db->order_by('u.name');
        $res['res'] = $this->db->get()->result();
        $res['total'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;


        return $res;
    }

    public function check_valid_supervisor($id = "", $user_id = "")
    {
        $this->db->select('u.id,u.name')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('ug.group_id', '5');
        $this->db->where('u.id', $id);
        $this->db->where('u.customer_user_id', $user_id);
        $res = $this->db->get()->row();

        return $res;
    }

    public function get_user_info($id = "")
    {
        $this->db->select('u.id,u.name,u.email,u.phone_number,u.full_address')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('u.id', $id);
        $res = $this->db->get()->row();

        return $res;
    }

    public function check_job($job_id = "", $user_id = "")
    {
        $this->db->select('id')->from('tbl_job');
        $this->db->where('customer_user_id', $user_id);
        $this->db->where('id', $job_id);
        $res = $this->db->count_all_results();
        if ($res > 0) {
            return true;
        }
        return false;
    }

    public function set_job_supervisors($job_id = "", $supervisor_id = "")
    {
        $this->db->where('id', $job_id);
        $this->db->update("tbl_job", array('supervisor_user_id' => $supervisor_id));
    }

    public function get_admin_ids()
    {
        $this->db->select('u.id')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('ug.group_id', 1);
        $res = $this->db->get()->result();
        $ret = array();
        if (!empty($res)) {
            foreach ($res as $r) {
                $ret[] = $r->id;
            }
        }
        return $ret;
    }

    public function get_hourly_rate($skill_id = "", $level_id = "")
    {
        $this->db->select('rate')->from('tbl_con_skill_level');
        $this->db->where('skill_id', $skill_id);
        $this->db->where('level_id', $level_id);
        $res = $this->db->get()->row();
        if (isset($res->rate)) {
            return $res->rate;
        }
        return 0;
    }

    function book_job($job_id = "")
    {
        if (!empty($job_id)) {
            $booking_number = $this->generate_booking_number();
            $this->db->where('id', $job_id);
            $this->db->update("tbl_job", array('booking_number' => $booking_number, 'status' => 1));

            return $booking_number;
        }
    }

    public function generate_booking_number()
    {

        while (1) {

            $booking_number = $this->generateRandomString(10, 'num');
            $this->db->select('booking_number')->from('tbl_job');
            $this->db->where('booking_number', $booking_number);
            $count = $this->db->count_all_results();

            if ($count < 1) {
                break;
            }
        }
        return $booking_number;

    }


    public function generate_quote_id($job_id = "")
    {

        while (1) {

            $quote_id = $this->generateRandomString();
            $this->db->select('quote_id')->from('tbl_job');
            $this->db->where('quote_id', $quote_id);
            $count = $this->db->count_all_results();

            if ($count < 1) {
                break;
            }
        }

        return $quote_id;

        // $paded = str_pad($randomNumber, 9, '0', STR_PAD_LEFT);

        // $delimited = '';

        // for($i = 0; $i < 9; $i++) {
        //     $delimited .= $paded[$i];
        //     if($i == 2 || $i == 5) {
        //         $delimited .= '-';
        //     }
        // }
        // $delimited; 

    }

    public function generateRandomString($length = 10, $char = '')
    {
        //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($char == 'num') {
            $characters = '0123456789';
        }
        if ($char == 'alphanum') {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function get_industry($term = "", $user_id = "")
    {
        $this->db->select('id,name')->from('tbl_con_industry');
        if (!empty($term)) {
            $where = "(name like '$term%')";
            $this->db->where($where);
        }
        $this->db->where('status', 1);
        $this->db->limit(20);

        $res = $this->db->get()->result();

        return $res;
    }

    public function get_meeting_place($term = "", $user_id = "")
    {
        $this->db->select('meeting_full_address,meeting_street,meeting_postcode_id,meeting_lat,meeting_long')->from('tbl_job');
        if (!empty($term)) {
            $where = "(meeting_full_address like '$term%')";
            $this->db->where($where);
        }
        $this->db->where('customer_user_id', $user_id);
        $res = $this->db->get()->result();
        return $res;
    }

    public function get_job($job_id = "", $user_id = "")
    {
        $this->db->select('*')->from('tbl_job');
        $this->db->where('customer_user_id', $user_id);
        $this->db->where('id', $job_id);
        $res = $this->db->get()->row();
        return $res;
    }

    public function generate_induction_number()
    {

        while (1) {

            $number = $this->generateRandomString(10, 'alphanum');
            $this->db->select('induction_number')->from('tbl_job_induction');
            $this->db->where('induction_number', $number);
            $count = $this->db->count_all_results();

            if ($count < 1) {
                break;
            }
        }
        return $number;
    }

    public function set_induction($array = array())
    {
        $job_id = isset($array['job_id']) ? $array['job_id'] : 0;
        if (!empty($job_id)) {
            $this->db->select('*')->from('tbl_job_induction')->where('job_id', $job_id);
            $res = $this->db->get()->row();
            if (!empty($res)) {
                $this->db->where('id', $res->id);
                $this->db->update("tbl_job_induction", $array);
                return $res->id;
            } else {
                $id = $this->db->insert("tbl_job_induction", $array);
                return $id;
            }
        }
        return false;
    }

    public function get_qualifications($user_id = "")
    {
        $ids = $this->get_admin_ids();
        $ids[] = $user_id;

        $this->db->select('id,name,code')->from('tbl_con_qualification');
        $this->db->where_in('entered_by', $ids);
        $this->db->limit(20);
        $this->db->where('status', 1);
        $res = $this->db->get()->result();

        return $res;
    }

    public function get_attributes($user_id = "")
    {
        $ids = $this->get_admin_ids();

        $this->db->select('id,name,is_mandatory')->from('tbl_con_priority');
        $this->db->where_in('entered_by', $ids);
        $this->db->limit(20);
        $this->db->where('status', 1);
        $res = $this->db->get()->result();

        return $res;
    }

    public function set_attributes($attributes = array(), $job_id = "")
    {
        if (!empty($attributes)) {
            $arr = explode(',', $attributes);
            if (!empty($arr)) {
                $batch = array();
                foreach ($arr as $k => $v) {
                    $batch[] = array(
                        'job_id' => $job_id,
                        'priority_id' => $v,
                        'order' => $k + 1
                    );
                }
                $this->db->where('job_id', $job_id)->delete('tbl_job_attribute');
                $res = $this->db->insert_batch('tbl_job_attribute', $batch);
                return $res;
            }
        }
    }

    public function get_job_skills($id = "")
    {
        $this->db->select('jd.id,jd.required_number,jd.total_hour, jd.start_date, s.name as skill, l.name as level,jd.start_date')->from('tbl_job_detail as jd');
        $this->db->join('tbl_con_skill as s', 'jd.skill_id = s.id');
        $this->db->join('tbl_con_level as l', 'jd.level_id = l.id');
        $this->db->where('jd.job_id', $id);
        $res = $this->db->get()->result();
        return $res;

    }

    public function generate_quote($job_id = "")
    {
        $res = $this->db->select('quote_id')->from('tbl_job')->where('id', $job_id)->get()->row();
        if (isset($res->quote_id) && !empty($res->quote_id)) {
            return $res->quote_id;
        } else {
            $quote_id = $this->generate_quote_id();
            $array = array('quote_id', $quote_id);
            $this->db->where('id', $job_id);
            $this->db->update("tbl_job", $array);
            return $quote_id;
        }
    }

    public function check_google_login($google_id = "", $email = "")
    {
        $this->db->select('u.id,u.password')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('google_id', $google_id);
        $this->db->where('ug.group_id', CUSTOMER);
        $this->db->where('u.email', $email);
        $res = $this->db->get()->row();
        return $res;
    }

    public function check_customer_fb($facebook_id = "", $email = "")
    {
        $this->db->select('u.id,u.password')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('facebook_id', $facebook_id);
        $this->db->where('ug.group_id', CUSTOMER);
        if (!empty($email)) {
            $this->db->where('u.email', $email);
        }
        $res = $this->db->get()->row();
        return $res;
    }

    public function check_if_customer($email = "")
    {
        $this->db->select('u.id,u.name,u.email,u.full_address,u.profile_image')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('ug.group_id', CUSTOMER);
        $this->db->where('u.email', $email);

        $res = $this->db->get()->row();

        return $res;
    }

    public function check_customer_by_id($id = "")
    {
        $this->db->select('u.id,register_from')->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('ug.group_id', CUSTOMER);
        $this->db->where('u.id', $id);

        $res = $this->db->get()->row();

        return $res;
    }

    public function get_customer_attributes($user_id = "")
    {
        $this->db->select('cp.order,p.name')->from('tbl_customer_priority as cp');
        $this->db->join('tbl_con_priority as p', 'p.id=cp.priority_id');
        $this->db->where('cp.customer_user_id', $user_id);
        $this->db->order_by('cp.order');
        $res = $this->db->get()->result();

        return $res;
    }

    public function get_customer_profile($user_id = "")
    {
        $this->db->select('u.name,u.email,u.full_address,u.phone_number,u.description,i.default_break_time,u.profile_image')->from('tbl_user as u');
        $this->db->join('tbl_customer_add_info as i', 'u.id = i.customer_user_id', 'left');
        $this->db->where('u.id', $user_id);
        $res = $this->db->get()->row();
        return $res;
    }

    public function set_customer_attributes($attributes = array(), $user_id = "")
    {
        if (!empty($attributes)) {
            $arr = explode(',', $attributes);
            if (!empty($arr)) {
                $batch = array();
                foreach ($arr as $k => $v) {
                    $batch[] = array(
                        'customer_user_id' => $user_id,
                        'priority_id' => $v,
                        'order' => $k + 1
                    );
                }
                $this->db->where('customer_user_id', $user_id)->delete('tbl_customer_priority');
                $res = $this->db->insert_batch('tbl_customer_priority', $batch);
                return $res;
            }
        }
    }

    public function get_lhc_customer_association($customer_user_id = "")
    {
        $query = $this->db->select('lca.lhc_user_id,u.name,u.email,u.full_address')
            ->from('tbl_lhc_customer_association as lca')
            ->join('tbl_user as u', 'u.id = lca.lhc_user_id')
            ->where('lca.customer_user_id', $customer_user_id)
            ->where('lca.status', 1);
        $res = $query->get()->result();

        $ret = new stdClass();
        $ret->lhcs = $res;
        $ret->count = count($res);
        return $ret;
    }

    public function check_lhc_customer_association($lhc_user_id = "", $customer_user_id = "")
    {
        $res = $this->db->select('id')
            ->from('tbl_lhc_customer_association')
            ->where('lhc_user_id', $lhc_user_id)
            ->where('customer_user_id', $customer_user_id)
            ->where('status', 1)
            ->get()
            ->num_rows();
        if ($res > 0) {
            return true;
        }
        return false;
    }

    public function set_lhc_customer_association($lhc_user_id = "", $customer_user_id = "")
    {
        $res = $this->db->select('u.id')->from('tbl_user as u')->where('id', $lhc_user_id)->get()->row();
        if (!empty($res)) {
            $array = array('lhc_user_id' => $lhc_user_id);
            $this->db->where('user_id', $customer_user_id);
            $this->db->update("keys", $array);
            return $res->id;
        }
        return false;
    }

    public function get_jobs_oldw($user_id = "", $lhc_user_id = 0)
    {
        $this->db->select('j.id,j.job_full_address,j.job_street,j.job_postcode_id,
            j.meeting_full_address,j.meeting_street,j.meeting_postcode_id,j.description,j.entered_date,s.name')
            ->from('tbl_job as j')
            ->join('tbl_job_detail as jd', 'jd.id = j.id')
            ->join('tbl_con_skill as s', 'jd.skill_id = s.id')
            ->where('j.customer_user_id', $user_id);
        if (!empty($lhc_user_id)) {
            $this->db->where('j.lhc_user_id', $lhc_user_id);
        }
        $this->db->order_by('j.entered_date', 'desc');
        $res = $this->db->get()->result();
        return $res;
    }


    public function get_jobs_mmm($user_id = "", $lhc_user_id = 0, $date = 0, $specific_date = false)
    {
        $all_jobs = array();
        $this->db->select('j.id as job_id,j.job_full_address,j.status as job_status,j.shift_status')
            ->from('tbl_job as j')
            ->where('j.customer_user_id', $user_id);
        if (!empty($lhc_user_id)) {
            $this->db->where('j.lhc_user_id', $lhc_user_id);
        }
        $jobs = $this->db->get()->result();
        $job_ids = array();
        if (!empty($jobs)) {
            foreach ($jobs as $j) {
                $job_ids[] = $j->job_id;
            }
        }
        if (!empty($job_ids)) {
            $this->db->select('jd.job_id,jd.start_date,jd.start_time,jd.end_time,s.name as skill')
                ->from('tbl_job_detail as jd')
                ->join('tbl_con_skill as s', 'jd.skill_id = s.id')
                ->where_in('jd.job_id', $job_ids)
                ->order_by('jd.start_date', 'asc');
            $shifts = $this->db->get()->result();
            $job_shifts = array();
            $skills_array = array();
            if (!empty($shifts)) {
                foreach ($shifts as $s) {
                    $job_shifts[$s->job_id][] = $s;
                    $skills_array[$s->job_id][] = $s->skill;
                }
            }
        }
        if (!empty($jobs)) {
            foreach ($jobs as $j) {
                if (isset($job_shifts[$j->job_id]) && isset($skills_array[$j->job_id])) {
                    $job = new stdClass();
                    $job = $j;
                    $job->start_date = $job_shifts[$j->job_id][0]->start_date;
                    $job->end_date = end($job_shifts[$j->job_id])->start_date;
                    $job->skills = $skills_array[$j->job_id];
                    $all_jobs[] = $job;
                }
            }
        }
        if (!empty($all_jobs)) {
            usort($all_jobs, function ($a, $b) {
                return strtotime($a->start_date) - strtotime($b->start_date);
            });
        }
        return $all_jobs;
    }

    public function get_jobs($user_id = "", $lhc_user_id = 0, $date = 0, $specific_date = false)
    {
        $this->db->select('j.id as job_id,jd.id as shift_id, j.job_full_address,j.job_street,jd.shift_status,
            jd.start_date,jd.start_time,jd.end_time,s.name as skill,j.status as job_status')
            ->from('tbl_job_detail as jd')
            ->join('tbl_con_skill as s', 'jd.skill_id = s.id')
            ->join('tbl_job as j', 'j.id = jd.job_id')
            ->where('j.customer_user_id', $user_id);
        if (!empty($lhc_user_id)) {
            $this->db->where('j.lhc_user_id', $lhc_user_id);
        }
        if (!empty($date)) {
            $timestamp = strtotime($date);
            $start = date('Y-m-01', $timestamp);
            $end = date('Y-m-t', $timestamp);
            if ($specific_date) {
                $start = date('Y-m-d', $timestamp);
                $end = date('Y-m-d', $timestamp);
            }
            $this->db->where('DATE(jd.start_date) >=', $start);
            $this->db->where('DATE(jd.start_date) <=', $end);
        }
        $this->db->limit(20);
        $this->db->group_by('jd.id');
        $this->db->order_by('jd.start_date', 'asc');

        $res = $this->db->get()->result();

        return $res;

        $shifts = array();
        $final_shifts = array();
        if (!empty($res)) {
            foreach ($res as $shift) {
                $shifts[$shift->start_date][] = $shift;
            }
            foreach ($shifts as $s) {
                $a = array(
                    'date' => $s[0]->start_date,
                    'jobs' => $s
                );
                $final_shifts[] = $a;
            }
        }
        return $final_shifts;
    }

    function get_peak_activity_level()
    {
        $this->db->select('peak_price_factor,filling_probability')->from('tbl_con_activity_level')->where('type', 'Peak');
        $res = $this->db->get()->result();
        return $res;
    }

    public function get_supervisor_info($id = "", $user_id = "")
    {
        $this->db->select('u.id,u.name,u.email,u.phone_number,u.full_address,IFNULL(u.profile_image,"") as profile_image', FALSE)->from('tbl_user as u');
        $this->db->join('tbl_user_group as ug', 'u.id = ug.user_id');
        $this->db->where('ug.group_id', SUPERVISOR);
        $this->db->where('u.customer_user_id', $user_id);
        $this->db->where('u.id', $id);
        $res = $this->db->get()->row();

        return $res;
    }

    public function validate_shift($shift_id, $user_id)
    {
        $this->db->select('jd.id,j.status')
            ->from('tbl_job_detail as jd')
            ->join('tbl_job as j', 'jd.job_id = j.id')
            ->where('jd.id', $shift_id)
            ->where('j.customer_user_id', $user_id);
        $res = $this->db->get()->row();
        return $res;
    }

    public function delete_shift($shift_id = "")
    {

        if ($this->db->where('job_detail_id', $shift_id)->delete('tbl_job_detail_break')) {
            return $this->db->where('id', $shift_id)->delete('tbl_job_detail');
        }

    }

    public function delete_job($job_id = "")
    {
        if ($this->delete_job_detail($job_id)) {
            return $this->db->where('id', $job_id)->delete('tbl_job');
        }
        return false;
    }

    public function delete_job_detail($job_id = "")
    {
        $res = $this->db->select('id')->from('tbl_job_detail')->where('job_id', $job_id)->get()->result();
        $detail_ids = array();
        if (!empty($res)) {
            foreach ($res as $r) {
                $detail_ids[] = $r->id;
            }

        }
        $res = $this->db->select('id')->from('tbl_job_alert')->where_in('job_detail_id', $detail_ids)->get()->row();
        if (!empty($res)) {
            return false;
        }

        if (!empty($detail_ids)) {
            $this->db->where_in('job_detail_id', $detail_ids)->delete('tbl_job_detail_break');
            return $this->db->where_in('id', $detail_ids)->delete('tbl_job_detail');
        }

    }

    public function validate_job($job_id, $user_id)
    {
        $this->db->select('j.id,j.status')
            ->from('tbl_job as j')
            ->join('tbl_job_detail as jd', 'jd.job_id = j.id')
            ->where('j.id', $job_id)
            ->where('j.customer_user_id', $user_id);
        $res = $this->db->get()->row();
        return $res;
    }

    /**
     * Get Timesheet for supervisor
     * @param $supervisorUserId
     * @param $jobId
     * @return mixed
     */
    public function get_timesheet($supervisorUserId, $jobId)
    {
        $timesheets = $this->db->select("tjs.job_detail_id as job_detail_id, tj.id as job_id, tjs.staff_user_id, tjs.id as job_staff_id, tjs.is_approved")
            ->join('tbl_job_detail as tjd', 'tjd.id = tjs.job_detail_id', 'inner')
            ->join('tbl_job as tj', 'tj.id = tjd.job_id', 'inner')
            ->where("(tj.supervisor_user_id = {$supervisorUserId} OR tj.customer_user_id = {$supervisorUserId}) AND tjs.job_status =" . JOB_STAFF_STATUS_COMPLETED)
            ->where('tj.id', $jobId)
            ->order_by('start_time', 'desc')
            ->order_by('end_time', 'desc')
            ->get('tbl_job_staff as tjs')->result();
        if ($timesheets) {
            $this->load->model('staff_webservice/staff_model');
            foreach ($timesheets as $timesheet) {
//                $overview = $this->staff_model->get_total_break($timesheet->job_detail_id, $timesheet->job_staff_id);
//                $timesheet->staffInfo = $this->staff_model->get_staff_details($timesheet->staff_user_id);
//                $timesheet->staffInfo = $this->staff_model->get_job_staff_details($timesheet->job_staff_id);
//                $timesheet->staffTimeSheet = $this->staff_model->get_job_staff_overview($timesheet->job_detail_id, $timesheet->job_staff_id);
//                $totalTime = new DateTime($timesheet->staffTimeSheet->hours['start']);
//                $totalTime = $totalTime->diff(new DateTime($timesheet->staffTimeSheet->hours['end']));
//                $timesheet->staffInfo->break_time = $overview['total_break_time'];
//                $timesheet->staffInfo->total_time = $totalTime->format('%H:%I:%S');
//                $timesheet->staffTimeSheetDetails = $this->staff_model->get_job_staff_breaks_as_timesheet($timesheet->job_detail_id, $timesheet->job_staff_id);
                $overview = $this->staff_model->get_total_break($timesheet->job_detail_id, $timesheet->job_staff_id);
                $timesheet->staffTimeSheet = $this->staff_model->get_job_staff_overview($timesheet->job_detail_id, $timesheet->job_staff_id);
                $timesheet->staffInfo = $this->staff_model->get_job_staff_details($timesheet->job_staff_id);
                $timesheet->staffInfo->break_time = $overview['total_break_time'];
                $timesheet->staffInfo->total_time = ($timesheet->staffTimeSheet->hours['end'] - $timesheet->staffTimeSheet->hours['start']);
                $timesheet->staffTimeSheetDetails = $this->staff_model->get_job_staff_timesheet_details($timesheet->job_detail_id, $timesheet->job_staff_id);
            }
        }
        return $timesheets;
    }


}
