<?php

require_once 'Input.php';
require_once 'Dropdown.php';

class WeekInput extends Input
{
    // the text input fields
    protected $year,
    		  $week,
    		  $hiddenInput; // $hidden already exists in Input
    
    
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
        $this->addClass('week-dropdown'); // this will be copied to children on render()
        
        // create week and year dropdown objects
        $this->year = new Dropdown($this->name.'-year');
        $this->year->setValues(range(date('Y')-3, date('Y')+1))
                    ->setLabels(range(date('Y')-3, date('Y')+1))
                    ->setSelected(date('Y'));
        
        // create array with week numbers from 01 to 53
        $weeks = array();
        for ($i=1; $i<=53; $i++)
        	$weeks[] = str_pad($i, 2, '0', STR_PAD_LEFT);
    
        $this->week = new Dropdown($this->name.'-week');
        $this->week->setValues($weeks)
                    ->setLabels($weeks)
                    ->setSelected(date('W'));
        
        // this will be the field we will actually fetch when posted, because that's easier. ;)
        // we need javascript to fill it when the dropdowns change, tho.
        $this->hiddenInput = new HiddenInput($name, date('Y-W'));
        
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
        if (empty($this->posted) || $this->isFlagSet($flag, self::INPUT_OVERRULE_POST))
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
                    $this->hiddenInput->setValue($selected); 
                }
            }
        }
        
        return $this;
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
            
            // copy the attributes given to WeekInput to the children year and week
            $excludes = array('name','id','value','label','selected','posted','required','invalidations');
            copySharedAttributes($this->year, $this, $excludes);
            copySharedAttributes($this->week, $this, $excludes);
                
            // start output
            if (! empty($this->label) && ! $this->inReportForm)
                $output .= '<label for="'.$this->id.'">'.$this->label.'</label> ';

            // The applied styles will only apply to the container
            $output .= '<span id="'.$this->id.'-container"'.$this->getStyle().'>';
            $output .= $this->year->render();
            $output .= $this->week->render();
            $output .= $this->hiddenInput->render();
			$output .= "</span>\n";
			
			// javascript to change the hidden input field when a dropdown changes
			// I create a trigger by id, so when there are multiple WeekInputs on the screen these scripts wont all fire every time one changes
			$output .= '<script type="text/javascript">
			                // fill hidden input field when one of the dropdowns changes
		                	$("#'.$this->name.'-year, #'.$this->name.'-week").change(function() {
        						$("#'.$this->name.'").val($("#'.$this->name.'-year").val() + "-" + $("#'.$this->name.'-week").val());
        					});
		                </script>'."\n";
            
    		$output .= $this->renderInvalidations();
            
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


