<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {

    }
    
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher)
    {

        $form = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Usuário',    
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Senha'],
                'second_options' => ['label' => 'Confirme a Senha'],
            ])
            ->add('cadastrar', SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if ( $form->isSubmitted() ) {

            $registro = $form->getData();
            //dump($registro);

            $user = new User();
            $user->setUsername($registro['username']);

            $user->setPassword(
                $passwordHasher->hashPassword($user, $registro['password'])
            );

            //$em = $this->getDoctrine()->getManager();
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('home'));

        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
