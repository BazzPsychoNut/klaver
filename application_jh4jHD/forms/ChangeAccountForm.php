<?php

require_once APPPATH.'libraries/Form.php';

class ChangeAccountForm extends Form 
{
	public 	$playername,
			$email,
			$submit;
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		
		$this->name = $name;
		
		$this->playername = new TextInput('playername');
		$this->playername->setLabel('Naam');
		
		$this->email = new TextInput('email');
		$this->email->setLabel('E-mail');
		
		// submit
		$this->submit = new SubmitButton('change_account', 'Bewaren');
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
		
		$output .= $this->playername->render().BRCLR;
		$output .= $this->email->render().BRCLR;
		
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
		
		// anything posted ?
		if (! $this->email->isPosted() && ! $this->playername->isPosted())
			$this->invalidate($this->playername, 'Er is niets ingevoerd om te wijzigen.');
		
		// email
		if ($this->email->isPosted() && ! $validate->email($this->email->getPosted()))
			$this->invalidate($this->email, 'Dit is een ongeldig email adres.');
		
		return $this->isValid;
	}
	
	/**
	 * Is the form posted?
	 * @return boolean
	 */
	public function isPosted()
	{
		return (! empty($_POST['change_account']));
	}
	
}

