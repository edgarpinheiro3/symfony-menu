<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Prato;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class OrderController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {

    }

    #[Route('/order', name: 'pedido')]
    public function index(OrderRepository $orderRepository)
    {

        $order = $orderRepository->findBy(
            ['mesa' => 'mesa1']
        );

        return $this->render('order/index.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/order/{id}', name: 'order')]
    public function order(Prato $prato)
    {
        $order = new Order();
        $order->setMesa('Mesa1');
        $order->setNome($prato->getName());
        $order->setNumero($prato->getId());
        $order->setPreco($prato->getPrice());
        $order->setStatus("aberto");

        $em = $this->doctrine->getManager();
        $em->persist($order);
        $em->flush();

        $this->addFlash('order', $order->getNome(). ' seu pedido foi aberto');

        return $this->redirect($this->generateUrl('menu'));

    }

    #[Route('/status/{id},{status}', name: 'status')]
    public function status($id,  $status)
    {
    
        $em = $this->doctrine->getManager();
        $order = $em->getRepository(Order::class)->find($id);

        $order->setStatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('pedido'));

    }

    #[Route('/delete/{id}', name: 'deleteOrder')]
    public function delete($id, OrderRepository $o)
    {

        $em = $this->doctrine->getManager();
        $order = $o->find($id);
        $em->remove($order);
        $em->flush();

        return $this->redirect($this->generateUrl('pedido'));
    }

}
