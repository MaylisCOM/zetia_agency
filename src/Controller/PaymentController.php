<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/paiement/choix', name: 'choix_paiement')]
    public function choixPaiement(): Response
    {
        return $this->render('boutique/payment.html.twig');
    }

    #[Route('/paiement/invite', name: 'guest_checkout')]
    public function guestCheckout(): Response
    {
        // Logique pour gérer l'achat en tant qu'invité
        return $this->render('paiement/guest_checkout.html.twig');
    }

}