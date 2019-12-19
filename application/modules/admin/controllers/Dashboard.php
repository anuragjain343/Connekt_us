<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends Common_Controller {

    public function __construct() {
    	
		parent::__construct();
		if(!$this->session->userdata('id')) {
            redirect('admin'); 
        }
    }

    //For dashboard data
	public function index(){ 

		$data['addCss'] = array();
		$data['addJs'] = array('plugins/sparkline/jquery.sparkline.min.js','plugins/jvectormap/jquery-jvectormap-1.2.2.min.js','plugins/jvectormap/jquery-jvectormap-world-mill-en.js','plugins/slimScroll/jquery.slimscroll.min.js','dist/js/pages/dashboard2.js');

		$this->load->admin_render('dashboard',$data);

	}//End function


	//For logout
	public function logout(){

		$this->session->sess_destroy();
		$this->session->set_flashdata('success', 'Sign out successfully done! ');
		redirect('admin');

	}//End function

}//end  Class
