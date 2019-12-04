<?php

namespace App\Helper;

use App\Repository\BoardRepository;

abstract class ThreadHelper {

    public function parseData($threadData) {
        $param['text'] = ThreadHelper::parseText($threadData['threadText']);
        $param['repliesArray'] = ThreadHelper::getRepliesArray($threadData['threadText']);
        $param['repliesString'] = ThreadHelper::getRepliesString($threadData['threadText']);
        $param['date'] = date("m/d/y H:i:s");

        if(isset($threadData['threadMediaFile'])) {
            $param['fileData'] = $threadData['threadMediaFile'];
        }
        else {
            $param['fileData'] = "";
        }
        

        return $param;
    }

    public function getRepliesString($text) {
        preg_match_all('#\>>(.*?)\>>#', $text, $match);

        return implode( ", ", $match[1]);
    }
    public function getRepliesArray($text) {
        preg_match_all('#\>>(.*?)\>>#', $text, $match);

        return $match[1];
    }

    public function parseText($text) {
        preg_match_all('#\>>(.*?)\>>#', $text, $replies);
        
        foreach($replies[1] as $reply) {
            //create reply url
           
            $text = str_replace(">>".$reply.">>", "<a href='#".$reply."'>" .$reply .'</a>', $text);
        }
    
        return $text;
    }

    public function ParseThreads($thread) {
        for( $i=0; $i < count($thread) ; $i++ ) {  
            $repliesFrom = $thread[$i]['repliesFrom'];
            $fileData = $thread[$i]['fileData'];

            //fileData
            if($repliesFrom == "" || strpos($fileData, ',') == false) {}
            else {
                $thread[$i]['fileData'] = explode( ",", $fileData);
            }
            //reply
            if($repliesFrom == "") {}
            //if contain only one reply
            elseif (strpos($thread[$i]['repliesFrom'], ',') == false) {     
                $thread[$i]['repliesFrom'] = "<a href='#" .$repliesFrom. "'> >>" .$repliesFrom. "</a>";
            }
            else {
                $replies = explode( ",", $repliesFrom);
                $thread[$i]['repliesFrom'] = "";

                foreach($replies as $reply) {
                    $thread[$i]['repliesFrom'] .= "<a href='#" .$reply. "'> >>" .$reply. "</a> ";
                }
            }
        }

        return $thread;
    }

    public function getThreadReplies($threadId, $threads, $newReply) {    
        foreach($threads as $thread) {
            if($thread['threadId'] == $threadId) {
                $thredInfo = $thread;
            }
        }

        if(strlen($thredInfo['repliesFrom'])>1) {
            $replies = $thredInfo['repliesFrom'] . ",";
        }
        elseif(strlen($thredInfo['repliesFrom'])<=1) {
            $replies = "";
        }

        $newReplies = $replies . $newReply;

        return $newReplies;
    }
} 