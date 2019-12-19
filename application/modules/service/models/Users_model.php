<?php
class Users_model extends CI_Model {

	function getDropdown_data($where,$offset='',$limit=''){
		
		$res['value_list']        = $this->db->select('valueId,valueName')->order_by('valueName', 'ASC')->where(array('status'=>1))->get(VALUE)->result_array();
        $res['strenght_list']   = $this->db->select('strengthId,strengthName')->order_by('strengthName', 'ASC')->where(array('status'=>1))->get(STRENGTHS)->result_array();
        $res['speciality_list'] = $this->db->select('specializationId,specializationName')->order_by('specializationName', 'ASC')->where($where)->or_where(array('userType'=>'both'))->get(SPECIALIZATIONS)->result_array();
         $res['opposite_speciality_list'] = $this->db->select('specializationId,specializationName')->order_by('specializationName', 'ASC')->where(array('userType !='=>$this->authData->userType,'status'=>1))->or_where(array('userType'=>'both'))->get(SPECIALIZATIONS)->result_array();
        if($where['userType'] == 'individual'){
        $res['job_title']       = $this->db->select('jobTitleId,jobTitleName,(SELECT COUNT(`usermeta`.`jobTitle_id`)  FROM `usermeta` LEFT JOIN `users` ON `usermeta`.`user_id` = `users`.`userId`  WHERE `jobTitle_id` = `jobTitleId` and `userType` !="'.$where['userType'].' and isActive = 1 and users.status  = 1 AND users.isverified = 1") as total_registered')
            ->order_by('jobTitleName', 'ASC')
            ->where($where)
            ->or_where(array('userType'=>'both'))
            ->get(JOB_TITLES)
            ->result_array();
            //lq();
        }else{
            $res['job_title']       = $this->db->query("SELECT jt.`jobTitleId`, jt.`jobTitleName`, COUNT(t2.`UID`) as total_registered FROM `job_titles` jt
            LEFT JOIN
                (
                    SELECT `t1`.`UID`, `t1`.`JOBTITLEID` FROM `users` u JOIN
                    (SELECT user_id as UID, current_job_title as JOBTITLEID FROM user_job_profile
                        UNION
                     SELECT user_id as UID, previous_job_title_id as JOBTITLEID  FROM user_previous_role 
                     GROUP BY least(user_id, previous_job_title_id) , greatest(user_id, previous_job_title_id)
                    ) as t1 
                    ON `t1`.`UID` = `u`.`userId` 
                    JOIN `user_job_profile` ujp ON `u`.`userId`  = `ujp`.`user_id`
                    WHERE 1=1 AND
                    `ujp`.`current_company` != ".$this->db->escape($this->authData->businessName)." AND
                    `u`.`userType` != 'business' AND `u`.`status` = 1 AND `u`.`isActive` = 1 AND `u`.`isverified` = 1
                ) as t2
            ON jt.`jobTitleId` = t2.`JOBTITLEID` 
            WHERE 1=1 AND
            jt.`userType` = 'both'
            GROUP BY jt.`jobTitleId` ORDER BY `jt`.`jobTitleName`"
            )->result_array();
            

        }
        if($where['userType'] == 'individual'){
            $res['opposite_job_title']       = $this->db->select('jobTitleId,jobTitleName,(SELECT COUNT(`usermeta`.`jobTitle_id`)  FROM `usermeta` LEFT JOIN `users` ON `usermeta`.`user_id` = `users`.`userId`  WHERE `jobTitle_id` = `jobTitleId` and `userType` ="'.$where['userType'].' and isActive = 1 and users.status = 1 AND `users`.`isverified` = 1") as total_registered')->order_by('jobTitleName', 'ASC')->where(array('userType !='=>$this->authData->userType,'status'=>1))->or_where(array('userType'=>'both'))->get(JOB_TITLES)->result_array();
        }else{
            $res['opposite_job_title']   = $this->db->query("SELECT jt.`jobTitleId`, jt.`jobTitleName`, COUNT(t2.`UID`) as total_registered FROM `job_titles` jt
            LEFT JOIN
                (
                    SELECT `t1`.`UID`, `t1`.`JOBTITLEID` FROM `users` u JOIN
                    (SELECT user_id as UID, current_job_title as JOBTITLEID FROM user_job_profile
                        UNION
                     SELECT user_id as UID, previous_job_title_id as JOBTITLEID  FROM user_previous_role 
                     GROUP BY least(user_id, previous_job_title_id) , greatest(user_id, previous_job_title_id)
                    ) as t1 
                    ON `t1`.`UID` = `u`.`userId` 
                    JOIN `user_job_profile` ujp ON `u`.`userId`  = `ujp`.`user_id`
                    WHERE 1=1 AND
                    `ujp`.`current_company` != ".$this->db->escape($this->authData->businessName)." AND
                    `u`.`userType` != 'business' AND `u`.`status` = 1 AND `u`.`isActive` = 1 AND `u`.`isverified` = 1
                ) as t2
            ON jt.`jobTitleId` = t2.`JOBTITLEID` 
            WHERE 1=1 AND
            jt.`userType` = 'both'
            GROUP BY jt.`jobTitleId` ORDER BY `jt`.`jobTitleName`"
            )->result_array();
        }

        $res['availability_list'] = get_availability();
        $salary_range_list = get_salary_drop_down();
        foreach($salary_range_list as $k => $v){
            $arr[] =  array($k => $v); 
        }
        $res['salary_range_list'] = $arr;
        $res['experience_list'] = get_experience_drop_down();
        $res['emplyement_type'] = employement_type();
        $res['company_list'] = $this->getComapny($limit, $offset);
        
        return $res;        
	}

