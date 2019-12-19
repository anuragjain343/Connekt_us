<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews_list_model extends CI_Model {

  
   function reviewDetail($data,$limit,$offset){


        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_THUMB;
        $data['limit'] = 10;
        $data['offset'] = 0;
        $this->db->select('r.*, u.fullName,NOW() as today_datetime,(case 
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
        if($data['userType'] == 'business'){
            $this->db->join(USERS. ' as u', "r.review_by = u.userId"); //to get user meta details  
        }else{
            $this->db->join(USERS. ' as u', "r.review_for = u.userId");
        }

        $where = array('r.review_for'=>$data['userId']);
        $wheres = array('r.review_by'=>$data['userId']);
        if($data['userType'] == 'business'){
            $this->db->where($where);
         }else{
             $this->db->where($wheres);
         }
         $this->db->order_by('r.reviewId','DESC');
        $this->db->limit($limit, $offset);
        $q = $this->db->get(); 
        $row = $q->result();
        
        if(!empty($row)){
            foreach($row as $v){ 
               $v->created_on = time_elapsed_string($v->created_on); 
               
            }
        }
        return $row;
        
   }

    public function countReview($data){

        $this->db->select('r.*');
        $this->db->from(REVIEWS .' as r'); //reviews
        $this->db->join(USERS. ' as u', "r.review_by = u.userId"); //to get user meta details  
        $where = array('r.review_for'=>$data['userId']);
        $this->db->where($where);
        $count = $this->db->count_all_results();
        return $count;
        
    }


}