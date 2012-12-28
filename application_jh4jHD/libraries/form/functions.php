<?php
/**
 * php file with common functions for marketingweb, extern marketingweb and www3
 */


/**
 * return nth value in list
 * @param separator $SepChar
 * @param list $StringList
 * @param int $index
 */
function GetValue($SepChar, $StringList, $index)
{
    $array = explode($SepChar, $StringList);
    return isset($array[$index]) ? $array[$index] : false;
}

/**
 * return number of items in list
 * @param separator $SepChar
 * @param list $StringList
 */
function GetListCount($SepChar, $StringList)
{
	$array = explode($SepChar, $StringList);
	return count($array);
}

/**
 * replace <br> to new line
 * @param unknown_type $str
 */
function br2nl($str){
	return preg_replace('/\<br\s*\/?\>/i', "\n", $str);
}


//remove whole directory with its content
function rmdirr($dirname)
{
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }

    // Simple delete for a file
    if (is_file($dirname) || is_link($dirname)) {
        return unlink($dirname);
    }

    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Recurse
        rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
    }

    // Clean up
    $dir->close();
    return rmdir($dirname);
}

/**
 * @return if $val is between $from and $to
 * @param mixed $val
 * @param mixed $from
 * @param mixed $to
 */
function between($val, $from, $to)
{
    return ($val >= $from && $val <= $to);
}

/**
 * format string to us style number with 2 decimals
 *
 * @param string $number
 * @return formatted number
 */
function formatNumber($number)
{
    // check eerst of het euro of us stijl number is
    $dot = strrpos($number, '.');
    $comma = strrpos($number, ',');
    if ($dot !== false && $comma !== false)  // punt en komma
    {
        if ($dot > $comma)  // us stijl
            $number = str_replace(',', '', $number);
        else // euro stijl
            $number = str_replace(array('.',','), array('','.'), $number);  // verwijder punt en vervang komma door punt
    }
    elseif ($dot === false && $comma !== false)  // alleen komma
    {
        $number = str_replace(',', '.', $number);
    }
    // Bij alleen een punt ga ik er vanuit dat het gebruikt wordt als euro komma
    
    $number = (float) $number;
    return number_format($number, 2, '.', '');  // return "12345.67"
}


/**
 * Translates a camel case string into a string with underscores (e.g. firstName -> first_name)
 * @param    string   $str    String in camel case format
 * @return    string            $str Translated into underscore format
 */
function to_underscores($str)
{
    $str[0] = strtolower($str[0]);
    $func = create_function('$c', 'return "_" . strtolower($c[1]);');
    return preg_replace_callback('/([A-Z])/', $func, $str);
}


/**
 * Translates a string with underscores into camel case (e.g. first_name -> firstName)
 * @param    string   $str                     String in underscore format
 * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
 * @return   string                              $str translated into camel caps
 */
function to_camel_case($str, $capitalise_first_char = false)
{
    if ($capitalise_first_char)
    {
        $str[0] = strtoupper($str[0]);
    }
    $func = create_function('$c', 'return strtoupper($c[1]);');
    return preg_replace_callback('/_([a-z])/', $func, $str);
}

/**
 * Check of $name gepost is en of het een waarde heeft.
 * Als $value meegeven wordt, check dan of de gepostte waarde daaraan gelijk is.
 *
 * @param string $name
 * @param string $value
 * @return boolean
 */
function isPosted($name, $value = null)
{
    if (! isset($_POST[$name]))
        return false;
    
    if (is_array($_POST[$name]))
    {
        if (! empty($value))
            return in_array($value, $_POST[$name]);
        else
            return count($_POST[$name]) > 0;
    }
    else
    {
        if (! empty($value))
            return $_POST[$name] == $value;
        else
            return ! empty($_POST[$name]);
    }
}

/**
 * get value of $_POST[$name] if it exists, otherwise null
 * We can use this function now instead of putting this check everywhere
 * @param string $name
 * @param optional string $else value to return if POST[$name] is empty
 */
function postValue($name, $else = null)
{
    return isPosted($name) ? $_POST[$name] : $else;
}

/**
 * Check of $name meegegeven is als GET variable en of het een waarde heeft.
 * Als $value meegeven wordt, check dan of de gepostte waarde daaraan gelijk is.
 *
 * @param string $name
 * @param string $value
 * @return boolean
 */
