<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Commande;
use App\Models\Utilisateur;

class CompteController extends Controller
{
    /**
     * Affiche l'espace client : infos + historique de commandes.
     */
    public function index(): void
    {
        $utilisateur = Auth::utilisateur();
        $commandes   = Commande::trouverParUtilisateur((int)$utilisateur['id']);

        $erreurs = Session::get('_erreurs', []);
        $ancien  = Session::get('_ancien', []);
        Session::supprimer('_erreurs');
        Session::supprimer('_ancien');

        $this->render('compte/index', [
            'titre'       => 'Mon compte',
            'utilisateur' => $utilisateur,
            'commandes'   => $commandes,
            'erreurs'     => $erreurs,
            'ancien'      => $ancien,
        ]);
    }

    /**
     * Traite la modification des informations du profil.
     */
    public function modifier(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée, veuillez réessayer.');
            redirect('compte');
        }

        $validator = new Validator($_POST);
        $validator
            ->requis('prenom', 'Le prénom est obligatoire.')
            ->requis('nom', 'Le nom est obligatoire.');

        if (!$validator->valide()) {
            Session::set('_erreurs', $validator->erreurs());
            Session::set('_ancien', $_POST);
            redirect('compte');
        }

        $utilisateur = Auth::utilisateur();
        Utilisateur::mettreAJourProfil((int)$utilisateur['id'], $_POST);

        // Met à jour les infos en session pour refléter le changement immédiatement
        Session::set('utilisateur', array_merge($utilisateur, [
            'nom'       => $_POST['nom'],
            'prenom'    => $_POST['prenom'],
            'telephone' => $_POST['telephone'] ?? null,
        ]));

        Session::flash('succes', 'Vos informations ont été mises à jour.');
        redirect('compte');
    }
}