<?php

namespace App\Controllers;

use App\Core\Controller;

class ProduitController extends Controller
{
    public function liste(): void
    {
        echo "<h1>Liste des parfums</h1>";
        echo "<p>✅ Route /parfums OK</p>";
        echo "<p><a href='" . url('') . "'>← Retour</a></p>";
    }

    public function fiche(string $slug): void
    {
        echo "<h1>Fiche produit</h1>";
        echo "<p>✅ Route /parfums/{slug} OK — paramètre reçu : <strong>" . e($slug) . "</strong></p>";
        echo "<p><a href='" . url('parfums') . "'>← Catalogue</a></p>";
    }
}