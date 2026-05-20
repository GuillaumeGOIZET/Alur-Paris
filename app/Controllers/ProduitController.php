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
        // (à implémenter à l'étape suivante)
        echo "Fiche produit : " . e($slug);
    }
}