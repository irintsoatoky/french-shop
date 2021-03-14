<?php
namespace App\Classe;

use App\Entity\Product;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;


class Cart 
{
	private $session;

	public function __construct(SessionInterface $session,EntityManagerInterface $entityManager)
	{
		$this->session = $session;
		$this->entityManager = $entityManager;
	}
	
	public function add($id)
	{
		$cart = $this->session->get('cart',[]);

		if(!empty($cart[$id])){
			$cart[$id]++;
		}else{
			$cart[$id] = 1;
		}

		return $this->session->set('cart',$cart);
	}
	public function get() 
	{
		return $this->session->get("cart");
	}
	public function remove() 
	{
		return $this->session->remove('cart');
	}
	public function delete($id) 
	{
		$cart = $this->session->get('cart',[]);
		unset($cart[$id]);

		return $this->session->set('cart',$cart);
	}
	public function decrease($id)
	{
		$cart = $this->session->get('cart',[]);

		if($cart[$id] > 1){
			$cart[$id]--;
		}else{
			unset($cart[$id]);
		}

		return $this->session->set('cart',$cart);
	}
	public function getFull()
	{
		        // Renvoie id + qté
        // return $this->render('cart/index.html.twig',[
        //     'cart' => $cart->get()
        // ]);
        $cartComplete = [];
        if($this->get()){// Panier non vide

            // Ajout de toutes les données concernant les produits ajoutés
            foreach($this->get() as $id => $quantity){
					// Sécurité : ajout d'un produit qui n'existe pas
					// => /cart/add/123456
					$object_product = $this->entityManager->getRepository(Product::class)->findOneById($id);
					if(!$object_product)	{
						$this->delete($id);
						continue; // n'exécute pas $cartComplette[] =....
					}
                $cartComplete[] = [
                    'product' => $object_product,
                    'quantity' => $quantity
                ];
            }
        }
		  return $cartComplete;
	}
}
?>