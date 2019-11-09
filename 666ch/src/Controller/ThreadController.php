<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Helper\ThreadHelper;


class ThreadController extends AbstractController
{
    public function thread($boardName,$threadId)
    {
        $request = Request::createFromGlobals();

        if($request->query->get('threadText') != null) {
            $id = $this->boardRep->getThreadId();
            $this->boardRep->sendThreadMessage($threadId, $id, $request->query);

            return $this->redirectToRoute('thread',['boardName' => $boardName, 'threadId' => $threadId]);
        }

        else{
            $threads = $this->boardRep->getThreadInfo($threadId);
            $param['threadInfo'] = ThreadHelper::parseThreads($threads);
            $param['boardName'] = $boardName;
            $param['mainThreadId'] = $threadId;

            return $this->render('thread/thread.html.twig',$param);
        }
    }
}