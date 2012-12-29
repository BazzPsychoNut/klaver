<?php

require_once 'Input.php';


class HiddenInput extends Input
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

            $output = '<input type="hidden"'.$this->getId().$this->getClass().$this->getName().$this->getValue().$this->getStyle().$this->getDisabled().$this->getTitle().$this->getOnchange().' />'."\n";
            
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


