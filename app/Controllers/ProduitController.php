<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Produit;
use App\Models\Categorie;

class ProduitController extends Controller
{
    /**
     * Affiche le catalogue des parfums avec filtres et tri.
     */
    public function liste(): void
    {
        // Récupère les filtres depuis l'URL (query string)
        $filtres = [
            'categorie_slug' => $_GET['categorie'] ?? null,
            'genre'          => $_GET['genre'] ?? null,
            'tri'            => $_GET['tri'] ?? 'nouveautes',
        ];

        $produits   = Produit::trouverPourCatalogue($filtres);
        $categories = Categorie::trouverToutesAvecCompte();

        $this->render('produit/liste', [
            'titre'          => 'Tous les parfums',
            'produits'       => $produits,
            'categories'     => $categories,
            'filtreActuel'   => $filtres,
        ]);
    }

    /**
     * Affiche la fiche détaillée d'un parfum.
     */
    public function fiche(string $slug): void
    {
        $produit = Produit::trouverParSlug($slug);

        // Produit introuvable → 404
        if ($produit === null) {
            http_response_code(404);
            $this->render('errors/404', ['titre' => 'Parfum introuvable']);
            return;
        }

        $images     = Produit::trouverImages((int)$produit['id']);
        $similaires = Produit::trouverSimilaires(
            (int)$produit['id_categorie'],
            (int)$produit['id'],
            3
        );

        $this->render('produit/fiche', [
            'titre'      => $produit['nom'],
            'produit'    => $produit,
            'images'     => $images,
            'similaires' => $similaires,
        ]);
    }
}