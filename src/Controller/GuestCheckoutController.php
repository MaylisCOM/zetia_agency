<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GuestCheckoutController extends AbstractController
{
#[Route('/guest_checkout', name: 'guest_checkout')]
public function guestCheckout(Request $request): \Symfony\Component\HttpFoundation\Response
{
// Récupère les informations invité ici et passe à la page de paiement
$email = $request->get('email');

return $this->render('checkout/guest_checkout.html.twig', [
'email' => $email
]);
}
}
