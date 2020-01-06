<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Helper\ThreadHelper;
use App\Entity\ThreadForm;
use App\Form\ThreadFormType;
use App\Repository\BoardRepository;

class ThreadController extends AbstractController
{
    public function thread($boardName,$threadId)
    {
        $request = Request::createFromGlobals();
        
        $boardRep = new BoardRepository();

        $product = new ThreadForm();
        $form = $this->createForm(ThreadFormType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $mediaFile = $form['mediaFile']->getData();
        
            if (isset($mediaFile)) {
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$mediaFile->guessExtension();
   
                try {
                    //fix
                    $mediaFile->move($this->get('kernel')->getProjectDir()."/public/uploads",$newFilename);
                } catch (FileException $e) { }

                $product->setFilename($newFilename);
                
            }      
            $id = $boardRep->getThreadId();

            $param = [
                'threadText' => $form['threadText']->getData(),
                'threadMediaFile' => $product->getFileName(),
                'id' => $id,
                'threadId' => $threadId,
                'date' => date("m/d/y H:i:s")
            ];

            $boardRep->sendThreadMessage($threadId, $id, $param);
            
            $param['threadText'] = ThreadHelper::parseText($form['threadText']->getData());

            //pass param to ajax response
            return new JsonResponse($param);
        }
        else{
            $threads = $boardRep->getThreadInfo($threadId);

            $param = [
                'threadInfo' => ThreadHelper::parseThreads($threads),
                'boardName' => $boardName,
                'mainThreadId' => $threadId,
                'form' => $form->createView(),
            ];      
            return $this->render('thread/thread.html.twig',$param);
        }
    }
}