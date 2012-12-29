<?php

require_once 'Input.php';


class PasswordInput extends Input
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
                
            $output = $this->getLabel().'<input type="password"'.$this->getId().$this->getClass().$this->getName().' value=""'.$this->getStyle().$this->getDisabled().$this->getMaxLength().$this->getTitle().$this->getOnchange().$this->getOnclick().' />'."\n";
            
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
    
	/**
     * @return the $maxLength
     */
    public function getMaxLength()
    {
        return ! empty($this->maxLength) ? ' maxlength="'.$this->maxLength.'"' : '';
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


