<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Utilisateur;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function inscriptionForm(): void
    {
        // Si déjà connecté, rediriger
        if (Auth::estConnecte()) {
            redirect('compte');
        }

        $this->render('auth/inscription', [
            'titre' => 'Créer un compte',
        ]);
    }

    /**
     * Traite la soumission du formulaire d'inscription.
     */
    public function inscription(): void
    {
        // Vérification CSRF
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée, veuillez réessayer.');
            redirect('inscription');
        }

        // Validation
        $validator = new Validator($_POST);
        $validator
            ->requis('prenom', 'Le prénom est obligatoire.')
            ->requis('nom', 'Le nom est obligatoire.')
            ->requis('email', 'L\'email est obligatoire.')
            ->email('email', 'L\'email n\'est pas valide.')
            ->requis('mot_de_passe', 'Le mot de passe est obligatoire.')
            ->min('mot_de_passe', 8, 'Le mot de passe doit faire au moins 8 caractères.')
            ->identique('mot_de_passe', 'mot_de_passe_confirmation', 'Les mots de passe ne correspondent pas.')
            ->accepte('a_accepte_cgv', 'Vous devez accepter les CGV.');

        // Email déjà utilisé ?
        if (Utilisateur::emailExiste($_POST['email'] ?? '')) {
            Session::flash('erreur', 'Cet email est déjà utilisé.');
            $this->reafficherInscription();
            return;
        }

        if (!$validator->valide()) {
            // On stocke les erreurs et les anciennes valeurs pour réafficher
            Session::set('_erreurs', $validator->erreurs());
            Session::set('_ancien', ['prenom' => $_POST['prenom'] ?? '', 'nom' => $_POST['nom'] ?? '', 'email' => $_POST['email'] ?? '']);
            redirect('inscription');
        }

        // Création du compte
        $idUtilisateur = Utilisateur::creer($_POST);

        // Connexion automatique après inscription
        $utilisateur = Utilisateur::findById($idUtilisateur);
        Auth::connecter($utilisateur);

        Session::flash('succes', 'Bienvenue ' . $utilisateur['prenom'] . ' ! Votre compte a été créé.');
        redirect('compte');
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function connexionForm(): void
    {
        if (Auth::estConnecte()) {
            redirect('compte');
        }

        $this->render('auth/connexion', [
            'titre' => 'Connexion',
        ]);
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function connexion(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée, veuillez réessayer.');
            redirect('connexion');
        }

        $email      = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $utilisateur = Utilisateur::verifierIdentifiants($email, $motDePasse);

        if ($utilisateur === null) {
            Session::flash('erreur', 'Email ou mot de passe incorrect.');
            Session::set('_ancien', ['email' => $email]);
            redirect('connexion');
        }

        Auth::connecter($utilisateur);
        Session::flash('succes', 'Connexion réussie. Bonjour ' . $utilisateur['prenom'] . ' !');

        // Redirige les admins vers le back-office, les clients vers leur compte
        if ($utilisateur['role'] === 'admin') {
            redirect('admin');
        }
        redirect('compte');
    }

    /**
     * Déconnexion.
     */
    public function deconnexion(): void
    {
        Auth::deconnecter();
        Session::flash('succes', 'Vous êtes déconnecté.');
        redirect('');
    }

    /**
     * Helper : réaffiche le formulaire d'inscription (cas email existant).
     */
    private function reafficherInscription(): void
    {
        Session::set('_ancien', ['prenom' => $_POST['prenom'] ?? '', 'nom' => $_POST['nom'] ?? '', 'email' => $_POST['email'] ?? '']);
        redirect('inscription');
    }
}