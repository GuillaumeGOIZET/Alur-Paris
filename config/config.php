<?php

/**
 * Configuration globale de l'application Alur Paris.
 * 
 * Ce fichier charge le .env, expose les variables d'environnement
 * via $_ENV et définit quelques constantes globales utiles.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Chargement du fichier .env situé à la racine du projet
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Variables qui doivent obligatoirement être présentes dans .env
$dotenv->required([
    'APP_ENV',
    'APP_URL',
    'DB_HOST',
    'DB_NAME',
    'DB_USER',
]);

// Constantes globales pratiques (raccourcis)
define('APP_ENV', $_ENV['APP_ENV']);
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('APP_URL', rtrim($_ENV['APP_URL'], '/'));

// En développement, on affiche toutes les erreurs PHP
// En production, on les masque (sécurité : ne pas révéler la structure interne)
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Fuseau horaire par défaut (Paris)
date_default_timezone_set('Europe/Paris');