<?php

require_once APPPATH.'libraries/Form.php';

class ChangePasswordForm extends Form 
{
	public 	$old_password,
			$password,
			$password_confirmation,
			$submit;
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		
		$this->name = $name;
		
		$this->old_password = new PasswordInput('old_password');
		$this->old_password->setLabel('Oude wachtwoord')->setRequired(true);
		
		$this->password = new PasswordInput('password');
		$this->password->setLabel('Nieuw wachtwoord')->setRequired(true);
		
		$this->password_confirmation = new PasswordInput('password_confirmation');
		$this->password_confirmation->setLabel('Nogmaals')->setRequired(true);
		
		// submit
		$this->submit = new SubmitButton('change_password', 'Bewaren');
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
		
		$output .= $this->old_password->render().BRCLR;
		$output .= $this->password->render().BRCLR;
		$output .= $this->password_confirmation->render().BRCLR;
		
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
		if (! $this->old_password->isPosted())
			$this->invalidate($this->old_password, 'Dit is een verplicht veld');
		if (! $this->password->isPosted())
			$this->invalidate($this->password, 'Dit is een verplicht veld');
		if (! $this->password_confirmation->isPosted())
			$this->invalidate($this->password_confirmation, 'Dit is een verplicht veld');
		
		// password
		if ($this->password->getPosted() != $this->password_confirmation->getPosted())
		{
			$this->invalidate($this->password, 'De wachtwoorden zijn niet gelijk.');
			$this->invalidate($this->password_confirmation, 'De wachtwoorden zijn niet gelijk.');
		}
		elseif (! $validate->password($this->password->getPosted()))
			$this->invalidate($this->password, 'Dit wachtwoord is te simpel. Het moet uit letters en cijfers bestaan en tenminste 6 karakters lang zijn.');

		return $this->isValid;
	}
	
	/**
	 * Is the form posted?
	 * @return boolean
	 */
	public function isPosted()
	{
		return (! empty($_POST['change_password']));
	}
	
}

