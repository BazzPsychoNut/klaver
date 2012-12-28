<?php

require_once 'Input.php';


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
    		    
                if (! empty($this->renderInColumns))
    		    {
    		        $rowsPerColumn = ceil(count($this->values) / $this->renderInColumns);
    		    }
    		    
    		    $columns = array(); // array to collect the values per column in
    		    $classesAtStart = $this->classes;
		        foreach ($this->values as $i => $value)
		        {
		            $checked = in_array($value, $selectedValues) ? ' checked="checked"' : '';
		            $id = $this->id.'-'.$this->toValidHtmlId($value);
		            
		            // add category class if it exists
		            if (isset($this->categories[$i]))
		            {
		                $this->addClass(str_replace(' ', '', $this->categories[$i]));
		            }
		            
		            $checkbox = '<input type="checkbox" id="'.$id.'"'.$this->getClass().$this->getName('[]').' value="'.$value.'"'.$checked.$this->getDisabled().$this->getTitle().$this->getOnclick().' />'."\n";
                    if (isset($this->labels[$i])) // I also want to display 0
                    {
                        $checkbox .= '<label for="'.$id.'">'.$this->labels[$i].'</label>'."\n";
                    }
                    
                    // render in columns per category, per given columns number, or don't render in columns
                    if (! empty($this->categories[$i]))
                    {
                        $columns[$this->categories[$i]][] = $checkbox;
                    }
                    elseif (! empty($this->renderInColumns))
                    {
                        $columns[floor($i / $rowsPerColumn)][] = $checkbox;
                    }
                    else
                    {
                        $output .= $checkbox;
                    }
                    
                    // reset classes, so the last entry doesn't have all category classes :)
                    $this->classes = $classesAtStart;
		        }
		        
		        if (! empty($columns))
		        {
		            // recreate the $output
		            $output = '<table><tr>';
		            $colcount = 0;
		            foreach ($columns as $key => $col)
		            {
		                $output .= '<td valign="top">';
		                if (! empty($this->categories)) 
		                {
		                    $categoryId = str_replace(' ', '', $key);
		                    $output .= '<input type="checkbox" class="'.$this->name.'-category" name="'.$categoryId.'" id="'.$categoryId.'"><label for="'.$categoryId.'" style="font-weight:bold;">'.$key."</label></strong><br/>\n";
		                }
		                
		                foreach ($col as $checkbox)
		                {
		                    $output .= $checkbox."<br/>\n";
		                }
		                $output .= '</td>';
		                
		                // go to new table row if the number of columns set in renderInColumns is reached. Add empty td for spacing.
		                if (! empty($this->categories) && ! empty($this->renderInColumns) && ++$colcount % $this->renderInColumns == 0)
		                    $output .= '</tr><tr><td colspan="'.$this->renderInColumns.'" style="padding-top:10px;"></td></tr><tr>'."\n";
		            }
		            $output .= '</tr></table>'."\n";
		            
		            // add js for the category checkboxes
		            if (! empty($this->categories)) 
		            {
    		            $output .= '<script type="text/javascript">
                            		// category checkbox: check all underlying checkboxes when a category name is clicked
                                    $("input.'.$this->name.'-category").click(function() {
                                        var classname = $(this).attr("name");
                                        var bool = $(this).is(":checked");
                                        $("."+classname).attr("checked", bool);
                                    });
    	                            </script>'."\n";
		            }
		        }
		        
		        $output = $this->getLabel().'<div id="'.$this->id.'-container"'.$this->getStyle().'>'."\n".$output."</div>\n";
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
			
    		    $output = '<input type="checkbox"'.$this->getId().$this->getClass().$this->getName().$this->getValue().$checked.$this->getStyle().$this->getDisabled().$this->getTitle().$this->getOnchange().$this->getOnclick().' />'."\n";
                if (! empty($this->label))
                {
                    $output .= '<label for="'.$this->id.'">'.$this->label.'</label>'."\n";
                }
                
                $output = '<div id="'.$this->id.'-container"'.$this->getStyle().'>'."\n".$output."</div>\n";
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
        
        return $this;
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
        
        return $this;
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
        
        return $this;
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
            
            if ($this->orientation == 'VERTICAL')
                $this->setRenderInColumns(1);
            else
                $this->setRenderInColumns(0);
        }
        
        return $this;
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

