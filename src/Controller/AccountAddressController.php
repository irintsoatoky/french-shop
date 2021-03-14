<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Classe\Cart;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class AccountAddressController extends AbstractController
{
    private $entityManager;
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;	
	}
    /**
     * @Route("/compte/adresses", name="account_address")
     */
    public function index(): Response
    {
        // dd($this->getUser());
        return $this->render('account/address.html.twig', [
            'controller_name' => 'AccountAddressController',
        ]);
    }
    /**
     * @Route("/compte/ajouter-une-adresse", name="account_address_add")
     */
    public function add(Request $request, Cart $cart): Response
    {
        // dd($this->getUser());
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

		$form = $form->handleRequest($request); // 1. ecoute de la request

        if ($form->isSubmitted() && $form->isValid()){// if 2(ecoute) && 3(check EmailType etc...)

            $address->setuser($this->getUser());
            // dd($address);
            if($address){
                $this->entityManager->persist($address);
				$this->entityManager->flush();
                if($cart->get()){
                    return $this->redirectToRoute('order');
                }else{
                    return $this->redirectToRoute('account_address');
                }
                    
            }
        }
        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_address_edit")
     */
    public function edit(Request $request,$id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        $form = $this->createForm(AddressType::class, $address);

        if (!$address || $address->getUser() !== $this->getUser()){// REdirection si : l'adresse n'existe pas OU {sécurité} si l'user n'est pas le meme que l'user courrant (barre d'adresse, on peut taper n'importe quel id...) 
            return $this->redirect('account_address');
        }

		$form = $form->handleRequest($request); // 1. ecoute de la request

        if ($form->isSubmitted() && $form->isValid()){// if 2(ecoute) && 3(check EmailType etc...)

            // dd($address);
                // $this->entityManager->persist($address); // unilite car mise à jour
				$this->entityManager->flush();
                return $this->redirectToRoute('account_address');
            
        }
        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
        /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_address_delete")
     */
    public function delete($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ($address && $address->getUser() === $this->getUser()){// REdirection si : l'adresse n'existe pas OU {sécurité} si l'user n'est pas le meme que l'user courrant (barre d'adresse, on peut taper n'importe quel id...) 
            $this->entityManager->remove($address);
			$this->entityManager->flush();
        }
        return $this->redirectToRoute('account_address');
    }
}
