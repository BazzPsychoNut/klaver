<?php

require_once 'form/Input.php';
require_once 'form/Dropdown.php';

class WeekInput extends Input
{
    // the text input fields
    protected $year,
    		  $week,
    		  $hidden;
    
    
    /**
     * construct Input
     * @param string $name
     * @param string optional $week
     */
    function __construct($name, $week = null)
    {
        $this->validate = new Validate();
    
        $this->setName($name);
        $this->setId($name); // normally you want the id to be the same as the name
        
        // create week and year dropdown objects
        $this->year = new Dropdown($this->name.'-year');
        $this->year->setValues(range(date('Y')-3, date('Y')+1));
        $this->year->setLabels(range(date('Y')-3, date('Y')+1));
        $this->year->setSelected(date('Y'));
        $this->year->addClass('week-dropdown');
        
        // create array with week numbers from 01 to 53
        $weeks = range(1,53);
        array_walk($weeks, function(&$nr){$nr = str_pad($nr, 2, '0', STR_PAD_LEFT);});
    
        $this->week = new Dropdown($this->name.'-week');
        $this->week->setValues($weeks);
        $this->week->setLabels($weeks);
        $this->week->setSelected(date('W'));
        $this->week->addClass('week-dropdown');
        
        // this will be the field we will actually fetch when posted, because that's easier. ;)
        // we need javascript to fill it when the dropdowns change, tho.
        $this->hidden = new HiddenInput($name, date('Y-W'));
        
        if (! empty($week))
        {
            $this->setSelected($week);
        }
    
        // store posted as selected
        $this->setPosted();
    }


    /**
     * @param string $selected
     * @param int $flag 
     */
    public function setSelected($selected, $flag = 0)
    {
        if ($this->isFlagSet($flag, self::INPUT_OVERRULE_POST))
        {
            if (strpos($selected, '-') === false)
                $this->validate->invalidate($selected, 'Invalid week string given. Needs to be format IYYY-IW.');
            else
            {
                list($year, $week) = explode('-', $selected);
                $this->validate->between($week, 1, 53);
                $this->validate->between($year, date('Y')-5, date('Y')+1);
                	
                if ($this->validate->isValid())
                {
                    $this->week->setSelected($week);
                    $this->year->setSelected($year);
                    $this->hidden->setValue($selected); 
                }
            }
        }
    }
    
    
	/**
     * render the date input element
     */
    public function render($echo = false)
    {
        try
        {
            $output = '';
            
            // default validity check
            if (! $this->validate->isValid())
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name));
                
            $value     = ! empty($this->selected) ? $this->selected : $this->value;
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
            if (! empty($this->width))
                $this->style .= ' width:'.$this->width.'px;';

            // we sill show/hide the container div for the dropdowns
            $hidden    = $this->getHidden() === true ? ' style="display:none;"' : '';
            
            // start output
            if (! empty($this->label) && ! $this->inReportForm)
                $output .= '<label for="'.$this->id.'">'.$this->label.'</label> ';
                
            $output .= '<span id="'.$this->id.'-container"'.$hidden.'>';
            $output .= $this->year->render();
            $output .= $this->week->render();
            $output .= $this->hidden->render();
			$output .= "</span>\n";
			
			// javascript to change the hidden input field when a dropdown changes
			// I create a trigger by id, so when there are multiple WeekInputs on the screen these scripts wont all fire every time one changes
			$output .= '<script type="text/javascript">
			                // fill hidden input field when one of the dropdowns changes
		                	$("#'.$this->name.'-year, #'.$this->name.'-week").change(function() {
        						$("#'.$this->name.'").val($("#'.$this->name.'-year").val() + "-" + $("#'.$this->name.'-week").val());
        					});
		                </script>
			           ';
            
    		if ($echo)
                echo $output;
            else
                return $output;
        }
        catch (Exception $e)
        {
            return FormError::dump($e->getMessage());
        }
    }
    
    
}


