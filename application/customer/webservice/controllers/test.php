<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MY_Model { 
     public function __construct(){
        parent::__construct();
     }

     public function setshiftStatus(){
          $res = $this->db->select('*')->from('tbl_job_detail')->get()->result();
          foreach ($res as $r) {
               $res = $this->db->query("CALL sp_setShiftStatus(?)", array($r->id));
               $this->db->freeDBResource();
          }
          $res = $this->db->select('*')->from('tbl_job')->get()->result();
          foreach ($res as $r) {
               $res = $this->db->query("CALL sp_setJobShiftStatus(?)", array($r->id));
               $this->db->freeDBResource();
          }
     }

     public function index($id=147){
          $this->load->model('email_model');
          $this->email_model->send_supervisor_pasword_link('pratik.desar@ebpearls.com',array('user_name'=>'pratik','link'=>'asdf'));
          $res = $this->db->query("CALL sp_setShiftStatus(?)", array($id));
          $this->db->freeDBResource();
          dd($res->row());
    }

     public function jobStatus($id=0){

          $res = $this->db->query("CALL sp_setJobShiftStatus(?)", array($id));
          $this->db->freeDBResource();
          dd($res->row());
     }
 
} 

 /*   SELECT player_name,
       weight,
       CASE WHEN weight > 250 THEN 'over 250'
            WHEN weight > 200 THEN '201-250'
            WHEN weight > 175 THEN '176-200'
            ELSE '175 or under' END AS weight_group
  FROM benn.college_football_players
*/
/*

BEGIN
  DECLARE shift_status INT DEFAULT 4;

  SELECT  
    if(CURDATE() > jd.start_date AND COUNT(js.id) = required_number, "1", "1") as status, 
    if(CURDATE() > jd.start_date AND COUNT(js.id) < required_number, "1", "2") as status, 
    if(CURDATE() < jd.start_date AND COUNT(js.id) = required_number, "1", "3") as status,
    if(CURDATE() < jd.start_date AND COUNT(js.id) < required_number, "1", "4") as status, jd.shift_status
  FROM (`tbl_job_detail` as jd, `tbl_job_staff` as js)
  WHERE `jd`.`id` =  job_detail_id
  AND `js`.`is_approved` =  1
  AND `js`.`job_detail_id` =  job_detail_id
  LIMIT 1
  INTO shift_status;

  SELECT shift_status AS `shift_status`;

END


  1. Past/All Shifts Locked - Greyscale 
  2. Past/Not All Shifts Locked - Orange 
  3. Present/Future/All Shifts Filled - Navy 
  4. Present/Future/Not All Shifts Filled - Red(Alert)

  BEGIN
  DECLARE ret INT DEFAULT 0;
  DECLARE userid123 INT DEFAULT 0 ;
  DECLARE isactive INT DEFAULT 0 ;
  DECLARE firstlogin TINYINT;
  DECLARE firstloginflag INT;
  DECLARE pQuery VARCHAR(500);
  
  
    SELECT id, `active` FROM `users` WHERE email = uEmail LIMIT 1 INTO userid123, isactive  ;
    IF isactive =1 THEN 
      SET @ret = 1 ;
      INSERT INTO `device_info` (`user_id`,`device_id`,`hash_code`,`device_type`) 
      VALUES(userid123, deviceId, hashcoded, deviceType) 
      ON DUPLICATE KEY UPDATE `hash_code` = hashcoded ;
     
      IF NOT EXISTS (SELECT id FROM `keys` WHERE user_id = userid123) THEN
      INSERT INTO `keys` (`user_id`,`key`) VALUES (userid123,UUID()) ON DUPLICATE KEY UPDATE `key` = UUID();
      ELSE
      UPDATE `keys` SET `key` = UUID() WHERE user_id = userid123;
      
      END IF;
      
      
      SELECT `users`.`first_name`,users.`last_name`, `users`.email, `users`.`id` AS user_id,users.`phone`,`keys`.`key`,users.`profile_image`,
      device_info.`device_id`, device_info.`hash_code`, @ret AS `status`
      FROM `device_info` 
      JOIN `users` ON `users`.`id` = userid123
      JOIN `keys` ON users.`id`= `keys`.`user_id`
      WHERE `device_info`.`user_id` = userid123 AND `device_id` = deviceId AND `device_type` = deviceType ;
      
    ELSE -- not active --
      SET @ret = 2 ;
      SELECT @ret AS `status` ;
    END IF ;
    
  
  SELECT @pQuery;
    END
wr_cal :
BEGIN
  -- DECLARE SECTION 
  DECLARE ret INT DEFAULT 0 ; -- 0-RECORD NOT FOUND,2=COUNTRY OR REGION NOT FOUND,1= WAGE CALCULATED
  DECLARE job_start_time TIME ;
  DECLARE job_end_time TIME ;
  DECLARE cal_time VARCHAR (8) ;
  DECLARE job_start_date DATE ;
  DECLARE special_wage DECIMAL (6, 2) ;
  DECLARE work_hour_wage DECIMAL (6, 2) ;
  DECLARE country_id INT DEFAULT 0 ;
  DECLARE country_region_id INT DEFAULT 0;
  DECLARE job_detail_query_val INT DEFAULT 0 ;
  DECLARE job_skill_id,job_level_id,job_required_number INT ;
  DECLARE query_times INT DEFAULT 0;
  DECLARE l00p_start_time TIME;
  DECLARE l00p_end_time TIME;
  DECLARE time_slot VARCHAR(500);
  DECLARE total_wage DECIMAL(6,2) DEFAULT 0.00;
  DECLARE wage_rate DECIMAL(6,2);
  DECLARE job_detail_id_val INT;
  DECLARE tmp_session_id VARCHAR(128);
  declare interval_time varchar(10);
  -- Declare continue handler for 1329 ;
   
  --  fetch data from job detail
  
   DECLARE job_detail_query CURSOR FOR SELECT  id,skill_id,level_id,required_number,start_date,start_time,
	     
	     end_time
	  FROM
	    tbl_job_detail job_detail 
	  WHERE job_id = p_job_id ;
	  
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET job_detail_query_val = 1 ;
   -- store unique session id in table
   SET tmp_session_id = UUID();
	  
  -- fetch  country && state
  SELECT 
    cr.country_id,
    cr.id 
  FROM
    tbl_job job,
    tbl_postcodes_geo pg,
    tbl_country_region cr 
  WHERE job.job_postcode_id = pg.id 
    AND pg.state = cr.short_name 
    AND job.id = p_job_id INTO country_id,
    country_region_id ;
	
  --   if country region is  not found
  IF country_id = 0 || country_region_id = 0 THEN
	 SET 	ret = 2;
	 SELECT ret AS STATUS,total_wage;
	  LEAVE wr_cal;
  END IF;
  -- loop job details
  
   OPEN job_detail_query ;
  query_loop :
  LOOP
    FETCH job_detail_query INTO job_detail_id_val,job_skill_id,job_level_id,job_required_number,job_start_date,job_start_time,job_end_time;
    
    IF job_detail_query_val = 1  THEN
        -- SET 	ret = 0;
	-- SELECT ret AS STATUS,total_wage;
     LEAVE query_loop ;
     
    END IF ;
         -- Loop  to split the time into one hour interval  
         
        SET     l00p_end_time = job_start_time;
        SET time_slot =  '';
		   WHILE  TIME_TO_SEC(l00p_end_time) < TIME_TO_SEC(job_end_time) DO
			  
			  SET l00p_start_time = l00p_end_time;
			  set interval_time = '';
			   select time_to_sec(timediff(job_end_time,l00p_start_time)) into interval_time;
			   -- difference is less than 1 hour
			   set interval_time = interval_time/60; -- min 
			   -- assumption it will not be less than sec (Handle Later)
			   if interval_time < 60 then
			   set interval_time = sec_to_time(interval_time*60);
			   SET l00p_end_time = ADDTIME(l00p_end_time,interval_time);
			   
			   
			   set interval_time = round(time_to_sec(interval_time)/3600,2);
			    
			   else
			  SET l00p_end_time = ADDTIME(l00p_end_time,'01:00:00');
			  SET interval_time = 1;
			   end if;
			   
			   
			  
			 
			  -- do calculation here
			-- SET  time_slot = CONCAT(time_slot,l00p_start_time,',');
			-- SET  time_slot = CONCAT(time_slot,l00p_end_time,',');
			 
			-- select job_skill_id,job_level_id,job_start_date;
			 
		 SELECT `calAllWR`(job_skill_id,job_level_id,job_start_date,l00p_start_time,l00p_end_time,country_id,country_region_id,tmp_session_id,job_detail_id_val,job_required_number,interval_time) INTO wage_rate;
		 -- select wage_rate,l00p_end_time,job_end_time;
			 -- insert into tbl_wr_tmp_cal set wage=wage_rate,job_detail_id=job_detail_id_val,time_from=l00p_start_time,time_to=l00p_end_time,uuid_val=tmp_session_id,job_date=job_start_date;
		  -- set total_wage = @total_wage + wage_rate;
		    
			  
		  END WHILE;
		   
		 
	
     
     
        
  END LOOP ;
    -- select sum of wage
  SET ret = 1;
  -- save wage rate 
  CALL saveJobDetailWR(tmp_session_id,@ret); 
  select ifnull(SUM(wage*required_number),'0.00') into total_wage   FROM tbl_wr_tmp_cal WHERE uuid_val=tmp_session_id; 
  -- update book amount in tbl_job
  
  update tbl_job set book_amount = total_wage where id = p_job_id;
  
  SELECT ret AS STATUS,total_wage;
  
  CLOSE job_detail_query; 
  
  
  
END


   BEGIN
     DECLARE ret INT DEFAULT 0;
     DECLARE userid123 INT DEFAULT 0 ;
     DECLARE isactive INT DEFAULT 0 ;
     DECLARE firstlogin TINYINT;
     DECLARE firstloginflag INT;
     DECLARE pQuery VARCHAR(500);
     DECLARE refKeyId INT DEFAULT 0;
     DECLARE oUUID VARCHAR(500);
    
     DECLARE UUID VARCHAR(500);
     SET UUID = UUID();
     SET oUUID = 0;
    
     SELECT
          tbl_user.id,
          tbl_user.`active`
     FROM `tbl_user`
          INNER JOIN tbl_user_group ON tbl_user.id = tbl_user_group.user_id
     WHERE email = uEmail AND tbl_user_group.group_id = userGroup
     LIMIT 1
     INTO userid123, isactive;

     IF isactive =1 THEN 
          SET @ret = 1;
          SELECT `token` FROM tbl_device_info WHERE `device_id` = deviceId AND `group_id` = userGroup INTO oUUID;
          IF oUUID = 0 THEN
               SET @ret = 1 ;
          ELSE
               DELETE FROM `keys` WHERE `key` = oUUID;
          END IF;
          DELETE FROM `tbl_device_info` WHERE `device_id` = deviceId AND `group_id` = userGroup ;
          INSERT INTO `tbl_device_info` (`user_id`,`device_id`,`hash_code`,`device_type`,`group_id`,`token`) 
          VALUES(userid123, deviceId, hashcoded, deviceType,userGroup,UUID) 
          ON DUPLICATE KEY UPDATE `hash_code` = hashcoded,`token`=UUID ;
          SELECT `keys`.`id` 
          FROM `keys` 
          JOIN `tbl_device_info` ON `tbl_device_info`.`token` = `keys`.`key` 
          WHERE `keys`.`user_id` = userid123 
          AND `tbl_device_info`.`device_id` = deviceId 
          LIMIT 1 
          INTO refKeyId;
          

          IF refKeyId = 0 THEN
               INSERT INTO `keys` (`user_id`,`key`) VALUES (userid123,UUID) ON DUPLICATE KEY UPDATE `key` = UUID;
               SET refKeyId = LAST_INSERT_ID();
          ELSE
               UPDATE `keys` SET `key` = UUID,`lhc_user_id` = 0 WHERE id = refKeyId;
          END IF;
                 
               
          SELECT `tbl_user`.`name`, 
               `tbl_user`.email, 
               `tbl_user`.`id` AS user_id,
               `keys`.`key`,
               `tbl_user`.`profile_image`,
               `tbl_user`.`full_address`,
               `tbl_user`.`phone_number`,
               `tbl_user`.`register_from`,
               `keys`.`lhc_user_id`,
          tbl_device_info.`device_id`, tbl_device_info.`hash_code`, @ret AS `status`
          FROM `tbl_device_info` 
          JOIN `tbl_user` ON `tbl_user`.`id` = userid123
          JOIN `keys` ON tbl_user.`id`= `keys`.`user_id`
          WHERE `tbl_device_info`.`user_id` = userid123 AND `device_id` = deviceId AND `device_type` = deviceType AND `keys`.`id` = refKeyId;
            
       ELSE -- not active --
            SET @ret = 2 ;
            SELECT @ret AS `status` ;
       END IF ;
       
  
  SELECT @pQuery;
   END
   */