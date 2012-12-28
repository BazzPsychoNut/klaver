<?php

require_once APPPATH.'libraries/Form.php';

class SignupForm extends Form 
{
	protected $player1 = array(),
			  $player2 = array(),
			  $team,
			  $submit;
	
	
	
	function __construct($name = null) 
	{
		$this->name = $name;
		
		// player 1
		$this->player1['name'] = new TextInput('player1_name');
		$this->player1['name']->setLabel('Naam')->setRequired(true);
		
		$this->player1['password'] = new PasswordInput('password');
		$this->player1['password']->setLabel('Wachtwoord')->setRequired(true);
		
		$this->player1['password_confirmation'] = new PasswordInput('password_confirmation');
		$this->player1['password_confirmation']->setLabel('Wachtwoord nogmaals')->setRequired(true);
		
		$this->player1['email'] = new TextInput('player1_email');
		$this->player1['email']->setLabel('E-mail')->setRequired(true);
		
		// player 2
		$this->player2['name'] = new TextInput('player2_name');
		$this->player2['name']->setLabel('Naam');
		
		$this->player2['email'] = new TextInput('player2_email');
		$this->player2['email']->setLabel('E-mail');
		
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
		
		$output .= '<h2>Team</h2>'."\n";
		$output .= $this->team->render().BRCLR;
		
		$output .= $this->submit->render().BRCLR;
		
		$output .= "</form>\n";
		
		return $output;
	}
}

