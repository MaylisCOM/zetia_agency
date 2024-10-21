<?php

namespace App\Controller;

use App\Entity\Illustration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends AbstractController
{
    #[Route('/boutique', name: 'boutique')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $illustrations = $entityManager->getRepository(Illustration::class)->findAll();

        return $this->render('boutique/index.html.twig', [
            'illustrations' => $illustrations,
        ]);
    }
}
