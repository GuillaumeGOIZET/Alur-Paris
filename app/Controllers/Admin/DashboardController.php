<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;

class DashboardController extends Controller
{
    public function index(): void
    {
        // L'accès admin est garanti par le middleware 'admin'
        echo "<h1>Back-office Alur Paris</h1>";
        echo "<p>✅ Accès admin autorisé. Bonjour " . e(Auth::utilisateur()['prenom']) . " !</p>";
        echo "<p>Le vrai dashboard sera construit demain.</p>";
        echo "<p><a href='" . url('') . "'>← Retour au site</a> · <a href='" . url('deconnexion') . "'>Déconnexion</a></p>";
    }
}