<?php 

// namespace Repository;

// require_once __DIR__."/../../models/sessions/Session.php";

// class AuthRepo
// {
//     protected $connection = null;
//     protected const table = "session_tbl";

//     public function __construct()
//     {
//         $this->connection = \Config\Database::getDBConnection();
//     }

    // public function getSession($refreshToken)
    // {
    //     $query = $this->connection->prepare('SELECT * FROM '. self::table .' WHERE refreshToken = :refreshToken');
    //     $query->bindParam(':refreshToken', $refreshToken, \PDO::PARAM_STR);
    //     $query->execute();

    //     if(!$query->rowCount())
    //         return null;
    //     $row = $query->fetch(\PDO::FETCH_ASSOC);
    //     $session = new \Models\Session(intval($row['user_id']), $row['refreshToken'], strtotime($row['exp']));
    //     $session->setSessionId(intval($row['session_id']));
    //     return $session;
    // }

//     public function getUserStatus($userId)
//     {
//         $query = $this->connection->prepare('SELECT banned, status, login_attempts, type FROM users_tbl WHERE user_id = :user_id');
//         $query->bindParam(':user_id', $userId, \PDO::PARAM_INT);
//         $query->execute();
        
//         if(!$query->rowCount())
//             return null;
            
//         $row = $query->fetch(\PDO::FETCH_ASSOC);
//         return (object)["banned" => boolval($row['banned']), "status" => $row['status'], "login_attempts" => intval($row['login_attempts']), "type" => $row['type']] ;
//     }
// }