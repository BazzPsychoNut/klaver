<?php

require_once 'form/Input.php';


class SubmitButton extends Input
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
                
            if ($this->getHidden() === true)
                $this->style .= ' display:none;';
            if (! empty($this->width))
                $this->style .= ' width:'.$this->width.'px;';
                                
            $output = '';
            if (! empty($this->label) && ! $this->inReportForm)
                $output .= '<label for="'.$this->id.'">'.$this->label.'</label> ';
                
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
                
            $output .= '<input type="submit" id="'.$this->id.'" class="'.$this->class.'" name="'.$this->name.'" value="'.$this->value.'" style="'.$this->style.'"'.$disabled.' title="'.$this->title.'" />'."\n";
            
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


