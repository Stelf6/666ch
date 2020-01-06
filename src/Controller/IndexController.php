<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Helper\ThreadHelper;
use App\Repository\BoardRepository;

class IndexController extends AbstractController 
{
    public function index()
    {   
        $boardRep = new BoardRepository();

        $param['boards'] = $boardRep->getBoards();
        return $this->render('index/index.html.twig',$param);
    }
}