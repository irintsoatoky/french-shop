<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;

use App\Entity\Product;



use Stripe\Stripe;

use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';// la boutique française
        $products_for_srtipe =[];

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if($order){
        $response = new JsonResponse(['error' => 'order']);
        }


        // dd($order->getOrderDetails()->getValues());
        // foreach($cart->getFull() as $product){// Ancienne version
            //     $products_for_stripe[] = [
            //         'price_data' => [
            //           'currency' => 'eur',
            //           'unit_amount' => $product['product']->getPrice(),
            //           'product_data' => [
            //             'name' => $product['product']->getName(),
            //             'images' => ["$YOUR_DOMAIN"."/uploads/".$product['product']->getIllustration()],
            //           ],
            //         ],
            //         'quantity' => $product['quantity'],
            //     ];
        // }


        foreach($order->getOrderDetails()->getValues() as $product){
            // dd($product);
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct()) ;
            $products_for_stripe[] = [
                'price_data' => [
                  'currency' => 'eur',
                  'unit_amount' => $product->getPrice(),
                  'product_data' => [
                    'name' => $product->getProduct(),
                    'images' => ["$YOUR_DOMAIN"."/uploads/".$product_object->getIllustration()],
                  ],
                ],
                'quantity' => $product->getQuantity()
            ];
        }

        // Ajout du transporteur (prix...)
        $products_for_stripe[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $order->getCarrierPrice(),
              'product_data' => [
                'name' => $order->getCarrierName(),
                'images' => ["$YOUR_DOMAIN"], // A revoir...
              ],
            ],
            'quantity' => $product->getQuantity()
        ];
        // dd($products_for_stripe);
    
        Stripe::setApiKey('sk_test_51ISgLcLz1CWPvjKMy7ONF3A3zS4N0xDTJ1kKdMoc4klqQApRvbIhzHqdJHMDufGsba5BnUGshMmH1koKtH9AeXVR003jt98lgg');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);

        $entityManager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
        

        // FIN : stripe     ********************************************

        // return $this->render('stripe/index.html.twig', [
        //     'controller_name' => 'StripeController',
        // ]);
    }
}
