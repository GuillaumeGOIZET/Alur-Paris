<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Services\PanierService;
use App\Models\Commande;
use App\Core\Mailer;

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
    /**
     * Crée une session de paiement Stripe et redirige le client vers Stripe.
     */
    public function payer(): void
    {
        $panier    = PanierService::detail();
        $livraison = Session::get('_commande_livraison', []);

        // Vérifications de sécurité
        if (empty($panier['lignes'])) {
            Session::flash('erreur', 'Votre panier est vide.');
            redirect('panier');
        }
        if (empty($livraison['email'])) {
            redirect('commande/livraison');
        }

        // Configure la clé secrète Stripe
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Construit la liste des articles pour Stripe
        $lignesStripe = [];
        foreach ($panier['lignes'] as $ligne) {
            $lignesStripe[] = [
                'price_data' => [
                    'currency'     => 'eur',
                    'product_data' => [
                        'name' => $ligne['nom'] . ' — ' . $ligne['contenance_ml'] . 'ml',
                    ],
                    // Stripe attend le montant en CENTIMES (entier)
                    'unit_amount' => (int) round($ligne['prix_ttc'] * 100),
                ],
                'quantity' => $ligne['quantite'],
            ];
        }

        // Ajoute les frais de livraison si applicables
        if ($panier['total_ttc'] < 150) {
            $lignesStripe[] = [
                'price_data' => [
                    'currency'     => 'eur',
                    'product_data' => ['name' => 'Frais de livraison'],
                    'unit_amount'  => 690, // 6,90 €
                ],
                'quantity' => 1,
            ];
        }

        try {
            $session = \Stripe\Checkout\Session::create([
                'mode'                 => 'payment',
                'line_items'           => $lignesStripe,
                'customer_email'       => $livraison['email'],
                'success_url'          => url('commande/succes') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => url('commande/annule'),
            ]);

            // On mémorise l'id de session Stripe pour vérifier au retour
            Session::set('_stripe_session_id', $session->id);

            // Redirige vers la page de paiement Stripe
            header('Location: ' . $session->url);
            exit;

        } catch (\Throwable $e) {
            if (APP_DEBUG) {
                Session::flash('erreur', 'Erreur Stripe : ' . $e->getMessage());
            } else {
                Session::flash('erreur', 'Une erreur est survenue lors de la création du paiement.');
            }
            redirect('commande/recapitulatif');
        }
    }

    /**
     * Page de retour après paiement réussi.
     * Vérifie le paiement, crée la commande, décrémente le stock, vide le panier.
     */
    public function succes(): void
    {
        $sessionId = $_GET['session_id'] ?? null;

        if ($sessionId === null) {
            redirect('');
        }

        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        try {
            // Récupère la session Stripe pour vérifier le paiement
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            // Vérifie que le paiement est bien réglé
            if ($session->payment_status !== 'paid') {
                Session::flash('erreur', 'Le paiement n\'a pas été confirmé.');
                redirect('commande/recapitulatif');
            }

            $panier    = PanierService::detail();
            $livraison = Session::get('_commande_livraison', []);

            // Si le panier est déjà vide, c'est que la commande a déjà été créée
            // (cas du rafraîchissement de la page succès) → on évite un doublon
            if (empty($panier['lignes'])) {
                $numero = Session::get('_derniere_commande');
                if ($numero) {
                    $this->afficherConfirmation($numero);
                    return;
                }
                redirect('');
            }

            // Prépare les données de la commande
            $donnees = $livraison;
            $donnees['id_utilisateur'] = Auth::id(); // null si invité
            $donnees['stripe_payment_intent_id'] = $session->payment_intent;

            $totaux = [
                'sous_total_ht' => $panier['sous_total_ht'],
                'tva'           => $panier['tva'],
                'livraison'     => $panier['total_ttc'] >= 150 ? 0 : 6.90,
                'total_ttc'     => $panier['total_ttc'] >= 150 ? $panier['total_ttc'] : $panier['total_ttc'] + 6.90,
            ];

            // Crée la commande en BDD (transaction)
            $idCommande = Commande::creer($donnees, $panier['lignes'], $totaux);
            $commande   = Commande::findById($idCommande);
            $numero     = $commande['numero_commande'];

            // Vide le panier et mémorise le numéro
            PanierService::vider();
            Session::set('_derniere_commande', $numero);
            Session::supprimer('_commande_livraison');

            // Envoie l'email de confirmation
            $this->envoyerEmailConfirmation($commande, $livraison);

            // Affiche la page de remerciement
            $this->afficherConfirmation($numero);

        } catch (\Throwable $e) {
            if (APP_DEBUG) {
                echo "Erreur : " . $e->getMessage();
            } else {
                Session::flash('erreur', 'Une erreur est survenue lors de la finalisation.');
                redirect('');
            }
        }
    }

    /**
     * Page de retour si le client annule le paiement.
     */
    public function annule(): void
    {
        Session::flash('erreur', 'Paiement annulé. Votre panier est conservé.');
        redirect('commande/recapitulatif');
    }

    /**
     * Affiche la page de confirmation de commande.
     */
    private function afficherConfirmation(string $numero): void
    {
        $commande = Commande::trouverParNumero($numero);

        $this->render('commande/confirmation', [
            'titre'    => 'Commande confirmée',
            'commande' => $commande,
        ]);
    }

    /**
     * Envoie l'email de confirmation de commande.
     */
    private function envoyerEmailConfirmation(array $commande, array $livraison): void
    {
        $corps = "
            <div style='font-family: Arial, sans-serif; max-width: 600px;'>
                <h2 style='color: #681223;'>Merci pour votre commande !</h2>
                <p>Bonjour " . htmlspecialchars($livraison['prenom']) . ",</p>
                <p>Nous avons bien reçu votre commande <strong>" . htmlspecialchars($commande['numero_commande']) . "</strong>.</p>
                <p>Montant total : <strong>" . number_format((float)$commande['montant_total_ttc'], 2, ',', ' ') . " €</strong></p>
                <p>Vous recevrez un email lorsque votre commande sera expédiée.</p>
                <p style='color: #888; font-size: 12px;'>Alur Paris — Parfumerie de niche</p>
            </div>
        ";

        Mailer::envoyer($commande['email_contact'], 'Confirmation de commande ' . $commande['numero_commande'], $corps);
    }
}