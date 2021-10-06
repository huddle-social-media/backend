<?php

namespace Repository;

require_once __DIR__."/../../config/Database.php";
require_once __DIR__."/UserRepo.php";

class OrganizationUserRepo extends UserRepo
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($organizationUser)
    {
        try{
            // can finalize after completed the ORM API
            $this->connection->beginTransaction();
            $sql = 'INSERT INTO '.self::table.' (email, username, organization_name, status, password, type, logged_in, login_attempts, date) VALUES (:email, :username, :organization_name, :status, :password, :type, :logged_in, :login_attempts, :date)';
            $query = $this->connection->prepare($sql);
            $query->bindValue(':email', $organizationUser->getEmail(), \PDO::PARAM_STR);
            $query->bindValue(':username', $organizationUser->getUsername(), \PDO::PARAM_STR);
            $query->bindValue(':organization_name', $organizationUser->getOrganizationName(), \PDO::PARAM_STR);
            $query->bindValue(':status', $organizationUser->getStatus(), \PDO::PARAM_STR);
            $query->bindValue(':password', $organizationUser->getHashedPassword(), \PDO::PARAM_STR);
            $query->bindValue(':type', $organizationUser->getType(), \PDO::PARAM_STR);
            $query->bindValue(':logged_in', $organizationUser->getLoggedIn(), \PDO::PARAM_BOOL);
            $query->bindValue(':login_attempts', $organizationUser->getLoginAttempts(), \PDO::PARAM_INT);
            $query->bindValue(':date', date('y/m/d'), \PDO::PARAM_STR);
            $query->execute();
            $organizationUser->setUserId(intval($this->connection->lastInsertId()));
            $this->connection->commit();
            return $organizationUser;
        }catch(\PDOException $ex) {
            $this->connection->rollBack();
            throw new \PDOException($ex->getMessage());
        }

    }
}
