<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Form\ChangePasswordType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;



use Doctrine\ORM\EntityManagerInterface;


class AccountPasswordController extends AbstractController
{
    private $entityManager;
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;	
	}

    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {    
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class,$user);
		$form = $form->handleRequest($request); // 1. ecoute de la request
        
        if ($form->isSubmitted() && $form->isValid()){// if 2(ecoute) && 3(check EmailType etc...)

            $old_pwd = $form->get('old_password')->getData();
            // dd($old_pwd); 
            if ($encoder->isPasswordValid($user,$old_pwd)){
                $new_pwd = $form->get('new_password')->getData();
                // dd($new_pwd);
                $password = $encoder->encodePassword($user,$new_pwd);
                $user->setPassword($password);
                $this->entityManager->persist($user);
				$this->entityManager->flush();
                $notification = "Votre mot de passe à bien été mise à jour.";

            }else{
                $notification = "Votre mot de passe actuel n'est pas le bon.";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
