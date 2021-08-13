<?php

namespace App\Controller;

use App\Entity\Product;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @Route("/log", name="all_logs")
     */
    public function log(LoggerInterface $dbLogger): Response
    {

        $dbLogger->error('Premier log');

        return $this->render('index.html.twig');
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(['enabled' => true]);

        return $this->render('home/index.html.twig',[
            'products' => $products
        ]);
    }

}
