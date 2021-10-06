<?php

namespace Repository;

require_once __DIR__."/../../config/Database.php";

class UserRepo
{
    protected $connection = null;
    protected const table = "users_tbl";

    public function __construct()
    {
        $this->connection = \Config\Database::getDBConnection();
    }

    public function checkUsername($username)
    {
        $query = $this->connection->prepare('SELECT user_id from '. self::table .' where username = :username');
        $query->bindParam(':username', $username, \PDO::PARAM_STR);
        $query->execute();
        $rowCount = $query->rowCount();

        if($rowCount !== 0)
            return true;
        return false;
    }

    public function checkEmail($email)
    {
        $query = $this->connection->prepare('SELECT user_id from '. self::table .' where email = :email');
        $query->bindParam(':email', $email, \PDO::PARAM_STR);
        $query->execute();
        $rowCount = $query->rowCount();

        if($rowCount !== 0)
            return true;
        return false;
    }

    public function getUser($email)
    {
      try{
        $sql = 'SELECT * FROM '.self::table.' WHERE email = :email';
        $query = $this->connection->prepare($sql);
        $query->bindValue(':email', $email, \PDO::PARAM_STR);
        $query->execute();
        if(!$query->rowCount())
          return  (object)[];
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        return (object)[
            "firstname" => $row['firstname'],
            "lastname" => $row['lastname'],
            "username" => $row['username'],
            "password" => $row['password'],
            "loginAttempts" => intval($row['login_attempts']),
            "type" => $row['type'],
            "userId" => intval($row['user_id']),
            "email" => $row['email'],
            "loggedIn" => boolval($row['logged_in']),
            "status" => $row['status'],
            "banned" => boolval($row['banned']), 
        ];
      }catch(\PDOException $ex) {
        throw new \PDOException($ex->getMessage());
      }
    }
}