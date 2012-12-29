<?php

require_once APPPATH.'libraries/Form.php';

class SignupForm extends Form 
{
	public 	$player1 = array(),
		   	$player2 = array(),
			$team,
			$singles,
			$submit;
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		
		$this->name = $name;
		
		// player 1
		$this->player1['name'] = new TextInput('player1_name');
		$this->player1['name']->setLabel('Naam')->setRequired(true);
		
		$this->player1['password'] = new PasswordInput('password');
		$this->player1['password']->setLabel('Wachtwoord')->setRequired(true);
		
		$this->player1['password_confirmation'] = new PasswordInput('password_confirmation');
		$this->player1['password_confirmation']->setLabel('Nogmaals')->setRequired(true);
		
		$this->player1['email'] = new TextInput('player1_email');
		$this->player1['email']->setLabel('E-mail')->setRequired(true);
		
		// player 2
		$this->player2['name'] = new TextInput('player2_name');
		$this->player2['name']->setLabel('Naam');
		
		$this->player2['email'] = new TextInput('player2_email');
		$this->player2['email']->setLabel('E-mail');
		
		// singles
		// select players without a team or players without someone else in their team ;)
		$sql = "select p.player_id
				,      p.name
				from
				(
				    select min(player_id) player_id
				    from   players 
				    group by team_id
				    having min(player_id) = max(player_id)
				    union
				    select player_id
				    from   players
				    where  team_id is null
				) t
				join   players p on t.player_id = p.player_id
				order by name";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		if (! empty($result))
		{
			$options = array();
			foreach ($result as $row)
				$options[$row['player_id']] = $row['name'];
			
			$this->singles = new Dropdown('singles');
			$this->singles->setLabel('Vorm een team met:')->addStyle('min-width:200px;')->appendOptions($options);
		}
		
		// team
		$this->team = new TextInput('team_name');
		$this->team->setLabel('Teamnaam');
		
		// submit
		$this->submit = new SubmitButton('aanmelden', 'Aanmelden');
		$this->submit->setLabel('&nbsp;')->addStyle('margin-top:20px');
	}
	
	/**
	 * render the form
	 * @return html 
	 */
	public function render() 
	{
		// render the form with the input fields
		$output = '<form name="'.$this->name.'" method="'.$this->method.'" action="'.$this->action.'" enctype="'.$this->enctype.'">'."\n";
		
		$output .= '<h2>Speler 1</h2>'."\n";
		foreach ($this->player1 as $input)
			$output .= $input->render().BRCLR;
		
		$output .= '<h2>Speler 2</h2>'."\n";
		foreach ($this->player2 as $input)
			$output .= $input->render().BRCLR;
		
		if (! empty($this->singles))
		{
			$output .= '<p style="margin-bottom:0; font-weight:bold;">Of</p>'."\n";
			$output .= $this->singles->render().BRCLR;
		}
		
		$output .= '<h2>Team</h2>'."\n";
		$output .= $this->team->render().BRCLR;
		
		$output .= $this->submit->render().BRCLR;
		
		$output .= "</form>\n";
		
		return $output;
	}
	
	/**
	 * validate the input
	 * @return boolean
	 */
	public function validate()
	{
		if (! $this->isPosted())
			return null;
		
		$validate = new Validate();
		
		// required fields
		foreach ($this->player1 as $input)
		{
			if (! $input->isPosted())
				$this->invalidate($input, 'Dit is een verplicht veld');
		}
		
		// emails
		if (! $validate->email($this->player1['email']->getPosted()))
			$this->invalidate($this->player1['email'], 'Dit is een ongeldig email adres.');
		if ($this->player2['email']->isPosted() && ! $validate->email($this->player2['email']->getPosted()))
			$this->invalidate($this->player2['email'], 'Dit is een ongeldig email adres.');
		
		// passwords
		if ($this->player1['password']->getPosted() != $this->player1['password_confirmation']->getPosted())
		{
			$this->invalidate($this->player1['password'], 'De wachtwoorden zijn niet gelijk.');
			$this->invalidate($this->player1['password_confirmation'], 'De wachtwoorden zijn niet gelijk.');
		}
		elseif (! $validate->password($this->player1['password']->getPosted()))
			$this->invalidate($this->player1['password'], 'Dit wachtwoord is te simpel. Het moet uit letters en cijfers bestaan en tenminste 6 karakters lang zijn.');
		
		// player 2
		if ($this->player2['name']->isPosted() xor $this->player2['email']->isPosted())
			$this->invalidate($this->player2['name'], 'Kan alleen 2e speler invoeren als zowel naam als e-mail adres worden opgegeven.');
		
		// team
		// I don't really care what team name is given. It will always be overwritten by anyone in the team changing it.
	}
	
	/**
	 * Is the form posted?
	 * @return boolean
	 */
	public function isPosted()
	{
		return (! empty($_POST['aanmelden'])) ;
	}
	
}

