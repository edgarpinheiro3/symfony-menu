<?php

namespace App\Controller;

use App\Entity\Prato;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {

    }

    #[Route('/menu', name: 'menu')]
    public function menu()
    {

        $em = $this->doctrine->getManager();
        $prato = $em->getRepository(Prato::class)->findAll();

        return $this->render('menu/index.html.twig', [
            'prato' => $prato,
        ]);
    }
}
