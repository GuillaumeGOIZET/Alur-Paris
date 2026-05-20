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
];