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
        // forward to CampaignError's error handling if possible
        if (class_exists('CampaignError'))
        {
            CampaignError::dump($message);
        }
        else
        {
            global $self;
            
            // log error
            if (! self::workingLocally())
            {
                require_once 'Query.php';
	            $sqlErrorLog = "insert into dm.web_errorlog
	                            (report_filename, error_level, error_message, post_values, user_login_id)
	                            values
	                            (substr(:report_filename,1,200), :error_level, substr(:error_message,1,4000), :post_values, :user_login_id)";
	            $queryErrorLog = new Query($sqlErrorLog);
	            $queryErrorLog->bind( array(':report_filename', ':error_level', ':error_message', ':post_values', ':user_login_id')
	                                , array($self, 3, $message, implode(',', $_POST), USERID)
	                                );
	            @$queryErrorLog->execute();
            }
            
            // display error on screen
            if (self::workingLocally() || self::$showErrors)
                return $message;
        }
    }
    
    /**
     * return if server is local (so user is developer)
     */
    protected static function workingLocally()
    {
        return $_SERVER['SERVER_NAME'] == 'localhost';
    }
}


