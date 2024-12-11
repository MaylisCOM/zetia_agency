<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Illustration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends AbstractController
{
    #[Route('/boutique', name: 'boutique')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les catégories
        $categories = $entityManager->getRepository(Categorie::class)->findAll();

        // Récupérer toutes les illustrations
        $illustrations = $entityManager->getRepository(Illustration::class)->findAll();

        return $this->render('boutique/index.html.twig', [
            'categories' => $categories,
            'illustrations' => $illustrations,
        ]);
    }

    #[Route('/boutique/categorie/{id}', name: 'boutique_categorie')]
    public function category(EntityManagerInterface $entityManager, $id): Response
    {
        // Récupérer la catégorie spécifique
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);

        // Si la catégorie n'existe pas, rediriger vers la page boutique
        if (!$categorie) {
            return $this->redirectToRoute('boutique');
        }

        // Récupérer les illustrations liées à cette catégorie
        $illustrations = $entityManager->getRepository(Illustration::class)->findBy(['categorie' => $categorie]);

        // Récupérer toutes les catégories pour le filtre
        $categories = $entityManager->getRepository(Categorie::class)->findAll();

        return $this->render('boutique/index.html.twig', [
            'categories' => $categories,
            'illustrations' => $illustrations,
            'selectedCategory' => $categorie, // Pour mettre en avant la catégorie sélectionnée
        ]);
    }

    #[Route('/boutique/produit/{id}', name: 'fiche_produit')]
    public function ficheProduit($id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le produit en question
        $illustration = $entityManager->getRepository(Illustration::class)->find($id);

        // Récupérer des produits similaires (ex. même catégorie)
        $relatedProducts = $entityManager->getRepository(Illustration::class)
            ->findBy(['categorie' => $illustration->getCategorie()], null, 4);

        return $this->render('boutique/fiche_produit.html.twig', [
            'illustration' => $illustration,
            'related_products' => $relatedProducts,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le produit à partir de son ID
        $illustration = $entityManager->getRepository(Illustration::class)->find($id);

        if (!$illustration) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        // Ajouter le produit au panier
        $cart = $request->getSession()->get('cart', []);
        $cart[$id] = 1; // Tu as mentionné qu'un seul exemplaire doit être ajouté à la fois
        $request->getSession()->set('cart', $cart);
        dd($cart);

        // Ajouter un message flash de succès
        $this->addFlash('success', 'Produit ajouté au panier !');

        // Rediriger vers l'URL passée dans le paramètre redirect_to
        $redirectTo = $request->query->get('redirect_to', $this->generateUrl('boutique'));

        return $this->redirect($redirectTo);
    }


    #[Route('/cart', name: 'cart')]
    public function cart(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cart = $request->getSession()->get('cart', []);

        $products = [];
        $totalPrice = 0;

        // Récupérer les produits du panier
        foreach ($cart as $id => $quantity) {
            $product = $entityManager->getRepository(Illustration::class)->find($id);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
                $totalPrice += $product->getPrice() * $quantity;
            }
        }

        return $this->render('boutique/cart.html.twig', [
            'products' => $products,
            'totalPrice' => $totalPrice,
        ]);
    }

    #[Route('/remove-from-cart/{id}', name: 'remove_from_cart', methods: ['POST'])]
    public function removeFromCart($id, Request $request): Response
    {
        // Récupérer le panier depuis la session
        $cart = $request->getSession()->get('cart', []);

        // Si l'article est dans le panier, le retirer
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        // Mettre à jour la session avec le panier modifié
        $request->getSession()->set('cart', $cart);

        // Ajouter un message flash de succès
        $this->addFlash('success', 'Produit retiré du panier.');

        // Rediriger vers la page panier
        return $this->redirectToRoute('cart');
    }

}
