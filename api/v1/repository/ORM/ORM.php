<?php

namespace Repository\ORM;

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

    public static function writeObject($object)
    {
        return DatabaseObject::MapObjectToRelation($object);
    }

    public static function checkUsername($username)
    {
        $query = 'SELECT * FROM `user` WHERE username = ?';
        return DatabaseObject::checkQuery($query, [$username]);
    }

    public static function checkEmail($email)
    {
        $query = 'SELECT * FROM `user` WHERE email = ?';
        return DatabaseObject::checkQuery($query, [$email]);
    }

    public static function makePost($id)
    {
        $query = "SELECT * FROM `post` WHERE post_id = ?;";
        return DatabaseObject::MapRelationToObject('Post', $query, [$id]);
    }
}