	   //get all details related to business profile
    function get_business_list($where){
     
        $defaultImg = base_url().DEFAULT_USER;
            $user_image = base_url().USER_IMAGE;
            $logo = base_url().COMPANY_LOGO;
            $default_logo = base_url().COMPANY_LOGO_DEFAULT;
            $userType = $this->db->select('u.userType')->where('u.userId',$where)->get(USERS.' as u')->row();
            if($userType->userType == 'business'){
                    $this->db->select('u.userId,u.fullName,u.businessName,u.email,u.userType,u.authToken,u.status,u.crd,u.deviceToken,u.phone,u.deviceType,u.isProfile,u.isNotify,COALESCE(job.jobTitleName,"") as jobTitleName,COALESCE(CEIL(AVG(r.rating)/0.5) * 0.5, "") as rating,COALESCE(aos.specializationName,"") as specializationName,

                        (case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage,
                   COALESCE(concat("'.$logo.'",um.company_logo),"") as company_logo
                        ');
                    $this->db->from(USERS .' as u');
                    $this->db->join(USER_META. ' as um', "um.user_id = u.userId","left"); //to get user details 
                    $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left"); //to get job titles
                    $this->db->join(REVIEWS .' as r',"r.review_for = u.userId","left"); //reviews
                    $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail
                    $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left");//to get area

            }else{

                $this->db->select('u.userId,u.fullName,u.businessName,u.email,u.phone,u.userType,u.authToken,u.status,u.crd,u.deviceToken,u.deviceType,u.isProfile,u.isNotify,COALESCE(aos.specializationName,"") as specializationName,COALESCE(job.jobTitleName,"") as jobTitleName,

                        (case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage
                        ');
                    $this->db->from(USERS .' as u');
                    $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail
                    $this->db->join(USER_EXPERIENCE. ' as user_current_role', "user_current_role.user_id = u.userId","left"); 
                    $this->db->join(JOB_TITLES. ' as job', "user_current_role.current_job_title = job.jobTitleId","left"); //to get job titles
                    $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left");//to get area
            }
            $this->db->where('u.userId',$where);
            $this->db->where('u.isActive',1);
            $result = $this->db->get(); 
            $res = $result->row(); 
            return $res;
    }

    function get_business_user($where){

        $default_logo = base_url().COMPANY_LOGO_DEFAULT;
        $logo = base_url().COMPANY_LOGO;

        
        $this->db->select('COALESCE(um.address,"") as address,COALESCE(um.description,"") as description,COALESCE(usr.phone,"") as phone,COALESCE(um.city,"") as city,COALESCE(um.state,"") as state,COALESCE(um.country,"") as country,COALESCE(um.latitude,"") as latitude,COALESCE(um.longitude,"") as longitude,COALESCE(um.bio,"") as bio,

            (case 
                    when( um.company_logo = "" OR um.company_logo IS NULL) 
                    THEN "'.$default_logo.'"
                    ELSE
                    concat("'.$logo.'",um.company_logo) 
                   END ) as company_logo

            ,COALESCE(aos.specializationId,"") as specializationId,COALESCE(job.jobTitleId,"") as jobTitleId

            ');
        $this->db->from(USER_META .' as um');
       
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = um.user_id","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization 

        $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left"); //to get job titles
        $this->db->join(USERS. ' as usr', "um.user_id = usr.userId","left"); //to get job titles
        

        if(!empty($where))
            $this->db->where($where);
            $this->db->where('usr.isActive',1);
            $result = $this->db->get();
            $res = $result->result_array(); 
            return $res;

    } //End function

   function get_indivisual_basic_info($where){

        $this->db->select('COALESCE(um.address,"") as address,COALESCE(um.description,"") as description,COALESCE(um.city,"") as city,COALESCE(um.state,"") as state,COALESCE(um.country,"") as country,COALESCE(um.latitude,"") as latitude,COALESCE(um.longitude,"") as longitude,COALESCE(um.bio,"") as bio,COALESCE(aos.specializationId,"") as specializationId,COALESCE(aos.specializationName,"") as specializationName,
            COALESCE(GROUP_CONCAT(DISTINCT val.valueId) ,"") as value,COALESCE(GROUP_CONCAT(DISTINCT val.valueName),"") as valueName,COALESCE(GROUP_CONCAT(DISTINCT str.strengthId),"") as strength,COALESCE(GROUP_CONCAT(DISTINCT str.strengthName),"") as strengthName,COALESCE(usr.phone,"") as phone');
        $this->db->from(USER_META .' as um');
       
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = um.user_id","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization 

        $this->db->join(USER_VALUE_MAPPING. ' as uv', "uv.user_id = um.user_id","left");
        $this->db->join(VALUE. ' as val', "uv.value_id = val.valueId","left");


        $this->db->join(USER_STRENGTH_MAPPING. ' as ustr',"ustr.user_id = um.user_id" ,"left");
        

        $this->db->join(STRENGTHS. ' as str', "ustr.strength_id = str.strengthId","left");
        $this->db->join(USER_EXPERIENCE. ' as ue', "um.user_id = ue.user_id","left");
        $this->db->join(USERS. ' as usr', "um.user_id = usr.userId","left");

        if(!empty($where))
            $this->db->where($where);
            $result = $this->db->get();  
            $res = $result->row(); 
            return $res;
    } //End function

    function get_previous_experience($where){
        $query = $this->db->select('user_previous_role.*,jb.jobTitleName')->JOIN('`job_titles` `jb`','`jb`.`jobTitleId`=`user_previous_role`.`previous_job_title_id`','left')->where($where)->get(PREVIOUS_EXPERIENCE);
        if($query->num_rows()){
           $result = $query->result();
           return $result;
        }
       
    }

    function get_user_experience($where){
         
        $exp = array();
        $this->db->select('exp.*,COALESCE(job.jobTitleId,"") as jobTitleId,COALESCE(job.jobTitleName,"") as jobTitleName,COALESCE(job.jobTitleName,"") as jobTitleName,COALESCE(aos.specializationId,"")  as next_speciality,COALESCE(aos.specializationName,"")  as next_speciality_name,');
        $this->db->from(USER_EXPERIENCE .' as exp');
        $this->db->join(JOB_TITLES. ' as job', "exp.current_job_title = job.jobTitleId","left"); //to get job titles

        $this->db->join(SPECIALIZATIONS. ' as aos', "exp.next_speciality = aos.specializationId","left");//to get area of specialization  
        $this->db->where($where);
        $a =  $this->db->get()->row(); 

        if(empty($a))
            return $exp;

        $exp['current_role'] = array(
               'current_job_title'=> $a->jobTitleId,
               'current_job_title_name'=> $a->jobTitleName,
               'current_company'=> $a->current_company,
               'current_experience'=>$a->currentExperience,
               'total_experience'=> $a->totalExperience,
               'current_description'=>$a->current_description

            );

        $exp['previous_role'] = !empty($this->get_previous_experience($where))?$this->get_previous_experience($where):array();


        if($a->expectedSalaryFrom != 'any'){
            $expectedSalaryFshow = '$'.$a->expectedSalaryFrom;
            $expectedSalaryTshow = '$'.$a->expectedSalaryTo;
            $expectedSalaryShow =  $expectedSalaryFshow.'-'.$expectedSalaryTshow;
        }else{
            $expectedSalaryShow = 'Any';
        }

        if($a->expectedSalaryFrom != 'any'){
            $expectedSalaryF = $a->expectedSalaryFrom;
            $expectedSalaryT = $a->expectedSalaryTo;
            $expectedSalary =  $expectedSalaryF.'-'.$expectedSalaryT;
        }else{
            $expectedSalary = 'Any';
        }
        $exp['next_role'] = array(
               'next_availability'=> $a->next_availability,
               'next_speciality'=> $a->next_speciality,
               'next_speciality_name'=> $a->next_speciality_name,
               'next_location'=>$a->next_location,
               'employementType'=>$a->employementType,
                'expectedSalaryShow'=>$expectedSalaryShow,
               'expectedSalary'=>$expectedSalary,
            );

        return $exp;
    } //End function

     //get user resume and CV by user ID
    function get_user_resume($where){
       

        $resume = base_url().USER_RESUME;
        $cv = base_url().USER_CV;

        $this->db->select('COALESCE(user_resume,"") as user_resume, COALESCE(user_cv,"") as user_cv,
            (case
                when(user_resume = "")
                THEN ""
                ELSE CONCAT ("'.$resume.'",user_resume) 
                END ) as user_resume_url ,

                (case
                when(user_cv = "")
                THEN ""
                ELSE CONCAT("'.$cv.'",user_cv) 
                END ) as user_cv_url');

        $res = $this->db->where($where)->get(USER_META)->row();
        return $res;
    }

    //get last inserted user reviews by user ID
    function get_user_reviews($user_id, $limit, $offset=0){
        
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $where['r.review_for'] =  $user_id;
 
        $this->db->select('r.*, u.fullName,
                (case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage');
        $this->db->from(REVIEWS .' as r'); //reviews
        $this->db->join(USERS. ' as u', "r.review_by = u.userId"); //to get user meta details
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('r.created_on', 'DESC');
        $result = $this->db->get();
        $res = $result->result(); 
        if($res[0]->is_anonymous == 1){
            $res[0]->fullName = 'Anonymous';
            $res[0]->profileImage = $defaultImg;
        }
        
        return $res;
    } //End function

     //get user reviews by user ID
    function get_user_reviews_list($user_id, $limit, $offset=0, $check_status=true){
        
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $where['r.review_for'] =  $user_id;
        $where['u.status'] =  1;
        
        if(!$check_status)
            $where['u.status'] = 0;
        
        $this->db->select('r.*, u.fullName,NOW() as today_datetime,aos.specializationName,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"

                    when( r.is_anonymous = 1) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage,

                   (case 
                    when( r.is_anonymous = 1) 
                    THEN "Anonymous"
                    ELSE
                    u.fullName
                   END ) as fullName
                   ');
        $this->db->from(REVIEWS .' as r'); //reviews
        $this->db->join(USERS. ' as u', "r.review_by = u.userId"); //to get user meta details
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization 

        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('r.created_on', 'DESC');
        $result = $this->db->get();
        $res = $result->result();
        return $res;
    } // End function

    //get user recommend by user ID
    function get_user_recommend_list($user_id, $limit, $offset=0, $check_status=true){


        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $where['rec.recommend_for'] =  $user_id;
        $where['u.status'] =  1;
        
        if(!$check_status)
            $where['u.status'] = 0;
        
        $this->db->select('rec.*, u.fullName,NOW() as today_datetime,specializationName,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage
                  
                   ');
        $this->db->from(RECOMMENDS .' as rec'); //recommend
        $this->db->join(USERS. ' as u', "rec.recommend_by = u.userId"); //to get user meta details
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization 

        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('rec.created_on', 'DESC');
        $this->db->order_by('rec.created_on', 'DESC');
        $result = $this->db->get();
        $res = $result->result();
        return $res;

    }

     //get user favourites by user ID
    function get_user_favourite_list($user_id, $userType, $limit, $offset, $check_status=true){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $where['fav.favourite_for'] =  $user_id;
        $where['u.status'] =  1;
        
        if(!$check_status)
            $where['u.status'] = 0;
     
        $this->db->select('fav.*, u.fullName,u.businessName,NOW() as today_datetime,specializationName,COALESCE(job.jobTitleName,"") as jobTitleName,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage
                  
                   ');
        $this->db->from(FAVOURITES .' as fav'); //favourites
        $this->db->join(USERS. ' as u', "fav.favourite_by = u.userId"); //to get user meta details
        $this->db->join(USER_META .' as um',"um.user_id = fav.favourite_by","left");

        if($userType == 'business'){
            $this->db->join(USER_EXPERIENCE. ' as exp', "exp.user_id = fav.favourite_by","left" );
            $this->db->join(JOB_TITLES. ' as job', "exp.current_job_title = job.jobTitleId","left"); //to get job titles           
        }else{
            $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left"); //to get job titles
        }
       
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('fav.created_on', 'DESC');
        $result = $this->db->get(); 
        $res = $result->result();
        return $res;

    } 

    //get user favourites by user ID
    function get_my_favourites($user_id, $userType, $limit, $offset, $check_status=true){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $where['fav.favourite_by'] =  $user_id;
        $where['u.status'] =  1;
        
        if(!$check_status)
            $where['u.status'] = 0;
     
        $this->db->select('fav.*, u.fullName,u.businessName,NOW() as today_datetime,COALESCE(aos.specializationName,"") as specializationName,COALESCE(job.jobTitleName,"") as jobTitleName,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage
                  
                   ');
        $this->db->from(FAVOURITES .' as fav'); //favourites
        $this->db->join(USERS. ' as u', "fav.favourite_for = u.userId"); //to get user meta details
        $this->db->join(USER_META .' as um',"um.user_id = fav.favourite_for","left");
        
        if($userType == 'business'){
            $this->db->join(USER_EXPERIENCE. ' as exp', "exp.user_id = fav.favourite_for","left" );
            $this->db->join(JOB_TITLES. ' as job', "exp.current_job_title = job.jobTitleId","left"); //to get job titles
        }else{
            $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left"); //to get job titles
        }
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('fav.favouriteId', 'DESC');
        $result = $this->db->get(); 
        $res = $result->result();
        return $res;

    } 

    //get user recommands by user ID
    function get_my_Recommands($user_id, $userType, $limit, $offset, $check_status=true){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $where['rec.recommend_by'] =  $user_id;
        $where['u.status'] =  1;
        
        if(!$check_status)
            $where['u.status'] = 0;
        
        $this->db->select('rec.*, u.fullName,NOW() as today_datetime,COALESCE(aos.specializationName,"") as specializationName,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage
                  
                   ');
        $this->db->from(RECOMMENDS .' as rec'); //recommend
        $this->db->join(USERS. ' as u', "rec.recommend_for = u.userId"); //to get user meta details
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization 

        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('rec.created_on', 'DESC');
        $result = $this->db->get();
        $res = $result->result();
        return $res;
    } 

    //get indivisual user list according to filteres
    function get_indivisual_search_list($search, $limit, $offset,$address,$city,$state,$country){
        //pr($search);
        $search_loc ='';
       if(!empty($search['location'])){
        $search_loc = $search['location'];
        }
        
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        if(!empty($search['job_title'])){
        $this->db->select('usr.userId,usr.status,usr.isActive,exp.currentExperience,exp.totalExperience,usr.upd as userTab,um.upd as Metta,usr.fullName,COALESCE(aos.specializationName,"") as specializationName,COALESCE(exp.current_company,"") as company,COALESCE(job.jobTitleName,"") as jobTitleName,COALESCE(um.address,"") as address,
            COALESCE(um.latitude, "") as latitude,COALESCE(um.longitude, "") as longitude,
            (case 
                    when( usr.profileImage = "" OR usr.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",usr.profileImage) 
                   END ) as profileImage,exp.expectedSalaryFrom,exp.expectedSalaryTo
            ');
        }else{
             $this->db->select('usr.userId,usr.status,usr.isActive,exp.totalExperience,exp.currentExperience,usr.upd as userTab,um.upd as Metta,usr.fullName,COALESCE(aos.specializationName,"") as specializationName,COALESCE(exp.current_company,"") as company,COALESCE(job.jobTitleName,"") as jobTitleName,COALESCE(um.address,"") as address,
            COALESCE(um.latitude, "") as latitude,COALESCE(um.longitude, "") as longitude,
            (case 
                    when( usr.profileImage = "" OR usr.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",usr.profileImage) 
                   END ) as profileImage,exp.expectedSalaryFrom,exp.expectedSalaryTo
            ');
        }
        $this->db->from(USERS .' as usr');
        $this->db->join(USER_META .' as um',"um.user_id = usr.userId","left");
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = usr.userId","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left");//to get area of specialization  
        $this->db->join(USER_EXPERIENCE. ' as exp', "exp.user_id = usr.userId","left" );
        $this->db->join(JOB_TITLES. ' as job', "exp.current_job_title = job.jobTitleId","left");
       
        $this->db->join(USER_STRENGTH_MAPPING. ' as ustr',"ustr.user_id = um.user_id" ,"left");
        $this->db->join(STRENGTHS. ' as str', "ustr.strength_id = str.strengthId","left");
        $this->db->join(USER_VALUE_MAPPING. ' as uv', "uv.user_id = um.user_id","left");
        $this->db->join(VALUE. ' as val', "uv.value_id = val.valueId","left");
        $this->db->join(PREVIOUS_EXPERIENCE. ' as previous_expe', "previous_expe.user_id = usr.userId","left");
        //searching with privious  + current experience with same job title, This query will work to add two coloumn from diffrent tables
      $this->db->join("(SELECT t1.UID, t1.JOBTITLEID, SUM(t1.previous_role_exp) as previous_role_exp FROM
        (SELECT user_id as UID, current_job_title as JOBTITLEID, `currentExperience` as previous_role_exp FROM ".USER_EXPERIENCE."
                        UNION ALL
        SELECT user_id as UID, previous_job_title_id as JOBTITLEID, SUM(`experience`) as previous_role_exp  FROM ".PREVIOUS_EXPERIENCE." 
        GROUP BY least(user_id, previous_job_title_id) , greatest(user_id, previous_job_title_id))
        as t1
        GROUP BY least(t1.UID, t1.JOBTITLEID) , greatest(t1.UID, t1.JOBTITLEID)) as t1", "`t1`.`UID` = `usr`.`userId`","left"); 

      //this query will work to add privious experience with same job title seperatly. to get result of user with privious work experience
       $this->db->join("(SELECT user_id as UID, previous_job_title_id as JOBTITLEID,SUM(`experience`) as `previous_role_exp1` FROM user_previous_role GROUP BY least(user_id, previous_job_title_id) , greatest(user_id, previous_job_title_id)) as prev_user_exp_t1", "`prev_user_exp_t1`.`UID` = `usr`.`userId`","left"); 

        $this->db->group_start(); //master group start
        
        if(!empty($this->authData->businessName)){
            $this->db->group_start();
            $this->db->where_not_in('current_company', $this->authData->businessName);
            $this->db->or_where('current_company',null);
            $this->db->group_end();
        
        }
        //$this->db->group_start();
        !empty($search['speciality_id']) ? $this->db->where(array('usm.specialization_id'=>$search['speciality_id'])) : "";
        
        !empty($search['employementType']) ? $this->db->where(array('exp.employementType'=>$search['employementType'])) : "";
       
        !empty($search['strength']) ? $this->db->where(array('ustr.strength_id'=>$search['strength'])) : ""; 
       
        !empty($search['availability']) ? $this->db->where(array('exp.next_availability'=>$search['availability'])) : ""; 
       
        !empty($search['value']) ? $this->db->where(array('uv.value_id'=>$search['value'])) : "";
         //$this->db->group_end();

        
        //check salary range
        if(isset($search['expectedSalaryFrom']) AND !empty($search['expectedSalaryTo']) AND $search['expectedSalaryTo'] != 99999999){
            
            /*
             * Due to above filters we need to start group here as they will be used in combination with salary
             */
            $this->db->group_start();
            
                 $this->db->group_start();
                    $this->db->where('expectedSalaryFrom >=',intval($search['expectedSalaryFrom']));
                    $this->db->where('expectedSalaryFrom <=',intval($search['expectedSalaryTo']));
                $this->db->group_end();
                
                $this->db->or_group_start();
                    $this->db->where('expectedSalaryTo >=',intval($search['expectedSalaryFrom']));
                    $this->db->where('expectedSalaryTo <=',intval($search['expectedSalaryTo']));
                $this->db->group_end();
                
                $this->db->or_group_start();
                    $this->db->where('expectedSalaryFrom >=',intval($search['expectedSalaryFrom']));
                    $this->db->where('expectedSalaryTo <=',intval($search['expectedSalaryFrom']));
                $this->db->group_end();
                
                $this->db->or_group_start();
                    $this->db->where('expectedSalaryFrom <=',intval($search['expectedSalaryTo']));
                    $this->db->where('expectedSalaryTo >=',intval($search['expectedSalaryTo']));
                $this->db->group_end();
                
                $this->db->or_group_start();
                    $this->db->where('expectedSalaryFrom =','any');
                $this->db->group_end();
                
            $this->db->group_end();

        }

        /*
         * This case is not known, and I don't think this case will occur 
         * But I have kept it as is
        */
       /* if(!isset($search['expectedSalaryFrom']) AND !empty($search['expectedSalaryTo']) AND $search['expectedSalaryTo'] != 99999999){
            
            $this->db->group_start();
                $this->db->where('expectedSalaryTo <=',intval($search['expectedSalaryTo']));
                $this->db->or_where('expectedSalaryFrom <=',intval($search['expectedSalaryTo']));
                $this->db->where('expectedSalaryFrom !=','any');
            $this->db->group_end();
        }*/
       
        //check job title
        if(empty($search['job_title'])){
            
            //look for experience
            if(isset($search['experienceFrom']) AND  !empty($search['experienceTo']) AND $search['experienceTo'] == 40 AND empty($search['speciality_id'])){
                 $this->db->group_start();
                 $this->db->group_start();
                $this->db->where('totalExperience >=',$search['experienceFrom']);
                $this->db->where('totalExperience <=',$search['experienceTo']);
                $this->db->group_end();
                $this->db->or_group_start();
                    $this->db->where('currentExperience >=',$search['experienceFrom']);
                    $this->db->where('currentExperience <=',$search['experienceTo']);
                     !empty($search['speciality_id']) ? $this->db->where(array('usm.specialization_id'=>$search['speciality_id'])) : "";
                    //$this->db->where('current_job_title',$search['job_title']);
                    //$this->db->or_having('privious_role_exp =','');
                $this->db->group_end();
                $this->db->group_end();
            }
            
            /*
             * This case is not known, and I don't think this case will occur 
             * But I have kept it as is
            */
            if(isset($search['experienceFrom']) AND  !empty($search['experienceTo']) AND $search['experienceTo'] != 40 ){
                //$search['experienceFrom'] = 0;
               $this->db->group_start();
               $this->db->group_start();
                $this->db->where('totalExperience >=',$search['experienceFrom']);
                $this->db->where('totalExperience <=',$search['experienceTo']);
                 $this->db->group_end();
                  $this->db->or_group_start();
                    $this->db->where('currentExperience >=',$search['experienceFrom']);
                    $this->db->where('currentExperience <=',$search['experienceTo']);
                     !empty($search['speciality_id']) ? $this->db->where(array('usm.specialization_id'=>$search['speciality_id'])) : "";
                    //$this->db->where('current_job_title',$search['job_title']);
                    //$this->db->or_having('privious_role_exp =','');
                $this->db->group_end();
                $this->db->group_end();
            }

        }
        else{
            //job title not empty, now look for experience
            
            /*
             * This case is not known, and I don't think this case will occur 
             * But I have kept it as is
            */
           /* if(!isset($search['experienceFrom']) AND !empty($search['experienceTo']) AND $search['experienceTo'] != 40){
                $search['experienceFrom'] = 0;
                $this->db->group_start();
                    $this->db->where('currentExperience >=',intval($search['experienceFrom']));
                    $this->db->where('currentExperience <=',intval($search['experienceTo']));
                    $this->db->where('current_job_title',$search['job_title']);
                $this->db->group_end();
            }*/
            if(isset($search['experienceFrom']) AND !empty($search['experienceTo'])){
                
                /*
                 * Due to above filters we need to start group here as they will be used in combination with salary
                */
                $this->db->group_start();
                
                    $this->db->group_start();
                        $this->db->where('currentExperience >=',$search['experienceFrom']);
                        $this->db->where('currentExperience <=',$search['experienceTo']);
                        $this->db->where('current_job_title',$search['job_title']);
                        //$this->db->or_having('privious_role_exp =','');
                    $this->db->group_end();
              
                //filert with current + privious experience with same job title
                    $this->db->or_group_start();
                        $this->db->where('t1.previous_role_exp >=',$search['experienceFrom']);
                        $this->db->where('t1.previous_role_exp <=',$search['experienceTo']);
                        $this->db->where('t1.JOBTITLEID',$search['job_title']);
                    $this->db->group_end();
                    //filter with privious job title experience. this will work in or condition to get data in or with current + privious experience with same job title
                     $this->db->or_group_start();
                        $this->db->where('prev_user_exp_t1.previous_role_exp1 >=',$search['experienceFrom']);
                        $this->db->where('prev_user_exp_t1.previous_role_exp1 <=',$search['experienceTo']);
                        $this->db->where('prev_user_exp_t1.JOBTITLEID',$search['job_title']);
                    $this->db->group_end();
                    
                $this->db->group_end();  //GROUP END HERE- this is required because of other filters applied above 
                
            }
            
            if(!isset($search['experienceFrom']) AND !empty($search['experienceTo']) AND $search['experienceTo'] != 40){
                $search['experienceFrom'] = 0;
                $this->db->or_group_start();
                    $this->db->where('t1.previous_role_exp >=',$search['experienceFrom']);
                    $this->db->where('t1.previous_role_exp <=',$search['experienceTo']);
                    $this->db->where('t1.JOBTITLEID',$search['job_title']);
                $this->db->group_end();

                 $this->db->or_group_start();
                    $this->db->where('prev_user_exp_t1.previous_role_exp1 >=',$search['experienceFrom']);
                    $this->db->where('prev_user_exp_t1.previous_role_exp1 <=',$search['experienceTo']);
                    $this->db->where('prev_user_exp_t1.JOBTITLEID',$search['job_title']);
                $this->db->group_end();
                
            }
            
            /*
             * This is the case where there will be only job title
             * experienceFrom = '' and experienceTo = ''
             * This is the case when user search from screen where only job title field is present.
             * So we need to show results on basis of job title only
             */
            if(isset($search['experienceFrom']) AND empty($search['experienceTo'])){
                // This is case where there will be only job_title coming as search parameter
                $this->db->group_start();
                $this->db->where('current_job_title',$search['job_title']);
                $this->db->or_where('t1.JOBTITLEID',$search['job_title']);
                $this->db->group_end();
            }
        }
        
        $this->db->group_end(); //master group end
        
        if(!empty($search_loc)){
            $this->db->group_start();
           
                $this->db->like('um.address', $search_loc);
                //$this->db->or_like('um.city', $search_loc);
                //ORDER BY FIELD VALUE
                $loc = $search_loc;
                
                $this->db->order_by("CASE WHEN um.address LIKE '".$this->db->escape_like_str($loc)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
               
                
                if(!empty($search['city'])){
                    $this->db->or_like('um.city', $search['city'], 'both');
                    //$this->db->or_like('um.address', $search['city'], 'both');
                    $c = $search['city'];
                    $this->db->order_by("CASE WHEN um.city LIKE '".$this->db->escape_like_str($c)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

                if(!empty($search['state'])){
                    $s = $search['state'];
                    $this->db->or_like('um.state', $search['state'], 'both');
                    //$this->db->or_like('um.address', $search['state'], 'both');
                    $this->db->order_by("CASE WHEN um.state LIKE '".$this->db->escape_like_str($s)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

                if(!empty($search['country'])){
                    $con = $search['country'];
                    $this->db->or_like('um.country', $search['country'], 'both');
                    //$this->db->or_like('um.address', $search['country'], 'both');
                    $this->db->order_by("CASE WHEN um.country LIKE '".$this->db->escape_like_str($con)."%'  THEN 1 ELSE 0 END", "DESC", FALSE);
                }
            $this->db->group_end();
        }
        
        // if(empty($search)){ $search cannot be empty - this case will not occur
        if(empty($search) ){
            
            $this->db->group_start();
                $this->db->like('um.address', $address);
                //$this->db->or_like('um.city', $address);
                
                //ORDER BY FIELD VALUE
                $this->db->order_by("CASE WHEN um.address LIKE '".$this->db->escape_like_str($address)."%' THEN 1 ELSE 0 END", "DESC", FALSE);

                if(!empty($city)){
                    
                    $this->db->or_like('um.city', $city, 'both');
                    //$this->db->or_like('um.address', $city, 'both');
                    //ORDER BY FIELD VALUE
                    $this->db->order_by("CASE WHEN um.city LIKE '".$this->db->escape_like_str($city)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

                if(!empty($state)){
                    
                    $this->db->or_like('um.state', $state, 'both');
                    //ORDER BY FIELD VALUE
                    $this->db->order_by("CASE WHEN um.state LIKE '".$this->db->escape_like_str($state)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

                if(!empty($country)){
                    
                    $this->db->or_like('um.country', $country, 'both');
                    $this->db->order_by("CASE WHEN um.country LIKE '".$this->db->escape_like_str($country)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }
            $this->db->group_end();
        }else{
            
            if(empty($search_loc)){

                $this->db->order_by("CASE WHEN um.address LIKE '".$this->db->escape_like_str($address)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                $this->db->order_by("CASE WHEN um.city LIKE '".$this->db->escape_like_str($city)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                $this->db->order_by("CASE WHEN um.state LIKE '".$this->db->escape_like_str($state)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                $this->db->order_by("CASE WHEN um.country LIKE '".$this->db->escape_like_str($country)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
            }
            
        }
        $this->db->where(array('usr.status'=>1,'usr.userType'=>'individual','usr.isActive'=>1, 'usr.isVerified'=>1, 'usr.jobProfileCompleted'=> 1));
        $this->db->group_by('usr.userId');
        $this->db->order_by('usr.upd', 'DESC');
       // $this->db->order_by('privious_role_exp', 'DESC');
       // $this->db->order_by('usr.userId', 'DESC');
        $this->db->limit($limit, $offset);
        $result = $this->db->get();
        $res    = $result->result(); 
        //lq();
        //log_event('This is query----- ');
        //log_event($this->db->last_query());
        return $res;
    }

    //for checking wheather the city according to user's registered location exist or not
    function searchCityRecord($where){

        $this->db->select('um.city');
        $this->db->from(USERS .' as u');
        $this->db->join(USER_META .' as um',"um.user_id = u.userId","left"); //to get user's meta info
        $this->db->where($where);
        $this->db->where('u.isActive',1);
        $this->db->where('u.isVerified',1);
        $this->db->limit(1);
        $result = $this->db->get(); 
        //echo lq();die;
        $num = $result->num_rows();
        if($num){
            return '1';
        }else{
            return '0';
        }
    }

    function searchCountryRecord($where){

        $this->db->select('um.country');
        $this->db->from(USERS .' as u');
        $this->db->join(USER_META .' as um',"um.user_id = u.userId","left"); //to get user's meta info
        $this->db->where($where);
        $this->db->where('u.isActive',1);
        $this->db->where('u.isVerified',1);
        $this->db->limit(1);
        $result = $this->db->get(); 
        //echo lq();die;
        $num = $result->num_rows();
        if($num){
            return '1';
        }else{
            return '0';
        }
    }
    
    //to business user list according to filters
    function get_business_search_list($search, $limit, $offset,$address,$city,$state,$country){
        //log_event(json_encode($search));
        $query = $this->db->select('current_company')->where('user_id',$this->authData->userId)->get(USER_EXPERIENCE)->row();
        if(!empty($query)){
            $user_comapny = $query->current_company;
        }else{
            $user_comapny = '';
        }
        $search_loc ='';
           if(!empty($search['location'])){
            $search_loc = $search['location'];
        }
       $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $logo = base_url().COMPANY_LOGO;
        $default_logo = base_url().COMPANY_LOGO_DEFAULT;

        $this->db->select('usr.userId,usr.fullName,usr.businessName,usr.upd,COALESCE(aos.specializationName,"") as specializationName,COALESCE(job.jobTitleName,"") as jobTitleName,COALESCE(um.address,"") as address,
            COALESCE(um.latitude, "") as latitude,COALESCE(um.longitude, "") as longitude,COALESCE(CEIL(AVG(r.rating)/0.5) * 0.5, "") as rating,
            (case 
                    when( usr.profileImage = "" OR usr.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",usr.profileImage) 
                   END ) as profileImage,

                   (case 
                    when( um.company_logo = "" OR um.company_logo IS NULL) 
                    THEN "'.$default_logo.'"
                    ELSE
                    concat("'.$logo.'",um.company_logo) 
                   END ) as company_logo


            ');
        $this->db->from(USER_META .' as um');
        $this->db->join(USERS. ' as usr', "um.user_id = usr.userId AND usr.businessName != '".$user_comapny."'"); //to get user meta details
       
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = usr.userId","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left");//to get area of specialization  

        $this->db->join(REVIEWS .' as r',"r.review_for = usr.userId","left"); //reviews

        $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left"); //to get job titles
       
        !empty($search['jobTitle']) ? $this->db->where(array('job.jobTitleId'=>$search['jobTitle'])) : "";
       
        !empty($search['speciality_id']) ? $this->db->where(array('usm.specialization_id'=>$search['speciality_id'])) : "";

        if(!empty($search['rating']) AND $search['rating'] != 0.0){
            //$this->db->where(array('r.rating'=>$search['rating']));
            $this->db->having(array('(CEIL(AVG(r.rating)/0.5) * 0.5)>='=>$search['rating']));
            $this->db->group_by('r.review_for');
        }
        
        if(!empty($search['company'])){
            $this->db->where(array('usr.businessName'=>$search['company']));
            //$this->db->or_like('usr.businessName', $search['company']);
        }
        /* if(!empty($query)){
             $this->db->not_like('usr.businessName',$query->current_company, 'none');
            //$this->db->where('usr.businessName !=',$query->current_company);
        }*/
       
        if(!empty($search_loc)){
            $this->db->group_start();
           
                $this->db->like('um.address', $search_loc);
                //$this->db->or_like('um.city', $search_loc);
                //ORDER BY FIELD VALUE
                $loc = $search['location'];
                $this->db->order_by("CASE WHEN um.address LIKE '".$this->db->escape_like_str($loc)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
               
                
                if(!empty($search['city'])){
                    $this->db->or_like('um.city', $search['city'], 'both');
                    //$this->db->or_like('um.address', $search['city'], 'both');
                    $c = $search['city'];
                    $this->db->order_by("CASE WHEN um.city LIKE '".$this->db->escape_like_str($c)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

                if(!empty($search['state'])){
                    $s = $search['state'];
                    $this->db->or_like('um.state', $search['state'], 'both');
                    //$this->db->or_like('um.address', $search['state'], 'both');
                    $this->db->order_by("CASE WHEN um.state LIKE '".$this->db->escape_like_str($s)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

                if(!empty($search['country'])){
                    $con = $search['country'];
                    $this->db->or_like('um.country', $search['country'], 'both');
                    //$this->db->or_like('um.address', $search['country'], 'both');
                    $this->db->order_by("CASE WHEN um.country LIKE '".$this->db->escape_like_str($con)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

            $this->db->group_end();
        }

        $this->db->order_by('usr.upd', 'DESC');
        
        if(empty($search)){
            $this->db->group_start();
                $this->db->like('um.address', $address);
                $this->db->or_like('um.city', $address);
                //ORDER BY FIELD VALUE
                $this->db->order_by("CASE WHEN um.address LIKE '".$address."%' THEN 1 ELSE 0 END", "DESC", FALSE);

                if(!empty($city)){
                    
                    $this->db->or_like('um.city', $city, 'both');
                    //$this->db->or_like('um.address', $city, 'both');
                    //ORDER BY FIELD VALUE
                    $this->db->order_by("CASE WHEN um.city LIKE '".$this->db->escape_like_str($city)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

                if(!empty($state)){
                    
                    $this->db->or_like('um.state', $state, 'both');
                    //ORDER BY FIELD VALUE
                    $this->db->order_by("CASE WHEN um.state LIKE '".$this->db->escape_like_str($state)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }

                if(!empty($country)){
                    $this->db->or_like('um.country', $country, 'both');
                    $this->db->order_by("CASE WHEN um.country LIKE '".$this->db->escape_like_str($country)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                }
                
            $this->db->group_end();
        }else{
            //$this->db->order_by('usr.upd', 'DESC');
            if(empty($search_loc)){
                $this->db->order_by("CASE WHEN um.address LIKE '".$this->db->escape_like_str($address)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                $this->db->order_by("CASE WHEN um.city LIKE '".$this->db->escape_like_str($city)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                $this->db->order_by("CASE WHEN um.state LIKE '".$this->db->escape_like_str($state)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
                $this->db->order_by("CASE WHEN um.country LIKE '".$this->db->escape_like_str($country)."%' THEN 1 ELSE 0 END", "DESC", FALSE);
            }
            
        }

        $this->db->where(array('usr.status'=>1,'usr.userType'=>'business','usr.isActive'=>1, 'usr.isVerified'=>1));
        $this->db->limit($limit, $offset);
        $this->db->group_by('usr.userId');
        $this->db->order_by('usr.upd', 'DESC');
        // $this->db->order_by('um.upd', 'DESC');
        //$this->db->order_by('usr.userId', 'DESC');
        $result = $this->db->get(); 
        
        $res = $result->result(); 
       // log_event($this->db->last_query());
        return $res;
    }


    //to get user's business profile detail
    function get_my_business_profile($where){
         //echo "hello";die;
        $default_logo = base_url().COMPANY_LOGO_DEFAULT;
        $logo = base_url().COMPANY_LOGO;
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $this->db->select('COALESCE(u.fullName, "") as fullName,COALESCE(u.phone, "") as phone,COALESCE(um.description, "") as description,COALESCE(u.email, "") as email,COALESCE(u.businessName, "") as businessName,COALESCE(u.userType, "") as userType,COALESCE(job.jobTitleName, "") as jobTitleName,COALESCE(um.address, "") as address,COALESCE(um.city, "") as city,COALESCE(um.state,"") as state,COALESCE(um.country,"") as country, COALESCE(um.latitude, "") as latitude,COALESCE(um.longitude, "") as longitude,COALESCE(um.bio, "") as bio,
            COALESCE(aos.specializationName, "") as specializationName,COALESCE(CEIL(AVG(r.rating)/0.5) * 0.5, "") as rating,
            (case 
                    when( um.company_logo = "" OR um.company_logo IS NULL) 
                    THEN "'.$default_logo.'"
                    ELSE
                    concat("'.$logo.'",um.company_logo) 
                   END ) as company_logo,
            (case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage,

            ');

        $this->db->from(USER_META .' as um');
        $this->db->join(USERS. ' as u', "um.user_id = u.userId"); //to get user meta details
       
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = um.user_id","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization 

        $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left"); //to get job titles
        $this->db->join(REVIEWS. ' as r',"r.review_for = um.user_id","left");
      
        $this->db->where($where);
        $result = $this->db->get();
        //pr($result);
        $res = $result->row(); 
        $base_url = substr(base_url(),0,-1);
        $res->profileUrl = $base_url.'/home/mobile_browser_email'.'/?id='.base64_encode($where['um.user_id']).'&userType='.$res->userType;
        //pr($res);
        return $res;

    }

    //to get count 
    function get_count($select,$where,$table){

        return $this->db->select($select)->get_where($table,$where)->row();
    }

    //to get indivisual information by user_id 
    function get_indivisual_profile($where){
         //echo "hello";die;

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;


        $this->db->select('COALESCE(usr.fullName, "") as fullName,COALESCE(aos.specializationName, "") as specializationName,COALESCE(um.address, "") address,COALESCE(um.city, "") as city,COALESCE(um.state,"") as state,COALESCE(um.country,"") as country,COALESCE(job.jobTitleName, "") as jobTitleName,COALESCE(um.latitude, "") as latitude ,COALESCE(um.longitude, "") as longitude,
            (case 
                    when( usr.profileImage = "" OR usr.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",usr.profileImage) 
                   END ) as profileImage
            ');
        $this->db->from(USERS .' as usr');
        $this->db->join(USER_META .' as um',"um.user_id = usr.userId","left"); //to get user's meta info
       
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = usr.userId","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left");//to get area of specialization  
        $this->db->join(USER_EXPERIENCE. ' as exp', "exp.user_id = usr.userId","left" );
        $this->db->join(JOB_TITLES. ' as job', "exp.current_job_title = job.jobTitleId","left"); //to get job titles
        $this->db->join(USER_STRENGTH_MAPPING. ' as ustr',"ustr.user_id = um.user_id" ,"left");
        $this->db->join(STRENGTHS. ' as str', "ustr.strength_id = str.strengthId","left");

        $this->db->join(USER_VALUE_MAPPING. ' as uv', "uv.user_id = um.user_id","left");
        $this->db->join(VALUE. ' as val', "uv.value_id = val.valueId","left");

        $this->db->where('usr.status',1);
        $this->db->where('usr.isActive',1);
        $this->db->where($where);
        $this->db->group_by('usr.userId');
        $this->db->order_by('usr.userId', 'DESC');
        $result = $this->db->get(); 
        
        $res    = $result->row(); 
       
        //pr($res);
        return $res;

    }

    //to get interview request data by request_id
    function get_request_data($where){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $this->db->select('usr.fullName as interview_for ,ireq.type,ireq.interviewer_name,ireq.location,ireq.latitude,ireq.longitude,ireq.date, ireq.time,req.status,
            (case 
                    when( usr.profileImage = "" OR usr.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",usr.profileImage) 
                   END ) as profileImage
            ');
        $this->db->from(USERS .' as usr');

        $this->db->join(INTERVIEW .' as intr',"intr.interview_for = usr.userId","left");
        $this->db->join(INTERVIEW_REQUEST .' as ireq',"ireq.interview_id = intr.interviewId","left");
        $this->db->join(REQUEST_PROGRESS .' as req',"req.request_id = ireq.requestId","left");
        $this->db->where('usr.status',1);
        $this->db->where($where);
        $result = $this->db->get(); 
        $res    = $result->result(); 
        return $res;

    }

    // Get Company/Business List for filters
    function getComapny($limit, $offset) {

        $this->db->select('DISTINCT (businessName) as company_name');
        $this->db->from(USERS .' as usr'); 

        $this->db->where(array('userType' => 'business'));
        
        $this->db->limit($limit, $offset);
        $this->db->order_by('usr.businessName', 'ASC');
        $result = $this->db->get();
        $res = $result->result();
        return $res;
    }

    //to get user's business profile detail
    function get_my_individual_profile($where){
        //echo "hello";
        $default_logo = base_url().COMPANY_LOGO_DEFAULT;
        $logo = base_url().COMPANY_LOGO;
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        
       //echo ceil(AVG(23)/0.5) * 0.5; die;
     //echo ceil(6.571); die; COALESCE("'.$rat.'"), "")
        $this->db->select('COALESCE(u.fullName, "") as fullName,COALESCE(u.userType, "") as userType,COALESCE(u.phone, "") as phone,COALESCE(u.email, "") as email,COALESCE(u.businessName, "") as businessName,COALESCE(job.jobTitleName, "") as jobTitleName,COALESCE(um.address, "") as address,COALESCE(um.city, "") as city,COALESCE(um.state,"") as state,COALESCE(um.country,"") as country, COALESCE(um.latitude, "") as latitude,COALESCE(um.longitude, "") as longitude,COALESCE(um.bio, "") as bio,
            COALESCE(aos.specializationName, "") as specializationName,COALESCE(CEIL(AVG(r.rating)/0.5) * 0.5, "") as rating,
            (case 
                    when( um.company_logo = "" OR um.company_logo IS NULL) 
                    THEN "'.$default_logo.'"
                    ELSE
                    concat("'.$logo.'",um.company_logo) 
                   END ) as company_logo,
            (case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage,

            ');

        $this->db->from(USER_META .' as um');
        $this->db->join(USERS. ' as u', "um.user_id = u.userId"); //to get user meta details
        ;
       
        $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = um.user_id","left"); //to get area of specialization detail

        $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left"); //to get area of specialization 

        $this->db->join(USER_EXPERIENCE. ' as exp', "exp.user_id = u.userId","left" );
        $this->db->join(JOB_TITLES. ' as job', "exp.current_job_title = job.jobTitleId","left"); //to get job titles
        $this->db->join(REVIEWS. ' as r',"r.review_for = um.user_id","left");
      
        $this->db->where($where);
        $result = $this->db->get(); 
        $res = $result->row(); 
        $res->profileUrl = base_url('home/mobile_browser_email').'/?id='.base64_encode($where['um.user_id']).'&userType='.$res->userType;
        //pr($res);
        return $res;

    }


    function get_user_view_list($user_id, $limit, $offset=0, $check_status=true){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $where['v.view_for'] =  $user_id;
        $where['u.status'] =  1;
        
        if(!$check_status)
            $where['u.status'] = 0;
     
        $this->db->select('v.*, u.fullName,u.businessName,COALESCE(job.jobTitleName,"") as jobTitleName,NOW() as today_datetime,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage
                  
                   ');
        $this->db->from(VIEW .' as v'); //views
        $this->db->join(USERS. ' as u', "v.view_by = u.userId"); //to get user meta details
        $this->db->join(USER_EXPERIENCE. ' as exp', "exp.user_id = v.view_by","left" );
        $this->db->join(USER_META .' as um',"um.user_id = v.view_by","left");
        $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left"); //to get job titles 
       
        $this->db->where($where);
        $this->db->where('u.isActive',1);
        $this->db->limit($limit, $offset);
        //$this->db->order_by('v.viewId', 'DESC');
        //$this->db->order_by('v.crd', 'DESC');
        $this->db->order_by('v.upd', 'DESC');
        $result = $this->db->get();
        $res = $result->result();
        return $res;

    }


    function get_count_viewed_data($table,$data,$where){//function created to get badge count of all notification is not viewed by user.
       foreach($data as $value){
            $this->db->select('COUNT("id") as '.$value.'');
            $this->db->where('isViewed',0);
            $this->db->where('notification_for',$where);
            $this->db->where('notification_type',$value);
            $get = $this->db->get($table);
            $result[] =  $get->row();
       }
      return $result;
    }

    function get_total_counts($data,$where){//this function will return count and data..
    //print_r($data);
        $count = 0;
        $result = '';
        foreach($data as $value){
            $this->db->from(NOTIFICATIONS);
            $this->db->where($where);
            $this->db->where('notification_type',$value);
            $query = $this->db->get();
            $count = $query->num_rows();
            $result += $count;
        }
        return $result;
    }

    function inactive_profile($table,$where){//Inactive and Active profile by user
        $get = $this->db->select('*')->where('userId',$where)->get($table);
        if($get->num_rows()){
           $result =  $get->row();
           /*$password = $result->password;
           if(password_verify($data,$password)){*/
           $res = $this->common_model->updateFields(USERS,array('isActive'=>0),array('userId'=>$where));
               if($res == true){
                   return $response = array('type' => "SU");
               }
               return $response = array('type' => "ADU");
           }
           /*return $response = array('type' => "WP");
        }*/
        return $response = array('type' => "UNF");
    }

    function contactUs($data,$table){//insert contact us using API
        $query = $this->db->insert($table,$data);
        if($query){
            return TRUE;
        }
        return FALSE;
    }

    function get_total_experience($table,$where){
       $query =  $this->db->select('SUM(`experience`) as total_experience')
        ->where($where)->get($table);
        if($query->num_rows()){
           $row = $query->row();
           return $row->total_experience;
        }
        return FALSE;
    }

   function resend_mail($where,$data){
        //$this->authData->userId;
        $email = $this->authData->email;
        $res = $this->common_model->updateFields(USERS,array('verifiedLink'=>$data['verifiedLink']),array('userId'=>$where));
        if($res){
            $send['email'] = $email; 
            $base_url = substr(base_url(),0,-1);
            $send['link'] =  $base_url.'://home/verified_email/?token='.$data["verifiedLink"].'&email='.encoding($send['email']).'&id='.$where.'&userType='.$this->authData->userType;

            $send['full_name'] = $data['fullName'];
            $message = $this->load->view('email/email_verified', $send, true);
            $this->load->library('Smtp_email');
            $subject = 'Verify your email address';
            //echo $email; echo $subject; echo $message;
            $isSend = $this->smtp_email->send_mail($send['email'],$subject,$message);
            if($isSend){
                return array('regType'=>'NR'); // Normal registration
            }else{
              return array('regType'=>'NRMU');   
            }
        }
            return FALSE; 
    }
    
    function get_user_total_experience($where){
       $query = $this->db->select('SUM(experience) as privious_exp')->from('user_previous_role')->where('user_id',$where)->get()->row();
       
        return $query;
        
    }
    
        
}//ENd Class