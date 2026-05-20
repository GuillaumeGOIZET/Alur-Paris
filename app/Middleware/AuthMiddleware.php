<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

/**
 * Middleware : vérifie que l'utilisateur est connecté.
 * Sinon, redirige vers la page de connexion.
 */
class AuthMiddleware
{
    public function handle(): bool
    {
        if (!Auth::estConnecte()) {
            Session::flash('erreur', 'Veuillez vous connecter pour accéder à cette page.');
            // Mémorise la page demandée pour y revenir après connexion
            Session::set('_redirection_apres_connexion', $_SERVER['REQUEST_URI'] ?? '');
            header('Location: ' . url('connexion'));
            exit;
        }
        return true;
    }
}