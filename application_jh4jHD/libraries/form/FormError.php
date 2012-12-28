<?php

/**
 * class to handle displaying and/or logging of catched errors in campaign management suite
 *
 * @author b-deruiter
 */
class FormError
{
    
    /**
     * change this variable to show errors in production or not
     */
    public static $showErrors = false;
    
    
    public static function dump($message)
    {
		// log error, but don't log when developing or testing
		if (! self::workingLocally() && strpos($_SERVER['PHP_SELF'], '/tests/') === false)
			file_put_contents('form_errorlog.txt', $message."\n".stacktrace()."\n", FILE_APPEND);
		
		// display error on screen when developing or testing
		if (self::workingLocally() || self::$showErrors || strpos($_SERVER['PHP_SELF'], '/tests/') !== false)
			return $message;
    }
    
    /**
     * return if server is local (so user is developer)
     */
    protected static function workingLocally()
    {
        return ! empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost';
    }
}


