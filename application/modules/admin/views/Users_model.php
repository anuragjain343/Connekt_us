<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = USERS;
    var $column_order = array('u.userId','u.fullName','u.businessName','val.valueName','str.strengthName','u.email','u.profileImage','u.status'); //set column field database for datatable orderable
    var $column_search = array('u.fullName'); //set column field database for datatable searchable
    var $order = array('userId' => 'DESC');  // default order
    var $where = '';
    var $group_by = 'u.userId';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where;
    }

    function prepare_query(){

        $this->db->select('u.*,
          GROUP_CONCAT(DISTINCT val.valueName) as value,GROUP_CONCAT(DISTINCT str.strengthName) as strength');
        $this->db->from(USERS.' as u');
        $this->db->join(USER_VALUE_MAPPING. ' as uv',"uv.user_id = u.userId","left");
        $this->db->join(VALUE. ' as val',"uv.value_id = val.valueId","left");
        $this->db->join(USER_STRENGTH_MAPPING. ' as ustr',"ustr.user_id = u.userId" ,"left");
        $this->db->join(STRENGTHS. ' as str',"ustr.strength_id = str.strengthId","left");
    }
   
   //prepare post list query
    private function posts_get_query(){

        $this->prepare_query();
        $i = 0;

        foreach ($this->column_search as $emp) // loop column 
        {
            if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])){
                $_POST['search']['value'] = $_POST['search']['value'];
            } else
                $_POST['search']['value'] = '';

            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start();
                    $this->db->like(($emp), $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like(($emp), $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
            }
            if(!empty($this->where))
                $this->db->where($this->where); 

            $count_val = count($_POST['columns']);
            for($i=1;$i<=$count_val;$i++){ 

                if(!empty($_POST['columns'][$i]['search']['value'])){ 
                    $this->db->where(array($this->table_col[$i]=>$_POST['columns'][$i]['search']['value'])); 
                }else if(!empty($_POST['columns'][$i]['search']['value'])){ 
                    $this->db->where(array($this->table_col[$i]=>$_POST['columns'][$i]['search']['value'])); 
                } 
            }

            if(!empty($this->group_by)){
                $this->db->group_by($this->group_by);
            }

            if(isset($_POST['order'])) // here order processing
            {
                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            } 
            else if(isset($this->order))
            {
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
    }

    function get_list(){

        $this->posts_get_query();
        if(isset($_POST['length']) && $_POST['length'] < 1) {
            $_POST['length']= '10';
        } else
        $_POST['length']= $_POST['length'];
        
        if(isset($_POST['start']) && $_POST['start'] > 1) {
            $_POST['start']= $_POST['start'];
        }
        $this->db->limit($_POST['length'], $_POST['start']);
        //print_r($_POST);die;
        $query = $this->db->get(); 
        return $query->result();
    }

    function count_filtered(){

        $this->posts_get_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all(){

        $this->prepare_query();
        return $this->db->count_all_results();
    }

    function getBusinessDetail($id){

        $default_logo = base_url().COMPANY_LOGO_DEFAULT;
        $logo = base_url().COMPANY_LOGO;
        $profileImg = base_url().USER_THUMB;
        $defaultImg = base_url().DEFAULT_USER;

        $this->db->select('um.address,um.bio,IF(um.company_logo = "" OR um.company_logo IS NULL,"'.$default_logo.'",concat("'.$logo.'",um.company_logo))as company_logo,aos.specializationName,job.jobTitleName,u.businessName,u.userId,u.email,u.fullName,u.status,count(DISTINCT(f.favouriteId)) as favourites,count(DISTINCT(r.reviewId)) as reviews,count(DISTINCT(rec.recommendId)) as recommends,avg(r.rating) as rating,
            (case 
                when( u.profileImage = "" OR u.profileImage IS NULL) 
                THEN "'.$defaultImg.'"
                ELSE
                concat("'.$profileImg.'",u.profileImage) 
               END ) as profileImage');

        $this->db->from(USERS .' as u');
        $this->db->join(USER_META.' as um',"u.userId = um.user_id",'left');
        $this->db->join(USER_SPECIALIZATION_MAPPING.' as usm',"usm.user_id = u.userId","left");
        $this->db->join(SPECIALIZATIONS.' as aos',"usm.specialization_id = aos.specializationId","left");
        $this->db->join(JOB_TITLES.' as job', "um.jobTitle_id = job.jobTitleId","left");
        $this->db->join(FAVOURITES.' as f', "u.userId = f.favourite_for AND f.favourite_for = '".$id."'","left");
        $this->db->join(REVIEWS.' as r', "u.userId = r.review_for AND r.review_for = '".$id."'","left");
        $this->db->join(RECOMMENDS.' as rec', "u.userId = rec.recommend_for AND rec.recommend_for = '".$id."'","left");
        $this->db->where(array('u.userId'=>$id));
        $result = $this->db->get();
        $res = $result->row();
        $res->rating = round($res->rating);
        return $res;
    }

    function getIndividualDetail($id){

        $basic_info = $this->getIndividualInfo($id);
        $experience = $this->getUserExperience($id);
        return array('info'=>$basic_info,'experience'=>$experience);

    }//End function


    function getIndividualInfo($id){

        $resume = base_url().USER_RESUME;
        $cv = base_url().USER_CV;
        $profileImg = base_url().USER_THUMB;
        $defaultImg = base_url().DEFAULT_USER;
        $this->db->select('um.address,um.bio,aos.specializationName,
            GROUP_CONCAT(DISTINCT val.valueName) as value,GROUP_CONCAT(DISTINCT str.strengthName) as strength,IF(user_resume =""||user_resume IS NULL,"",CONCAT ("'.$resume.'",user_resume))as resume,IF(user_cv =""||user_cv IS NULL,"",CONCAT ("'.$cv.'",user_cv))as cv,u.email,u.fullName,u.userId,u.status,
               (case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                        THEN "'.$defaultImg.'"
                    ELSE
                        concat("'.$profileImg.'",u.profileImage) 
               END ) as profileImage');
        $this->db->from(USERS.' as u');
        $this->db->join(USER_META.' as um',"u.userId = um.user_id",'left');
        $this->db->join(USER_SPECIALIZATION_MAPPING.' as usm',"usm.user_id = u.userId","left");
        $this->db->join(SPECIALIZATIONS.' as aos',"usm.specialization_id = aos.specializationId","left");
        $this->db->join(USER_VALUE_MAPPING. ' as uv',"uv.user_id = u.userId","left");
        $this->db->join(VALUE. ' as val',"uv.value_id = val.valueId","left");
        $this->db->join(USER_STRENGTH_MAPPING. ' as ustr',"ustr.user_id = u.userId" ,"left");
        $this->db->join(STRENGTHS. ' as str',"ustr.strength_id = str.strengthId","left");
        $this->db->where(array('u.userId'=>$id));
        $result = $this->db->get();  
        $row = $result->row(); 
        $row->strength = !empty($row->strength) ? explode(',',$row->strength) : '';
        $row->value = !empty($row->value) ? explode(',',$row->value) : '';
        return $row;

    }//End function


/*    function getUserExperience($id){
        
        $exp = new stdClass;
        $this->db->select('exp.*,job.jobTitleName as current_job_title,aos.specializationName as next_speciality');
        $this->db->from(USER_EXPERIENCE .' as exp');
        $this->db->join(JOB_TITLES. ' as job', "exp.current_job_title = job.jobTitleId","left");
        $this->db->join(SPECIALIZATIONS. ' as aos', "exp.next_speciality = aos.specializationId","left");
        $this->db->where(array('user_id'=>$id));
        $exp =  $this->db->get()->row();
        if(empty($exp)) return $exp;
        $exp->previous_role = !empty($exp->previous_role) ? json_decode($exp->previous_role,true) : '';
        return $exp;

    }//End function
*/

    function getInterviewRequest($where){

         $this->db->select('`interview`.`interviewer_name`,`interview`.`location`,`interview`.`date`,`interview`.`time`,`request`.`requestId`,`request`.`is_finished`,`request`.`request_by`,`request`.`request_for`,`user`.`fullName`,`user_for`.`fullName` as Name,DATE_FORMAT(`request`.`created_on` ,"%M-%d-%Y %H:%m:%s") as created_on,`interview`.`interview_status`,`interview`.`is_delete`,`interview`.`type`,`request`.`is_finished`,DATE_FORMAT(`request`.`upd`,"%M-%d-%Y %H:%m:%s") as upd,`request`.`request_offer_status`');
       $this->db->join('`users` `user`','`user`.`userId` = `request`.`request_by`');
       $this->db->join('`users` `user_for` ','`user_for`.`userId` = `request`.`request_for`');
       $this->db->join('`interviews` `interview`','`interview`.`request_id` = `request`.`requestId`');
       $this->db->where($where);
        $query = $this->db->get('`requests` `request`');
        if($query->num_rows()){
            $result = $query->row();
            return $result;
        }
        return false;
    }
    function getUserExperience($id){
        
        $exp = new stdClass;
        $this->db->select('exp.*,job.jobTitleName as current_job_title,aos.specializationName as next_speciality');
        $this->db->from(USER_EXPERIENCE .' as exp');
        $this->db->join(JOB_TITLES. ' as job', "exp.current_job_title = job.jobTitleId","left");
        $this->db->join(SPECIALIZATIONS. ' as aos', "exp.next_speciality = aos.specializationId","left");
        $this->db->where(array('user_id'=>$id));
        $exp =  $this->db->get()->row();
        if(empty($exp)) return $exp;
        $exp->previous_role = !empty($exp->previous_role) ? json_decode($exp->previous_role,true) : '';
        
        $i = 0;
        foreach ($exp->previous_role as $val) { 
            $jobId = $val['previous_job_title']; 
            $previous_job_title[$i] = $this->getPreviousJobTitle($jobId); 
            $exp->previous_role[$i]['previous_job_title'] = $previous_job_title[$i]->previous_job_title;
            $i++; 
        }  
        
        return $exp;

    }//End function


    function getPreviousJobTitle($jobId){

        $this->db->select('jobTitleName as previous_job_title')->from(JOB_TITLES);
        $this->db->where(array('jobTitleId'=>$jobId,'status'=>1));
        $jobTitle =  $this->db->get()->row();
        return $jobTitle;
    }





}