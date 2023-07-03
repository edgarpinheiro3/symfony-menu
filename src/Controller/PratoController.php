<?php

namespace App\Controller;

use App\Entity\Prato;
use App\Repository\PratoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/prato', name: 'prato.')]
class PratoController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {

    }

    #[Route('/', name: 'index')]
    public function index()
    {
        $em = $this->doctrine->getManager();
        $prato = $em->getRepository(Prato::class)->findAll();

        return $this->render('prato/index.html.twig', [
            'prato' => $prato,
        ]);
    }

    #[Route('/store', name: 'store')]
    public function store(Request $request)
    {
        $prato = new Prato();
        $prato->setName('Lasanha');
        $prato->setDescription('Lasanha é tanto um tipo de massa alimentícia formada por fitas largas, como também um prato, por vezes chamado lasanha ao forno.');
        $prato->setPrice('120.5');

        //EntityManager
        $em = $this->doctrine->getManager();
        $em->persist($prato);
        $em->flush();

        return new Response("Prato Criado");
    }
}