function isGet($name, $value = null)
{
    if (! isset($_GET[$name]))
        return false;
    
    if (is_array($_GET[$name])) // weet niet of dit mogelijk is, maar och
    {
        if (! empty($value))
            return in_array($value, $_GET[$name]);
        else
            return count($_GET[$name]) > 0;
    }
    else
    {
        if (! empty($value))
            return $_GET[$name] == $value;
        else
            return ! empty($_GET[$name]);
    }
}

/**
 * check of opgegeven datum een geldige dd-mm-yyyy datum is
 *
 * @param string $date
 * @return boolean
 */
function isDate($date)
{
    if (! strstr($date, '-'))
        return false;
        
    $arr = explode('-', $date);
    
    if (count($arr) != 3)  // dd, mm en yyyy
        return false;
        
    list($d, $m, $y) = $arr;
    if (! checkdate($m, $d, $y))  // php built in function to check if date is valid
        return false;
    
    return true;
}

/**
 * convert given date string to Ymd date. Handy for determining later or earlier dates
 * @param string $date
 * @return string Y-m-d
 */
function dateYmd($date)
{
    if (isDate($date)) // then it's dd-mm-yyyy
    {
        $arr = explode('-', $date);
        return $arr[2].$arr[1].$arr[0];
    }
    else // dd-mmm-yy, mm-dd-yyyy or yyyy-mm-dd
    {
        return date('Ymd', strtotime($date));
    }
}

/**
 * Does email address look like a valid email?
 *
 * @param string $email
 * @return boolean
 */
