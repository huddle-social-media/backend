<?php



namespace Repository\ORM;

use PDOException;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class DatabaseObject
{
    private static $readConn;
    private static $writeConn;

    private static function startConnection()
    {
        try
        {
            self::$readConn = \Config\Database::connectReadDB();
            self::$writeConn = \Config\Database::connectWriteDB();
        }catch(\PDOException $ex)
        {
            error_log('Connection error - '.$ex, 0);
            // SEND ERROR RESPONSE HERE ############
        }
        
    }

    public static function MapRelationToObject($type, $query, $params)
    {
        $res = self::readQuery($query, $params);

        $res->setFetchMode(\PDO::FETCH_CLASS, '\\Models\\'.$type);
        
        if($res->rowCount() > 1)
        {
            $tempArray = [];
            foreach($res as $object)
            {
                array_push($tempArray, $object);
            }

            return $tempArray;

        }elseif($res->rowCount() == 1)
        {
            return $res->fetch();
        }else
        {
            return null;
        }
    }

    // ########### UNDER TESTING ############# (Have to add exceptions)
    public static function objectToArray($data) {
        $className = "";
        if(is_object($data))
        {
            $className = get_class($data);
        }
        if ((! is_array($data)) and (! is_object($data)))
            return $data; 
    
        $result = array();
    
        $data = (array) $data;
        foreach ($data as $key => $value) {
            if($className !== "")
            {
                $pos = strpos($key, $className);
                if($pos != false)
                {
                    $key = substr($key, $pos + strlen($className));
                }
                
            }
            $key = str_replace('*', '', $key);
            $key = preg_replace('/[\x00-\x1F\x7F]/', '', $key);     // removes hidden characters that interfere with checking
            if (is_object($value))
                $value = (array) $value;
            if (is_array($value))
                $result[$key] = self::objectToArray($value);
            else
                $result[$key] = $value;
        }

        return $result;
    }

    // ########### UNDER TESTING ############# (Have to add exceptions)
    public static function MapObjectToRelation($object)
    {

        $DbTableName = $object->getDbTable();

        $attributes = [];
        $values = [];
        $params = [];


        $array = self::objectToArray($object);

        foreach($array as $key=>$value)
        {
            
            if(!is_array($value) && $value != NULL && $key != "dbTable")
            {
                $attributes[] = $key;
                $values[] = $value;
                $params[] = '?';

            }
        }
        
        $parmsString = join(", ", $params);
        $attrString = join(", ", $attributes);       


        if(self::$writeConn == NULL)
        {
            self::startConnection();
        }

        $query = "INSERT INTO `".$DbTableName."`(".$attrString.") VALUES(".$parmsString.");";
        try
        {
            self::$writeConn->beginTransaction();
            $stmt = self::$writeConn->prepare($query);
            $stmt->execute($values);
            $id = self::$writeConn->lastInsertId();
            self::$writeConn->commit();

        }catch(\PDOException $ex)
        {
            error_log('Error running query - '.$ex, 0);
            self::$writeConn->rollBack();
            $response = new \Helpers\Response();
            $response->setSuccess(false);
            $response->setHttpStatusCode(500);
            $response->addMessage("Database Error");
            $response->send();
            exit;
        }
        

        return $id;


    }

    public static function checkQuery($query, $params)
    {
        $res = self::readQuery($query, $params);

        return $res->rowCount();
    }

    private static function readQuery($query, $params)
    {
        if(self::$readConn === null)
        {
            self::startConnection();
        }
        try
        {
            $res = self::$readConn->prepare($query);
            $res->execute($params);
            
        }catch(\PDOException $ex)
        {
            error_log('Error running query - '.$ex, 0);
            $response = new \Helpers\Response();
            $response->setSuccess(false);
            $response->setHttpStatusCode(500);
            $response->addMessage("Database Error");
            $response->send();

            exit;
        }

        return $res;
    }

    public static function UpdateObjectInRelation($object, $keys)
    {

        $DbTableName = $object->getDbTable();

        $attributes = [];
        $values = [];
        $params = [];


        $array = self::objectToArray($object);

        foreach($array as $key=>$value)
        {
            
            if(!is_array($value) && $value != NULL && $key != "dbTable")
            {
                $attributes[] = $key;
                $values[] = $value;
                $params[] = '?';

            }
        }
        $valueString = "";
        $whereString = "";
        
        for($i = 0; $i < count($attributes); $i++)
        {
            if(in_array($attributes[$i], $keys))
            {
                $whereString = $whereString."$attributes[$i] = $values[$i] &&";
                unset($values[$i]);
                $values = array_values($values);

            }else
            {
                $valueString = $valueString."$attributes[$i] = ?, ";
            }
            
        }

        $whereString = substr($whereString, 0, strlen($whereString)- 3);
        $valueString = substr($valueString, 0, strlen($valueString) - 2);

        if(self::$writeConn == NULL)
        {
            self::startConnection();
        }

        $query = "UPDATE `".$DbTableName."` SET $valueString WHERE $whereString;";

        try
        {
            self::$writeConn->beginTransaction();
            $stmt = self::$writeConn->prepare($query);
            $stmt->execute($values);
            $id = self::$writeConn->lastInsertId();
            self::$writeConn->commit();

        }catch(\PDOException $ex)
        {
            error_log('Error running query - '.$ex, 0);
            self::$writeConn->rollBack();
            $response = new \Helpers\Response();
            $response->setSuccess(false);
            $response->setHttpStatusCode(500);
            $response->addMessage("Database Error");
            $response->addMessage($ex->getMessage());
            $response->send();
            exit;
        }
        

        return $id;


    }


}