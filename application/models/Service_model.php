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
		$res = $this->db->select('email')->where(array('email'=>$data['email']))->get('users');
		
		if($res->num_rows() == 0)
		{
            $this->db->insert('users',$data);
            $last_id = $this->db->insert_id();
            return array('regType'=>'NR','returnData'=>$this->userInfo(array('userId' => $last_id))); // Normal registration
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
        

    function select_mapping($table,$where,$coloumn){
    	$this->db->select($coloumn);
    	$this->db->where($where);
    	$get = $this->db->get($table);
    	if($get->num_rows()){
    		$result = $get->row();
    		return $result;
    	}else{
    		return false;
    	}
    }    
        //get user info
	function userInfo($where){
           
            $req = $this->db->select('u.userId,u.fullName,u.businessName,u.email,u.userType,u.authToken,u.status,u.crd,u.deviceToken,u.deviceType,u.isProfile,u.isNotify,u.profileImage')->where($where)->get(USERS.' as u');
            $returnData = array();
            if($req->num_rows())
            {
                $result = $req->row();
                $table = USER_SPECIALIZATION_MAPPING;
                $where = array('user_id'=>$result->userId);
                //$value = $result->userId;
                $field = 'specialization_id';
                $specility	 =  $this->common_model->select_mapping($table,$where,$field); 
                if($specility == false){
                	$result->specility = '';
                }else{
                	$result->specility = $specility;
                }

                if($result->userType == 'business'){
                	$table = USER_META;
                	$where = array('user_id'=>$result->userId );
     				$where2 =  array('company_logo'=>'company_logo ! = ""');
               	$result->companyLogo =  $this->common_model->is_image_exist($table,$where,$where2);
            	}
                
            		$table = USER_META;
                	$where = array('user_id'=>$result->userId );
     				$where2 =  array('jobTitle_id'=>'jobTitle_id ! = 0');
               		$result->job_title =  $this->common_model->is_image_exist($table,$where,$where2);

                if (!empty($result->profileImage)) {
                    $image = $result->profileImage;
                    //check if image consists url- happens in social login case
                    if (filter_var($result->profileImage, FILTER_VALIDATE_URL)) { 
                        $result->thumbImage = $image;
                    }
                    else{
                    	$result->profileImage = base_url().USER_IMAGE.$image;
                        $result->thumbImage = base_url().USER_THUMB.$image;
                    }
                }else{
                	$result->thumbImage = base_url().DEFAULT_USER;
                }
            } 	
           
		return $result;	
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
