<?php

namespace App\Controller;

use App\Entity\Pole;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
* @Route("/poles")
*/
class PoleController extends AbstractController
{
    /**
     * @Route("/presentation", name="poles_index")
     * 
     */
    public function index()
    {
        return $this->render('pole/index.html.twig');
    }

     /**
     * @Route("/{nomPole}/description", name="pole_description")
     */
    public function poleDescription(Pole $pole)
    {
        return $this->render('pole/'.$pole->getNomPole().'.html.twig', [
            'pole'=> $pole,
        ]);
    }
}

