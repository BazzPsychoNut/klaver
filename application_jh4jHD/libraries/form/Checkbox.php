<?php

require_once 'form/Input.php';


class Checkbox extends Input
{
    
    protected $renderInColumns;
    protected $orientation = 'HORIZONTAL';
    
    
    /**
     * render the hidden input element
     */
	public function render($echo = false)
    {
        try
        {
            $output = '';
            
            // default validity check
            if (! $this->validate->isValid())
            {
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name, false));
            }
            
            // I will assume style adjustments apply to the container div if there are more
            // checkboxes and that it will apply to the checkbox if there is only one.
            if ($this->getHidden() === true)
                $this->style .= ' display:none;';
            if (! empty($this->width))
                $this->style .= ' width:'.$this->width.'px;';
                
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
            $onclick   = ! empty($this->onclick) ? ' onclick="'.$this->onclick.'"' : null;
                            
            // if it is a collection of checkboxes the property "values" is filled, otherwise the property "value"
            if (! empty($this->values))
            {
                // get the selected values
                $selectedValues = array(); // default empty array
                if (isset($this->selected))
                {
                    if (is_array($this->selected))
                        $selectedValues = $this->selected;
                    else
                        $selectedValues = array($this->selected);
                }
    		    
    		    if ($this->orientation == 'VERTICAL')
    		    {
    		        $this->setRenderInColumns(1);
    		    }
    		    if (! empty($this->renderInColumns))
    		    {
    		        $rowsPerColumn = ceil(count($this->values) / $this->renderInColumns);
    		    }
    		    
    		    $columns = array(); // array to collect the values per column in
		        foreach ($this->values as $i => $value)
		        {
		            $checked = in_array($value, $selectedValues) ? ' checked="checked" ' : '';
		            $id = $this->id.'-'.$this->toValidHtmlId($value);
		            
    		        $checkbox = '<input type="checkbox" id="'.$id.'" class="'.$this->class.'" name="'.$this->name.'[]" value="'.$value.'" title="'.$this->title.'"'.$checked.$disabled.$onclick.'/>'."\n";
                    if (! empty($this->labels[$i]))
                    {
                        $checkbox .= '<label for="'.$id.'">'.$this->labels[$i].'</label>'."\n";
                    }
                    
                    if (! empty($this->renderInColumns))
                    {
                        $columns[floor($i / $rowsPerColumn)][] = $checkbox;
                    }
                    else
                    {
                        $output .= $checkbox;
                    }
		        }
		        
		        if (! empty($columns))
		        {
		            // recreate the $output
		            $output = '<table><tr>';
		            foreach ($columns as $col)
		            {
		                $output .= '<td valign="top">';
		                foreach ($col as $checkbox)
		                {
		                    $output .= $checkbox."<br/>\n";
		                }
		                $output .= '</td>';
		            }
		            $output .= '</tr></table>'."\n";
		        }
		        
		        if (! empty($this->label) && ! $this->inReportForm)
                {
                    $output = '<label for="'.$this->id.'">'.$this->label.'</label>'."\n".$output;
                }
		        $output = '<div id="'.$this->id.'-container" style="'.$this->style.'">'."\n".$output."</div>\n";
            }
            else // single checkbox
            {
                if (empty($this->value))
                {
                    $this->setValue('off');
                    if ($this->selected === true) // for intuitive use
                        $this->selected = 'off';
                }
                
    		    $checked = $this->value == $this->selected ? ' checked="checked" ' : '';
			
                $output .= '<input type="checkbox" id="'.$this->id.'" class="'.$this->class.'" name="'.$this->name.'" value="'.$this->value.'" style="'.$this->style.'" title="'.$this->title.'"'.$checked.$disabled.$onclick.'/>'."\n";
                if (! empty($this->label))
                {
                    $output .= '<label for="'.$this->id.'">'.$this->label.'</label>'."\n";
                }
                
                $output = '<div id="'.$this->id.'-container" style="'.$this->style.'">'."\n".$output."</div>\n";
            }
            
            // when posting, also send a hidden field so that if none of the options are selected
            // we still know that the checkbox has been posted
            require_once 'form/HiddenInput.php';
            $hidden = new HiddenInput($this->name.'-isPosted', 1);
            $output .= $hidden->render();
            
            // return or echo output
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
     * @param string $selected
     */
    public function setSelected($selected, $flag = 0)
    {
        if (! isset($_POST[$this->name.'-isPosted']) || $this->isFlagSet($flag, self::INPUT_OVERRULE_POST)) // check the hidden input field that is always send
        {
            // checkboxes can be array or single value. If array of values is given, make sure selected is an array
            if (! empty($this->values) && ! is_array($selected) && strlen($selected) != 0)
            {
                // assume commas mean a comma separated list
                if (strstr($selected, ','))
                    $selected = explode(',', $selected);
                else
                    $selected = array($selected);
            }
                
            $this->selected = $selected;
        }
    }
    
    /**
     * store posted values
     * @param $posted
     */
    protected function setPosted()
    {
        if (isset($_POST[$this->name.'-isPosted']) && isPosted($this->name)) // check the hidden input field that is always send
        {
            // checkboxes can be array or single value. If array of values is given, make sure posted is an array
            $posted = $_POST[$this->name];
            if (! empty($this->values) && ! is_array($posted))
                $posted = array($posted);
            
            $this->posted = $posted;
            $this->selected = $posted;  // so we can always retrieve the selected fields with getSelected()
        }
    }
    
    /**
     * give amount of columns the list should be displayed in
     * @param int $int
     */
    public function setRenderInColumns($int)
    {
        if ($this->validate->numeric($int))
        {
            $this->renderInColumns = $int;
        }
    }
    
    /**
     * set the orientation (place the fields horizontally or vertically on screen)
     * @param string $orientation
     */
    public function setOrientation($orientation)
    {
        if ($this->validate->inArray($orientation, array('HORIZONTAL','VERTICAL')))
        {
            $this->orientation = $orientation;
        }
    }
    
    /**
     * @return renderInColumns
     */
    public function getRenderInColumns()
    {
        return $this->renderInColumns;
    }
    
    /**
     * @return orientation
     */
    public function getOrientation()
    {
        return $this->orientation;
    }
    
    
}


?>
