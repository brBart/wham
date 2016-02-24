<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Mysession
 * is a custom class created to easily access PHP session variables
 */

Class Mysession {
    
    public function __construct()
    {
        session_start();
    }
    
    /**
     * Destroys the session after unsetting all session variables. 
     */
    
    public static function destory_session()
    {
        foreach ($_SESSION as $key=>$val) {
            self::unsetVar($key);
        }
        session_destroy();
    }
    
    /**
     * Returns the value of a session variable if present, else returns NULL
     * @param string $var Name of the session variable to query
     * @return multi Value of session variable or NULL 
     */
    
    public static function getVar($var)
    {
        return isset($_SESSION[$var]) ? $_SESSION[$var] : NULL ;
    }
    
    /**
     * Initialises/modifies the value of a session variable
     * @param string $key Name of the session variable
     * @param array $value Value to be set
     */
    
    public static function setVar($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Unsets a session variable if present, else does nothing. 
     * @param string $var Name of the session variable to unset.
     */
    public static function unsetVar($var)
    {
        if (isset($_SESSION[$var])) { unset($_SESSION[$var]); }
    }
    
    /**
     * A quick check method to compare the value of a session variable
     * @param string $key Name of the session variable
     * @param multi $value Value to compare with this session variable
     * @return bool Returns true if session variable's value is $value, else will return false. 
     */
    
    public static function quickCheck($key, $value) 
    {
        return ((isset($_SESSION[$key])) && ($_SESSION[$key] == $value)) ? true : false ;
    }
}




// End Of File