<?php

require_once 'form/Input.php';


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
                
            $value = ! empty($this->selected) ? $this->selected : $this->value;
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
                
            $output = '<input type="hidden" id="'.$this->id.'" class="'.$this->class.'" name="'.$this->name.'" value="'.$value.'" style="'.$this->style.'"'.$disabled.' title="'.$this->title.'" />'."\n";
            
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


