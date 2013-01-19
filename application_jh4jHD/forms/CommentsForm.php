<?php

require_once APPPATH.'libraries/Form.php';

class CommentsForm extends Form 
{
	public 	$comment,
	        $submit;
	
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		$this->session = $CI->session;
		
		$this->name = ! empty($name) ? $name : __CLASS__;
		
		$this->comment = new TextAreaInput('comment');
		$this->comment->setLabel('Opmerking')->setRows(5)->setCols(50);
		
		// submit
		$this->submit = new SubmitButton('add_comment', 'Toevoegen');
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
		
		$output .= $this->comment->render().BRCLR;
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
		
		//$validate = new Validate();
		if (empty($this->comment))
		    $this->invalidate($this->comment, 'Kan geen leeg bericht plaatsen.');
		
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

