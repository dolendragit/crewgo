<?php
use MongoDB\BSON\Type;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_master_data_view extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Fetch skill data
     * @param type $id
     * @return type
     */
    public function getSkill($id = "", $status = "")
    {
        $this->table_name = 'tbl_con_skill';
        $this->primary_key = 'id';
        $params['select'] = "*";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        if ($status != "") {
            $params['where']['status'] = $status;
        }
        return $skill_data = $this->get($params);

    }

    /**
     * Fetch industry data
     * @param string|type $id
     * @param string $status
     * @return type
     */
    public function getIndustry($id = "", $status = "")
    {
        $this->table_name = 'tbl_con_industry';
        $this->primary_key = 'id';
        $params['select'] = "*";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        if ($status != "") {
            $params['where']['status'] = $status;
        }
        return $skill_data = $this->get($params);

    }

    /**
     * get list of industries mapped with skills
     * @return mixed
     */
    public function getIndustryWithSkills(){
        $query="SELECT ci.id,ci.name FROM tbl_con_industry_skill ind
JOIN tbl_con_industry ci on ci.id = ind.industry_id
Group BY ind.industry_id";
        $getIndustries=$this->db->query($query);
        return $getIndustries->result();
    }

    /**
     * Fetch industry skill data
     * @param string $industry_id
     * @return type object
     * @internal param type $id
     */
    public function getIndustrySkill($industry_id = "")
    {
        $this->db->select('industry_id,skill_id,industry.name AS industry_name,skill.name AS skill_name')
            ->from('tbl_con_industry_skill as in_sk')
            ->join('tbl_con_industry as industry', 'in_sk.industry_id = industry.id')
            ->join('tbl_con_skill as skill', 'in_sk.skill_id = skill.id');
        if ($industry_id != "") {
            $this->db->where('in_sk.industry_id', $industry_id);
        }

        $industry_skill_query = $this->db->get();


        return $industry_skill_query->result();
    }

    /**
     * Fetch skill subskill/level data
     * @param null $skill_id
     * @param null $level_id
     * @param string $skill_level
     * @return type
     * @internal param type $id
     */
    public function getSkillSubskill($skill_id = NULL, $level_id = NULL, $skill_level = 'skill')
    {
        $this->db->select('ci.name as industry_name,skill_id,level_id,skill.name AS skill_name,level.name AS level_name, skill.description AS description')
            ->from('tbl_con_skill_level as sk_ssl')
            ->join('tbl_con_industry as ci','sk_ssl.industry_id = ci.id','left')
            ->join('tbl_con_skill as skill', 'sk_ssl.skill_id = skill.id')
            ->join('tbl_con_level as level', 'sk_ssl.level_id = level.id');
        if (!empty($skill_id)) {
            $this->db->where('sk_ssl.skill_id', $skill_id);
        }
        if (!empty($level_id)) {
            $this->db->where('sk_ssl.level_id', $level_id);
        }

        if ($skill_level == "not_configured_skill") {
            $this->db->where("sk_ssl.skill_id not in (SELECT skill_id FROM tbl_wr_special_day where country_id='" . $this->input->post('country_id') . "' and country_region_id='" . $this->input->post('region_id') . "' UNION SELECT skill_id FROM tbl_wr_work_hour where  country_id='" . $this->input->post('country_id') . "' and country_region_id='" . $this->input->post('region_id') . "' UNION SELECT skill_id FROM tbl_wr_overtime  where   country_id='" . $this->input->post('country_id') . "' and country_region_id='" . $this->input->post('region_id') . "' UNION SELECT skill_id FROM  tbl_wr_bk_not_taken where  country_id='" . $this->input->post('country_id') . "' and country_region_id='" . $this->input->post('region_id') . "')");


            $this->db->group_by('sk_ssl.skill_id');

            var_dump($skill_level);
        }
        if ($skill_level == "not_configured_level") {
            $this->db->where("sk_ssl.level_id not in (SELECT level_id FROM tbl_wr_special_day where skill_id='" . $skill_id . "' and country_region_id='" . $this->input->post('country_id') . "' and country_region_id='" . $this->input->post('region_id') . "' UNION SELECT level_id FROM tbl_wr_work_hour  where skill_id='" . $skill_id . "' and country_id='" . $this->input->post('country_id') . "' and country_region_id='" . $this->input->post('region_id') . "' UNION SELECT level_id FROM tbl_wr_overtime where skill_id='" . $skill_id . "'and country_id='" . $this->input->post('country_id') . "' and country_region_id='" . $this->input->post('region_id') . "' UNION SELECT level_Id FROM  tbl_wr_bk_not_taken where skill_id='" . $skill_id . "' and country_id='" . $this->input->post('country_id') . "' and country_region_id='" . $this->input->post('region_id') . "')");


            $this->db->group_by('sk_ssl.level_id');
        }

        if ($skill_level == 'skill') {
            $this->db->group_by('sk_ssl.skill_id');

        }
        if ($skill_level == 'level') {
            $this->db->group_by('sk_ssl.level_id');

        }

        $this->db->order_by('sk_ssl.skill_id', 'asc');
        $this->db->order_by('sk_ssl.level_id', 'asc');

        $skill_subskill_query = $this->db->get();

        if ((!empty($skill_id) && $skill_level == 'skill') || (!empty($level_id) && $skill_level == 'level')) {

            return $skill_subskill_query->row();
        }

        return $skill_subskill_query->result();
    }


    /**
     * Fetch subskill data
     * @param Type|string $id
     * @param string $status
     * @return Type
     */
    public function getSubskill($id = "", $status = "")
    {
        $this->table_name = 'tbl_con_level';
        $this->primary_key = 'id';
        $params['select'] = "*";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        if ($status != "") {
            $params['where']['status'] = $status;
        }
        return $sub_skill_data = $this->get($params);

    }

    /**
     * Fetch ppe data
     * @param Type|string $id
     * @return Type
     */
    public function getPPE($id = "")
    {
        $this->table_name = 'tbl_con_ppe';
        $this->primary_key = 'id';
        $params['select'] = "*";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        return $sub_skill_data = $this->get($params);

    }

    /**
     * Fetch priority data
     * @param Type|string $id
     * @return Type
     */
    public function getPriority($id = "")
    {
        $this->table_name = 'tbl_con_priority';
        $this->primary_key = 'id';
        $params['select'] = "*";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        return $sub_skill_data = $this->get($params);

    }

    /**
     * Fetch qualification data
     * @param Type|string $id
     * @return Type
     */
    public function getQualification($id = "")
    {
        $this->table_name = 'tbl_con_qualification';
        $this->primary_key = 'id';
        $params['select'] = "*";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        return $sub_skill_data = $this->get($params);

    }

    /**
     * Fetch activity level
     * @param type $id
     * @return type
     */
    public function getActivitylevel($id = "")
    {
        $this->table_name = 'tbl_con_activity_level';
        $this->primary_key = 'id';
        $params['select'] = "*,concat_ws(' ',type,name) as activity_level";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        return $sub_skill_data = $this->get($params);

    }

    /**
     * Fetch sociable hour
     * @param Type|string $id
     * @param string $country_region_id
     * @param string $days
     * @param string $except_id
     * @return Type
     */
    public function getSociableHour($id = "", $country_region_id = "", $days = "", $except_id = "")
    {
        $this->db->select('sh.*,(select name from tbl_country where  id=cr.country_id) as country_name,cr.name as region_name');
        $this->db->from('tbl_con_social_hour sh');
        $this->db->join('tbl_country_region cr', 'sh.country_region_id = cr.id');

        if ($id != "") {
            $this->db->where('sh.id', $id);

        }
        if ($days != "") {
            $this->db->where('sh.days', $days);

        }
        if ($except_id != "") {
            $this->db->where('sh.id !=', $except_id);

        }
        if ($country_region_id != "") {
            $this->db->where('sh.country_region_id', $country_region_id);
        }
        $query = $this->db->get();
        if ($id != "")
            return $query->row();
        else
            return $query->result();

    }


    /**
     *
     * @param Type|string $id
     * @param Type|string $country_region_id
     * @param Type|string $except_id
     * @return Type
     */
    public function getCancellation($id = "", $country_region_id = "", $except_id = "")
    {
        $this->db->select('sh.*,(select name from tbl_country where  id=cr.country_id) as country_name,cr.name as region_name');
        $this->db->from('tbl_con_job_cancellation sh');
        $this->db->join('tbl_country_region cr', 'sh.country_region_id = cr.id');

        if ($id != "") {
            $this->db->where('sh.id', $id);

        }

        if ($except_id != "") {
            $this->db->where('sh.id !=', $except_id);

        }
        if ($country_region_id != "") {
            $this->db->where('sh.country_region_id', $country_region_id);
        }
        $query = $this->db->get();
        if ($id != "")
            return $query->row();
        else
            return $query->result();

    }

    /**
     * Fetch country
     * @param Type|string $id
     * @return Type
     */
    public function getCountry($id = "")
    {
        $this->table_name = 'tbl_country';
        $this->primary_key = 'id';
        $params['select'] = "*";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        return $sub_skill_data = $this->get($params);

    }

    /**
     * Fetch region
     * @param Type|string $id
     * @param string $country_id
     * @return Type
     */
    public function getRegion($id = "", $country_id = "")
    {
        $this->table_name = 'tbl_country_region';
        $this->primary_key = 'id';
        $params['select'] = "*,(select name from tbl_country where id=country_id) as country_name,";
        if ($id != "") {
            $params['where']['id'] = $id;
        }
        if ($country_id != "") {
            $params['where']['country_id'] = $country_id;
        }
        return $sub_skill_data = $this->get($params);

    }

    /**
     * get lhc_doc data
     * @param Type|string $id
     * @param Type|string $status
     * @return Type
     */
    public function getLhcDoc($id = "", $status = "")
    {
        if ($id != "") {
            $edit_doc = "SELECT lhc.*,reg.name as region_name,(SELECT name from tbl_country where id=reg.country_id) AS country_name,ind.name as industry_name FROM tbl_con_lhc_doc lhc LEFT JOIN tbl_country_region reg ON lhc.region_id = reg.id LEFT JOIN tbl_con_industry ind ON lhc.industry_id = ind.id WHERE lhc.id = " . $id;
            $run = $this->db->query($edit_doc);

        } else {
            $query = "SELECT lhc.*,reg.name as region_name,(SELECT name from tbl_country where id=reg.country_id) AS country_name,ind.name as industry_name FROM tbl_con_lhc_doc lhc LEFT JOIN tbl_country_region reg ON lhc.region_id = reg.id LEFT JOIN tbl_con_industry ind ON lhc.industry_id = ind.id";
            $run = $this->db->query($query);
        }
        if ($id != "") {
            return $run->row();
        }
        return $run->result();
    }

    /**
     * holidays data
     * @param Type|string $id
     * @param Type|string $status
     * @return Type
     */
    public function getHolidayData($id = "")
    {
        if ($id != "") {
            $edit_hol = "SELECT cal.*,reg.name as region_name,(SELECT name from tbl_country where id=reg.country_id) AS country_name FROM tbl_wr_calendar cal LEFT JOIN tbl_country_region reg ON cal.country_region_id = reg.id WHERE cal.id = " . $id;
            $run = $this->db->query($edit_hol);

        } else {
            $query = "SELECT cal.*,reg.name as region_name,(SELECT name from tbl_country where id=reg.country_id) AS country_name FROM tbl_wr_calendar cal LEFT JOIN tbl_country_region reg ON cal.country_region_id = reg.id";
            $run = $this->db->query($query);
        }
        if ($id != "") {
            return $run->row();
        }
        return $run->result();
    }

    /**
     * shift_type data
     * @param Type|string $id
     * @param Type|string $status
     * @return Type
     */
    public function getShiftData($id = "", $status = "")
    {
        if ($id != "") {
            $query = "SELECT * FROM tbl_wr_shift_type WHERE id = " . $id;
            $run = $this->db->query($query);
        } else {
            $query = "SELECT * FROM tbl_wr_shift_type";
            $run = $this->db->query($query);

        }
        if ($id != "") {
            return $run->row();
        }
        return $run->result();
    }


    /**
     * get the list of customers from the customer_industry table
     * @param $industry_id
     * @return Type
     * @internal param Type $id
     * @internal param Type $status
     */
    public function getIndustryAccess($industry_id)
    {
        if ($industry_id != "") {
            //query for customers from tbl_user for selected industry
            $query = "SELECT u.name as customer,u.id as user_id,ci.*,EXISTS
(SELECT id FROM tbl_customer_industry where industry_id=" . $industry_id . " and has_access=1 and customer_user_id=u.id)
as access
FROM tbl_user u 
LEFT JOIN tbl_customer_industry ci on ci.customer_user_id = u.id and ci.industry_id=" . $industry_id . "
JOIN tbl_user_group ug on u.id = ug.user_id 
where ug.group_id = 3 order by access DESC";
            $run = $this->db->query($query);
        } //if industry_id not received, then error
        else {
            return FALSE;

        }
        return $run->result();
    }


    public function getCalendarYear($get_year_only = 'year')
    {

        $query = " SELECT id,`year`, `date`, `name`
            FROM (`tbl_wr_calendar`)";
        $query .= " WHERE `status` =  1";

        /* AND `year` =  '2016'
         AND `country_id` =  '1'

         AND CASE
    WHEN EXISTS(SELECT id FROM tbl_wr_calendar WHERE country_id='1' AND country_region_id='1' AND YEAR='2016')
         THEN country_region_id='1'
         ELSE country_region_id  IS NULL
       END
             ORDER BY `year` DESC";*/

        if (!empty($this->input->post('year'))) {
            $query .= " AND `year` =  '" . $this->input->post('year') . "'";
        }
        //append post variables
        if (!empty($this->input->post('country_id'))) {
            $query .= " AND `country_id` =  '" . $this->input->post('country_id') . "'";
        }

        if (!empty($this->input->post('region_id')) && $get_year_only != "year") {
            $query .= " AND CASE
	   WHEN EXISTS(SELECT id FROM tbl_wr_calendar WHERE country_id='" . $this->input->post('country_id') . "'
               AND country_region_id='" . $this->input->post('region_id') . "' 
                   AND YEAR='" . $this->input->post('year') . "') 
            THEN country_region_id='" . $this->input->post('region_id') . "' 
            ELSE country_region_id = 0 
          END";

        }
        if ($get_year_only == 'year') {
            $query .= " group by year";
        }

        $query .= " order by year DESC,date DESC";

        $fetch_data = $this->db->query($query);

        return $fetch_data->result();

    }


    /**
     * get wage Shift Type
     */

    public function getWRshiftType($shift_type_id = NULL, $status = 0)
    {
        $this->db->select('id,name,time_from,time_to')->from('tbl_wr_shift_type')
            ->where('status', $status);
        if (!empty($shift_type_id)) {
            $this->db->where('id', $shift_type_id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result();

    }

    /**
     * get the list of industry for industry access select option
     * @return Type
     * @internal param Type $id
     * @internal param Type $status
     */
    public function getAccessIndustry()
    {
        //query for customers from tbl_user
        $query = "SELECT * FROM tbl_con_industry_skill cis LEFT JOIN tbl_con_industry ci on cis.industry_id = ci.id Group by ci.id";
        $run = $this->db->query($query);
        return $run->result();
    }


    public function getWRInterval()
    {
        return $this->db->select('*')->from('tbl_wr_interval')->where('status', 1)->get()->result();
    }

    public function getWRDay()
    {
        return $this->db->select('*')->from('tbl_wr_day')->where('status', 1)->get()->result();
    }


    /**
     * show States list
     * @param Type|string $country_id
     * @return Type
     */
    public function getstates($country_id = "")
    {
        if ($country_id != "") {
            $query = "SELECT con.name as country_name, cr.* FROM tbl_country con JOIN tbl_country_region cr on con.id=cr.country_id WHERE cr.id = " . $country_id;
            $run = $this->db->query($query);

        } else {
            $query = "SELECT con.name as country_name, cr.* FROM tbl_country con JOIN tbl_country_region cr on con.id=cr.country_id";
            $run = $this->db->query($query);


        }
        if ($country_id != "") {
            return $run->row();
        }
        return $run->result();
    }

    /**
     * LP Manage
     * @param string $user_id
     * @return mixed
     */
    public function getLhcCompanies($user_id = "")
    {
        if ($user_id != "") {
            $query = "SELECT u.id as user_id,u.name,u.profile_image,info.*,
GROUP_CONCAT(ci.name SEPARATOR '<br>') as industry_name, u.full_address,u.phone_number,u.email,u.active,u.entered_date 
FROM tbl_user u 
JOIN tbl_user_group ug on u.id = ug.user_id 
LEFT JOIN tbl_lhc_industry li on u.id = li.lhc_user_id
LEFT JOIN tbl_con_industry ci on ci.id = li.industry_id
LEFT JOIN tbl_lhc_add_info info on info.lhc_user_id = u.id
WHERE ug.group_id = 2 AND u.id =" . $user_id . "
Group BY u.id";
            $run = $this->db->query($query);
        } else {
            $query = "SELECT u.id as user_id,u.name,u.profile_image,
GROUP_CONCAT(ci.name SEPARATOR '<br>') as industry_name, u.full_address,u.phone_number,u.email,u.active,u.entered_date 
FROM tbl_user u 
JOIN tbl_user_group ug on u.id = ug.user_id 
LEFT JOIN tbl_lhc_industry li on u.id = li.lhc_user_id
LEFT JOIN tbl_con_industry ci on ci.id = li.industry_id
WHERE ug.group_id = 2 
Group BY u.id";
            $run = $this->db->query($query);
        }
        if ($user_id != "") {
            return $run->row();
        }
        return $run->result();
    }

    /**get the documents as per LP users
     * @param string $id
     * @return null
     */
    public function getLhcUserDoc($id=""){
        if($id!=""){
            $this->db->where('lhc_user_id',$id);
            $query = $this->db->get('tbl_lhc_doc');
            return $query->result();
        }
        else{
            return NULL;
        }
    }

}
