<?php

// namespace Repository;

// use PDOException;

// class SessionRepo
// {
//     protected $connection = null;
//     protected const table = "session_tbl";

//     public function __construct()
//     {
//         $this->connection = \Config\Database::getDBConnection();
//     }

//     public function update($session)
//     {
//         try{
//             $this->connection->beginTransaction();
//             $sql = "UPDATE ".self::table." SET refreshToken = :refreshToken, exp = :expirationTime WHERE session_id = :session_id";
//             $query = $this->connection->prepare($sql);
//             $query->bindValue(':refreshToken', $session->getRefreshToken(), \PDO::PARAM_STR);
//             $query->bindValue(':expirationTime', date('y/m/d H:i:s', $session->getExpirationTime()), \PDO::PARAM_STR);
//             $query->bindValue(':session_id', $session->getSessionId(), \PDO::PARAM_INT);
//             $query->execute();
//             $this->connection->commit();
//         }catch(PDOException $ex){
//             $this->connection->rollBack();
//             throw new \PDOException($ex->getMessage());
//         }
//     }

    // public function create($session)
    // {
    //     try{
    //         $this->connection->beginTransaction();
    //         $sql = "INSERT INTO ".self::table." (user_id, refreshToken, exp) VALUES (:user_id, :refreshToken, :exp)";
    //         $query = $this->connection->prepare($sql);
    //         $query->bindValue(':user_id', $session->getUserId() ,\PDO::PARAM_INT);
    //         $query->bindValue(':exp', date('y/m/d H:i:s', $session->getExpirationTime()), \PDO::PARAM_STR);
    //         $query->bindValue(':refreshToken', $session->getRefreshToken(), \PDO::PARAM_STR);
    //         $query->execute();
    //         $session->setSessionId(intval($this->connection->lastInsertId()));
    //         $this->connection->commit();
    //         return $session;
    //     }catch(PDOException $ex){
    //         $this->connection->rollBack();
    //         throw new \PDOException($ex->getMessage());
    //     }
    // }
// }
