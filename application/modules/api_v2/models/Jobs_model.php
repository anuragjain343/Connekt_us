<?php
class Jobs_model extends CI_Model {

	  //$logo = base_url().COMPANY_LOGO;

    function jobDetail($jobId,$job_type){
        $fields='';
        $default_logo = base_url().COMPANY_LOGO_DEFAULT;  
        $default_video_thumb = '';  
        $logo = base_url().COMPANY_LOGO; 
        $logoThumb = base_url().'uploads/screening_video_thumb/'; 
    
        if($job_type==2){

            $this->db->select('j.jobId,u.businessName,jt.jobTitleName,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.visit_counts as total_view,j.crd,,s.specializationName as industry,s.specializationId as industry_id,pj.why_they_should_join,pj.question_description,pj.is_job_video_screening,pj.is_video_url,pj.video,pj.video_thumb_image,(case 
            when( um.company_logo = "" OR um.company_logo IS NULL) 
            THEN "'.$default_logo.'"
            ELSE
            concat("'.$logo.'",um.company_logo) 
            END ) as company_logo,
            (case 
            when( pj.video_thumb_image = "0" OR pj.video_thumb_image IS NULL OR pj.video_thumb_image = "") 
            THEN "'.$default_video_thumb.'"
            ELSE
            concat("'.$logoThumb.'",pj.video_thumb_image) 
            END ) as video_thumb_image');
        }   

        if($job_type==1){
            
            $this->db->select('j.jobId,u.businessName,jt.jobTitleName,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.visit_counts as total_view,j.crd,,s.specializationName as industry,s.specializationId as industry_id,(case 
            when( um.company_logo = "" OR um.company_logo IS NULL) 
            THEN "'.$default_logo.'"
            ELSE
            concat("'.$logo.'",um.company_logo) 
            END ) as company_logo');
        }

        $this->db->from(JOBS .' as j');
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");

        if($job_type==2){    
            $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = j.jobId","left"); 
        }

        $this->db->join(USER_META. ' as um', "user_id = j.posted_by_user_id","left"); 
        $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
        $this->db->where('j.jobid',$jobId);
        $result = $this->db->get(); 
        $res = $result->row(); 
        if($job_type==2){

            if (filter_var($res->video, FILTER_VALIDATE_URL)){ 
                    $res->video = $res->video;
            }else{
                if(empty($res->video)){
                    $res->video = '';
                }else{
                    $res->video = base_url().'/uploads/screening_video/'.$res->video;    
                }
                
            }
            $res->question_description =json_decode($res->question_description);

        }
       $res->jobUrl =  base_url().'/home/mobile_browser_email'.'/?jobId='.base64_encode($res->jobId).'&jobType='.$job_type.'&posted_by_user_id='.$res->posted_by_user_id; 
       $jobApplicants           = $this->get_applicants($res->jobId,$select='');
       $jobshortlisted           = $this->get_shortlisted($res->jobId,$select='');

       $res->applicants         = $jobApplicants;
       $res->applicants_count   = count($jobApplicants);

       $res->shortlisted        =  $jobshortlisted;
       $res->shortlisted_count  =  count($jobshortlisted);
       //$res->total_view         = $this->common_model->get_total_count(JOB_VIEWS, $where=array('job_id'=>$jobId));
       return $res;
    }

