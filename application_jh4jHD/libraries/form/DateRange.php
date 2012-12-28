<?php

require_once 'DateInput.php';

/**
 * Create 2 date input fields for selecting a date range
 *
 * @author b-deruiter
 */
class DateRange extends Input
{
    // the 2 date objects
    protected $dateFrom,
    		  $dateTo;
    
    
    /**
     * construct DateRange object
     *
     * @param string $name
     * @param date-string $from
     * @param date-string $to
     */
    function __construct($name, $from, $to)
    {
        try
        {
            $this->validate = new Validate();
            
            $this->name = $name;
            
            if (! empty($from))
                $this->validate->date($from);
            if (! empty($to))
                $this->validate->date($to);
            
            if (! $this->validate->isValid())
                throw new Exception($this->validate->getMessage('Error creating '.get_class($this).' object with name '.$this->name));
            
            // create the two date input fields
            $this->setDateFrom(new DateInput($this->name.'-from', $from));
            $this->setDateTo(new DateInput($this->name.'-to', $to));
            
            // set default labels
            $this->setLabels(array('Between', 'and'));
        }
        catch (Exception $e)
        {
            return FormError::dump($e->getMessage());
        }
    }
    
    /**
     * render the date range input fields
     */
    public function render($echo = false)
    {
        try
        {
            // default validity check
            if (! $this->validate->isValid())
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name));
            
            // via casting we can pass all attributes that were set on DateRane down to the DateInput fields
            copySharedAttributes($this->dateFrom, $this, array('name','id','value','label','selected','posted'));
            copySharedAttributes($this->dateTo, $this, array('name','id','value','label','selected','posted'));
                
            // render the date range input fields
            $output = $this->dateFrom->render() . $this->dateTo->render();
            
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
     * set labels for the Dates
     *
     * @param array $labels (from, to)
     */
    public function setLabels($labels)
    {
        $this->dateFrom->setLabel($labels[0]);
        $this->dateTo->setLabel($labels[1]);
    }
    
    /**
     * get the labels of the dates
     * @return array (from, to)
     */
    public function getLabels()
    {
        return array($this->dateFrom->label, $this->dateTo->label);
    }
    
	/**
     * @return DateInput $dateFrom
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

	/**
     * @return DateInput $dateTo
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

	/**
     * @param DateInput $dateFrom
     */
    public function setDateFrom($dateFrom)
    {
        if ($this->validate->isInstanceOf($dateFrom, 'DateInput'))
        {
            $this->dateFrom = $dateFrom;
        }
        
        return $this;
    }

	/**
     * @param DateInput $dateTo
     */
    public function setDateTo($dateTo)
    {
        if ($this->validate->isInstanceOf($dateTo, 'DateInput'))
        {
            $this->dateTo = $dateTo;
        }
        
        return $this;
    }

    
    
}


