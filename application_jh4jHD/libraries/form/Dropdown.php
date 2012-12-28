<?php

require_once 'form/Input.php';


class Dropdown extends Input
{
    
    protected $multiple;
    protected $size;
    protected $categories;
    
   
    
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
            
            $multiple = $this->multiple === true ? ' multiple="multiple"' : '';
            $size = ! empty($this->size) ? ' size="'.$this->size.'"' : '';
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
            if ($this->getHidden() === true)
                $this->style .= ' display:none;';
            if (! empty($this->width))
                $this->style .= ' width:'.$this->width.'px;';
            
            // create select tag
            $output = '';
            if (! empty($this->label) && ! $this->inReportForm)
                $output .= '<label for="'.$this->id.'">'.$this->label.'</label>';
                
            // multi select dropdown?
            if ($this->multiple === true)
    		{
                $output .= '<span class="dropdown-multipletext">Hold Ctrl to (de)select multiple.</span><br/>'."\n";
                $name = substr($this->name, -2) != '[]' ? $this->name.'[]' : $this->name;
    		}
    		else
    		{
    		    $name = $this->name;
    		}
    		$onchange = ! empty($this->onchange) ? ' onchange="'.$this->onchange.'"' : null;
    		$onclick  = ! empty($this->onclick) ? ' onclick="'.$this->onclick.'"' : null;
    		    
    		$output .= '<select class="'.$this->class.'" name="'.$name.'" id="'.$this->id.'"'.$multiple.$size.' style="'.$this->style.'" '.$disabled.' title="'.$this->title.'"'.$onchange.$onclick.'>'."\n";
    		
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
	                $selected = in_array($value, $selectedValues) ? 'selected="selected" ' : '';
                else
                    $selected = $value == $selectedValues ? 'selected="selected" ' : '';
                    
	            $id = $this->id.'-'.$this->toValidHtmlId($value);
	            
	            // the actual option
		        $output .= '<option id="'.$id.'" value="'.$value.'" '.$selected.'>'.$this->labels[$i]."</option>\n";
		        
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
     * use $query object to populate options list
     * @param Query $query
     * @param string $valueColumn
     * @param string $labelColumn
     * @param string $categoryColumn
     */
    public function appendOptionsFromQuery(Query $query, $valueColumn = 'VALUE', $labelColumn = 'OPTION', $categoryColumn = null)
    {
        if ($this->validate->isQuery($query)) // validate and execute $query
        {
            foreach ($query->fetchAll() as $row)
            {
                $this->appendOption( isset($row[strtoupper($valueColumn)]) ? $row[strtoupper($valueColumn)] : null,
                                     isset($row[strtoupper($labelColumn)]) ? $row[strtoupper($labelColumn)] : null,
                                     (! empty($categoryColumn) && isset($row[strtoupper($categoryColumn)])) ? $row[strtoupper($categoryColumn)] : null
                                   );
            }
        }
    }
    
    /**
     * @return the $multiple
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

	/**
     * @return the $size
     */
    public function getSize()
    {
        return $this->size;
    }

	/**
     * @param array $categories
     */
    public function setCategories($categories)
    {
        $categories = array_values($categories); // enforce numeric array
        if ($this->validate->isArray($categories))
        {
            $this->categories = $categories;
        }
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
    }

	/**
     * @param int $size
     */
    public function setSize($size)
    {
        if ($this->validate->numeric($size))
        {
            $this->size = $size;
        }
    }
    
    /**
     * append array of options ( $value => $label ) for the dropdown
     * @param $options
     */
    public function appendOptions($options)
    {
        foreach ($options as $value => $label)
        {
            $this->appendOption($value, $label);
        }
    }

    /**
     * Append individual option <option value="$value">$option</option>
     * @param string $value
     * @param string $label
     * @param optional string $category
     */
	public function appendOption($value, $label, $category = null)
    {
        $this->values[] = xsschars($value);
        $this->labels[] = xsschars($label);
        if (! empty($category))
            $this->categories[] = $category;
    }

    /**
     * Prepend individual option <option value="$value">$option</option>
     * @param string $value
     * @param string $label
     * @param optional string $category
     */
	public function prependOption($value, $label, $category = null)
    {
        // prepend values to array (Yes, this will set all other numeric keys 1 higher)
        array_unshift($this->values, $value);
        array_unshift($this->labels, $label);
        
        if (! empty($category))
            array_unshift($this->categories, $category);
    }

    
}


