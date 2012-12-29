<?php

require_once 'Input.php';

/**
 * class to create a <input type="file">
 * Don't forget to set the enctype of the form in order to actually upload the file!!
 * enctype="multipart/form-data"
 *
 */
class FileInput extends Input
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
                
            $output = $this->getLabel().'<input type="file"'.$this->getId().$this->getClass().$this->getName().$this->getSize().$this->getStyle().$this->getDisabled().$this->getTitle().$this->getOnchange().$this->getOnclick().' />'."\n";
            
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


?>