<?php

namespace App\Controller;

use App\Entity\Prato;
use App\Form\PratoType;
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

    #[Route('/new', name: 'new')]
    public function store(Request $request)
    {
        $prato = new Prato();
        //Formulário
        $form = $this->createForm(PratoType::class, $prato);
        $form->handleRequest($request);

        if ( $form->isSubmitted() ) {

            //EntityManager
            $em = $this->doctrine->getManager();
            $image = $request->files->get('prato')['image'];

            if ($image) {
                $name = md5(uniqid()).'.'.$image->guessClientExtension(); 
            }

            //dd($image);
            $image->move($this->getParameter('bilder_ordner'), $name);

            $prato->setImage($name);
            $em->persist($prato);
            $em->flush();

            return $this->redirect($this->generateUrl('prato.index'));

        }

        return $this->render('prato/new.html.twig', [
            'pratoForm' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, PratoRepository $p)
    {

        $em = $this->doctrine->getManager();
        $prato = $p->find($id);
        $em->remove($prato);
        $em->flush();

        //Mensagem
        $this->addFlash('message', 'Prato excluído com Sucesso!');

        return $this->redirect($this->generateUrl('prato.index'));
    }

    #[Route('/show/{id}', name: 'show')]
    public function show(Prato $prato)
    {
        //dump($prato);
        return $this->render('prato/show.html.twig', [
            'prato' => $prato,
        ]);
    }

}
