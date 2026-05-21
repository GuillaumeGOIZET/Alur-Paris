<?php

/**
 * Définition de toutes les routes de l'application.
 * 
 * Format : 'METHOD URI' => 'Controller@method'
 * 
 * Les paramètres dynamiques sont entre {accolades} :
 *   /parfums/{slug}     → le contrôleur recevra $slug en argument
 *   /commande/{id}      → le contrôleur recevra $id en argument
 */

return [
    // ===== Pages publiques =====
    'GET /'                     => 'HomeController@index',
    
    // ===== Catalogue =====
    'GET /parfums'              => 'ProduitController@liste',
    'GET /parfums/{slug}'       => 'ProduitController@fiche',
    
    // ===== Pages éditoriales =====
    'GET /maison'               => 'PageController@maison',
    'GET /contact'              => 'PageController@contact',
    'POST /contact'             => 'PageController@envoyerMessage',
    
    // ===== Pages légales =====
    'GET /mentions-legales'         => 'PageController@mentions',
    'GET /cgv'                      => 'PageController@cgv',
    'GET /politique-confidentialite'=> 'PageController@confidentialite',

    // ===== Panier =====
    'GET /panier'              => 'PanierController@index',
    'POST /panier/ajouter'     => 'PanierController@ajouter',
    'POST /panier/modifier'    => 'PanierController@modifier',
    'POST /panier/retirer'     => 'PanierController@retirer',

    // ===== Authentification =====
    'GET /inscription'   => 'AuthController@inscriptionForm',
    'POST /inscription'  => 'AuthController@inscription',
    'GET /connexion'     => 'AuthController@connexionForm',
    'POST /connexion'    => 'AuthController@connexion',
    'GET /deconnexion'   => 'AuthController@deconnexion',

    /// ===== Espace client (protégé : connexion requise) =====
    'GET /compte'        => ['CompteController@index', 'auth'],

    // ===== Back-office admin (protégé : admin requis) =====
    'GET /admin'         => ['Admin\\DashboardController@index', 'admin'],

    // ===== Tunnel de commande =====
    'GET /commande'                  => 'CommandeController@demarrer',
    'GET /commande/livraison'        => 'CommandeController@livraison',
    'POST /commande/livraison'       => 'CommandeController@traiterLivraison',
    'GET /commande/recapitulatif'    => 'CommandeController@recapitulatif',

    // ===== Favoris =====
    'GET /favoris'           => 'FavorisController@index',
    'POST /favoris/basculer' => 'FavorisController@basculer',

    // ===== Paiement (Stripe) =====
    'GET /commande/payer'         => 'CommandeController@payer',
    'GET /commande/succes'        => 'CommandeController@succes',
    'GET /commande/annule'        => 'CommandeController@annule',

    // ===== Admin =====
    'GET /admin/produits' => ['Admin\\ProduitController@index', 'admin'],
    'GET /admin/produits/nouveau'        => ['Admin\\ProduitController@nouveau', 'admin'],
    'POST /admin/produits/enregistrer'   => ['Admin\\ProduitController@enregistrer', 'admin'],
    'POST /admin/produits/supprimer'     => ['Admin\\ProduitController@supprimer', 'admin'],
    'GET /admin/produits/{id}/editer'    => ['Admin\\ProduitController@editer', 'admin'],
];