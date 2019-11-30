<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


class Thread
{
    private $id;
    private $threadId;
    private $text;
    private $repliesTo;
    private $repliesFrom;
    private $date;

    public function creteThread() {
        
    }

    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }

    public function setThreadId($threadId) {
        $this->threadId = $threadId;
    }
    public function getThreadId() {
        return $this->threadId;
    }

    public function setText($text) {
        $this->text = $text;
    } 
    public function getText() {
        return text;
    }

    public function setRepliesTo($repliesTo) {
        $this->repliesTo = $repliesTo;
    }
    public function getRepliesTo() {
        return $this->repliesTo;
    }

    public function setRepliesFrom($repliesFrom) {
        $this->repliesFrom = $repliesFrom;
    }
    public function getRepliesFrom() {
        return $this->repliesTo;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    public function getDate() {
        return $this->date;
    }
}