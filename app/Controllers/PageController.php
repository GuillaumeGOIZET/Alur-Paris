<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Models\ContactMessage;
class PageController extends Controller
{
    public function maison(): void
    {
        $this->render('about/maison', ['titre' => 'La Maison']);
    }

    public function mentions(): void
    {
        $this->render('legal/mentions', ['titre' => 'Mentions légales']);
    }

    public function cgv(): void
    {
        $this->render('legal/cgv', ['titre' => 'Conditions générales de vente']);
    }

    public function confidentialite(): void
    {
        $this->render('legal/confidentialite', ['titre' => 'Politique de confidentialité']);
    }
    public function contact(): void
    {
        $this->render('contact/index', ['titre' => 'Contact']);
    }

    /**
     * Traite l'envoi du formulaire de contact.
     */
    public function envoyerMessage(): void
    {
        // Vérification CSRF
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée, veuillez réessayer.');
            redirect('contact');
        }

        // Validation
        $validator = new Validator($_POST);
        $validator
            ->requis('nom', 'Le nom est obligatoire.')
            ->requis('email', 'L\'email est obligatoire.')
            ->email('email', 'L\'email n\'est pas valide.')
            ->requis('message', 'Le message est obligatoire.')
            ->min('message', 10, 'Votre message doit faire au moins 10 caractères.');

        if (!$validator->valide()) {
            Session::set('_erreurs', $validator->erreurs());
            Session::set('_ancien', $_POST);
            redirect('contact');
        }

        // Enregistrement en BDD
        ContactMessage::creer($_POST);

        // Envoi d'un email de notification à l'équipe Alur Paris
        $corpsHtml = self::construireEmailContact($_POST);
        \App\Core\Mailer::envoyer(
            $_ENV['SMTP_FROM_EMAIL'],   // on s'envoie le message à soi-même (la boîte contact)
            'Nouveau message de contact : ' . ($_POST['sujet'] ?? 'Sans sujet'),
            $corpsHtml
        );

        // Même si l'email échoue, le message est en BDD, donc on confirme au visiteur
        Session::flash('succes', 'Votre message a bien été envoyé. Nous vous répondrons rapidement.');
        redirect('contact');
    }

    /**
     * Construit le corps HTML de l'email de notification de contact.
     */
    private static function construireEmailContact(array $donnees): string
    {
        $nom     = htmlspecialchars($donnees['nom'] ?? '', ENT_QUOTES, 'UTF-8');
        $email   = htmlspecialchars($donnees['email'] ?? '', ENT_QUOTES, 'UTF-8');
        $sujet   = htmlspecialchars($donnees['sujet'] ?? 'Sans sujet', ENT_QUOTES, 'UTF-8');
        $message = nl2br(htmlspecialchars($donnees['message'] ?? '', ENT_QUOTES, 'UTF-8'));

        return "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #681223;'>Nouveau message de contact</h2>
                <p><strong>De :</strong> {$nom} ({$email})</p>
                <p><strong>Sujet :</strong> {$sujet}</p>
                <hr style='border: none; border-top: 1px solid #eee;'>
                <p><strong>Message :</strong></p>
                <p>{$message}</p>
            </div>
        ";
    }
}