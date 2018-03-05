<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Membercontroller extends CI_controller
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
		$this->load->view('home');
	}
}