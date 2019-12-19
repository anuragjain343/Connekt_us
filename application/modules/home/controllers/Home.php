<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CommonFront {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        
    }
    public function index(){
    
       //$this->load->front_render('index','','');
        $this->load->view('index');
    } 

    function _alpha_spaces_check($string){
        if(alpha_spaces($string)){
            return true;
        } else{
            $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
            return FALSE;
        }
    }//end of fun.

   
    function verified_email(){
        $this->load->library('user_agent');//user agent library used to check which browser used by user
        $this ->load-> library('Mobile_Detect');
        $detect = new Mobile_Detect();// this library can be used to check which mobile user using. with os
        $verify_link['link'] = $_GET['token'];
        $verify_email['email'] = decoding($_GET['email']);
        $id = $_GET['id'];
        if($this->agent->is_mobile()){
          if($detect->isAndroidOS()){
            $send['link'] = base_url().'home/mobile_browser_email/?token='.$verify_link['link'].'&email='.$_GET['email'].'&id='.$id;
           }if($detect->isiOS()){
                $base_url= rtrim(base_url(), '/');
                $send['link'] = base_url().'home/mobile_browser_email/?token='.$verify_link['link'].'&email='.$_GET['email'].'&id='.$id;
            }
            redirect($send['link']);
            /*}*/
        }
        else{//if user using desktop browser this condition will work

            $res = $this->common_model->getsingle(USERS,array('email'=>$verify_email['email'],'verifiedLink'=>$verify_link['link']),array('userId'));
            if(!empty($res)){
                $response = $this->common_model->updateFields(USERS,array('isVerified'=>1,'verifiedLink'=>''),array('userId'=>$res->userId));
                if($response == true){
                    $this->session->set_flashdata('successfull', 'Email Verified Successfully');
                    redirect('home');
                }
                 $this->session->set_flashdata('fail', 'Email verification failed');
                 redirect('home');
            }
            $this->session->set_flashdata('fail', 'Email link has been expired, Please genrate another link.');
                redirect('home');
        }

    } 


    function mobile_browser_email(){//this function will work for mobile browser.
        $verify_link['link'] = $_GET['token'];
        $verify_email['email'] = decoding($_GET['email']);
        $userType = $_GET['userType'];
        if(empty($userType)){
        $id = $this->uri->segment(5);
            $res = $this->common_model->getsingle(USERS,array('email'=>$verify_email['email'],'verifiedLink'=>$verify_link['link']),array('userId'));
            if(!empty($res)){
                $response = $this->common_model->updateFields(USERS,array('isVerified'=>1,'verifiedLink'=>''),array('userId'=>$res->userId));
                if($response == true){
                    $this->session->set_flashdata('successfull', 'Email Verified Successfully');
                    redirect('home');
                }
                 $this->session->set_flashdata('fail', 'Email verification failed');
                 redirect('home');
            }
            $this->session->set_flashdata('fail', 'Email link has been expired, Please generate another link.');
             redirect('home');
         }
        redirect('home');
    }

    public function contactUs(){
       $res = array();
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[50]|callback__alpha_spaces_check');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|max_length[100]|min_length[10]');
        if ($this->form_validation->run() == FALSE){
            foreach($_POST as $key =>$value){ //loop will extract the value of an array by key and index will me messages. easy to show thorugh ajax by key and value..
            $res['messages'][$key] = form_error($key);
            }//foreach end..
        }else{
            $dataReg = array();
            $dataReg['name']            =       $this->input->post('name');     
            $dataReg['email']           =       $this->input->post('email');     
            $dataReg['subject']         =       $this->input->post('subject');
            $dataReg['message']         =      $this->input->post('message');
            $dataReg['type']            =     'contact_us';

            $message = $this->load->view('email/landingPage_email', $dataReg, true);
            $this->load->library('Smtp_email');
            $subject = $dataReg['subject'];
            $send['email'] = 'info@connektus.com.au';
            $isSend = $this->smtp_email->send_mail($send['email'],$subject,$message);


            //$dataReg['emailLink']       =       md5(uniqid()); // use this when email will send.
            $response = $this->Users_model->contactUs($dataReg,CONTACT_US);
            if($response == TRUE){
                $res['messages']['success'] = "Successfully added your message...";
            }else{
                $res['messages']['unsucces'] = "Sorry! Something went wrong.";
            }
        }//end of else..
        echo json_encode($res);  //USED JSON ENCODE TO SHOW ERROR THROUGH AJAX.
       //$this->load->front_render('index','','');

    }


    public function newsletterSubscribe(){
         $res = array();
        
        $this->form_validation->set_rules('email_newsLetter', 'Email', 'trim|required|valid_email');  

        if ($this->form_validation->run() == FALSE){
            foreach($_POST as $key =>$value){ //loop will extract the value of an array by key and index will me messages. easy to show thorugh ajax by key and value..
            $res['messages'][$key] = form_error($key);
            //pr($res);

            }//foreach end..
        }else{
            $data = array();
            $data['email']           =       $this->input->post('email_newsLetter');     
            $message = $this->load->view('email/newsletter_email', $data, true);
            $this->load->library('Smtp_email');
            $subject = 'Newsletter';
            $send['email'] = 'info@connektus.com.au';
            $isSend = $this->smtp_email->send_mail($send['email'],$subject,$message);
            //$dataReg['emailLink']       =       md5(uniqid()); // use this when email will send.
            if($isSend){
                $res['messages']['success'] = "Email address successfully added for newsletter";
            }else{
                $res['messages']['unsucces'] = "Sorry! Something went wrong.";
            }
        }
        echo json_encode($res);  //USED JSON ENCODE TO SHOW ERROR THROUGH AJAX.
       //$this->load->front_render('index','','');

    }

   
}//END OF CLASS
