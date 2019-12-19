<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Options extends CommonService{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('options_model'); //load user model
        $this->list_limit = 20;  //limit record
    }
    
    function getTNC_get(){
         $this->check_service_auth();
        $where = array('option_name' => 'tc_page');
        $res = $this->options_model->getTNC($where,OPTIONS);
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'data'=>$res['link']);
        $this->response($response);
    }

    function getContactUs_get(){
         $this->check_service_auth();
        if($this->authData->userType == "business"){
            $where = array('option_name' => 'contact_type_employer');
        }else{
             $where = array('option_name' => 'contact_type');
        }
       
        $res = $this->options_model->getTNC($where,OPTIONS);
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'data'=>$res['link']);
        $this->response($response);
    }

    function getAboutUs_get(){
         $this->check_service_auth();
        $where = array('option_name' => 'about_page');
        $res = $this->options_model->getTNC($where,OPTIONS);
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'data'=>$res['link']);
        $this->response($response);
    } 

    function getPrivacy_get(){
        $where = array('option_name' => 'pp_page');
        $res = $this->options_model->getTNC($where,OPTIONS);
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'data'=>$res['link']);
        $this->response($response);
    }

}//End Class 

