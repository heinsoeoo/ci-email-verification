<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class My_model extends CI_model
{
	
	public function __construct(){
		parent::__construct();	
	}

	public function checkuser($email){
		$this->db->where('email',$email);
		$res=$this->db->get('member');
		return $res->num_rows();
	}

	public function insertdata($data,$table){
		$data=$this->regdata;
		$this->db->insert($table,$data);
		return true;
	}

	// public function get_hash_value($email){
	// 	$sql="SELECT hash FROM member WHERE email='$email'";
	// 	$res=$this->db->query($sql);
	// 	return $res->result();
	// }

	public function checkhash($email,$hash,$data){
		$this->db->where('email',$email);
		$this->db->where('hash',$hash);
		$this->db->update('member',$data);
		return true;
	}


	public function checkver($email){
		$this->db->where('email',$email);
		$this->db->where('status',1);
		$res=$this->db->get('member');
		return $res->num_rows();
	}

	public function loginuser($email,$pass){
		$pass=md5($pass);
		$this->db->where('email',$email);
		$this->db->where('password',$pass);
		$res=$this->db->get('member');
		return $res->result();
	}
}