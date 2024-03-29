<?php

namespace App\Controller;

use App\Entity\Prato;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {

    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {

        $em = $this->doctrine->getManager();
        $prato = $em->getRepository(Prato::class)->findAll();

        $random = array_rand($prato, 2);
        //dump($prato[$random[0]]);
        //dump($prato[$random[1]]);

        return $this->render('home/index.html.twig', [
            'prato1' => $prato[$random[0]],
            'prato2' => $prato[$random[1]],
        ]);
    }
}
