<?php

namespace Repository;

use PDOException;

require_once __DIR__."/../../config/Database.php";
require_once __DIR__."/UserRepo.php";



class CasualUserRepo extends UserRepo
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($casualUser)
    {
        try{
            $this->connection->beginTransaction();
            $sql = 'INSERT INTO '.self::table.' (email, username, firstname, lastname, status, password, type, logged_in, login_attempts, date) VALUES (:email, :username, :firstname, :lastname, :status, :password, :type, :logged_in, :login_attempts, :date)';
            $query = $this->connection->prepare($sql);
            $query->bindValue(':email', $casualUser->getEmail(), \PDO::PARAM_STR);
            $query->bindValue(':username', $casualUser->getUsername(), \PDO::PARAM_STR);
            $query->bindValue(':firstname', $casualUser->getFirstname(), \PDO::PARAM_STR);
            $query->bindValue(':lastname', $casualUser->getLastname(), \PDO::PARAM_STR);
            $query->bindValue(':status', $casualUser->getStatus(), \PDO::PARAM_STR);
            $query->bindValue(':password', $casualUser->getHashedPassword(), \PDO::PARAM_STR);
            $query->bindValue(':type', $casualUser->getType(), \PDO::PARAM_STR);
            $query->bindValue(':logged_in', $casualUser->getLoggedIn(), \PDO::PARAM_BOOL);
            $query->bindValue(':login_attempts', $casualUser->getLoginAttempts(), \PDO::PARAM_INT);
            $query->bindValue(':date', date('y/m/d'), \PDO::PARAM_STR);
            $query->execute();
            $casualUser->setUserId(intval($this->connection->lastInsertId()));
            $this->connection->commit();
            return $casualUser;
        }catch(\PDOException $ex) {
            $this->connection->rollBack();
            throw new \PDOException($ex->getMessage());
        }
    }

}