    function get_applicants($jobId,$select){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        if(!empty($select)){
             $this->db->select($select);
        }else{
        $this->db->select('u.userId,u.fullName,
           (case 
            when( u.profileImage = "" OR u.profileImage IS NULL) 
            THEN "'.$defaultImg.'"
            ELSE
            concat("'.$user_image.'",u.profileImage) 
        END ) as profileImage');
        }
        $this->db->from(JOB_APPLICANTS .' as ja');
        $this->db->join(USERS. ' as u', "u.userId = ja.applied_by_user_id","left"); 
        $this->db->where('ja.job_id',$jobId); 
        $orwhere = array(0,2,9);
        $this->db->where_in('ja.job_application_status',$orwhere);
        $result = $this->db->get(); 
        $res = $result->result();
       
        if($res){
          return $res;  
        }
        return array();
     }

     function get_shortlisted($jobId,$select){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        if(!empty($select)){
             $this->db->select($select);
        }else{
        $this->db->select('u.userId,u.fullName,
           (case 
            when( u.profileImage = "" OR u.profileImage IS NULL) 
            THEN "'.$defaultImg.'"
            ELSE
            concat("'.$user_image.'",u.profileImage) 
        END ) as profileImage');
        }
        $this->db->from(JOB_APPLICANTS .' as ja');
        $this->db->join(USERS. ' as u', "u.userId = ja.applied_by_user_id","left"); 
        $this->db->where('ja.job_id',$jobId);
        $orwhere = array(1,3,4,5,6,7);
        $this->db->where_in('ja.job_application_status',$orwhere);
        $result = $this->db->get(); 
        $res = $result->result();

        if($res){
          return $res;  
        }
        return array();
     }
    //END oF job Appplicants JOB_VIEWS

    function get_job_detail_individualSide($jobId,$job_type){
        $userId    = $this->authData->userId;
        $fields='';
        $default_logo = base_url().COMPANY_LOGO_DEFAULT;  
        $default_video_thumb = '';  
        $logo = base_url().COMPANY_LOGO; 
        $logoThumb = base_url().'uploads/screening_video_thumb/'; 
        $logoThumbAns = base_url().'uploads/answer_screening_video_thumb/'; 
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;

        $whereview = array('job_id'=>$jobId,'viewed_by_user_id'=>$this->authData->userId);
        $view_job   = $this->common_model->getsingle(JOB_VIEWS,$whereview,$fld =NULL,$order_by = '',$order = '');
        $whereviewcount = array('jobId'=>$jobId);
        $jobcounts   = $this->common_model->getsingle(JOBS,$whereviewcount,$fld =NULL,$order_by = '',$order = '');

        if(empty($view_job)){

            $viewData['job_id']             = $jobId;
            $viewData['viewed_by_user_id']  = $this->authData->userId;
            $viewData['crd']                = datetime();
            $primium_job_id = $this->common_model->insertData(JOB_VIEWS,$viewData);
        }else{
           
            $wherejobview = array('job_id'=>$jobId,'viewed_by_user_id'=>$this->authData->userId);
            $setdata = array('crd'=>datetime());
            $primium_job_id = $this->common_model->updateFields(JOB_VIEWS,$setdata,$wherejobview);
        }
            
            $jobcounts->visit_counts = $jobcounts->visit_counts+1;
            $set['visit_counts'] = $jobcounts->visit_counts;
            $wherejobid     = array('jobId'=>$jobId);             
            $count_job_id = $this->common_model->updateFields(JOBS,$set,$wherejobid);

        

        if($job_type==2){

            $this->db->select('j.jobId,ja.jobApplicantId,u.businessName,u.fullName,jt.jobTitleName,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,,s.specializationName as industry,pj.question_description,s.specializationId as industry_id,pj.why_they_should_join,pj.is_job_video_screening,pj.is_video_url,pj.video,pj.video_thumb_image,(case 
            when( um.company_logo = "" OR um.company_logo IS NULL) 
            THEN "'.$default_logo.'"
            ELSE
            concat("'.$logo.'",um.company_logo) 
            END ) as company_logo,
            (case 
            when( pj.video_thumb_image = "0" OR pj.video_thumb_image IS NULL OR pj.video_thumb_image = "") 
            THEN "'.$default_video_thumb.'"
            ELSE
            concat("'.$logoThumb.'",pj.video_thumb_image) 
            END ) as video_thumb_image,
            (case 
            when( ja.job_application_status = "0" AND ja.applied_by_user_id = '.$userId.') 
            THEN "0"
            when( ja.job_application_status = "1" AND ja.applied_by_user_id = '.$userId.') 
            THEN "1"
            when( ja.job_application_status = "2" AND ja.applied_by_user_id = '.$userId.') 
            THEN "2"
            when( ja.job_application_status = "3" AND ja.applied_by_user_id = '.$userId.') 
            THEN "3"
            when( ja.job_application_status = "4" AND ja.applied_by_user_id = '.$userId.') 
            THEN "4"
            when( ja.job_application_status = "5" AND ja.applied_by_user_id = '.$userId.') 
            THEN "5"
            when( ja.job_application_status = "6" AND ja.applied_by_user_id = '.$userId.') 
            THEN "6"
            when( ja.job_application_status = "7" AND ja.applied_by_user_id = '.$userId.') 
            THEN "7"
            when( ja.job_application_status = "9" AND ja.applied_by_user_id = '.$userId.') 
            THEN "9"
            ELSE
            "8" END ) as job_application_status,u.userId,
            (case 
                when( u.profileImage = "" OR u.profileImage IS NULL) 
            THEN "'.$defaultImg.'"
            ELSE
            concat("'.$user_image.'",u.profileImage) 
            END ) as profileImage,ja.video as answerVideo,(case 
            when( ja.video_thumb = "0" OR ja.video_thumb IS NULL OR ja.video_thumb = "") 
            THEN "'.$default_video_thumb.'"
            ELSE
            concat("'.$logoThumbAns.'",ja.video_thumb) 
            END ) as answer_video_thumb_image,j.visit_counts as total_view
            '
            );
        }

        if($job_type==1){

            $this->db->select('j.jobId,ja.jobApplicantId,u.businessName,u.fullName,jt.jobTitleName,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,,s.specializationName as industry,s.specializationId as industry_id,(case 
            when( um.company_logo = "" OR um.company_logo IS NULL) 
            THEN "'.$default_logo.'"
            ELSE
            concat("'.$logo.'",um.company_logo) 
            END ) as company_logo,  
            (case 
            when( ja.job_application_status = "0" AND ja.applied_by_user_id = '.$userId.') 
            THEN "0"
            when( ja.job_application_status = "1" AND ja.applied_by_user_id = '.$userId.' ) 
            THEN "1"
            when( ja.job_application_status = "2" AND ja.applied_by_user_id = '.$userId.') 
            THEN "2"
            when( ja.job_application_status = "3" AND ja.applied_by_user_id = '.$userId.') 
            THEN "3"
            when( ja.job_application_status = "4" AND ja.applied_by_user_id = '.$userId.') 
            THEN "4"
            when( ja.job_application_status = "5" AND ja.applied_by_user_id = '.$userId.' ) 
            THEN "5"
            when( ja.job_application_status = "6" AND ja.applied_by_user_id = '.$userId.') 
            THEN "6"
            when( ja.job_application_status = "7" AND ja.applied_by_user_id = '.$userId.') 
            THEN "7"
            when( ja.job_application_status = "9" AND ja.applied_by_user_id = '.$userId.') 
            THEN "9"
            ELSE
            "8" END ) as job_application_status,u.userId,

            (case 
                when( u.profileImage = "" OR u.profileImage IS NULL) 
                THEN "'.$defaultImg.'"
            ELSE
                concat("'.$user_image.'",u.profileImage) 
            END ) as profileImage,j.visit_counts as total_view');
        }

        $this->db->from(JOBS .' as j');
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
        $this->db->join(JOB_APPLICANTS. ' as ja', "ja.job_id = j.jobId AND ja.applied_by_user_id = $userId","left");

        if($job_type==2){    
            $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = j.jobId","left"); 
        }
        $this->db->join(USER_META. ' as um', "user_id = j.posted_by_user_id","left"); 
        $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
        $this->db->where('j.jobid',$jobId);
        $result = $this->db->get(); 
        //lq();
        $res = $result->row(); 

        if($job_type==2){

            if(filter_var($res->video, FILTER_VALIDATE_URL)){ 
                    $res->video = $res->video;
            }else{
                if(empty($res->video)){
                    $res->video = '';
                }else{
                    $res->video = base_url().'/uploads/screening_video/'.$res->video;    
                }
                
            }
             $res->question_description =json_decode($res->question_description);
              if(filter_var($res->answerVideo, FILTER_VALIDATE_URL)){ 
                    $res->answerVideo = $res->answerVideo;
            }else{
                if(empty($res->answerVideo)){
                    $res->answerVideo = '';
                }else{
                    $res->answerVideo = base_url().'/uploads/answer_screening_video/'.$res->answerVideo;    
                }
                
            }
           

        }
        // $select='job_application_status';
        //$res->total_view     = $this->common_model->get_total_count(JOB_VIEWS, $where=array('job_id'=>$jobId));
        if($res->job_application_status==3 OR $res->job_application_status==4 OR $res->job_application_status==5 OR $res->job_application_status==6 OR $res->job_application_status==7){
          $res->interviewDetails       = $this->interviewDetails(JOB_INTERVIEWS,$where=array('job_applicant_id'=>$res->jobApplicantId),''); 

        }
        $res->jobUrl =  base_url().'/home/mobile_browser_email'.'/?jobId='.base64_encode($res->jobId).'&jobType='.$job_type.'&posted_by_user_id='.$res->posted_by_user_id;  
 

        $job_saved = $this->common_model->getsingle(SAVE_JOBS,array('job_id'=>$jobId ,'save_by_user_id'=>$userId));
        if(!empty($job_saved)){
         $res->is_job_saved =1;   
        }else{
         $res->is_job_saved =0; 
        }
        return $res;
    }

    function interviewDetails($table,$jId,$select){
        if(!empty($select)){
            $this->db->select($select);
        }else{
            $this->db->select('interviewer_name,date,time,interview_location,latitude as interview_latitude,longitude as interview_longitude');
        }
        
        $this->db->from($table);
        $this->db->where($jId);
        $result = $this->db->get(); 
        return $res = $result->row();
    }
    

    //job listing individual side
    function get_job_list_individual($job_title,$company_name,$industry,$employment_type,$location,$latitude,$longitude,$salaryFrom,$salaryTo,$offset,$limit,$job_type,$is_filter,$default_location,$default_latitude,$default_longitude,$job_city,$job_state,$job_country){
        $userId    = $this->authData->userId;
        $defaultImg     = base_url().DEFAULT_USER;
        $user_image     = base_url().USER_IMAGE;
        $default_logo   = base_url().COMPANY_LOGO_DEFAULT;  
        $logo           = base_url().COMPANY_LOGO; 

        if($is_filter == 1){
        $this->db->select('j.jobId,jt.jobTitleName,jt.jobTitleId,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,,s.specializationName as industry,pj.why_they_should_join,s.specializationId as industry_id,u.userId,u.fullName,u.businessName,
           (case 
            when( u.profileImage = "" OR u.profileImage IS NULL) 
            THEN "'.$defaultImg.'"
            ELSE
            concat("'.$user_image.'",u.profileImage) 
        END ) as profileImage,(case 
        when( um.company_logo = "" OR um.company_logo IS NULL) 
        THEN "'.$default_logo.'"
        ELSE
        concat("'.$logo.'",um.company_logo) 
        END ) as company_logo,round(( 6371 * acos( cos( radians("'.$latitude.'") ) * cos( radians(j.job_latitude)) * cos( radians(j.job_longitude) - radians("'.$longitude.'") ) + sin( radians("'.$latitude.'") ) * sin( radians(j.job_latitude)))),1) AS distance_in_km,ja.job_application_status,
            (case 
            when( ja.job_application_status = "0" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "0"
            when( ja.job_application_status = "1" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "1"
            when( ja.job_application_status = "2" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "2"
            when( ja.job_application_status = "3" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "3"
            when( ja.job_application_status = "4" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "4"
            when( ja.job_application_status = "5" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "5"
            when( ja.job_application_status = "6" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "6"
            when( ja.job_application_status = "7" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "7"
            when( ja.job_application_status = "9" AND ja.applied_by_user_id = '.$userId.') 
            THEN "9"
            ELSE
            "8" END ) as job_application_status');
        }else{
            $this->db->select('j.jobId,jt.jobTitleName,jt.jobTitleId,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,,s.specializationName as industry,pj.why_they_should_join,s.specializationId as industry_id,u.userId,u.fullName,u.businessName,
           (case 
            when( u.profileImage = "" OR u.profileImage IS NULL) 
            THEN "'.$defaultImg.'"
            ELSE
            concat("'.$user_image.'",u.profileImage) 
        END ) as profileImage,(case 
        when( um.company_logo = "" OR um.company_logo IS NULL) 
        THEN "'.$default_logo.'"
        ELSE
        concat("'.$logo.'",um.company_logo) 
        END ) as company_logo,round(( 6371 * acos( cos( radians("'.$default_latitude.'") ) * cos( radians(j.job_latitude)) * cos( radians(j.job_longitude) - radians("'.$default_longitude.'") ) + sin( radians("'.$default_latitude.'") ) * sin( radians(j.job_latitude)))),1) AS distance_in_km,ja.job_application_status,
            (case 
            when( ja.job_application_status = "0" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "0"
            when( ja.job_application_status = "1" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "1"
            when( ja.job_application_status = "2" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "2"
            when( ja.job_application_status = "3" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "3"
            when( ja.job_application_status = "4" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "4"
            when( ja.job_application_status = "5" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "5"
            when( ja.job_application_status = "6" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "6"
            when( ja.job_application_status = "7" AND ja.applied_by_user_id = "'.$userId.'") 
            THEN "7"
            when( ja.job_application_status = "9" AND ja.applied_by_user_id = '.$userId.') 
            THEN "9"
            ELSE
            "8" END ) as job_application_status');
        }

        $this->db->from(JOBS .' as j');
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
        $this->db->join(JOB_APPLICANTS. ' as ja', "ja.job_id = j.jobId","left");
        $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = j.jobId","left");
        $this->db->join(USER_META. ' as um', "user_id = j.posted_by_user_id","left"); 
        $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");

        if($is_filter ==1){

        if(!empty($job_title)){
            $this->db->where('jt.jobTitleId',$job_title);
        }
        if(!empty($company_name)){
            $this->db->where('u.businessName',$company_name);
        }
        if(!empty($industry)){
            $this->db->where('j.industry',$industry);
        }
        if(!empty($employment_type)){
            $this->db->where('j.employment_type',$employment_type);
        }
        if(!empty($location)){
            //$this->db->having('distance_in_km <=',25);
            $this->db->like('j.job_location',$location);
            if(!empty($job_city)){
            $this->db->or_like('j.job_city',$job_city);
            }
            if(!empty($job_state)){
            $this->db->or_like('j.job_state',$job_state);
            }
            if(!empty($job_country)){
            $this->db->or_like('j.job_country',$job_country);
            }
        }
        if(!empty($salaryFrom) AND !empty($salaryTo)){
            $this->db->where('j.salary_to >=',$salaryFrom);
            $this->db->where('j.salary_to <=',$salaryTo);
        }else if(!empty($salaryFrom) AND empty($salaryTo)){
            $this->db->where('j.salary_to >=',$salaryFrom);
        }else if(empty($salaryFrom) AND !empty($salaryTo)){
            $this->db->where('j.salary_to <=',$salaryTo);
        }
        if(!empty($job_type)){
            $this->db->where('j.job_type',$job_type);
        }
        if(!empty($location)){
            $this->db->order_by('distance_in_km','ASC');
        }
        }else{
            $this->db->having('distance_in_km <=',25);
            $this->db->order_by('j.job_type','desc');
            $this->db->order_by('distance_in_km','ASC'); 
        }
        $this->db->where('j.status',1);
        $this->db->where('j.is_expired',0);
        $this->db->order_by('j.jobId','desc');
        $this->db->group_by('j.jobId');
        $this->db->limit($limit, $offset);
        $result = $this->db->get(); 
        $res = $result->result();
      
        return $res;  
    }

    function get_job_list_business($job_title,$company_name,$industry,$employment_type,$location,$latitude,$longitude,$salaryFrom,$salaryTo,$offset,$limit,$jobType,$job_status,$job_city,$job_state,$job_country){

         $logoThumb = base_url().'uploads/screening_video_thumb/'; 
        $userId    = $this->authData->userId;
         $default_video_thumb = ''; 
        $this->db->select('j.jobId,pj.why_they_should_join,pj.question_description,jt.jobTitleName as job_title,jt.jobTitleId as job_title_id,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,j.status,s.specializationName as industry,pj.is_job_video_screening,pj.is_video_url,pj.video,s.specializationId as industry_id,u.businessName,j.is_expired,
            (case 
            when( pj.video_thumb_image = "0" OR pj.video_thumb_image IS NULL OR pj.video_thumb_image = "") 
            THEN "'.$default_video_thumb.'"
            ELSE
            concat("'.$logoThumb.'",pj.video_thumb_image) 
            END ) as video_thumb_image,round(( 6371 * acos( cos( radians("'.$latitude.'") ) * cos( radians(j.job_latitude)) * cos( radians(j.job_longitude) - radians("'.$longitude.'") ) + sin( radians("'.$latitude.'") ) * sin( radians(j.job_latitude)))),1) AS distance_in_km,
            (case 
            when( ja.job_application_status = "0" ) 
            THEN "0"
            when( ja.job_application_status = "1" ) 
            THEN "1"
            when( ja.job_application_status = "2") 
            THEN "2"
            when( ja.job_application_status = "3") 
            THEN "3"
            when( ja.job_application_status = "4") 
            THEN "4"
            when( ja.job_application_status = "5") 
            THEN "5"
            when( ja.job_application_status = "6") 
            THEN "6"
            when( ja.job_application_status = "7") 
            THEN "7"
            when( ja.job_application_status = "9") 
            THEN "9"
            ELSE
            "8" END ) as job_application_status');

        $this->db->from(JOBS .' as j');
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
        $this->db->join(JOB_APPLICANTS. ' as ja', "ja.job_id = j.jobId","left");
        $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = j.jobId","left");

        $this->db->join(USER_META. ' as um', "user_id = j.posted_by_user_id","left"); 
        $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");

        if(!empty($job_title)){
            $this->db->where('jt.jobTitleId',$job_title);
        }
        if(!empty($company_name)){
            $this->db->where('u.businessName',$company_name);
        }
        if(!empty($industry)){
            $this->db->where('j.industry',$industry);
        }
        if(!empty($employment_type)){
            $this->db->where('j.employment_type',$employment_type);
        }
        if(!empty($location)){
            $this->db->having('distance_in_km <=',25);
            if(!empty($job_city)){
            $this->db->or_like('j.job_city',$job_city);
            }
            if(!empty($job_state)){
            $this->db->or_like('j.job_state',$job_state);
            }
            if(!empty($job_country)){
            $this->db->or_like('j.job_country',$job_country);
            }
        }
        if(!empty($salaryFrom) AND !empty($salaryTo)){

            $this->db->where('j.salary_to >=',$salaryFrom);
            $this->db->where('j.salary_to <=',$salaryTo);

        }else if(!empty($salaryFrom) AND empty($salaryTo)){

            $this->db->where('j.salary_to >=',$salaryFrom);

        }else if(empty($salaryFrom) AND !empty($salaryTo)){
            $this->db->where('j.salary_to <=',$salaryTo);
        }
        if(!empty($jobType)){
            $this->db->where('j.job_type',$jobType);
        }  
        if(!empty($job_status)){
            $where_jobstatus ='';
            if($job_status=='Active'){
               $where_jobstatus = array('j.status'=>1,'j.is_expired!='=>1);
            }else if($job_status=='Draft'){
                //$where_jobstatus = array('j.status'=>0,'j.is_expired!='=>1);
                $where_jobstatus = array('j.status'=>0,'j.is_expired!='=>1);
            }else if($job_status=='Expired'){
               //$where_jobstatus = array('j.is_expired'=>1,'j.status!='=>0);
               $where_jobstatus = array('j.is_expired'=>1,'j.status'=>1);
            }else{

            }
            $this->db->where($where_jobstatus);
        }
        $this->db->where('j.posted_by_user_id',$userId);

        if(!empty($location)){
            $this->db->order_by('distance_in_km','ASC');
        }
        $this->db->order_by('j.jobId','desc');
        
        $this->db->group_by('j.jobId');
        $this->db->limit($limit, $offset);
        $result = $this->db->get(); 
        $res = $result->result();
        //lq();
        //pr($res->video);
        foreach ($res as $key => $value) {
            //pr($value->video);
            $res[$key]->question_description =json_decode($value->question_description);

            $res[$key]->total_applicants     = $this->common_model->get_total_count(JOB_APPLICANTS, $where=array('job_id'=>$value->jobId));

            if($value->job_type==2 AND $value->video!='0'){

            if (filter_var($value->video, FILTER_VALIDATE_URL)){ 
                    $res[$key]->video = $value->video;
            }else{
                $res[$key]->video = base_url().'/uploads/screening_video/'.$value->video;
            }
            }
        }
       
        return $res;  
    }

  // get applicats details
    function get_job_applicant_detail($job_id,$user_id,$job_type){

        $fields='';
        $default_video_thumb = '';  
        $logo       = base_url().COMPANY_LOGO; 
        $logoThumb  = base_url().'uploads/screening_video_thumb/'; 
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
       $logoThumbAns = base_url().'uploads/answer_screening_video_thumb/'; 
    

        $this->db->select('ja.job_id as jobId,jt.jobTitleName,jt.jobTitleId as job_title_id,um.address,s.specializationName as industry,s.specializationId as industry_id,ja.video as answerVideo,(case 
            when( ja.video_thumb = "0" OR ja.video_thumb IS NULL OR ja.video_thumb = "") 
            THEN "'.$default_video_thumb.'"
            ELSE
            concat("'.$logoThumbAns.'",ja.video_thumb) 
            END ) as answer_video_thumb_image,ja.job_application_status ,u.userId,u.fullName,um.bio,

            (case 
                when( u.profileImage = "" OR u.profileImage IS NULL) 
                THEN "'.$defaultImg.'"
                ELSE
                concat("'.$user_image.'",u.profileImage) 
            END ) as profileImage');
        

        // $this->db->from(JOBS .' as j');
      /*  $this->db->from(JOB_APPLICANTS .' as ja');
        $this->db->join(JOBS. ' as j', "j.jobId = ja.job_id","left");
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");

        if($job_type==2){    

            $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = ja.job_id","left"); 
        }

        $this->db->join(USER_META. ' as um', "um.user_id = ja.applied_by_user_id","left");
         $this->db->join(USER_EXPERIENCE. ' as ue', "ue.user_id = ja.applied_by_user_id AND ue.current_job_title =jt.jobTitleId","left");  
        $this->db->join(USERS. ' as u', "u.userId = ja.applied_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");*/

    $this->db->from(USERS .' as u');
    $this->db->join(JOB_APPLICANTS. ' as ja', "ja.applied_by_user_id = '$user_id'","left");
    $this->db->join(USER_EXPERIENCE. ' as ue', "ue.user_id = u.userId","left");
    $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = ue.current_job_title","left");
    $this->db->join(USER_META. ' as um', "um.user_id = u.userId","left");
    $this->db->join(USER_SPECIALIZATION_MAPPING. ' as sm', "sm.user_id = u.userId","left");
    $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = sm.specialization_id","left");
    $this->db->where('ja.applied_by_user_id',$user_id);
    $this->db->where('ja.job_id',$job_id);
    $this->db->where('u.userId',$user_id);

        $result = $this->db->get(); 
        $res = $result->row(); 
        if($job_type==2){

            if(filter_var($res->answerVideo, FILTER_VALIDATE_URL)){ 
                    $res->answerVideo = $res->answerVideo;
            }else{
                if(empty($res->answerVideo)){
                    $res->answerVideo = '';
                }else{
                    $res->answerVideo = base_url().'/uploads/answer_screening_video/'.$res->answerVideo;    
                }
                
            }
        }
        // $select='job_application_status';
        $res->job_type = $job_type;

        return $res;   
    }

   
    function get_save_job_list_individual($limit,$offset,$jobTitle){
        $userId    = $this->authData->userId;
         $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $default_logo   = base_url().COMPANY_LOGO_DEFAULT;  
        $logo           = base_url().COMPANY_LOGO; 

        $this->db->select('j.jobId,pj.why_they_should_join,pj.question_description,jt.jobTitleName,jt.jobTitleId as job_title_id,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,j.status,s.specializationName as industry,u.businessName,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage,(case 
        when( um.company_logo = "" OR um.company_logo IS NULL) 
        THEN "'.$default_logo.'"
        ELSE
        concat("'.$logo.'",um.company_logo) 
        END ) as company_logo');

        $this->db->from(JOBS .' as j');
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
        $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = j.jobId","left");
        $this->db->join(SAVE_JOBS. ' as sj', "sj.job_id = j.jobId AND sj.save_by_user_id ='".$userId."' ","left");
        $this->db->join(USER_META. ' as um', "user_id = j.posted_by_user_id","left"); 
        //$this->db->join(USER_EXPERIENCE. ' as ue', "current_job_title = j.posted_by_user_id","left"); 
        $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
        $this->db->order_by('j.jobId','desc');
        $this->db->group_by('j.jobId');
        $this->db->where('save_by_user_id',$userId);
         if(!empty($jobTitle)){
          $this->db->where('j.job_title_id',$jobTitle);   
        }
        $this->db->limit($limit, $offset);
        $result = $this->db->get(); 
        $res    = $result->result();
        foreach ($res as $key => $value) {
            $res[$key]->question_description =json_decode($value->question_description);
        }
        return $res;  
    }

    function getJobTitle_data($search){
         $where = array('userType'=>$this->authData->userType,'status'=>1);
        $this->db->select('jobTitleId,jobTitleName');
        if(!empty($search)){
            $this->db->like('jobTitleName',$search);
        }
        $get = $this->db->get(JOB_TITLES);
        $result['job_title'] =  $get->result();
        foreach ($result['job_title'] as $key => $value) {
           $result['job_title'][$key]->count = $this->jobTitleCount($value->jobTitleId);
       
        }
         $result['speciality_list'] = $this->db->select('specializationId,specializationName')->order_by('specializationName', 'ASC')->where($where)->or_where(array('userType'=>'both'))->get(SPECIALIZATIONS)->result_array();
        $result['emplyement_type'] = employement_type();
        return $result;
    }

    function jobTitleCount($jobid){
        $this->db->select('*');
        $this->db->from(JOBS .' as j');
        $this->db->where('j.job_title_id',$jobid);
        $res = $this->db->get();
        if($res->num_rows() > 0){
            return $res->num_rows() ; 
        }else{
            return 0; // TRAINER RECORD NOT  FOUND 
        }
    }

    function get_job_track_process($jobId,$userId){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;

        $this->db->select('j.jobId,jt.jobTitleName as job_title,s.specializationName as industry,ja.jobApplicantId,j.posted_by_user_id,u.fullName,u.businessName,(case 
                when( u.profileImage = "" OR u.profileImage IS NULL) 
                THEN "'.$defaultImg.'"
                ELSE
                concat("'.$user_image.'",u.profileImage) 
            END ) as profileImage,(case 
            when( ja.job_application_status = "0") 
            THEN "0"
            when( ja.job_application_status = "1") 
            THEN "1"
            when( ja.job_application_status = "2") 
            THEN "2"
            when( ja.job_application_status = "3") 
            THEN "3"
            when( ja.job_application_status = "4") 
            THEN "4"
            when( ja.job_application_status = "5") 
            THEN "5"
            when( ja.job_application_status = "6") 
            THEN "6"
            when( ja.job_application_status = "7") 
            THEN "7"
            when( ja.job_application_status = "9") 
            THEN "9"
            ELSE
            "8" END ) as job_application_status,ja.crd as applide_date,ja.upd as shortlisted_date

            ');
        
                // $this->db->from(JOBS .' as j');
                $this->db->from(JOB_APPLICANTS .' as ja');
                $this->db->join(JOBS. ' as j', "j.jobId = ja.job_id","left");
                $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
                $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
                $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
                $where = array('applied_by_user_id'=>$userId,'job_id'=>$jobId);
                $this->db->where($where);
                $result = $this->db->get(); 
                $count = $result->num_rows();
                $res = $result->row(); 
                if(!empty($count)){
                $res->interviewDetails  = $this->interviewDetails(JOB_INTERVIEWS,$where=array('job_applicant_id'=>$res->jobApplicantId),'upd,date as interview_date,time');
                $res->applicant_name = $this->authData->fullName;
                }else{
                    $res='';
                }
                return $res;    
    }

      function get_job_track_process_business_side($jobId,$userId){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;

        $this->db->select('j.jobId,jt.jobTitleName as job_title,s.specializationName as industry,ja.jobApplicantId,j.posted_by_user_id,u.fullName,u.businessName,(case 
                when( u.profileImage = "" OR u.profileImage IS NULL) 
                THEN "'.$defaultImg.'"
                ELSE
                concat("'.$user_image.'",u.profileImage) 
            END ) as profileImage,(case 
            when( ja.job_application_status = "0") 
            THEN "0"
            when( ja.job_application_status = "1") 
            THEN "1"
            when( ja.job_application_status = "2") 
            THEN "2"
            when( ja.job_application_status = "3") 
            THEN "3"
            when( ja.job_application_status = "4") 
            THEN "4"
            when( ja.job_application_status = "5") 
            THEN "5"
            when( ja.job_application_status = "6") 
            THEN "6"
            when( ja.job_application_status = "7") 
            THEN "7"
            when( ja.job_application_status = "9") 
            THEN "9"
            ELSE
            "8" END ) as job_application_status,ja.crd as applide_date,ja.upd as shortlisted_date,ua.fullName as applicant_name,jt1.jobTitleName as applicant_job_title,sp.specializationName as applicatns_industry,(case 
                when( ua.profileImage = "" OR ua.profileImage IS NULL) 
                THEN "'.$defaultImg.'"
                ELSE
                concat("'.$user_image.'",ua.profileImage) 
            END ) as applicant_profileImage

            ');
        
                // $this->db->from(JOBS .' as j');
                $this->db->from(JOB_APPLICANTS .' as ja');
                $this->db->join(JOBS. ' as j', "j.jobId = ja.job_id","left");
                $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left");
                $this->db->join(USERS. ' as ua', "ua.userId = ja.applied_by_user_id","left"); 
                $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
                $this->db->join(USER_EXPERIENCE. ' as uj', "uj.user_id = ua.userId","left");
                $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usp', "usp.user_id = ua.userId","left");
                $this->db->join(JOB_TITLES. ' as jt1', "jt1.jobTitleId = uj.current_job_title","left");
                $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
                $this->db->join(SPECIALIZATIONS. ' as sp', "sp.specializationId = usp.specialization_id","left");
                $where = array('applied_by_user_id'=>$userId,'job_id'=>$jobId);
                $this->db->where($where);
                $result = $this->db->get(); 
                $count = $result->num_rows();
                $res = $result->row(); 
                
                if(!empty($count)){

                    $res->interviewDetails  = $this->interviewDetails(JOB_INTERVIEWS,$where=array('job_applicant_id'=>$res->jobApplicantId),'upd,date as interview_date,time');
                }else{
                   $res = ''; 
                }
                //$res->applicant_name = $this->authData->fullName;

                return $res;    
    }

       function get_applide_job_list_individual($limit,$offset,$jobTitle){
        $userId    = $this->authData->userId;
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $default_logo   = base_url().COMPANY_LOGO_DEFAULT;  
        $logo           = base_url().COMPANY_LOGO; 

        $this->db->select('j.jobId,pj.why_they_should_join,pj.question_description,jt.jobTitleName ,jt.jobTitleId as job_title_id,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,j.status,s.specializationName as industry,u.businessName,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage,(case 
        when( um.company_logo = "" OR um.company_logo IS NULL) 
        THEN "'.$default_logo.'"
        ELSE
        concat("'.$logo.'",um.company_logo) 
        END ) as company_logo');

        $this->db->from(JOBS .' as j');
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
        $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = j.jobId","left");
        $this->db->join(JOB_APPLICANTS. ' as ja', "ja.job_id = j.jobId AND ja.applied_by_user_id ='".$userId."'","left");
        $this->db->join(USER_META. ' as um', "user_id = j.posted_by_user_id","left"); 
        $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
        $this->db->where('ja.applied_by_user_id',$userId);

        if(!empty($jobTitle)){
          $this->db->where('j.job_title_id',$jobTitle);   
        }
        $this->db->order_by('ja.crd','desc');
        $this->db->group_by('j.jobId');
        $this->db->limit($limit, $offset);
        $result = $this->db->get(); 
        $res    = $result->result();
        foreach ($res as $key => $value) {
            $res[$key]->question_description =json_decode($value->question_description);
        }
        return $res;  
    }

    function get_active_job_list_individual($limit,$offset,$userId){
        //$userId    = $this->authData->userId;
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $default_logo   = base_url().COMPANY_LOGO_DEFAULT;  
        $logo           = base_url().COMPANY_LOGO; 
        $this->db->select('j.jobId,pj.why_they_should_join,pj.question_description,jt.jobTitleName ,jt.jobTitleId as job_title_id,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,j.status,s.specializationName as industry,u.fullname,u.businessName,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage,(case 
        when( um.company_logo = "" OR um.company_logo IS NULL) 
        THEN "'.$default_logo.'"
        ELSE
        concat("'.$logo.'",um.company_logo) 
        END ) as company_logo');

        $this->db->from(JOBS .' as j');
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
        $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = j.jobId","left");
        //$this->db->join(JOB_APPLICANTS. ' as ja', "ja.job_id = j.jobId AND ja.applied_by_user_id ='".$userId."'","left");
        $this->db->join(USER_META. ' as um', "user_id = j.posted_by_user_id","left"); 
        $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
        $this->db->where('j.status',1);
        $this->db->where('j.posted_by_user_id', $userId);
        $this->db->order_by('j.crd','desc');
        $this->db->group_by('j.jobId');
        $this->db->limit($limit, $offset);
        $result = $this->db->get(); 
        $res    = $result->result();
        foreach ($res as $key => $value) {
            $res[$key]->question_description =json_decode($value->question_description);
        }
        return $res;  
    }

    //JOB VIEWER LIST 
    function get_job_viewer_list($limit,$offset,$jobId){

        $userId    = $this->authData->userId;
        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_IMAGE;
        $this->db->select('u.userId,u.fullName,
           (case 
            when( u.profileImage = "" OR u.profileImage IS NULL) 
            THEN "'.$defaultImg.'"
            ELSE
            concat("'.$user_image.'",u.profileImage) 
        END ) as profileImage,jt.jobTitleId,jt.jobTitleName,jv.crd');
       
        $this->db->from(JOB_VIEWS .' as jv');
        $this->db->join(USERS. ' as u', "u.userId = jv.viewed_by_user_id","left"); 
        $this->db->join(USER_EXPERIENCE. ' as ue', "ue.user_id = jv.viewed_by_user_id","left"); 
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = ue.current_job_title","left");
        $this->db->where('jv.job_id', $jobId);
        $this->db->order_by('jv.crd','desc');
        $this->db->limit($limit,$offset);
        $result = $this->db->get();
        $res    = $result->result();
        return $res; 

    }

    function get_count_viewed_data($table,$data,$where){//function created to get badge count of all notification is not viewed by user.
        $count =0;
       foreach($data as $key => $value){
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
            $result = $count+1;
        }
        return $result;
    }

    function get_user_purchase_history($where,$limit,$offset){
       
        $this->db->select('ubi.purchaseId,ubi.job_id,ubi.user_id,ubi.currency,ubi.amount,ubi.crd
         ,jt.jobTitleName,jt.jobTitleId');
        $this->db->from(JOB_PURCHASE_HISTORY .' as ubi');
        $this->db->join(JOBS. ' as j', "j.posted_by_user_id = ubi.user_id AND j.jobId =ubi.job_id","left"); 
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
       
        $this->db->where($where);
        $this->db->order_by('ubi.crd','desc');
        $this->db->group_by('ubi.job_id');
        $this->db->limit($limit,$offset);
        $result = $this->db->get();
        $res    = $result->result();
        return $res; 

    }
    

    function getNearByUser($userId,$jobId){

        $jobdetail = $this->getJobLetLog($jobId);
        $jobLet    = $jobdetail->job_latitude;
        $jobLong   = $jobdetail->job_longitude;

        $this->db->select('um.user_id,u.userType,u.email,u.deviceToken,u.profileImage,round(( 6371 * acos(cos(radians("'.$jobLet.'") ) * cos( radians(um.latitude)) * cos(radians(um.longitude) - radians("'.$jobLong.'") ) + sin( radians("'.$jobLet.'") ) * sin( radians(um.latitude)))),1) AS distance_in_km,');
        $this->db->from(USERS .' as u');
        $this->db->join(USER_META. ' as um', "um.user_id = u.userId","left"); 
        $this->db->having('distance_in_km <=',25);
        $this->db->where('u.userType','individual');
        $result = $this->db->get();
        $res    = $result->result();
        return $res; 
       
    }

    function getJobLetLog($jobId){
       $this->db->select('*'); 
       $this->db->from(JOBS .' as j');
       $this->db->where('jobId',$jobId);
       $result = $this->db->get();
       $res    = $result->row();
      return $res;
    }

    function getDetailsForNotification($jobId){

       $this->db->select('*'); 
       $this->db->from(JOBS .' as j');
       $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
       $this->db->where('jobId',$jobId);
       $result = $this->db->get();
       $res    = $result->row();
       return $res;
    } 

    function getjobTitleAndAreaOfSpacility($jobid){

       $this->db->select('*'); 
       $this->db->from(JOBS .' as j');
       $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");
       $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
       $this->db->where('j.jobid',$jobid);
       $result = $this->db->get();
       $res    = $result->row();
       return $res;

    }

     function getBillingInfo($userId){

       $this->db->select('*'); 
       $this->db->from(USER_BLLLING_INFO .' as ub');
       $this->db->join(USERS. ' as u', "u.userId = ub.user_id","left"); 
       $this->db->where('u.userId',$userId);
       $result = $this->db->get();
       $res    = $result->row();
       return $res;

    }

    
        
}//ENd Class