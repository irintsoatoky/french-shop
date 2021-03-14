<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Product;
use App\Entity\Header;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// symfony console debug:route      => Affiche toutes les routes
// symfony console debug:autowiring => affiche tous les services dispo
// symfony console debug:autowiring => session affiche tous les services liés à la session
class HomeController extends AbstractController
{
    private $entityManager;
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;	
	}
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        $bestSellers = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        $headers = $this->entityManager->getRepository(Header::class)->findall();
        // dd($headers);
        return $this->render('home/index.html.twig',[
            'bestSellers' => $bestSellers,
            'headers' => $headers
        ]);
        // return $this->render('home/index.html.twig',[
        //         'headers' => $headers
        //     ]);
    }
}