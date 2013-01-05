<?php

require_once APPPATH.'libraries/Form.php';

class ChangeTeamForm extends Form 
{
	public 	$team,
			$submit;
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		
		$this->name = $name;
		
		$this->team = new TextInput('team');
		$this->team->setLabel('Team naam');
		
		// submit
		$this->submit = new SubmitButton('change_team', 'Bewaren');
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
		
		// anything posted ?
		if (! $this->team->isPosted())
		    $this->invalidate($this->team, 'Er is niets ingevoerd om te wijzigen.');
		
		return $this->isValid;
	}
	
	/**
	 * Is the form posted?
	 * @return boolean
	 */
	public function isPosted()
	{
		return $this->submit->isPosted();
	}
	
}

