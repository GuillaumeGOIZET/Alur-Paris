<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Produit;

class HomeController extends Controller
{
    public function index(): void
    {
        $produitsVedette = Produit::trouverMisEnAvant(4);

        $this->render('home/index', [
            'titre' => 'Accueil',
            'produitsVedette' => $produitsVedette,
        ]);
    }
}