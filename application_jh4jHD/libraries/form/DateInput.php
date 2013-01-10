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
            $text->setWidth(100);
            
            // no new line between input field and the image so there will be no space interpreted by the browser
            $output .= $text->render();
            if (empty($this->disabled))
            {
	            $output .= '<script type="text/javascript">
        	    		    $("input#'.$this->id.'").datepicker({ dateFormat: "dd-mm-yy" });  // = d-m-Y
        	    		    </script>'."\n";
            }
    		
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