function isValidEmail($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


/**
 * convert string containing email or comma-separated list of emails to Swift's email array
 * @param string $email
 */
function makeSwiftEmailArray($email)
{
	if (isValidEmail($email))
		return array($email);
		
	// if it's a list of emails, then run this function for each email address
	if (strstr($email, ','))
	{
		$emails = explode(',', $email);
		$return = array();
		foreach ($emails as $eml)
		{
			// array_merge, because if there is no name, this function returns array([0]=>email) (so initially same key 0 for every email)
			$return = array_merge($return, makeSwiftEmailArray(trim($eml)));
		}
		return $return;
	}
		
	// if the email address is in the name <email> format
	if (strstr($email, '<'))
	{
		$parts = explode('<', $email);
		$name = trim(trim($parts[0]), '"'); // first remove white spaces, then remove optional double quotes
		$address = trim(trim($parts[1]), '>'); // first remove white spaces, then remove the > at the end
		return array($address => $name);
	}
	
	// else error
	echo error("Couldn't extract valid email array from ".$email);
	return false;
}


/**
 * start html page
 *
 * @param boolean $closeHead
 * @return html <head> string
 */
function htmlHeader($closeHead = true, $jslibrary = 'prototype')
{
    ob_start();
    
    global $self;
    $title = basename($self, '.php');
    
//  $output  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'."\n";
//	$output .= '   "http://www.w3.org/TR/html4/strict.dtd">'."\n";
    $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
	$output .= '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
	$output .= "<head>\n";
	$output .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n"; // central europe
	$output .= '	<title>'.$title.'</title>'."\n";
	if ($jslibrary == 'prototype')
	{
    	$output .= '	<script type="text/javascript" src="'.WEB_URL.'js/prototype.js"></script>'."\n";
    	$output .= '	<script type="text/javascript" src="'.WEB_URL.'js/scripts.js"></script>'."\n";
    	$output .= '	<script type="text/javascript" src="'.WEB_URL.'js/tablesort.js"></script>'."\n";
	}
	elseif ($jslibrary == 'jquery')
	{
	    $output .= '    <script type="text/javascript" src="'.WEB_URL.'js/jquery.min.js"></script>'."\n";
	    $output .= '    <script type="text/javascript" src="'.WEB_URL.'js/marketingweb.js"></script>'."\n";
	}
	$output .= '	<script type="text/javascript" src="'.CALENDAR_ROOT.'calendar.js"></script>'."\n";
	$output .= '	<script type="text/javascript" src="'.CALENDAR_ROOT.'lang/calendar-en.js"></script>'."\n";
	$output .= '	<script type="text/javascript" src="'.CALENDAR_ROOT.'calendar-setup.js"></script>'."\n";
	$output .= '	<link type="text/css" rel="stylesheet" href="'.WEB_URL.'css/basic.css" />'."\n";
	$output .= '	<link type="text/css" rel="stylesheet" href="'.WEB_URL.'css/style.css" />'."\n";
	$output .= '	<link type="text/css" rel="stylesheet" href="'.CALENDAR_ROOT.'calendar-system.css" />'."\n";
	
	if ($closeHead)
	{
		$output .= "</head>\n";
		$output .= "<body>\n";
	}
	
	return $output;
}


function htmlFooter()
{
    @ob_end_flush();
    
    $output = @getCustomErrors();
    $output .= "</body>\n";
    $output .= "</html>\n";
    return $output;
}


function arrayToTable($array, $options = 'DEFAULT')
{
    // fouten afvangen
    if (empty($array))
        return false;
    if (!is_array($array))
        return false;
        
    // mogelijke flags: 'DEFAULT','VERTICAL_HEADERS','EXCEL','NO_TOTALS'
    if (! is_array($options))
        $options = explode(",", $options);
    
	// initialiseer arrays
    $columnNames = $totals = array();
	
	// zoek eerst row met meeste velden
	// zodat we zeker geen velden missen
	$numFields = 0;
	$columnsRow = 0;
	foreach ($array as $num => $row)
	{
		if (count($row) > $numFields)
		{
			$numFields = count($row);
			$columnsRow = $num;
		}
	}
	
	$thStyle = in_array('VERTICAL_HEADERS', $options) ? ' style="writing-mode:tb-rl; filter:flipv fliph; vertical-align:bottom;"' : '';
	
	// geef array als html table weer
	$table  = '<table class="result sortable">'."\n";
	$table .= '  <thead>'."\n";
	$table .= '    <tr class="tablehead">'."\n";
	$table .= "      <th>#</th>\n";
	foreach ($array[$columnsRow] as $head => $val)  // loop door de eerder bepaalde "langste" rij
	{
		$table .= '      <th'.$thStyle.'>'.initcap(str_replace('_', ' ', $head)).'</th>'."\n";
		$columnNames[] = $head;  // maak kolomnamen array
		$totals[$head] = 0;  // initialiseer totalen array
	}
	$table .= '    </tr>'."\n";
	$table .= '  </thead>'."\n";
	$table .= '  <tbody>'."\n";
	
	$color = '#ffffff';  // hard-coded, zodat het ook in excel goed komt
	$rowline = 'roweven';
	$rownum = 1;
	$too_many_rows = false;
	$maxRows = in_array('EXCEL', $options) ? 65000 : 1000;
	foreach ($array as $row)
	{
        if ($too_many_rows)
        {
            $rownum++;
        }
		// We cannot use getRowNum() within this query (apparently), so we just loop through all records
		elseif ($rownum >= $maxRows)
		{
			$too_many_rows = true;
			$rownum++;
		}
		else
		{
    	    if (in_array('EXCEL', $options))
    	    {
    			$table .= "    <tr>\n";
    			$table .= '      <td style="background-color:'.$color.'">'.$rownum++."</td>\n";
    	    }
    	    else // html
    	    {
    			$table .= '    <tr class="'.$rowline.'">'."\n";  // even / oneven
    			$table .= '      <td>'.$rownum++."</td>\n";
    	    }
    		
    		foreach ($columnNames as $colname)
    		{
    		    // Total kolom is grijs
    		    // voor output naar excel moet de achtergrondkleur per cell opgegeven worden.
    		    // anders geen stijl, behalve als de waarde numeriek is. Dan rechts uitlijnen.
    		    if ($colname == 'Total')  // o.a. voor $query->toPivotArray()
                    $table .= '      <td style="font-weight:bold; background-color:#c8c8c8;';
    		    elseif (in_array('EXCEL', $options))
                    $table .= '      <td style="background-color:'.$color.';';
    		    else
                    $table .= '      <td style="';
                    
    			if (strlen($row[$colname]) == 0)  // afhandelen mogelijke NULLs
    			    $table .= '"></td>'."\n";
			    else
    			{
        			if (is_numeric($row[$colname]))
        			{
        				$table .= ' text-align:right;';  // right align numbers
        				$totals[$colname] += $row[$colname]; // add value to total
        			}
        			$table .= '">'.$row[$colname]."</td>\n";
    			}
                    
    		}
    		$table .= "    </tr>\n";
    		
    		$color = $color == '#ffffff' ? '#dcddde' : '#ffffff';  // change background-color for the next row (excel)
    		$rowline = $rowline == 'roweven' ? 'rowodd' : 'roweven'; // row class (html)
		}
	}
	$table .= '  </tbody>'."\n";
        
    if (! in_array('NO_TOTALS', $options))
    {
        $table .= '  <tfoot>'."\n";
        $table .= '    <tr>'."\n";
        $table .= '      <td></td>'."\n";  // row number
        foreach ($columnNames as $colname)
            $table .= '      <td style="text-align:right; font-weight:bold; background-color:#c8c8c8;">'. (isset($totals[$colname]) ? $totals[$colname] : ' ') . '</td>'."\n";
        $table .= '      </tr>'."\n";
        $table .= "  </tfoot>\n";
    }
        
	$table .= '</table>'."\n";
	
	if ($too_many_rows)
		$table .= '<div class="info">Gestopt bij '.$maxRows.' rijen. Er zijn in totaal '.$rownum.' rijen.</div>'."\n";
		
	return $table;
}

/**
 * array left join array2 on (array of) $fields = (array of) $fields
 * notice: rows of array2 can be joined multiple times to array, but rows of array are considered unique.
 *
 * @param array $array
 * @param array $array2
 * @param mixed $fields array of fields (or single value) to join the arrays on
 * @return joined array
 */
function array_join($array, $array2, $fields)
{
    if (!is_array($array) || !is_array($array2))
        return false;
        
    // create array if single value is given
    if (!is_array($fields))
        $fields = array($fields);
    
    $count = count($fields);  // store count in variable
        
	foreach ($array as $nr => $row)
	{
	    foreach($array2 as $nr2 => $row2)
	    {
	        // check all join fields
	        $c = $count;
	        foreach ($fields as $field)
	        {
	            if ($row[$field] == $row2[$field])
	               $c--;
	        }
	        
	        // if all fields are the same counter $c will be 0
	        if ($c == 0)
	        {
	            $array[$nr] += $row2;  // add data of found row to array
	            break;  // stop after first found match and go to next row
	        }
	    }
	}
	
	return $array;
}

/**
 * Check if any value in array one exists in array two
 *
 * @param array $array1
 * @param array $array2
 * @return boolean
 */
function any_in_array($array1, $array2)
{
    foreach ($array1 as $a)
    {
        if (in_array($a, $array2))
            return true;
    }
    return false;
}

/**
 * convert any array to an object
 * I like this better than simply type casting, because this will convert the keys to lowercase
 * and can convert multidimensional arrays through recursion.
 *
 * @param array $array
 */
function arrayToObject($array)
{
    if(! is_array($array))
        return $array;
    
    $object = new stdClass();
    if (is_array($array) && count($array) > 0)
    {
        foreach ($array as $name => $value)
        {
            $name = strtolower(trim($name));
            if (! empty($name))
                $object->$name = arrayToObject($value);
        }
        return $object;
    }
    else {
        return false;
    }
}


/**
* Convert special characters to HTML entities. All untrusted content
* should be passed through this method to prevent XSS injections.
*
* echo xsschars($username);
*
* @param string   $value string   to convert
* @param boolean  $double_encode  encode existing entities
* @return string
*/
function xsschars($value, $double_encode = TRUE)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'ISO-8859-1', $double_encode);
}

