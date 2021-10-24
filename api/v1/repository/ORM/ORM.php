<?php

namespace Repository\ORM;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class ORM
{
    public static function makeUserList(string $name)
    {

        $query = "SELECT * FROM user WHERE `name` LIKE ?;";
        return DatabaseObject::MapRelationToObject('User', $query, ['%'.$name.'%']); 
    }

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
    }

    public static function writeObject($object)
    {
        return DatabaseObject::MapObjectToRelation($object);
    }

    public static function checkUsername(string $username)
    {
        $query = 'SELECT * FROM `user` WHERE username = ?';
        return DatabaseObject::checkQuery($query, [$username]);
    }

    public static function checkEmail(string $email)
    {
        $query = 'SELECT * FROM `user` WHERE email = ?';
        return DatabaseObject::checkQuery($query, [$email]);
    }

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

    public static function updateObject($object, $primaryKeysArray)
    {
        return DatabaseObject::UpdateObjectInRelation($object, $primaryKeysArray);
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

}