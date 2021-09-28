<?php

namespace App\Controller;

use App\Controller\Mercure\CookieGenerator as MercureCookieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function __invoke(MercureCookieGenerator $cookieGenerator): Response
    {
        $response = $this->render('chat/index.html.twig', []);
        $response->headers->setCookie($cookieGenerator->generate());

        return $response;
    }
}