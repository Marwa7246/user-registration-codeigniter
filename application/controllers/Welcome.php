<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('ForgotPassword');
    }
    
    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');    
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->load->model('Usermodel', 'usermodel', TRUE);

    }  
    
    public function ForgotPassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email'); 
                
        if($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('forgot');
            $this->load->view('footer');
        }else{

        $email = $this->input->post('email');      
        $findemail = $this->usermodel->ForgotPassword($email);  
        if($findemail){
            $this->usermodel->sendpassword($findemail);        
            }else{
        $this->session->set_flashdata('msg',' Email not found!');
        redirect(base_url().'user/Login','refresh');
            }    
        }
    }


    

}
