<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Service extends CommonService{
    
    public function __construct(){
        parent::__construct();
        
    }

     //registration here
    function registration_post(){
        $this->form_validation->set_rules('fullName', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__alpha_spaces_check',array('required'=>'Please enter your full name'));
        $this->form_validation->set_rules('userType', 'User Type', 'trim|required|min_length[2]|max_length[50]|callback__alpha_spaces_check'); 
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]', array('is_unique' => 'This email address is already exists'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]',array('required'=>'Please enter password'));
        if($this->input->post('userType') == 'business'){
            //for user type business only
            $this->form_validation->set_rules('businessName', 'Business name', 'trim|required|max_length[50]'); 
        }
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);
        }
            $authtoken = $this->service_model->generate_token();
            $profileImage = array();
            if(!empty($_FILES['profileImage']['name'])){
                
                $folder = 'profile';
                $this->load->model('Image_model');
                $profileImage = $this->Image_model->updateMedia('profileImage',$folder);

            }
            if(is_array($profileImage) && !empty($profileImage['error']))
            {
                $response = array('status' => FAIL, 'message' =>$profileImage['error']);
                $this->response($response);
            }
            

            $authtoken = $this->service_model->generate_token();
            $data = array();
            $set = array('fullName','email','businessName','userType','deviceToken','deviceType');
            foreach ($set as $key => $val) {
                $post= $this->post($val);
                $data[$val] = (isset($post) && !empty($post)) ? $post :''; 
            }

            $data['password'] =  password_hash($this->post('password'), PASSWORD_DEFAULT);  //it is goood to use php's password hashing algo

            if(is_string($profileImage) && !empty($profileImage)){

                $data['profileImage']       = $profileImage;
            }
            
            elseif (filter_var($this->input->post('profileImage'), FILTER_VALIDATE_URL)) {
                $data['profileImage'] = $this->input->post('profileImage');
            }
          
            $data['authToken']      = $authtoken; 
            $result = $this->service_model->registration($data);
            //pr($result);
            if(is_array($result)){
                
                switch ($result['regType']){
                    case "NR":
                    $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(110),'userDetail'=>$result['returnData']);
                    break;
                    case "AE":
                    $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(117));
                    break;

                    default:
                    $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
                }
            }
            else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }
            $this->response($response);
       
    } //End Function

    //validation callback for checking alpha_spaces
    function _alpha_spaces_check($string){
        if(alpha_spaces($string)){
            return true;
        }
        else{
            $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
            return FALSE;
        }
    }

    //login here
    function login_post(){

        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
        $this->form_validation->set_rules('password','Password','trim|required');
        $this->form_validation->set_rules('userType', 'User Type', 'trim|required|min_length[2]|max_length[100]|callback__alpha_spaces_check'); 

        if($this->form_validation->run() == FALSE)
        {
                $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
                $this->response($response, 400);
        }
        
            $authtoken = $this->service_model->generate_token();
            $data = array();
            $data['email'] = $this->post('email');
            $data['password'] = $this->post('password');
            $data['deviceToken'] = $this->post('deviceToken');
            $data['deviceType'] = $this->post('deviceType');
            $data['userType'] = $this->post('userType');
            $data['authToken'] = $authtoken;
            $result = $this->service_model->login($data,$authtoken);
            if(is_array($result)){
                switch ($result['returnType']) {
                    case "SL":
                    $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(106), 'userDetail' => $result['userInfo']);
                    break;
                    case "WP":
                    $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(124));
                    break;
                    case "WE":
                    $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(122));
                    break;
                    case "IU":
                    $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(132));
                    break;
                    case "WS":
                    $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(134));
                    break;
                    default:
                    $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(106), 'userDetail' => $result['userInfo']);
                }
            }
            else{
                $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(122));
            }
            $this->response($response);
       
    } //End Function
        
      //forgot(reset) password
    public function resetPassword_post(){
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }
        
        $email = $this->post('email');
        
        $user = $this->common_model->getsingle(USERS, array('email'=>$email), 'userId, fullName');
        if(empty($user)){
            $response = array('status' => FAIL, 'message' => 'This email address is does not exists');
            $this->response($response);
        }
        
        
        $new_pass = mt_rand(100000, 999999); //generate a random 6 digit number
        
        //update new password of user
        $new_pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);  //it is goood to use php's password hashing algo
        $is_updated = $this->common_model->updateFields(USERS, array('password'=> $new_pass_hash), array('userId'=>$user->userId));
        if($is_updated){
            //send mail to user
            
            $data['full_name'] = $user->fullName; $data['new_password'] = $new_pass;
            $message = $this->load->view('email/reset_password', $data, true);
            $this->load->library('smtp_email');
            $subject = 'Uconnekt-Reset Password';
            $isSend = $this->smtp_email->send_mail($email,$subject,$message);
            if($isSend){
                $response = array('status' => SUCCESS, 'message' => 'We have sent you a message. Please check your mail.'); //email sent
            }
            else{
                $response = array('status' => SUCCESS, 'message' => 'Error, not able to send email'); //email not sent
            }
            
        } else{
            $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118)); //failed to update
        }
        
        $this->response($response);
        
    }
  
}//End Class 

