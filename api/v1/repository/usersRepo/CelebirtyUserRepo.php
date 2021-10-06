<?php

namespace Repository;


require_once __DIR__."/../../config/Database.php";
require_once __DIR__."/UserRepo.php";

class CelebrityUserRepo extends UserRepo
{
    public function __construct()
    {
        parent::__construct();
    }
    public function create($celebrityUser)
    {
        try{
            $this->connection->beginTransaction();
            $sql = 'INSERT INTO '.self::table.' (email, username, firstname, lastname, status, password, type, logged_in, login_attempts, date) VALUES (:email, :username, :firstname, :lastname, :status, :password, :type, :logged_in, :login_attempts, :date)';
            $query = $this->connection->prepare($sql);
            $query->bindValue(':email', $celebrityUser->getEmail(), \PDO::PARAM_STR);
            $query->bindValue(':username', $celebrityUser->getUsername(), \PDO::PARAM_STR);
            $query->bindValue(':firstname', $celebrityUser->getFirstname(), \PDO::PARAM_STR);
            $query->bindValue(':lastname', $celebrityUser->getLastname(), \PDO::PARAM_STR);
            $query->bindValue(':status', $celebrityUser->getStatus(), \PDO::PARAM_STR);
            $query->bindValue(':password', $celebrityUser->getHashedPassword(), \PDO::PARAM_STR);
            $query->bindValue(':type', $celebrityUser->getType(), \PDO::PARAM_STR);
            $query->bindValue(':logged_in', $celebrityUser->getLoggedIn(), \PDO::PARAM_BOOL);
            $query->bindValue(':login_attempts', $celebrityUser->getLoginAttempts(), \PDO::PARAM_INT);
            $query->bindValue(':date', date('y/m/d'), \PDO::PARAM_STR);
            $query->execute();
            $celebrityUser->setUserId(intval($this->connection->lastInsertId()));
            $this->connection->commit();
            return $celebrityUser;
        }catch(\PDOException $ex) {
            $this->connection->rollBack();
            throw new \PDOException($ex->getMessage());
        }

    }
}
