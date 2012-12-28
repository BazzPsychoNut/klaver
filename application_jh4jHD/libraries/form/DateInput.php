<?php

require_once 'TextInput.php';


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
                
            // we sill show/hide the container div for the text field and the image and not the text field and the image themselves
            $this->removeStyle('display:none');
            $hidden = $this->getHidden() === true ? ' style="display:none;"' : '';
            
            // create TextInput object with all same properties as this DateInput object
            $text = new TextInput($this->name);
            copySharedAttributes($text, $this);
            $text->setMaxLength(10);
            $text->setWidth(80);
            
            // no new line between input field and the image so there will be no space interpreted by the browser
            if (! defined('IMAGE_ROOT'))
                define('IMAGE_ROOT', 'http://marketingweb.itservices.lan/images/');
            $output = '<span id="'.$this->id.'-container"'.$hidden.'>'
                    . $text->render()
                    . '<img class="date-button" id="button-'.$this->id.'" src="'.IMAGE_ROOT.'calendar.gif" height="28" style="vertical-align:-85%;" onmouseover="this.style.cursor=\'pointer\';" onmouseout="this.style.cursor=\'auto\';" />'
                    . "</span>\n";
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


