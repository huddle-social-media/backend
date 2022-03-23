<?php

namespace Models;

class IssueChat extends HuddleObj
{
    private $message_id;
    private $user_id;
    private $issue_id;
    private $message;
    private $replied_to;
    private $message_date;
    private $message_time;
    private $read_status;
    private $status;
    private $sent_to;

    protected $dbTable = "issue_chat";
    protected $primaryKeysArray = ["message_id"];

    public function setMessageId($messageId)
    {
        $this->message_id = $messageId;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function setIssueId($issueId)
    {
        $this->issue_id = $issueId;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setRepliedTo($repliedTo)
    {
        $this->replied_to = $repliedTo;
    }

    public function setMessageTime($messageTime)
    {
        $this->message_time = $messageTime;
    }

    public function setMessageDate($messageDate)
    {
        $this->message_date = $messageDate;
    }

    public function setReadStatus($readStatus)
    {
        $this->read_status = $readStatus;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setSentTo($sentTo)
    {
        $this->sent_to = $sentTo;
    }

    public function getMessageId()
    {
        return $this->message_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getIssueId()
    {
        return $this->issue_id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getRepliedTo()
    {
        return $this->replied_to;
    }

    public function getMessageTime()
    {
        return $this->message_time;
    }

    public function getMessageDate()
    {
        return $this->message_date;
    }

    public function getReadStatus()
    {
        return $this->read_status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSentTo()
    {
        return $this->sent_to;
    }

}