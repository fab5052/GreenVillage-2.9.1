<?php

namespace App\Controller;

use Exception;
use App\Entity\Product;
use App\Entity\Rubric;
use App\Repository\ProductRepository;
use App\Repository\RubricRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\EventListener\SlugListener;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/rubrics', name: 'rubric_')]
class RubricController extends AbstractController
{

    private ProductRepository $productRepository;
    private RubricRepository $rubricRepository;
    private PaginatorInterface $paginator;

    public function __construct(
        ProductRepository $productRepository,
        RubricRepository $rubricRepository,
        PaginatorInterface $paginator
    ) 
    
    {
        $this->productRepository = $productRepository;
        $this->rubricRepository = $rubricRepository;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        try {
            $viewRubrics = $entityManager->getRepository(Rubric::class)->findBy(['parent' => null]);
          //  $viewLastCreatedAt = $entityManager->getRepository(Rubric::class)->findOneBy([], ['createdAt' => 'DESC']);

        } catch (Exception $e) {
            $this->addFlash('error', 'Impossible de charger les rubriques. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_home');
        }
              
            
        return $this->render('rubric/rubrics.html.twig', [
            'rubrics' => $viewRubrics,
          //  'rubric' => $lastRubric
        ]);
    }

    #[Route('/last-rubric', name: 'app_last_rubric')]
    public function showLastRubric(RubricRepository $rubricRepository): JsonResponse
    {
        $lastRubric = $rubricRepository->showLastRubric();

        if (!$lastRubric) {
            return $this->json(['message' => 'No rubrics found.'], 404);
        }

        return $this->json([
            'id' => $lastRubric->getId(),
            'label' => $lastRubric->getLabel(),
            'createdAt' => $lastRubric->getCreatedAt()->format('Y-m-d H:i:s'),
            'lastRubric' => $lastRubric->getImage(),
        ]);
    }


  //  #[Route('/', name: 'home')]
   // public function index(EntityManagerInterface $entityManager): Response
    
    // {
    //     try {
    //         // Chargement des rubriques principales avec leurs sous-rubriques
    //         // $rubrics = $entityManager->getRepository(Rubric::class)
    //         //     ->createQueryBuilder('r')
    //         //     ->leftJoin('r.children', 'c')
    //         //     ->addSelect('c')
    //         //     ->where('r.parent IS NULL')
    //         //     ->getQuery()
    //         //     ->getResult();
    //         $rubrics = $entityManager->getRepository(Rubric::class)->findBy(['parent' => null]);

    //     } catch (Exception $e) {
    //         $this->addFlash('error', 'Impossible de charger les rubriques. Veuillez réessayer plus tard.');
    //         $rubrics = [];
    //     }
    
    //     return $this->render('/index.html.twig', [
    //         'rubrics' => $rubrics,
    //     ]);
    // }
    // {
    //     try {
    //         $viewRubrics = $entityManager->getRepository(Rubric::class)->findBy(['parent' => null]);
    //       // $lastRubric = $entityManager->getRepository(Rubric::class)->findOneBy([], ['createdAt' => 'DESC']);

    //     } catch (Exception $e) {
    //         $this->addFlash('error', 'Impossible de charger les rubriques. Veuillez réessayer plus tard.');
    //         return $this->redirectToRoute('app_home');
    //     }
        
    // // public function showLastRubric(): Response
    // // {
    // //     $lastRubric = $this->getDoctrine()
    // //         ->getRepository(Rubric::class)
    // //         ->findOneBy([], ['createdAt' => 'DESC']);

        
            
    //     return $this->render('rubric/rubrics.html.twig', [
    //         'rubric' => $viewRubrics,
    //         'rubrics' => $viewRubrics,
    //     ]);
    // }
}
