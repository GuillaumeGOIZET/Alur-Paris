<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Utilisateur;
use App\Core\Database;

class DashboardController extends Controller
{
    public function index(): void
    {
        $stats = Commande::statistiques();

        // Quelques compteurs supplémentaires
        $pdo = Database::getConnection();
        $nbProduits = (int)$pdo->query("SELECT COUNT(*) FROM produit WHERE supprime_le IS NULL")->fetchColumn();
        $nbClients  = (int)$pdo->query("SELECT COUNT(*) FROM utilisateur WHERE role = 'client'")->fetchColumn();
        $nbMessages = (int)$pdo->query("SELECT COUNT(*) FROM contact_message WHERE est_traite = 0")->fetchColumn();

        $this->render('admin/dashboard', [
            'titre'      => 'Tableau de bord',
            'stats'      => $stats,
            'nbProduits' => $nbProduits,
            'nbClients'  => $nbClients,
            'nbMessages' => $nbMessages,
        ], 'admin'); // ← le 3e paramètre = layout admin
    }
}