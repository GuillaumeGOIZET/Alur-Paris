<?php

/**
 * Point d'entrée unique de l'application (front controller).
 * 
 * Toutes les requêtes HTTP arrivent ici via le .htaccess.
 * Ce fichier charge la config, démarre la session et dispatche au routeur.
 */

// 1. Charge la config (qui charge à son tour vendor/autoload.php et le .env)
require_once __DIR__ . '/../config/config.php';

// 2. Démarre la session PHP (utile pour le panier, l'auth)
session_start();

// 3. Charge les routes
$routes = require __DIR__ . '/../config/routes.php';

// 4. Initialise le routeur et dispatche
use App\Core\Router;

try {
    $router = new Router($routes);
    $router->dispatch();
} catch (Throwable $e) {
    if (APP_DEBUG) {
        // En dev : on affiche l'erreur complète
        echo "<h1>Erreur</h1>";
        echo "<p><strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        // En prod : on log et on affiche une page propre
        http_response_code(500);
        echo "<h1>Erreur serveur</h1>";
        echo "<p>Une erreur est survenue. Merci de réessayer plus tard.</p>";
    }
}