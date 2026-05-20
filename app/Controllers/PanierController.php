<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\PanierService;

class PanierController extends Controller
{
    /**
     * Affiche la page du panier.
     */
    public function index(): void
    {
        $panier = PanierService::detail();

        $this->render('panier/index', [
            'titre'  => 'Mon panier',
            'panier' => $panier,
        ]);
    }

    /**
     * [AJAX] Ajoute un produit au panier. Retourne du JSON.
     */
    public function ajouter(): void
    {
        $idProduit = (int)($_POST['id_produit'] ?? 0);
        $quantite  = (int)($_POST['quantite'] ?? 1);

        $resultat = PanierService::ajouter($idProduit, $quantite);
        $resultat['nb_articles'] = PanierService::nombreArticles();

        $this->json($resultat);
    }

    /**
     * [AJAX] Modifie la quantité d'un produit. Retourne du JSON.
     */
    public function modifier(): void
    {
        $idProduit = (int)($_POST['id_produit'] ?? 0);
        $quantite  = (int)($_POST['quantite'] ?? 1);

        $resultat = PanierService::modifier($idProduit, $quantite);

        // On renvoie aussi les totaux à jour pour rafraîchir l'affichage
        $resultat['panier'] = PanierService::detail();

        $this->json($resultat);
    }

    /**
     * [AJAX] Retire un produit du panier. Retourne du JSON.
     */
    public function retirer(): void
    {
        $idProduit = (int)($_POST['id_produit'] ?? 0);

        $resultat = PanierService::retirer($idProduit);
        $resultat['panier'] = PanierService::detail();

        $this->json($resultat);
    }
}