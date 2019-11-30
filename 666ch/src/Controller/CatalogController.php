<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogController extends AbstractController
{
    public function catalog($boardName)
    {
      

        return new Response(
            '<html><body>in catalog '.$boardName.'</body></html>'
        );
    }
}