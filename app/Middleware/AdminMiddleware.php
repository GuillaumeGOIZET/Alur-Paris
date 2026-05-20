<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

/**
 * Middleware : vérifie que l'utilisateur est administrateur.
 * Sinon, bloque l'accès (403 si connecté mais pas admin, connexion sinon).
 */
class AdminMiddleware
{
    public function handle(): bool
    {
        // Pas connecté du tout → page de connexion
        if (!Auth::estConnecte()) {
            Session::flash('erreur', 'Accès réservé. Veuillez vous connecter.');
            header('Location: ' . url('connexion'));
            exit;
        }

        // Connecté mais pas admin → accès interdit
        if (!Auth::estAdmin()) {
            http_response_code(403);
            Session::flash('erreur', 'Accès refusé : vous n\'avez pas les droits administrateur.');
            header('Location: ' . url(''));
            exit;
        }

        return true;
    }
}