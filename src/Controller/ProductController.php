<?php

namespace App\Controller;

use App\Entity\Product;
use App\Classe\Search;
use App\Form\SearchType;



use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;	
	}
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        // Prépare l'affichage des produits
		$products = $this->entityManager->getRepository(Product::class)->findAll();


        // Prépare l'affichage du formulaire de recherche
        $search = new Search;
        $form = $this->createForm(SearchType::class,$search);
        $form = $form->handleRequest($request); // 1. ecoute de la request

        if ($form->isSubmitted() && $form->isValid()){// if 2(ecoute) && 3(check EmailType etc...)

            // $search = $form->getData();// inutile car $search est déjà lié au formulaire
            // dd($search->string);
            // dd(!( $search->string === null && $search->categories === []));

            if(!( $search->string === null && $search->categories === [])){
                $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search); 
                        // findWithSearch n'existe pas : il faut la créer dans ProductRepository
            }

        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug): Response
    {
		$product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        // dd($slug);
        if(!$product)
            return $this->redirectToRoute('products');

        // Récup des meilleures ventes 
        $bestSellers = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'bestSellers' => $bestSellers
        ]);
    }
}
