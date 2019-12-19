<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class TestMe extends CommonService{
     function getDropdownList_get(){

    	//check for auth
        $this->check_service_auth();
        $offset = $this->get('offset'); $limit = $this->get('limit');
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
        
        $where = array('userType'=>$this->authData->userType,'status'=>1);

        $result = 'sdfsdf';
        $response = array('status' => SUCCESS, 'result'=>$result);
        $this->response($response);  
    }
}