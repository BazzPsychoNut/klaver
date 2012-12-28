<?php

require_once 'form/Input.php';


class TextInput extends Input
{
    
    protected $maxLength;

    /**
     * render the hidden input element
     */
    public function render($echo = false)
    {
        try
        {
            // default validity check
            if (! $this->validate->isValid())
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name));
                
            $value     = ! empty($this->selected) ? $this->selected : $this->value;
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
            $maxLength = ! empty($this->maxLength) ? ' maxlength="'.$this->maxLength.'"' : '';
            
            $output = '';
            if (! empty($this->label) && ! $this->inReportForm)
                $output .= '<label for="'.$this->id.'">'.$this->label.'</label> ';
            $output .= '<input type="text" id="'.$this->id.'" class="'.$this->getClass().'" name="'.$this->name.'" value="'.$value.'" style="'.$this->getStyle().'"'.$disabled.$maxLength.' title="'.$this->title.'" />'."\n";
            
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
     * @return the $maxLength
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

	/**
     * @param int $maxLength
     */
    public function setMaxLength($maxLength)
    {
        if (! $this->validate->numeric($maxLength))
            throw new Exception('Invalid value for maxLength: '.$maxLength);
        else
            $this->maxLength = $maxLength;
        
        return $this;
    }

}


