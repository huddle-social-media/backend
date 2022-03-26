<?php

namespace Models;

class Chat extends HuddleObj
{
    private $message_id;
    private $user_id;
    private $sent_to;
    private $replied_to;
    private $date_time;
    private $status;
    private $sent_status;
    private $read_status;
    private $message;
    private $message_date;
    private $message_time;

    protected $dbTable = 'global_chat';
    protected $primaryKeysArray = ['message_id'];

    public function setMessageId($messageId)
    {
        $this->message_id = $messageId;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function setSentTo($sentTo)
    {
        $this->sent_to = $sentTo;
    }

    public function setRepliedTo($repliedTo)
    {
        $this->replied_to = $repliedTo;
    }

    public function setDateTime($dateTime)
    {
        $this->date_time = $dateTime;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setSentStatus($sentStatus)
    {
        $this->sent_status = $sentStatus;
    }

    public function setReadStatus($readStatus)
    {
        $this->read_status = $readStatus;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setMessageDate($messageDate)
    {
        $this->message_date = $messageDate;
    }

    public function setMessageTime($messageTime)
    {
        $this->message_time = $messageTime;
    }

    public function getMessageId()
    {
        return $this->message_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getSentTo()
    {
        return $this->sent_to;
    }

    public function getRepliedTo()
    {
        return $this->replied_to;
    }

    public function getDateTime()
    {
        return $this->date_time;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSentStatus()
    {
        return $this->sent_status;
    }

    public function getReadStatus()
    {
        return $this->read_status;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getMessageDate()
    {
        return $this->message_date;
    }

    public function getMessageTime()
    {
        return $this->message_time;
    }
}