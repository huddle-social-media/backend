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

    public static function makeCasualUser(int $id)
    {
        $query = "SELECT * FROM user WHERE userId = ?;";
        return DatabaseObject::MapRelationToObject('CasualUser', $query, [$id]); 
    }

    public static function makeUserStatus(int $id)
    {
        $query = "SELECT * FROM user WHERE userId = ?;";
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

    public static function makePost($id)
    {
        $query = "SELECT * FROM `post` WHERE post_id = ?;";
        return DatabaseObject::MapRelationToObject('Post', $query, [$id]);
    }

    public static function updateObject($object, $primaryKeysArray)
    {
        return DatabaseObject::UpdateObjectInRelation($object, $primaryKeysArray);
    }

    public static function makeSession($refreshToken)
    {
        $query = "SELECT * FROM `session` WHERE refreshToken = ?";
        return DatabaseObject::MapRelationToObject('Session', $query, [$refreshToken]);
    }

}