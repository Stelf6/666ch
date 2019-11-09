<?php

namespace App\Repository;

use PDO;
use App\Entity\Board;

use App\Helper\ThreadHelper;
use Appp\Helper\BoardHelper;


class BoardRepository 
{
    protected $conn;

    public function __construct()
    {
        $this->conn = new PDO('mysql:host=127.0.0.1;dbname=666ch', 'root', '');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    

    public function getBoard($boardName) {
        $stmt = $this->conn->prepare("SELECT * FROM boards WHERE name = :board");
        $stmt->bindValue(":board", $boardName);
        $stmt->execute();

        return $stmt->fetchAll()[0];
    }

    public function getBoards()
    {
        $stmt = $this->conn->prepare("SELECT * FROM boards");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, "App\Entity\Board");
    }

    public function updateThreadList($threadId, $boardName) {
        $board = $this->getBoard($boardName);

        if(strlen($board['threadList'])>1) {
            $threadList = $board['threadList']. "," . $threadId;
        }
        elseif(strlen($board['threadList'])<=1) {
            $threadList = $threadId;
        }

        $stmt = $this->conn->prepare("UPDATE boards
                                      SET threadList=:threadList
                                      WHERE name=:boardName");
		   
        $stmt->bindValue(":boardName", $boardName);
        $stmt->bindValue(":threadList", $threadList);
        $stmt->execute();
    }

    public function getThreadList($boardName) {
        $board = $this->getBoard($boardName);

        if(strlen($board['threadList'])>1) {
            $threads = explode(",", $board['threadList']);

            foreach($threads as $thread) {      
                $threadsInfo[] = $this->getThreadInfo($thread)[0];
            }
        }
        elseif(strlen($board['threadList'])<=1) {
            $threadsInfo = "thread list is empty";
        }

        return $threadsInfo;
    }

    public function getThreadId() {
        $stmt = $this->conn->prepare("SELECT threadId FROM thread ");
        $stmt->execute();
        $threadId = $stmt->fetchAll()[0][0];
        $threadId++;

        $stmt = $this->conn->prepare("UPDATE thread
                                      SET threadId=:threadId
		                              WHERE id=1");
		
        $stmt->bindValue(":threadId", $threadId);
        $stmt->execute();

        return $threadId;
    }

    //threadData == request
    public function createThread($boardName, $threadData) {
        $threadId = $this->getThreadId();

        //create thread table    
        $stmt = $this->conn->prepare("CREATE TABLE `666ch`.`$threadId` 
                                    ( `id` INT(255) NOT NULL AUTO_INCREMENT , 
                                    `threadId` INT(255) NOT NULL , 
                                    `text` VARCHAR(255) NOT NULL ,
                                    `repliesTo` VARCHAR(255) NOT NULL ,
                                    `repliesFrom` VARCHAR(255) NOT NULL ,
                                    `date` VARCHAR(55) NOT NULL ,
                                    PRIMARY KEY (`id`)) ENGINE = InnoDB");
        
        $stmt->execute();
        
        $this->sendThreadMessage($threadId, $threadId, $threadData);
        $this->updateThreadList($threadId, $boardName);

        return $threadId;
    }

    public function getThreadInfo($threadName) {
        $stmt = $this->conn->prepare("SELECT * FROM `$threadName`");
        $stmt->execute();
      
        return $stmt->fetchAll();
    }

    public function sendThreadMessage($bdId, $threadId, $threadData) {
        $threadParam = ThreadHelper::ParseData($threadData->get('threadText'));

        foreach($threadParam['repliesArray'] as $reply) {
            $this->updateThreadRepliesFrom($reply, $threadId, $bdId);
        }

        $stmt = $this->conn->prepare("INSERT INTO `$bdId` (`id`, `threadId`, `text`, `repliesTo`, `date`) 
                                      VALUES (NULL, :threadId, :text, :repliesTo, :date)");

        $stmt->bindValue(":threadId", $threadId);
        $stmt->bindValue(":text", $threadParam['text']);
        $stmt->bindValue(":repliesTo", $threadParam['repliesString']);
        $stmt->bindValue(":date", $threadParam['date']);
        $stmt->execute();
    }

    public function updateThreadRepliesFrom($threadId, $newReply, $bdId) {
        $threads = $this->getThreadInfo($bdId);

        $newReplies = ThreadHelper::getThreadReplies($threadId, $threads, $newReply);

        $stmt = $this->conn->prepare("UPDATE `$bdId`
                                      SET repliesFrom=:repliesFrom
		                              WHERE threadId=:threadId");
		
        $stmt->bindValue(":repliesFrom", $newReplies);
        $stmt->bindValue(":threadId", $threadId);
        $stmt->execute();
    }
}
