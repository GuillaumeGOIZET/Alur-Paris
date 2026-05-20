<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\FavorisService;

class FavorisController extends Controller
{
    public function index(): void
    {
        $produits = FavorisService::detail();

        $this->render('favoris/index', [
            'titre'    => 'Mes favoris',
            'produits' => $produits,
        ]);
    }

    public function basculer(): void
    {
        $idProduit = (int)($_POST['id_produit'] ?? 0);

        $resultat = FavorisService::basculer($idProduit);
        $resultat['nombre'] = FavorisService::nombre();

        $this->json($resultat);
    }
}