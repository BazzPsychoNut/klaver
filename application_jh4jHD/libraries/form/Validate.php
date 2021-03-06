<?php
require_once 'functions.php';

class Validate
{
    // attributes
    protected $valid;
    protected $message;
    
    /**
     * constructor
     *
     * @return Validate object
     */
    public function __construct()
    {
        $this->valid = true; // initialize to true
        $this->message = '';
    }
    
    
    /**
     * check if input value is numeric
     *
     * @param string $input
     * @return boolean
     */
    public function numeric($input)
    {
        if (is_numeric($input))
            return true;
        else
            return $this->invalidate($input, ' is not a valid number.');
    }
    
    /**
     * check if input value is a date dd-mm-yyyy
     *
     * @param string $input
     * @return boolean
     */
    public function date($input)
    {
        preg_match('#^(\d\d)-(\d\d)-(\d\d\d\d)$#', $input, $matches);
        if (isset($matches[1]) && isset($matches[2]) && isset($matches[3]) && checkdate($matches[2],$matches[1],$matches[3]))
            return true;
        else
            return $this->invalidate($input, ' is not a valid date.');
    }
    
    /**
     * check if input value is a valid email address
     *
     * @param string $email
     * @return boolean
     */
    public function email($email)
    {
        if (strstr($email, '<') && strstr($email, '>'))
        {
            $start = strpos($email, '<') + 1;
            $stop = strpos($email, '>');
            $email = substr($email, $start, ($stop - $start));
        }
        
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
        	return $this->invalidate($email, ' is not a valid e-mail address.');
        	
        return true;
    }
    
    /**
     * check if password is valid:
     * - at least 6 characters long
     * - consists of a mix of letters and other character
     *
     * @param string $password
     */
    public function password($password)
    {
        if (strlen($password) < 6)
            return $this->invalidate($password, 'Your password is not safe. It has to be at least 6 characters long.');
        
        $hasLetter = false;
        $hasNumber = false;
        for ($i=0; $i<strlen($password); $i++)
        {
            if (is_numeric($password[$i]))
                $hasNumber = true;
            if (! is_numeric($password[$i]))
                $hasLetter = true;
                
            if ($hasLetter && $hasNumber)
                return true;
        }
        
        return $this->invalidate($password, 'Your password is not safe. Both letters and numbers are required.');
    }
    
    /**
     * check that input has at least the required minimum length
     *
     * @param string $input
     * @param int $length
     */
    public function minLength($input, $length)
    {
        if (strlen($input) >= $length)
            return true;
        else
            return $this->invalidate($input, " is smaller than the required minimum length of $length.");
    }
    
    /**
     * check that input has at most the allowed maximum length
     *
     * @param string $input
     * @param int $length
     */
    public function maxLength($input, $length)
    {
        if (strlen($input) <= $length)
            return true;
        else
            return $this->invalidate($input, " is bigger than the allowed maximum length of $length.");
    }
    
    /**
     * check if given filename would be a valid filename
     *
     * @param string $filename
     */
    public function filename($filename)
    {
        $sanitized_name = preg_replace('/[^0-9a-z\.\_\-]/i','', $filename);
        if ($sanitized_name == $filename)
            return true;
        else
            return $this->invalidate($filename, " is not a valid filename.");
    }
    
