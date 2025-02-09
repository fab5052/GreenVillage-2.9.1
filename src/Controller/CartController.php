<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use App\Repository\ImageRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    private ProductRepository $productRepository;

    private ImageRepository $imageRepository;

    public function __construct(
        ProductRepository $productRepository,
        ImageRepository $imageRepository,
    ) {
    
        $this->productRepository = $productRepository;
        $this->imageRepository = $imageRepository;

    }
    private function calculateProductDetails(Product $product, int $quantity): array
    {
        $tvaRate = $product->getTva()?->getRate() ?? 0;
        $priceWithTva = $product->getPrice() * (1 + $tvaRate / 100);
        $total = $priceWithTva * $quantity;
        $totalTaxes = ($priceWithTva - $product->getPrice()) * $quantity;

        return [
            'priceWithTva' => $priceWithTva,
            'total' => $total,
            'totalTaxes' => $totalTaxes,
        ];
    }

    #[Route('/', name: 'index')]
    public function viewCart(SessionInterface $session): Response
    {
        try {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez vous connecter pour voir votre panier.');
                return $this->redirectToRoute('app_login');
            }

            $panier = $session->get('panier', []);
            $dataProduct = [];
            $total = 0;
            $totalTaxes = 0;
        
            foreach ($panier as $id => $quantity) {
               // $id = $request->get('id');
               $product = $this->productRepository->findOneBy(['id' => $id]);
               $images = $this->imageRepository->findAll();

               if ($product) {
                   $productDetails = $this->calculateProductDetails($product, $quantity);
                   $total += $productDetails['total'];
                   $totalTaxes += $productDetails['totalTaxes'];
               
                   $dataProduct[] = [
                       'product' => $product,
                       'quantity' => $quantity,
                       'priceWithTax' => $productDetails['priceWithTva'],
                       'images' => $images,
                   ];
               }
            }

            $session->set('ttc', $total);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('cart/index.html.twig', [
            'products' => $dataProduct,
            'total' => $total,
            'totalTaxes' => $totalTaxes,
            'images' => $images,
        ]);
    }

    #[Route('/add/{id}', name: 'app_add')]
    public function add(Product $product = null, SessionInterface $session): Response
    {
        try {
            if (!$product) {
                $this->addFlash('error', 'Produit non trouvé.');
                return $this->redirectToRoute('cart_index');
            }

            $panier = $session->get('panier', []);
            $panier[$product->getId()] = ($panier[$product->getId()] ?? 0) + 1;

            $session->set('panier', $panier);

            return $this->redirectToRoute('cart_index');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }
    }

    #[Route('/allRemove/{id}', name: 'app_allRemove')]
    public function allRemove(Product $product, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        unset($panier[$product->getId()]);
        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'app_remove')]
    public function remove(Product $product, SessionInterface $session): Response
    {
        try {
            $panier = $session->get('panier', []);
            $id = $product->getId();

            if (!empty($panier[$id])) {
                $panier[$id]--;
                if ($panier[$id] <= 0) {
                    unset($panier[$id]);
                }
            }

            $session->set('panier', $panier);

            return $this->redirectToRoute('cart_index');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }
    }

    #[Route('/order', name: 'order')]
    public function order(SessionInterface $session, EntityManagerInterface $entityManager, SendMailService $mail): Response
    {
        try {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez vous connecter pour passer une commande.');
                return $this->redirectToRoute('app_login');
            }

            $panier = $session->get('panier', []);
            if (empty($panier)) {
                $this->addFlash('warning', 'Votre panier est vide.');
                return $this->redirectToRoute('cart_index');
            }

            $paiement = $session->get('paiement');
            if (empty($paiement)) {
                $this->addFlash('warning', 'Aucun moyen de paiement sélectionné.');
                return $this->redirectToRoute('cart_index');
            }

            $order = (new Order())
                ->setUser($this->getUser())
                ->setReference('GreVil:' . uniqid() . mt_rand(100, 999))
                ->setPaymentMethod($paiement)
                ->setType('commande')
                ->setPaymentDate(new \DateTimeImmutable())
                ->setPaymentStatus('En Cours de traitement')
                ->setDate(new \DateTimeImmutable())
                ->setStatus('En Attente');

            $totalAmount = 0;
            $orderDetails = []; // Initialize as an array

            foreach ($panier as $id => $quantity) {
                $product = $entityManager->getRepository(Product::class)->find($id);
                if ($product) {
                    $productDetails = $this->calculateProductDetails($product, $quantity);
                    $totalAmount += $productDetails['total'];
            
                    $orderDetail = (new OrderDetails())
                        ->setOrder($id)
                        ->setProduct($product)
                        ->setQuantity($quantity)
                        ->setPrice($productDetails['priceWithTax']);
            
                    $orderDetails[] = $orderDetail; 
                    $entityManager->persist($orderDetail); 
                } else {
                    $this->addFlash('warning', "Le produit avec l'ID ($id) n'existe pas et a été ignoré.");
                }
            }
            
            if (empty($orderDetails)) {
                $this->addFlash('warning', 'Aucun produit valide dans votre panier.');
                return $this->redirectToRoute('cart_index');
            }
            
            $order->setTotal($totalAmount);
            $entityManager->persist($order);
            $entityManager->flush(); 

            $mail->send(
                'no-reply@village-green.fr',
                $session->get('user')->getEmail(),
                'Votre commande sur le site Village Green',
                'recap',
                [
                    'order' => $order,
                    'orderDetails' => $orderDetails,
                ]
            );

            $session->clear();
            $this->addFlash('success', 'Votre commande a été enregistrée avec succès.');

            return $this->redirectToRoute('app_profile');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }
    }


    #[Route('/ChoiceMultipleDeliveryByCart', name: 'Choice_Multiple_Delivery_By_Cart')]
    public function ChoiceMultipleDeliveryByCart(SessionInterface $session): Response
    {
        try {
            $panier = $session->get('panier', []);
            if (empty($panier)) {
                $this->addFlash('warning', 'Votre panier est vide.');
                return $this->redirectToRoute('cart_index');
            }
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez vous connecter pour passer une commande.');
                return $this->redirectToRoute('app_login');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }


        return $this->render('delivery/Choice_multiple_delivery.html.twig', []);
    }




    #[Route('/recap', name: 'recap')]
    public function recap(ProductRepository $productRepository, SessionInterface $session): Response
    {
        try {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez vous connecter pour voir votre récapitulatif.');
                return $this->redirectToRoute('app_login');
            }

            $panier = $session->get('panier', []);
            $address = $session->get('address');

            $dataProduct = array_filter(array_map(function ($id) use ($productRepository, $panier) {
                $product = $productRepository->findBy($id);
                return $product ? [
                    'product' => $product,
                    'quantity' => $panier[$id],
                    'label' => $productRepository->getLabel(),
                ] : null;
            }, array_keys($panier)));
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('cart/recap.html.twig', [
            'recap' => [
                'products' => $dataProduct,
                'addresses' => $address,
            ],
        ]);
    }
}
