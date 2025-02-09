<?php

namespace App\Controller;

use App\Entity\Rubric;
use App\Repository\RubricRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, RubricRepository $rubricRepository): Response
    {
        try {
            // Récupérer les rubriques principales (qui n'ont pas de parent)
            $rubrics = $rubricRepository->findBy(['parent' => null]);
    
        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de charger les rubriques. Veuillez réessayer plus tard.');
            $rubrics = [];
        }
    
        return $this->render('main/index.html.twig', [
            'rubrics' => $rubrics, // On passe bien les rubriques principales
        ]);
    }
}    