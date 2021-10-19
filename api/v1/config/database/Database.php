<?php


namespace Config;
class Database
{
    private static $writeHost = 'localhost';
    private static $writeUsername = 'root';
    private static $writePassword = '';
    private static $writeDbName = 'huddle_test';
    private static $writeCharset = 'utf8';

    private static $readHost = 'localhost';
    private static $readUsername = 'root';
    private static $readPassword = '';
    private static $readDbName = 'huddle_test';
    private static $readCharset = 'utf8';

    private static $writeDBConnection;
    private static $readDBConnection;

    public static function connectWriteDB()
    {
        if(self::$writeDBConnection === null)
        {
            $dsn = "mysql:host=".self::$writeHost.";dbname=".self::$writeDbName.";charset=".self::$writeCharset;
            self::$writeDBConnection = new \PDO($dsn, self::$writeUsername, self::$writePassword);
            self::$writeDBConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$writeDBConnection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        }

        return self::$writeDBConnection;
    }

    public static function connectReadDB()
    {
        if(self::$readDBConnection === null)
        {
            $dsn = "mysql:host=".self::$readHost.";dbname=".self::$readDbName.";charset=".self::$readCharset;
            self::$readDBConnection = new \PDO($dsn, self::$readUsername, self::$readPassword);
            self::$readDBConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$readDBConnection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        }

        return self::$readDBConnection;
    }
}