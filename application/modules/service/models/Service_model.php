<?php
class Service_model extends CI_Model {
    
    /**
    * Generate token for users
    */
    function generate_token()
    {
        $this->load->helper('security');
        $res = do_hash(time().mt_rand());
        $new_key = substr($res,0,config_item('rest_key_length'));
        return $new_key;
    }
    /**
    * Update users deviceid and auth token while login
    */
    function checkDeviceToken()
    {
        $sql = $this->db->select('id')->where('deviceToken', $deviceToken)->get('users');
        if($sql->num_rows())
        {
            $id = array();
            foreach($sql->result() as $result)
            {
                $id[] = $result->id;
            }
            $this->db->where_in('id', $id);
            $this->db->update('users',array('deviceToken'=>''));

            if($this->db->affected_rows() > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        return true;
    } //Function for check Device Token
    
    /*
    Function for check provided token is resultid or not
    */
    function isValidToken($authToken)
    {
        $this->db->select('*');
        $this->db->where('authToken',$authToken);
        if($sql = $this->db->get('users'))
        {
            if($sql->num_rows() > 0)
            {
                return $sql->row();
            }
        }
        return false;
    }

    function registration($data)
    {   
        $res = $this->db->select('email,userId')->where(array('email'=>$data['email']))->get('users');
        
        if($res->num_rows() == 0)
        {
            $row = $res->row();    
            $this->db->insert('users',$data);
            $last_id = $this->db->insert_id();
            $send['email'] = $data['email']; 
            //$send['link'] = base_url('profile').'?token='.$data['verifiedLink'].'/?email='.encoding($send['email']).'/?id='.encoding($row['userId']);
            $base_url = substr(base_url(),0,-1);

            $getUserType = $this->common_model->getsingle(USERS,array('userId'=>$last_id),'userType');

            $send['link'] =  $base_url.'://home/verified_email/?token='.$data["verifiedLink"].'&email='.encoding($send['email']).'&id='.$last_id.'&userType='.$getUserType->userType;
            $send['full_name'] = $data['fullName'];
            $message = $this->load->view('email/email_verified', $send, true);
            $this->load->library('Smtp_email');
            $subject = 'Verify your email address';
            //echo $email; echo $subject; echo $message;
            $isSend = $this->smtp_email->send_mail($send['email'],$subject,$message);
            if($isSend == 1){
            return array('regType'=>'NR','returnData'=>$this->userInfo(array('userId' => $last_id))); // Normal registration
            }
        }
        else
        {   
            return array('regType'=>'AE'); //already exist
        }
        return FALSE;
        
        
    } //End Function users Register

    function updateDeviceIdToken($id,$deviceToken,$authToken,$deviceType='')
    {
        $req = $this->db->select('userId')->where('userId',$id)->get('users');
        if($req->num_rows())
        {
            $this->db->update('users',array('deviceToken'=>''),array('userId !='=>$id,'deviceToken'=>$deviceToken));
            $this->db->update('users',array('deviceToken'=>$deviceToken,'authToken'=>$authToken,'deviceType'=>$deviceType),array('userId'=>$id));
            return TRUE;
        }
        return FALSE;
    }//End Function Update Device Token 
        
         //get user info
    function userInfo($where){

            $defaultImg = base_url().DEFAULT_USER;
            $user_image = base_url().USER_THUMB;
            $logo = base_url().COMPANY_LOGO;
            $default_logo = base_url().COMPANY_LOGO_DEFAULT;
            $userType = $this->db->select('u.userType')->where($where)->get(USERS.' as u')->row();
            if($userType->userType == 'business'){
                    $this->db->select('u.userId,u.isVerified,u.fullName,u.businessName,u.email,u.userType,u.authToken,u.status,u.crd,u.deviceToken,u.deviceType,u.isProfile,u.isNotify,COALESCE(job.jobTitleName,"") as jobTitleName,COALESCE(CEIL(AVG(r.rating)/0.5) * 0.5, "") as rating,COALESCE(u.phone,"") as phone,COALESCE(aos.specializationName,"") as specializationName,

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
                    $this->db->join(USER_EXPERIENCE. ' as ue', "ue.user_id = u.userId","left"); 
                    $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left");
                   /* $this->db->join(JOB_TITLES. ' as job', "um.jobTitle_id = job.jobTitleId","left");*/ //to get job titles
                    $this->db->join(REVIEWS .' as r',"r.review_for = u.userId","left"); //reviews
                    $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail
                    $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left");//to get area

            }else{

                $this->db->select('u.userId,u.isVerified,u.fullName,u.businessName,u.email,u.userType,u.authToken,u.status,u.crd,u.deviceToken,u.phone,u.deviceType,u.isProfile,u.isNotify,COALESCE(aos.specializationName,"") as specializationName,COALESCE(job.jobTitleName,"") as jobTitleName,

                        (case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage
                        ');
                    $this->db->from(USERS .' as u');
                    $this->db->join(USER_META. ' as um', "um.user_id = u.userId","left"); 
                    $this->db->join(USER_EXPERIENCE. ' as ue', "ue.user_id = u.userId","left"); 
                    $this->db->join(JOB_TITLES. ' as job', "ue.current_job_title = job.jobTitleId","left");
                    $this->db->join(USER_SPECIALIZATION_MAPPING. ' as usm', "usm.user_id = u.userId","left"); //to get area of specialization detail
                    $this->db->join(SPECIALIZATIONS. ' as aos', "usm.specialization_id = aos.specializationId","left");//to get area
            }
            $this->db->where($where);
            $result = $this->db->get(); 
            //lq();
            $res = $result->row(); 
            return $res;

    } //End Function usersInfo

    function login($data,$authToken){
       
        $res = $this->db->select('*')->where(array('email'=>$data['email']))->get(USERS);
        if($res->num_rows()){
            $result = $res->row();
            if($result->status == 1)
            {
                //check user type
                $user_type = $result->userType;
                if($user_type != $data['userType']){
                    return array('returnType'=>'IU'); // Invalid user
                }
                //verify password- It is good to use php's password hashing functions so we are using password_verify fn here
                if(password_verify($data['password'], $result->password)){
                     $res = $this->common_model->getsingle(USERS,array('userId'=>$result->userId));
                    $this->common_model->updateFields(USERS,array('upd'=>$res->upd,'isActive'=>1),array('userId'=>$result->userId));

                    $updateData = $this->updateDeviceIdToken($result->userId,$data['deviceToken'],$authToken,$data['deviceType']);
                    if($updateData){
                        return array('returnType'=>'SL','userInfo'=>$this->userInfo(array('userId'=>$result->userId)));
                    }
                    else{
                        return FALSE;
                    }
                }
                else{
                    return array('returnType'=>'WP'); // Wrong Password
                }
            }
            return array('returnType'=>'WS','userInfo'=>$this->userInfo(array('userId'=>$result->userId)));
            // InActive
        }
        else {
            return array('returnType'=>'WE'); // Wrong Email
        }
    }//End users Login
        
        

    
        
}//ENd Class
?>
