<?php

require_once APPPATH.'libraries/Form.php';

class LoginForm extends Form 
{
	public 	$email,
			$password,
			$submit;
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		
		$this->name = $name;
		
		$this->email = new TextInput('email');
		$this->email->setLabel('E-mail')->setRequired(true);
		
		$this->password = new PasswordInput('password');
		$this->password->setLabel('Wachtwoord')->setRequired(true);
		
		// submit
		$this->submit = new SubmitButton('inloggen', 'Inloggen');
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
		
		$output .= $this->email->render().BRCLR;
		$output .= $this->password->render().BRCLR;
		
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
		if (! $this->email->isPosted())
			$this->invalidate($this->email, 'Dit is een verplicht veld');
		if (! $this->password->isPosted())
			$this->invalidate($this->password, 'Dit is een verplicht veld');
		
		// emails
		if (! $validate->email($this->email->getPosted()))
			$this->invalidate($this->email, 'Dit is een ongeldig email adres.');

		return $this->isValid;
	}
	
	/**
	 * Is the form posted?
	 * @return boolean
	 */
	public function isPosted()
	{
		return (! empty($_POST['inloggen']));
	}
	
}

