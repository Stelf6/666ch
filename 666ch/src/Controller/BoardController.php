<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Board;

class BoardController extends AbstractController
{
    public function board($boardName): Response
    {
        $request = Request::createFromGlobals();

        if($request->query->get('threadText') != null) {
            $threadId = $this->boardRep->createThread($boardName, $request->query);

            return $this->redirectToRoute('thread',['boardName' => $boardName, 'threadId' => $threadId]);
        }

        else{
            $param['threads'] = $this->boardRep->getThreadList($boardName);
            $param['boardName'] = $boardName;

            if($param ['threads'] == "thread list is empty") {
                return $this->render('board/boardIsEmpty.html.twig', $param);
            }

            return $this->render('board/board.html.twig', $param);
        }
    }
}