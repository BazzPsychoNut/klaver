<?php

require_once 'form/FormError.php';
require_once 'form/WeekInput.php';

/**
 * Create 2 week input fields for selecting a week range
 *
 * @author b-deruiter
 */
class WeekRange extends Input
{
    // the 2 week objects
    protected $weekFrom,
              $weekTo;
    
    protected $validate;
    
    
    /**
     * construct WeekRange object
     *
     * @param string $name
     * @param iyyy-iw $from
     * @param iyyy-iw $to
     */
    function __construct($name, $from = null, $to = null)
    {
        try
        {
            $this->validate = new Validate();
            
            $this->name = $name;
            
            $this->setWeekFrom(new WeekInput($name.'-from', $from));
            $this->setWeekTo(new WeekInput($name.'-to', $to));
            
            // set default labels
            $this->setLabels(array('Between', 'and'));
        }
        catch (Exception $e)
        {
            return FormError::dump($e->getMessage());
        }
    }
    
    /**
     * render the week range dropdown fields
     */
    public function render($echo = false)
    {
        try
        {
            // default validity check
            if (! $this->validate->isValid())
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name));
                
            // render the week range dropdowns
            $output = '';
            $output .= $this->weekFrom->render();
            $output .= $this->weekTo->render();
            
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
    
    /**
     * set labels for the Weeks
     *
     * @param array $labels array(from, to)
     */
    public function setLabels($labels)
    {
        $this->weekFrom->setLabel($labels[0]);
        $this->weekTo->setLabel($labels[1]);
    }
    
	/**
     * @return the $weekFrom
     */
    public function getWeekFrom()
    {
        return $this->weekFrom;
    }

	/**
     * @return the $weekTo
     */
    public function getWeekTo()
    {
        return $this->weekTo;
    }

	/**
     * @param string $weekFrom
     */
    public function setWeekFrom($weekFrom)
    {
        if ($this->validate->isInstanceOf($weekFrom, 'WeekInput'))
        {
        	$this->weekFrom = $weekFrom;
        }
    }

	/**
     * @param string $weekTo
     */
    public function setWeekTo($weekTo)
    {
        if ($this->validate->isInstanceOf($weekTo, 'WeekInput'))
        {
        	$this->weekTo = $weekTo;
        }
    }

    
    
}


