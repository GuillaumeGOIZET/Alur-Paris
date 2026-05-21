<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Commande;

class CommandeController extends Controller
{
    /**
     * Liste toutes les commandes.
     */
    public function index(): void
    {
        $commandes = Commande::trouverToutesAdmin();

        $this->render('admin/commandes/liste', [
            'titre'     => 'Gestion des commandes',
            'commandes' => $commandes,
        ], 'admin');
    }

    /**
     * Affiche le détail d'une commande.
     */
    public function detail(string $id): void
    {
        $commande = Commande::trouverDetailAdmin((int)$id);

        if ($commande === null) {
            Session::flash('erreur', 'Commande introuvable.');
            redirect('admin/commandes');
        }

        $this->render('admin/commandes/detail', [
            'titre'    => 'Commande ' . $commande['numero_commande'],
            'commande' => $commande,
            'statuts'  => Commande::tousLesStatuts(),
        ], 'admin');
    }

    /**
     * Change le statut d'une commande.
     */
    public function changerStatut(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée.');
            redirect('admin/commandes');
        }

        $id       = (int)($_POST['id'] ?? 0);
        $idStatut = (int)($_POST['id_statut'] ?? 0);

        Commande::changerStatut($id, $idStatut);
        Session::flash('succes', 'Statut de la commande mis à jour.');

        redirect('admin/commandes/' . $id);
    }
}