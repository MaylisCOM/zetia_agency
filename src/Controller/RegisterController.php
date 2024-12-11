<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
#[Route('/register', name: 'app_register')]
public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{
$user = new User();
$form = $this->createForm(RegisterUserType::class, $user);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$user->setPassword(
$passwordEncoder->encodePassword(
$user,
$form->get('password')->getData()
)
);

$entityManager->persist($user);
$entityManager->flush();

return $this->redirectToRoute('app_login');
}

return $this->render('register/index.html.twig', [
'form' => $form->createView(),
]);
}
}
