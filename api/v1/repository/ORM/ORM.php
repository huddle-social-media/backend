<?php

namespace Repository\ORM;

<<<<<<< HEAD
=======
require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

>>>>>>> origin/master
class ORM
{
    public static function makeUserList(string $name)
    {

        $query = "SELECT * FROM user WHERE `name` LIKE ?;";
        return DatabaseObject::MapRelationToObject('User', $query, ['%'.$name.'%']); 
    }

<<<<<<< HEAD
    public static function makeCasualUser(int $id)
    {
        $query = "SELECT * FROM user WHERE userId = ?;";
        return DatabaseObject::MapRelationToObject('CasualUser', $query, [$id]); 
=======
    public static function makeUserById(int $id, $type)
    {
        $query = "SELECT * FROM user WHERE userId = ?;";
        return DatabaseObject::MapRelationToObject($type, $query, [$id]); 
    }

    public static function makeUserByEmail(string $email, $type)
    {
        $query = "SELECT * FROM user WHERE email = ?;";
        return DatabaseObject::MapRelationToObject($type, $query, [$email]); 
    }

    public static function makeUserStatus(int $id)
    {
        $query = "SELECT * FROM user WHERE user_id = ?;";
        return DatabaseObject::MapRelationToObject('UserStatus', $query, [$id]); 
>>>>>>> origin/master
    }

    public static function writeObject($object)
    {
        return DatabaseObject::MapObjectToRelation($object);
    }

<<<<<<< HEAD
    public static function checkUsername($username)
=======
    public static function checkUsername(string $username)
>>>>>>> origin/master
    {
        $query = 'SELECT * FROM `user` WHERE username = ?';
        return DatabaseObject::checkQuery($query, [$username]);
    }

<<<<<<< HEAD
    public static function checkEmail($email)
=======
    public static function checkEmail(string $email)
>>>>>>> origin/master
    {
        $query = 'SELECT * FROM `user` WHERE email = ?';
        return DatabaseObject::checkQuery($query, [$email]);
    }

<<<<<<< HEAD
    public static function makePost($id)
    {
        $query = "SELECT * FROM `post` WHERE post_id = ?;";
        return DatabaseObject::MapRelationToObject('Post', $query, [$id]);
    }
=======
    public static function makePost($post_id)
    {
        $query = "SELECT * FROM `post` WHERE post_id = ?;";
        return DatabaseObject::MapRelationToObject('Post', $query, [$post_id]);
    }

    public static function getPostsListbyUserId($userId ,$privacyStatus ,$numOfPosts)
    {
        $query = 'SELECT * FROM `post` WHERE user_id = ? AND privacy_status = ? ORDER BY date_time DESC LIMIT ? ;';
        return DatabaseObject::MapRelationToObject('Post', $query, [$userId, $privacyStatus, $numOfPosts]);
    }

    public static function updateObject($object)
    {
        return DatabaseObject::UpdateObjectInRelation($object);
    }

    public static function makeSession($refreshToken)
    {
        $query = "SELECT * FROM `session` WHERE refresh_token = ?";
        return DatabaseObject::MapRelationToObject('Session', $query, [$refreshToken]);
    }

    public static function makeTip($tip_id)
    {
        $query = "SELECT * FROM `tip` WHERE tip_id = ?;";
        return DatabaseObject::MapRelationToObject('Tip', $query, [$tip_id]);
    }

    public static function getTipsListbyUserId($userId ,$numOfTips)
    {
        $query = 'SELECT * FROM `tip` WHERE user_id = ? ORDER BY date_time DESC LIMIT ? ;';
        return DatabaseObject::MapRelationToObject('Tip', $query, [$userId, $numOfTips]);
    }

    public static function makeFollow($userId, $follower)
    {
        $query = "SELECT * FROM `follow` WHERE user_id = ? AND follower = ?;";
        return DatabaseObject::MapRelationToObject('Follow', $query, [$userId, $follower]);
    }

    public static function makeLike($postId, $userId){
        $query ="SELECT * FROM `like` WHERE post_id = ? AND user_id = ?";
        return DatabaseObject::MapRelationToObject('Like', $query, [$postId, $userId]);
    }
    // public static function commentpost($id){

    //     $query="SELECT * FROM `comment` WHERE commetId =?";
    //     return DatabaseObject::MapRelationToObject('Comment', $query, [$id]);
    // }
    public static function getlikeCount($id){
        $query="SELECT COUNT(*) as total_like_count FROM `like` WHERE post_id=?";
        $total_like_count = DatabaseObject::runReadQuery($query, [$id]);
        return $total_like_count['total_like_count'];
    }

    // public static function Unlike($Unlike){

    //     $query ="DELETE * FROM `like` WHERE post_id=?";
    //     return DatabaseObject::MapRelationToObject('Like', $query, [$Unlike]);
    // }

>>>>>>> origin/master
}