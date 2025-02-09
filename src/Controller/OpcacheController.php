<?php

namespace App\Controller;

// use Amnuts\Opcache\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OpcacheController
{
    #[Route('/opcache-gui', name: 'opcache-gui')]
    public function index(): Response
    {
        // Chemin vers le fichier principal de Opcache GUI
        $opcacheGuiPath = __DIR__ . '/../../public/opcache-gui/index.php';

        if (!file_exists($opcacheGuiPath)) {
            throw new \Exception('Opcache GUI is not installed or path is incorrect.');
        }

        // Inclut le fichier PHP directement (sort du rendu Twig habituel)
        ob_start();
        include $opcacheGuiPath;
        $content = ob_get_clean();

        return new Response($content);
    }
}