/**
 * secho - safe echo function
 * @param $value
 */
function secho($value)
{
    echo xsschars($value);
}

/**
 * safely echo a posted value if it exists
 * @param string $value
 */
function echoPost($value)
{
    if (isPosted($value))
        secho($_POST[$value]);
}

function echoRequest($name)
{
    if (! empty($_REQUEST[$name]))
        secho($_REQUEST[$name]);
}


/**
 * get the first and last day of given period
 * All periods that are by default used in the ReportForm marketingwebpages, including day and year,
 * are accepted for ease of programming
 *
 * @param $period
 * @param period type $type ('day','week','month','quarter' or 'year')
 * @return array($firstDate, $lastDate)
 */
function getPeriodFirstAndLast($period, $type)
{
    global $debug;
    try
    {
        $type = strtolower($type);
        if ($type == 'day')
        {
            $parts = explode('-', $period);
            $day = $parts[2].'-'.$parts[1].'-'.$parts[0];
            return array($day, $day);
        }
        elseif ($type == 'week')
        {
            if (strlen($period) != '7' || strstr($period, '-') === false)
                throw new Exception('Invalid week period given (IYYY-IW) - '.$period);
            
            $year = substr($period,0,4);
            $week = substr($period,-2);
            // jan 4th is always in ISO week 1
            $date = strtotime($year.'0104 +'.($week - 1).' weeks');
            // first day of week is sunday. If date('w') == 0 the it's sunday
            $first = date('w', $date) == 0 ? $date : strtotime('last sunday', $date);
            $last = date('w', $date) == 6 ? $date : strtotime('next saturday', $date);
            
            return array(date('d-m-Y', $first), date('d-m-Y', $last));
        }
        elseif ($type == 'month')
        {
            if (strlen($period) != '7' || strstr($period, '-') === false)
                throw new Exception('Invalid month period given (YYYY-MM) - '.$period);
            
            $year = substr($period,0,4);
            $month = substr($period,-2);
            
            $first = strtotime($year.$month.'01');
            $last = strtotime($year.$month.date('t',$first)); // date('t') returns number of days in given month
            
            return array(date('d-m-Y', $first), date('d-m-Y', $last));
        }
        elseif ($type == 'quarter')
        {
            if (strlen($period) != '6' || strstr($period, '-') === false)
                throw new Exception('Invalid quarter period given (YYYY-Q) - '.$period);
                
            $year = substr($period, 0, 4);
            $quarter = substr($period, -1);
            $firstMonth = str_pad(1 + (($quarter - 1) * 3), 2, '0', STR_PAD_LEFT);
            $lastMonth = str_pad($quarter * 3, 2, '0', STR_PAD_LEFT);
            
            $first = strtotime($year.$firstMonth.'01');
            $last = strtotime($year.$lastMonth.date('t',strtotime($year.$lastMonth.'01')));
            
            return array(date('d-m-Y', $first), date('d-m-Y', $last));
        }
        elseif ($type == 'year')
        {
            if (strlen($period) != '4')
                throw new Exception('Invalid year period given (YYYY) - '.$period);
                
            $first = strtotime($period.'0101');
            $last = strtotime($period.'1231');
            
            return array(date('d-m-Y', $first), date('d-m-Y', $last));
        }
        
        // else invalid input
        throw new Exception('Invalid period type given - '.$type);
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
        return false;
    }
}

