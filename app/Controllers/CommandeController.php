<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Services\PanierService;

class CommandeController extends Controller
{
    /**
     * Étape 0 : entrée du tunnel.
     * Si panier vide → retour panier.
     * Si connecté → directement au formulaire de livraison.
     * Si invité → propose connexion ou commande invité.
     */
    public function demarrer(): void
    {
        $panier = PanierService::detail();

        // Panier vide : on ne peut pas commander
        if (empty($panier['lignes'])) {
            Session::flash('erreur', 'Votre panier est vide.');
            redirect('panier');
        }

        // Si déjà connecté, on saute l'étape du choix
        if (Auth::estConnecte()) {
            redirect('commande/livraison');
        }

        // Sinon, on propose connexion ou mode invité
        $this->render('commande/demarrer', [
            'titre'  => 'Commander',
            'panier' => $panier,
        ]);
    }

    /**
     * Étape 1 : formulaire de livraison.
     */
    public function livraison(): void
    {
        $panier = PanierService::detail();

        if (empty($panier['lignes'])) {
            Session::flash('erreur', 'Votre panier est vide.');
            redirect('panier');
        }

        // Récupère les anciennes valeurs (si retour en arrière) et erreurs
        $donnees = Session::get('_commande_livraison', []);
        $erreurs = Session::get('_erreurs', []);
        Session::supprimer('_erreurs');

        // Pré-remplit avec les infos du compte si connecté
        $utilisateur = Auth::utilisateur();

        $this->render('commande/livraison', [
            'titre'       => 'Livraison',
            'panier'      => $panier,
            'donnees'     => $donnees,
            'erreurs'     => $erreurs,
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * Traite le formulaire de livraison, puis redirige vers le récap.
     */
    public function traiterLivraison(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée, veuillez réessayer.');
            redirect('commande/livraison');
        }

        // Validation
        $validator = new Validator($_POST);
        $validator
            ->requis('email', 'L\'email est obligatoire.')
            ->email('email', 'L\'email n\'est pas valide.')
            ->requis('prenom', 'Le prénom est obligatoire.')
            ->requis('nom', 'Le nom est obligatoire.')
            ->requis('adresse', 'L\'adresse est obligatoire.')
            ->requis('code_postal', 'Le code postal est obligatoire.')
            ->requis('ville', 'La ville est obligatoire.');

        if (!$validator->valide()) {
            Session::set('_erreurs', $validator->erreurs());
            Session::set('_commande_livraison', $_POST);
            redirect('commande/livraison');
        }

        // On stocke les infos de livraison en session pour l'étape suivante
        Session::set('_commande_livraison', $_POST);

        redirect('commande/recapitulatif');
    }

    /**
     * Étape 2 : récapitulatif avant paiement.
     */
    public function recapitulatif(): void
    {
        $panier   = PanierService::detail();
        $livraison = Session::get('_commande_livraison', []);

        if (empty($panier['lignes'])) {
            Session::flash('erreur', 'Votre panier est vide.');
            redirect('panier');
        }

        // Si pas d'infos de livraison, on renvoie au formulaire
        if (empty($livraison['email'])) {
            redirect('commande/livraison');
        }

        $this->render('commande/recapitulatif', [
            'titre'     => 'Récapitulatif',
            'panier'    => $panier,
            'livraison' => $livraison,
        ]);
    }
}