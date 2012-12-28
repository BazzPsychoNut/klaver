<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller 
{

	public function index()
	{
		$data = array();
		
		// create the form input fields attributes
		
		// TODO use my own forms library for better understanding and more functionality (labels etc) 
		
		// player1
		$data['player1']['name'] = array(
				'name'   => 'player1_name',
				'id'     => 'player1_name',
		);
		$data['player1']['password'] = array(
				'name'   => 'password',
				'id'     => 'password',
		);
		$data['player1']['password_confirmation'] = array(
				'name'   => 'password_confirmation',
				'id'     => 'password_confirmation',
		);
		$data['player1']['email'] = array(
				'name'   => 'player1_email',
				'id'     => 'player1_email',
		);
		
		// player 2
		// You cannot set a password for another player, so you can signup a complete team,
		// but the other player will have to create a password. He will initially get 'Welkom01'.
		// He will receive an email with a link to the page where to create the password
		$data['player2']['name'] = array(
				'name'   => 'player2_name',
				'id'     => 'player2_name',
		);
		$data['player2']['email'] = array(
				'name'   => 'player2_email',
				'id'     => 'player2_email',
		);
		
		// team
		$data['team']['name'] = array(
				'name'   => 'team_name',
				'id'     => 'team_name',
		);
		
		
		
		$this->load->view('headerView');
		$this->load->view('signupView', $data);
		$this->load->view('footerView');
	}
}

/* Location: ./application/controllers/home.php */