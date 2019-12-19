<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Common_Controller {

    public $data = "";

    function __construct() {

        parent::__construct();
    }

    //admin login
    public function index() {
        
        if(!empty($this->session->userdata('id')))
            redirect('admin/dashboard');
        
    	$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'password', 'required');
		$this->form_validation->set_error_delimiters('<div class="err_msg">', '</div>');

		if ($this->form_validation->run() == FALSE){ 
			$data['error'] = validation_errors();   	
		}else { 
			$admin['email']  	= $this->input->post('email');
			$admin['password'] 	= $this->input->post('password');

			$isLogin = $this->common_model->isLogin($admin,ADMIN);
			
			if($isLogin){
				$this->session->set_flashdata('success', 'User authentication successfully done! ');
				 redirect('admin/dashboard');
			}else{
				$data['error'] = "Invalid email or password";
			}
		}

        $this->load->view('login',$data);

    }//End function


    //view admin profile
    public function profile(){

        if(empty($this->session->userdata('id')))
            redirect(site_url().'admin');
        $data['parent'] = "Profile";
        $where = array('id' => $this->session->userdata('id')); 
        $table = ADMIN;

        $data['admin'] = $this->common_model->getsingle($table,$where); 
        $data['option'] = $this->common_model->getsingle(OPTIONS,array('option_name'=>'verify_email'),'option_value'); 
        $data['title'] = "Profile";
        $this->load->admin_render('adminProfile', $data, '');

    }//End function

    function verifyEmail_tab_action(){
       $getValue = $this->common_model->getsingle(OPTIONS,array('option_name'=>'verify_email'),'option_value');
       if($getValue->option_value == 1){
         $res = $this->common_model->updateFields(OPTIONS,array('option_value'=>0),array('option_name'=>'verify_email'));
            $response = array('status'=>0,'message'=>'Disabled');
       }else{
         $this->common_model->updateFields(OPTIONS,array('option_value'=>1),array('option_name'=>'verify_email'));
          $response = array('status'=>1,'message'=>'Enabled');
       }
       echo json_encode($response); 
    }


    //change admin password
    function changePassword(){

        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]',array('required'=>'Please enter current password','min_length'=>'Password must contain atleast 6 characters'));
        $this->form_validation->set_rules('npassword', 'new password', 'trim|required|matches[rnpassword]|min_length[6]',array('required'=>'Please enter new password','min_length'=>'Password must contain atleast 6 characters','matches'=>"New password & retype new password don't match"));
        $this->form_validation->set_rules('rnpassword', 'retype new password ', 'trim|required',array('required'=>'Please retype new password'));

        $this->form_validation->set_error_delimiters('<div class="err_msg">', '</div>');
        if ($this->form_validation->run() == FALSE){ 

            $error = validation_errors(); 
            $res['status']=0; $res['message']= $error; 
            echo json_encode($res);      
        }else {
        	
            $password =$this->input->post('password');
            $npassword =$this->input->post('npassword');
            $table  = ADMIN;
            $select = "password";
            $where = array('id' => $this->session->userdata('id')); 
            $admin = $this->common_model->getsingle($table,$where,$select);
            
            if(password_verify($password, $admin->password)){

                $set =array('password'=> password_hash($this->input->post('npassword') , PASSWORD_DEFAULT)); 
                $update = $this->common_model->updateFields($table, $set, $where);
                $res = array(); 
                $res['url']= base_url('admin/profile'); $res['status']=1; $res['message']='Password Updated successfully'; 
                echo json_encode($res); die;
                
            }else{
                $res['message']= "Your Current Password is Wrong !";
                echo json_encode($res); die;    
            }
        }

    }//End Function


    //update profile
    function updateProfile(){

        $this->form_validation->set_rules('name', 'Name', 'required|callback__alpha_dash_space',array('required'=>'Please enter name '));
        if ($this->form_validation->run() == FALSE){ 

            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
            $response = array('status' => 0, 'message' => $requireds , 'url' => base_url('admin/profile'));    
        } else { 
            
            $additional_data = array(
                'name' => $this->input->post('name')
            );
            $image = '';
            if(!empty($_FILES['image']['name'])):

                $this->load->model('image_model');
                $folder  = 'profile';
                $image = $this->image_model->updateMedia('image',$folder);
                if(isset($image['error']) && !empty($image['error'])){
                    $response = array('status' => 0, 'message' => $image['error']);
                    echo json_encode($response); die;
                }
            endif;
            if(!empty($image)){
                $additional_data['image'] = $image;
            }
            $table = ADMIN;
            $where = array('id' => $this->session->userdata('id'));
            $update =  $this->common_model->updateFields($table, $additional_data, $where);
            $where_in = array('id' => $this->session->userdata('id'));
            $updated_session = $this->common_model->getsingle($table,$where_in);
            $session_data['name']   = $updated_session->name ;
            $session_data['image']      = $updated_session->image ;
            $this->session->set_userdata($session_data);
            $response = array('status' => 1, 'message' => 'Your profile updated successfully', 'url' => base_url('admin/profile')); //success msg
            
        }
        echo json_encode($response);

    }//End Function


    public function aboutUs(){

        if(empty($this->session->userdata('id')))
            redirect(site_url().'admin');
        $data['parent'] = "About";
        $where = array('id' => $this->session->userdata('id')); 
        $table = ADMIN;
        $data['content'] = $this->common_model->optionDataRetrive(OPTIONS,array('option_name'=>'about_page'));
        //$data['admin'] = $this->common_model->getsingle($table,$where); 
        $data['title'] = "About us";
        $this->load->admin_render('aboutus', $data, '');

    }//End function

    public function privacyPolicy(){
        if(empty($this->session->userdata('id')))
            redirect(site_url().'admin');
       $data['title'] = "Privacy";
       $data['parent'] = "Privacy";
       $data['content'] = $this->common_model->optionDataRetrive(OPTIONS,array('option_name'=>'pp_page'));
       $this->load->admin_render('privacy_policy',$data,'');
    }


    public function termCondition(){

        if(empty($this->session->userdata('id')))
            redirect(site_url().'admin');
        $data['parent'] = "Terms";
        $where = array('id' => $this->session->userdata('id')); 
        $table = ADMIN;

        $data['content'] = $this->common_model->getsingle(OPTIONS,array('option_name'=>'tc_page'));
        $data['title'] = "Terms";
        $this->load->admin_render('terms_condition', $data, '');

    }//End function

    public function contactUs(){

        if(empty($this->session->userdata('id')))
            redirect(site_url().'admin');
        $data['parent'] = "Contact us";
        $where = array('id' => $this->session->userdata('id')); 
        $table = ADMIN;
         $data['content'] = $this->common_model->getsingle(OPTIONS,array('option_name'=>'contact_type'));
        //$data['admin'] = $this->common_model->getsingle($table,$where); 
        $data['title'] = "Contact us";
        $this->load->admin_render('contactUs', $data, '');

    }//End function


    function _alpha_dash_space($str){

       if($str!=''){ 
            $res=( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? 0 : 1;
            if($res=='0'){
                 $this->form_validation->set_message('_alpha_dash_space',"The Name accept Only alphabets ");
                 return FALSE;
            }else{
                 return TRUE;
            }
        }else{
            $this->form_validation->set_message('_alpha_dash_space',"The Name field is required");
                 return FALSE;
        }
    } 



}