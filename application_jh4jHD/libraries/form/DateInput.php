<?php

require_once 'form/Input.php';


class DateInput extends Input
{

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
                
            $value     = ! empty($this->selected) ? $this->selected : $this->value;
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
            if (! empty($this->width))
                $this->style .= ' width:'.$this->width.'px;';

            // we sill show/hide the container div for the text field and the image
            $hidden    = $this->getHidden() === true ? ' style="display:none;"' : '';
            
            // start output
            if (! empty($this->label) && ! $this->inReportForm)
                $output .= '<label for="'.$this->id.'">'.$this->label.'</label> ';
                
            // no new line between input field and the image so there will be no space interpreted by the browser
            $output .= '<span id="'.$this->id.'-container" '.$hidden.'><input type="text" id="'.$this->id.'" class="'.$this->class.'" name="'.$this->name.'" value="'.$value.'" size="10" style="'.$this->style.'" '.$disabled.' title="'.$this->title.'" /><img class="date-button" id="button-'.$this->id.'" src="'.IMAGE_ROOT.'calendar.gif" height="28" style="vertical-align:-85%;" onmouseover="this.style.cursor=\'pointer\';" onmouseout="this.style.cursor=\'auto\';" />'."\n";
            $output .= "</span>\n";
            if (empty($this->disabled))
            {
	            $output .= '<script type="text/javascript">
        	    		    Calendar.setup({
        	    		        inputField  : "'.$this->id.'",   // ID of the input field
        	    		        ifFormat    : "%d-%m-%Y",    // the date format
        	    		        button      : "button-'.$this->id.'",     // ID of the button
        	    		        firstDay	: 1
        	    		    });
        	    		    </script>'."\n";
            }
    		
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