    /**
     * check if given filename would be a valid filename
     *
     * @param string $filename
     */
    public function isValidUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) !== false)
            return true;
        else
            return $this->invalidate($url, " is not a valid url.");
    }
    
    /**
     * check if given postcode is a valid Nederlandse postcode
     * @param string $postcode
     */
    public function postcode($postcode)
    {
        if (preg_match('/^[1-9][0-9]{3}\s?[a-z|A-Z]{2}$/', $postcode)) // 4 cijfers, 0 of 1 spatie, 2 letters
            return true;
        else
            return $this->invalidate($postcode, ' is not a valid postcode.');
    }
    
    /**
     * check if given value exists in array
     * @param string $value
     * @param array $array
     */
    public function allowedValue($value, $array)
    {
        if (in_array($value, $array))
            return true;
        else
            return $this->invalidate($value, ' is not one of the allowed values.');
    }
    
    /**
     * check if given value is less than test value (numeric or alphabetically)
     * @param mixed $value
     * @param mixed $test
     */
    public function lessThan($value, $test)
    {
        if (! is_numeric($value) || ! is_numeric($test))
        {
            $value = chr($value); // implicit check for objects or arrays
            $test = chr($test);
        }
        
        if ($value < $test)
            return true;
        else
            return $this->invalidate($value, " is not more than $test.");
    }
    
    /**
     * check if given value is higher than test value (numeric or alphabetically)
     * @param mixed $value
     * @param mixed $test
     */
    public function moreThan($value, $test)
    {
        if (! is_numeric($value) || ! is_numeric($test))
        {
            $value = chr($value); // implicit check for objects or arrays
            $test = chr($test);
        }
        
        if ($value > $test)
            return true;
        else
            return $this->invalidate($value, " is not more than $test.");
    }
    
    /**
     * check if given value is between start and end values (numeric or alphabetically)
     * @param mixed $value
     * @param mixed $test
     */
    public function between($value, $start, $end)
    {
        if (! is_numeric($value) || ! is_numeric($start) || ! is_numeric($end))
        {
            $value = chr($value); // implicit check for objects or arrays
            $start = chr($start);
            $end = chr($end);
        }
        
        if ($value >= $start && $value <= $end)
            return true;
        else
            return $this->invalidate($value, " is not between $start and $end");
    }
    
    /**
     * check if given $id is a valid id in html
     * @param string $id
     */
    public function htmlId($id)
    {
        if (preg_match('/(^[a-z]{1}[a-z0-9_\-\.\:]+)$/i', $id))
            return true;
        else
            return $this->invalidate($id, " is not a valid html id or name.");
    }
    
    /**
     * check that given value is not an array
     * @param string $value
     */
    public function isArray($value)
    {
        if (is_array($value))
            return true;
        else
            return $this->invalidate($value, "Given value has to be an array.");
    }
    
    /**
     * check that given value is not an array
     * @param string $value
     */
    public function isNotArray($value)
    {
        if (! is_array($value))
            return true;
        else
            return $this->invalidate($value, "Array given where not expected.");
    }
    
    /**
     * check that $value is in allowed values of $array
     * @param string $value
     * @param array $array
     */
    public function inArray($value, $array)
    {
        if (is_array($array) && in_array($value, $array))
            return true;
        else
            return $this->invalidate($value, " was not in array of allowed values: ".implode(',', $array));
    }
    
    /**
     * check if array has only unique values
     * @param $array
     */
    public function hasUniqueValues($array)
    {
        $unique = array_unique($array);
        if (count($unique) == count($array))
            return true;
        else
            return $this->invalidate($array, "Not all values in array are unique");
    }
    
    /**
     * check that given value is not empty
     * @param mixed $value
     */
    public function isNotEmpty($value)
    {
        if (! empty($value))
            return true;
        else
            return $this->invalidate($value, "Given value is empty.");
    }
    
    /**
     * check that given value is a boolean
     * @param bool $value
     */
    public function isBoolean($value)
    {
        if (is_bool($value))
            return true;
        else
            return $this->invalidate($value, "Given value is not a boolean.");
    }
    
    /**
     * check that $var is objects of $class
     * @param object or array of objects $var
     * @param classname $class
     */
    public function isInstanceOf($var, $class)
    {
        if (is_array($var))
        {
            $errors = array();
            foreach ($var as $i => $val)
            {
                if (! $val instanceof $class)
                    $errors[] = "$val at index $i is not an object of $class";
            }
            if (empty($errors))
                return true;
            else
                return $this->invalidate($var, implode("<br/>\n", $errors));
        }
        else
        {
            if ($var instanceof $class)
                return true;
            else
                return $this->invalidate($var, " is not an object of $class");
        }
    }
    
    /**
     * check that $query is a Query and that it executes
     * @param Query $query
     */
    public function isQuery($query)
    {
        if ($this->isInstanceOf($query, 'Query'))
        {
            if ($query->execute())
                return true;
            else
            {
                $message = "Could not execute query";
                if (defined('ACCOUNT_LEVEL') && ACCOUNT_LEVEL == 'ADMIN')
                    $message = $query->getError($message);
                    
                return $this->invalidate($query, $message);
            }
        }
    }
    
    /**
     * validate phone number
     * @param phone number $phonenumber
     */
    public function isMobileNumber($phonenumber)
    {
        $valid = false;
        if (preg_match('/^06[0-9]{8}$/', $phonenumber))
            $valid = true;
        elseif (preg_match('/^\+316[0-9]{8}$/', $phonenumber))
            $valid = true;
            
        if ($valid)
            return true;
        else
            return $this->invalidate($phonenumber, 'Invalid phone number given');
    }
    
    /**
     * Run all validations given in the array. Values in the array have to match
     * method names of this Validate class exactly.
     * @param array $array validation methods
     * @param mixed $value to validate
     */
    public function validateAll($value, $array)
    {
        $noValidationMethods = array('invalidate','reset','getMessage','isValid','validateAll','__construct');
        $methods = array_diff(get_class_methods($this), $noValidationMethods);
        foreach ($array as $validation)
        {
            try
            {
                // if given $validation is a valid method of this class then run that method
                if (! in_array($validation, $methods))
                    throw new Exception('Invalid validation asked: '.$validation);
                
                $r = new ReflectionMethod($this, $validation);
                if (count($r->getParameters()) > 1)
                    throw new Exception('Cannot perform validation '.$validation.', because more than just value parameter is required.');
                
                $this->$validation($value);
            }
            catch (Exception $e)
            {
                echo error($e->getMessage());
            }
        }
        return $this->valid;
    }
    
    /**
     * getter for valid property
     * returns false if any validation on the page did not pass
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }
    
    /**
     * return the found error messages optionally formatted in error divs
     */
    public function getMessage($message, $inErrorDiv = true)
    {
        return $inErrorDiv ? '<div class="error"><p>'.$message.'</p><p>'.$this->message."</p></div>\n" : $message."<br/>\n".$this->message;
    }
    
    /**
     * reset validator (so you don't have to create a new object for each use if you don't want to)
     */
    public function reset()
    {
        $this->valid = true;
        $this->message = '';
    }
    
    
    /**
     * function to handle found invalidities
     * public, so it is possible to use this class with custom validation
     *
     * @param string $message
     * @return boolean
     */
    public function invalidate($var, $message)
    {
        $this->valid = false;  // invalidate the posted vars
        
        if (empty($var))
            $this->message .= "''";
        elseif (defined('ACCOUNT_LEVEL') && ACCOUNT_LEVEL == 'ADMIN')
           	$this->message .= dump($var);
        else
            $this->message .= xsschars($var);
        
        $this->message .= ' '.$message."<br/>\n";
        
        return false;  // always return false
    }
    
    
}
