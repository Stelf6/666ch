<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Helper\ThreadHelper;

class IndexController extends AbstractController 
{
    public function index()
    {   
        $param['boards'] = $this->boardRep->getBoards();
        return $this->render('index/index.html.twig',$param);
    }
}