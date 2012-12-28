<?php

require_once 'Input.php';


class Dropdown extends Input
{
    
    protected $multiple;
    
   
    
    /**
     * render the dropdown
     */
	public function render($echo = false)
    {
        try
        {
            // default validity check
            if (! $this->validate->isValid())
            {
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name, false));
            }
                
            $selectedValues = ! empty($this->selected) ? $this->selected : '';
            
            // create select tag
            $output = $this->getLabel();
                
            // multi select dropdown?
            if ($this->multiple === true)
    		{
                $output .= '<span class="dropdown-multipletext">Hold Ctrl to (de)select multiple.</span><br/>'."\n";
                $name = $this->getName('[]');
    		}
    		else
    		{
    		    $name = $this->getName();
    		}
    		
    		$output .= '<select'.$this->getId().$this->getClass().$name.$this->getMultiple().$this->getSize().$this->getStyle().$this->getDisabled().$this->getTitle().$this->getOnchange().$this->getOnclick().'>'."\n";
    		
    		// create options
    		$lastCategory = '';
	        foreach ($this->values as $i => $value)
	        {
		        // end category?
		        if (! empty($this->categories[$i]) && ! empty($lastCategory) && $lastCategory != $this->categories[$i])
	            {
	                $output .= '</optgroup>'."\n";
	            }
	            // (new) category?
	            if (! empty($this->categories[$i]) && $lastCategory != $this->categories[$i])
	            {
	                $output .= '<optgroup label="'.$this->categories[$i].'">'."\n";
	                $lastCategory = $this->categories[$i];
	            }
	            
	            // selected
	            if (is_array($selectedValues))
	                $selected = in_array($value, $selectedValues) ? ' selected="selected"' : '';
                else
                    $selected = $value == $selectedValues ? ' selected="selected"' : '';
                    
	            $id = $this->id.'-'.$this->toValidHtmlId($value);
	            
	            // the actual option
		        $output .= '<option id="'.$id.'" value="'.$value.'"'.$selected.'>'.$this->labels[$i]."</option>\n";
		        
	        }
	        
	        // end last category?
	        if (! empty($lastCategory))
            {
                $output .= '</optgroup>'."\n";
            }
	        
	        $output .= '</select>'."\n";
            
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
     * @return the $multiple
     */
    public function getMultiple()
    {
        return $this->multiple === true ? ' multiple="multiple"' : null;
    }

    /**
     * @param boolean $multiple
     */
    public function setMultiple($multiple)
    {
        if ($this->validate->isBoolean($multiple))
        {
            $this->multiple = $multiple;
        }
        
        return $this;
    }

    
}


