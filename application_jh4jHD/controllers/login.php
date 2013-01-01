<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller 
{

	public function index()
	{
		$data = array();
		
		// create the form
		require_once APPPATH.'forms/LoginForm.php';
		$form = new LoginForm('login_form');
		
		// handle post of form
		if ($form->isPosted())
		{
			try
			{
				if (! $form->validate())
					throw new Exception('Het inloggen is mislukt, omdat niet alle velden goed zijn ingevuld.');
				
				// check email & password
				$sql = "select * from players where email = ?";
				$query = $this->db->query($sql, array($form->email->getPosted()));
				$row = $query->row_array();
				list($password, $personal_salt) = explode(' ', $row['password']);
				
				if (sha1($form->password->getPosted() . $this->config->item('system_salt') . $personal_salt) != $password)
					throw new Exception('Ongeldig e-mail adres of wachtwoord.');
				
				// login succes, so store session data
				$this->session->set_userdata(array(
						'user_logged_in' 	=> true,
						'user_name'			=> $row['name'],
						'user_id'			=> $row['player_id'],
						'user_level'		=> $row['level'],
						));
				
				// if password == 'Welkom01', the user is required to change his password
				if ($form->password->getPosted() == 'Welkom01')
				{
					$this->session->set_userdata(array('user_logged_in' => false, 'user_has_default_password' => true));
					redirect('account');
				}
				
				$data['feedback'] = success('Je bent nu ingelogd.');
				
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
		
		$data['form'] = $form;
		
		$this->load->view('headerView');
		$this->load->view('loginView', $data);
		$this->load->view('footerView');
	}
	
}