/**
 * format number to euro currency string
 * @param numeric $number
 * @return euro currency formatted string
 */
function euro($number)
{
    return '&euro;.'.number_format($number, 2, ',', '.');
}

/**
 * encapsulate message with error div
 * @param string $msg
 * @return html div error message
 */
function error($msg)
{
    $div = '<div class="error">';

    // first remove error divs from $msg
    if (strstr($msg, $div))
    {
        // loop through all error divs
        while (($pos = strrpos($msg, $div)) !== false) // get last occurence of error div
        {
            // cut out error div
            $start = substr($msg, 0, $pos);
            $end = substr($msg, $pos + strlen($div));
            // find matching </div>
            $endpos = strpos($end, "</div>\n");
            if ($endpos === false)
                $endpos = strpos($end, "</div>");
            // cut out </div>
            $msg = $start . substr($end, 0, $endpos) . substr($end, $endpos + 6);
        }
    }
    
    // then surround $msg with (new) error div
    return $div.$msg."</div>\n";
}

/**
 * encapsulate message with success div
 * @param string $msg
 * @return html div error message
 */
function success($msg)
{
    return '<div class="succes">'.$msg."</div>\n";
}

/**
 * encapsulate message with blue 'info' div
 * @param string $msg
 * @return html div info message
 */
function info($msg)
{
    return '<div class="info">'.$msg."</div>\n";
}


/**
 * debug function to readably dump a variable to screen
 * @param mixed $var
 */
function dump($var, $return = false)
{
    if (empty($var) && $var !== 0)
        return;
    
    $output = '';
    
    if ($varName = getVarName($var))
        $output .= '<pre><strong>'.$varName.":</strong></pre>\n";
    
    if (@include_once 'Zend/Debug.php')
    {
        $output .= Zend_Debug::dump($var, null, false);
    }
    else
    {
        if (is_object($var))
        {
            // check if object has own debug methods
            if (method_exists($var, 'debug'))
                $output .= $var->debug();
            elseif (method_exists($var, 'toString'))
                $output .= $var->toString()."<br/>\n";
            else
                $output .= "<pre>".print_r($var, true)."</pre>";
        }
        elseif (is_array($var))
            $output .= "<pre>".print_r($var, true)."</pre>";
        else
            $output .= $var."<br/>\n";
    }
    
    if ($_SERVER['SERVER_NAME'] == 'localhost') // working locally
        $output .= stacktrace();
    
    if ($return)
        return $output;
    else
        echo $output;
}

