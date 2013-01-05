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
				$sql = "select p.*
						,      t.name team_name 
						,      t.poule_id
						from   players  p
						left join teams t on p.team_id = t.team_id
						where  p.email = ?";
				$query = $this->db->query($sql, array($form->email->getPosted()));
				$row = $query->row_array();
				
				if ($row['level'] == 0)
					throw new Exception('Je account is niet actief. Klik op de activatielink in je e-mail.');
				
				list($password, $personal_salt) = explode(' ', $row['password']);
				if (sha1($form->password->getPosted() . $this->config->item('system_salt') . $personal_salt) != $password)
					throw new Exception('Ongeldig e-mail adres of wachtwoord.');
				
				// login succes, so store session data
				$this->session->set_userdata(array(
						'user_logged_in' 	=> true,
						'user_name'			=> $row['name'],
						'user_id'			=> $row['player_id'],
						'user_level'		=> $row['level'],
						'user_team_id'		=> $row['team_id'],
						'user_team'			=> $row['team_name'],
						'user_poule_id'     => $row['poule_id'],
						));
				
				// if password == 'Welkom01', the user is required to change his password
				if ($form->password->getPosted() == 'Welkom01')
				{
					$this->session->set_userdata(array('user_logged_in' => false, 'user_has_default_password' => true));
					redirect('account');
				}
				
				$data['feedback'] = success('Je bent nu ingelogd als '.$this->session->userdata('user_name').'.');
				
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
		
		$data['form'] = $form;
		
		if ($this->uri->segment(3) == 'new_email')
			$data['feedback'] = success('Je email adres is gewijzigd. Daarom is je account uitgeschakeld.<br/>Er is een e-mail naar het nieuwe adres verstuurd met een activatie link, zodat je weer kunt inloggen.');
		
		$this->load->view('headerView');
		$this->load->view('loginView', $data);
		$this->load->view('footerView');
	}
	
	
}

