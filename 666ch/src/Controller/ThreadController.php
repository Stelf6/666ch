<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use App\Helper\ThreadHelper;
use App\Entity\MediaFile;
use App\Form\MediaFileType;

class ThreadController extends AbstractController
{
    public function thread($boardName,$threadId)
    {
        $request = Request::createFromGlobals();
        
        $product = new MediaFile();
        $form = $this->createForm(MediaFileType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaFile = $form['mediaFile']->getData();

            if ($mediaFile) {
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                // $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$mediaFile->guessExtension();

                // Move the file to the directory
                try {
                    $mediaFile->move(
                        $this->getParameter('mediaFile_directory'),
                        $newFilename
                    );
                } catch (FileException $e) { }

                $product->setFilename($newFilename);
            }
        }
        
        if($request->request->get('threadText') != null) {
            $id = $this->boardRep->getThreadId();

            $imagePath = str_replace("\\", "/", $this->getParameter('mediaFile_directory'));

            $param = [
                'threadText' => $request->request->get('threadText'),
                'threadMediaFile' => $product->getFileName(),
            ];

            $this->boardRep->sendThreadMessage($threadId, $id, $param);

            return $this->redirectToRoute('thread',['boardName' => $boardName, 
                                                    'threadId' => $threadId]);
        }

        else{
            $threads = $this->boardRep->getThreadInfo($threadId);

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