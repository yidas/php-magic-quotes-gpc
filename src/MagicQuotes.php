<?php

/**
* MagicQuotes 
* 
* Implement Magic Quotes to PHP 5.4 above version, which prevents SQL Injection
* for legacy code.
*
* @author   Nick Tsai <myintaer@gmail.com>
* @version  1.0.0
* @example  
*   // Rewirte all requests of PHP
*   MagicQuotes::init();
* @example 
*   // Rewrite $_POST only
*   MagicQuotes::bindValues($_POST);
*
*/
class MagicQuotes
{
    /**
     * @var bool $isInit Flag of init() 
     */
    private static $isInit = false;

    /**
     * @var string $driver Current MySQL dirver 
     */
    private static $driver;

    /**
     * @var mixed $connection Connection of current MySQL dirver 
     */
    private static $connection;

    /**
     * Auto implement Magic Quotes GPC
     */
    public static function init()
    {
        // Check is on already
        if (self::isInit()) {
            
            return false;
        }

        // GPC binding value
        self::bindValues($_GET);
        self::bindValues($_POST);
        self::bindValues($_COOKIE);
        
        return self::$isInit = true;
    }

    /**
     * Check the MagicQuotes is init or not
     *
     * @return bool Result
     */
    public static function isInit()
    {
        // Check if Magic Quotes GCP is already active
        if (!get_magic_quotes_gpc()) {
            
            return self::$isInit;
        }

        return true;
    }

    /**
     * Bind value recursively
     *
     * @param mixed Processed data
     */
    public static function bindValues(&$data)
    {
        if (!is_array($data)) {

            $data = self::bindValue($data);

        } else {

            foreach ($data as $key => $value) {

                self::bindValues($data[$key]);
            } 
        }
    }

    /**
     * Bind value
     *
     * @param string Processed value
     */
    public static function bindValue(&$value)
    {
        self::setDriver();

        switch (self::$driver) {
            case 'pdo':
                return self::$connection->quote($value);
                break;

            case 'mysqli':
                return self::$connection->real_escape_string($value);
                break;

            case 'mysql':
                return mysql_real_escape_string($value);
                break;
            
            default:
                return addslashes($value);
                break;
        }
    }

    /**
     * Set driver with cache mechanism
     */
    private static function setDriver()
    {
        if (!self::$driver) {
            
            if (defined('PDO::ATTR_DRIVER_NAME')) {
            
                self::$driver = 'pdo';

                self::$connection = new PDO("mysql:");

            } 
            elseif (function_exists('mysqli_connect')) {

                self::$driver = 'mysqli';

                self::$connection = new mysqli("");
            }
            elseif (function_exists('mysql_real_escape_string')) {

                self::$driver = 'mysql';
            }
            else {

                self::$driver = 'none';
            }
        }
    }
}
