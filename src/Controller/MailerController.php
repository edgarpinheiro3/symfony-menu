<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/mail', name: 'mail')]
    public function sendEmail(MailerInterface $mailer, Request $request)
    {

        $emailForm =  $this->createFormBuilder()
            ->add('descricao', TextareaType::class,[
                'attr' => array('rows' => '5')
            ])
            ->add('Enviar', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-danger float-right'
                ]
            ])
            ->getForm();
        
        $emailForm->handleRequest($request);

            
            if ( $emailForm->isSubmitted() ) {

                $descricao = $emailForm->getData();
                $text = ($descricao['descricao']);

                $email = (new TemplatedEmail())
                    ->from('edgar@cliquecertificadodigital.com.br')
                    ->to('edgar@cliquecertificadodigital.com.br')
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Primeiro Projeto em Symfony')
                    //->text('Enviando emails!')
                    //->html('<p>See Twig integration for better HTML integration!</p>');
                    ->htmlTemplate('mailer/mail.html.twig')

                    ->context([
                        'mesa' => $mesa,
                        'text' => $text,
                    ]);

                $mailer->send($email);
                $this->addFlash('message', 'Enviado email teste!');

                return $this->redirect($this->generateUrl('mail'));

            }

            return $this->render('mailer/index.html.twig', [
                'emailForm' => $emailForm->createView()
            ]);

    }
}
