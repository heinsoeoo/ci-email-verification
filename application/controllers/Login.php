<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Login extends CI_controller
{
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('My_model');
	}

	public function index(){
		$data['message']="";
	    $data['alert']="";
		$this->load->view('login',$data);
	}

	public function register(){
		if (isset($_POST['username'])) {
			$this->regdata = array(    //$data is a global variable
	            'username' => $_POST['username'],
	            'email' => $_POST['email'],
	            'password' => md5($_POST['password']),
	            'hash' => md5(rand(0, 1000))
	        );
	        $check=$this->My_model->checkuser($this->regdata['email']);
	        if ($check>0) {
	        	$data['message']="Sorry, this email is already registered";
	        	$data['alert']="alert alert-danger";
				$this->load->view('register',$data);
	        }else{
	        	$this->My_model->insertdata($this->regdata,'member');
	        	$this->send_confirmation();
	        	$data['message']="Your account has been made, please verify it by clicking the activation link that has been send to your email.";
	        	$data['alert']="alert alert-success";
				$this->load->view('register',$data);
	        }
		}else{
			$data['message']="";
			$data['alert']="";
			$this->load->view('register',$data);
		}
		
	}

	public function send_confirmation() {
      $this->load->library('email');  	//load email library
      $this->email->from('soulhunter@membership.soulhunt3r.me', 'Membership'); //sender's email
      $address = $_POST['email'];	//receiver's email
      $subject="Welcome to MySite!";	//subject
      $message= /*-----------email body starts-----------*/
        'Thanks for signing up, '.$_POST['username'].'!
      
        Your account has been created. 
        Here are your login details.
        -------------------------------------------------
        Email   : ' . $_POST['email'] . '
        Password: ' . $_POST['password'] . "
        -------------------------------------------------
                        
        Please click this link to activate your account:";
            
        //  . base_url() . 'login/verify?' . 
        // 'email=' . $_POST['email'] . '&hash=' . $this->regdata['hash'] ;
		/*-----------email body ends-----------*/		      
      $this->email->to($address);
      $this->email->subject($subject);
      $this->email->message($message);
      $this->email->send();
    }

    public function verify() {
    	$email=$_GET['email'];
    	$hash=$_GET['hash'];
    	$verify=$this->My_model->checkver($email);
    	if($verify){
			$data['message']="Sorry! This email is already registered.";
			$data['style']="none";
    		$this->load->view('successreg',$data);
		}else{
			$para['status']=1;
	        $check=$this->My_model->checkhash($email,$hash,$para);
	        if ($check) {
	        	$data['message']="Register successful!";
	        	$data['style']="block";
	        	$this->load->view('successreg',$data);
	        }
		}	
    }

	public function login(){
		if (isset($_POST['email'])) {

			$email=$this->input->post("email");
			$pass=$this->input->post("password");
			
			$check=$this->My_model->loginuser($email,$pass);
			if (count($check)>0) {
				$verify=$this->My_model->checkver($email);
				if ($verify) {
					$this->session->set_userdata('adminlogin',$check[0]);
					redirect(base_url());
				}else{
					$data['message']="Please verify your email first.";
					$data['alert']="alert alert-danger";
					$this->load->view('login',$data);
				}	
					
			}else{
				$data['message']="Incorrect email or password";
				$data['alert']="alert alert-danger";
				$this->load->view("login",$data);
			}		

		}else{
			$data['message']="";
			$data['alert']="";
			$this->load->view('login',$data);
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		$data['message']="";
		$data['alert']="";
		$this->load->view('login',$data);
	}

}