<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class CompteController extends Controller
{
    public function index(): void
    {
        // Protection : si non connecté, rediriger vers connexion
        if (!Auth::estConnecte()) {
            \App\Core\Session::flash('erreur', 'Veuillez vous connecter pour accéder à votre compte.');
            redirect('connexion');
        }

        $this->render('compte/index', [
            'titre'       => 'Mon compte',
            'utilisateur' => Auth::utilisateur(),
        ]);
    }
}