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
        $query = "SELECT * FROM user WHERE user_id = ?;";
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

    public static function makeEvent($eventId)
    {
        $query = "SELECT * FROM `event` WHERE event_id = ?;";
        return DatabaseObject::MapRelationToObject('Event', $query, [$eventId]);
    }

    public static function getEventList()
    {
        $today = date("Y-m-d");
        $query = "SELECT * FROM `event` WHERE event_date > ? AND status = 'active' ORDER BY date_time;";
        return DatabaseObject::MapRelationToObject('Event', $query, [$today]);
    }

     public static function getAttendingEvents($userId)
     {
        $today = date("Y-m-d");
        $query = "SELECT * FROM `event` WHERE event_date > ?  AND event_id IN (SELECT event_id FROM event_attendant WHERE user_id = ? AND status = 'active' ) ORDER BY date_time;";
        return DatabaseObject::MapRelationToObject('Event', $query, [$today, $userId]);
     }

     public static function getAttendingEventIdList($userId){
        $query="SELECT event_id FROM `event_attendant` WHERE user_id = ? AND status = 'active'";
        $event_id_list = DatabaseObject::runReadQuery($query, [$userId]);
        return $event_id_list;
    }

    public static function makeEventAttendant($eventId, $userId)
    {
        $query = "SELECT * FROM `event_attendant` WHERE event_id = ? AND user_id = ?;";
        return DatabaseObject::MapRelationToObject('EventAttendant', $query, [$eventId, $userId]);
    }

    public static function checkEventAttendant($eventId, $userId)
    {
        $query = "SELECT * FROM `event_attendant` WHERE event_id = ? AND user_id = ?";
        return DatabaseObject::checkQuery($query, [$eventId, $userId]);
    }

    public static function getCollaboratorList($eventId, $type)
    {
        $class = $type."User";
        $class = ucfirst($class);
        $query = "SELECT * FROM `user` WHERE status = 'active' AND state = 'active' AND type = ? AND user_id IN (SELECT user_id FROM `event_collaborator` WHERE event_id = ? AND state = 'accepted' AND status = 'active');";
        return DatabaseObject::MapRelationToObject($class , $query, [$type, $eventId]);
    }

    public static function getPostedEvents($userId)
    {
        $query = "SELECT * FROM `event` WHERE status = 'active' AND user_id = ? ORDER BY date_time DESC;";
        return DatabaseObject::MapRelationToObject('Event', $query, [$userId]);
    }

    public static function makeIssue($issueId)
    {
        $query = "SELECT * FROM `issue` WHERE issue_id = ?;";
        return DatabaseObject::MapRelationToObject('Issue', $query, [$issueId]);
    }

    public static function getIssuesOnUser($userId)
    {
        $query = "SELECT * FROM `issue` WHERE state = 'pending' AND status = 'active' AND interest IN (SELECT interest FROM issue_interest WHERE user_id = ? AND status = 'active') ORDER BY date_time DESC;";
        return DatabaseObject::MapRelationToObject('Issue', $query, [$userId]);
    }

    

    

    

}