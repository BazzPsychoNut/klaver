<?php

require_once 'Input.php';


class Button extends Input
{
    

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
                
            $output = $this->getLabel().'<input type="button"'.$this->getId().$this->getClass().$this->getName().$this->getValue().$this->getStyle().$this->getDisabled().$this->getTitle().$this->getOnchange().$this->getOnclick().' />'."\n";
            
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