/**
 * get the stacktrace in simplified (less detailed) format
 */
function stacktrace()
{
    $output = array();
    
    $trace = debug_backtrace();
    // first line is this function, so start at 2nd line ($i=1)
	for ($i=1; $i<count($trace); $i++)
    {
        $line = $trace[$i];
        $function = ! empty($line['class']) ? $line['class'].$line['type'].$line['function'] : $line['function'];
        $pars = array();
        foreach ($line['args'] as $arg) {
            if (is_object($arg))
                $pars[] = get_class($arg);
            elseif (is_array($arg))
            	$pars[] = trim(getVarName($arg).' Array('.count($arg).')');
            elseif (is_string($arg) && strlen($arg) > 30)
            	$pars[] = trim(getVarName($arg).' <i>long string</i> '.substr(xsschars($arg), 0, 20));
            else
                $pars[] = trim(getVarName($arg).' '.xsschars($arg));
        }
        $output[] = "[$i] => ".$line['file'].' at line '.$line['line'].' using function <strong>'.$function.'('.implode(', ', $pars).')</strong>';
    }
    
    return '<pre><strong>stacktrace: </strong>'."\n".implode("\n", $output).'</pre>'."\n";
}


/**
 * Returns the position of the $nth occurrence of $needle in $haystack, or false if it doesn't exist, or false when illegal parameters have been supplied.
 *
 * @param  string  $haystack   the string to search in.
 * @param  string  $needle     the string to search for.
 * @param  integer $nth        the number of the occurrence to look for.
 * @param  integer $offset     the position in $haystack to start looking for $needle.
 * @return MIXED   integer     either the position of the $nth occurrence of $needle in $haystack,
 * or boolean     false if it can't be found.
 */
function strnpos($haystack, $needle, $nth, $offset = 0)
{
	if (1 > $nth || 0 === strlen($needle))
		return false;
	
	//  $offset is incremented in the call to strpos, so make sure that the first call starts at the right position by initially decrementing $offset.
	--$offset;
	do
	{
		$offset = strpos($haystack, $needle, ++$offset);
	}
	while (--$nth && false !== $offset);
	
	return $offset;
}


/**
 * This function will take an undefined amount of arguments and use the first value that is not empty
 */
function coalesce()
{
    $args = func_get_args();
    foreach ($args as $arg)
    {
        if (! empty($arg))
            return $arg;
    }
    
    // if everything was empty, then return null
    return null;
}


/**
 * simple function to return the name of the first variable with the same value as $var
 * for use by dump, where we cannot use getVariableName
 * @param mixed $var
 * @return string
 */
function getVarName($var)
{
    if (empty($var))
    	return null;
    
    $vars = array();
    // note: using get_defined_vars() instead of $GLOBALS will result in output $var
    foreach ($GLOBALS as $key => $val) {
        if ($val === $var)
            $vars[] = '$'.$key;
    }
    if (empty($vars))
    {
        foreach (get_defined_vars() as $key => $val) {
            if ($val === $var && $key != 'var')
                $vars[] = '$'.$key;
        }
    }
    return implode(' or ', $vars); // per definition $vars cannot be empty
}


/**
 * function to search a group of checkboxes with given name for a post of given value
 * @param string $name
 * @param string $value
 */
function inCheckboxGroup($name, $value)
{
    return isPosted($name) && in_array($value, $_POST[$name]);
}

/**
 * copy Oracle's initcap function. php doesn't lowercase the rest by default with ucfirst.
 * @param string $string
 */
function initcap($string)
{
    return ucfirst(strtolower($string));
}

/**
 * get array of all files in the given directory and it's subdirectories
 * You can filter the output by extension
 * 
 * @param string $dir
 * @param string $extension
 * @return array $files
 */
function getFiles($dir, $extension = null)
{
    $files = scandir($dir);
    foreach ($files as $i => $entry)
    {
        if (substr($entry, 0, 1) != '.')
        {
            if (is_dir($dir . '/' . $entry))
            {
                // get subdir recursively and add to end of $files array
                foreach (getFiles($dir . '/' . $entry, $extension) as $entry)
                    $files[] = $entry;
            }
            elseif (empty($extension) || substr($entry, -strlen($extension)) == $extension)
            {
                $files[] = $dir . '/' . $entry;
            }
        }

        unset($files[$i]); // remove initial value, because we always add to array
    }

    return array_values($files);
}


