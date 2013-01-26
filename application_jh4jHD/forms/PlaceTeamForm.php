<?php

require_once APPPATH.'libraries/Form.php';

class PlaceTeamForm extends Form 
{
	public 	$team,
			$poule,
			$submit;
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		
		$this->name = $name;
		
		$this->team = new Dropdown('team');
		$this->team->setLabel('Team');
		$this->team->appendOptions($this->fetch_teams_options());
		
		$this->poule = new RadioInput('poule');
		$this->poule->setLabel('Poule');
		$this->poule->appendOptions(array(1 => 'Poule A', 2 => 'Poule B'));
		
		// submit
		$this->submit = new SubmitButton('place_team', 'Plaatsen');
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
		$output .= $this->poule->render().BRCLR;
		
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
	
	/**
	 * fetch the teams to fill the dropdown
	 */
	protected function fetch_teams_options()
	{
	    $options = array();
	    
	    $sql = "select t.team_id
				,      concat(t.name, ' (', min(p.name), ' en ', max(p.name), ')') name
				from   teams t
				join   players p on t.team_id = p.team_id
				group by t.team_id
                ,      t.name
                order by name";
	    $query = $this->db->query($sql);
	    foreach ($query->result_array() as $row)
	        $options[$row['team_id']] = $row['name'];
	    
	    return $options;
	}
	
}

