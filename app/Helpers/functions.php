<?php

/**
 * Fonctions utilitaires globales de l'application.
 * Chargées automatiquement via composer.json (autoload "files").
 */

if (!function_exists('url')) {
    /**
     * Génère une URL absolue à partir d'un chemin relatif.
     * Exemple : url('parfums/oud-royal') => http://localhost/alur-paris/public/parfums/oud-royal
     */
    function url(string $path = ''): string
    {
        return APP_URL . '/' . ltrim($path, '/');
    }
}

if (!function_exists('e')) {
    /**
     * Échappe une chaîne pour l'affichage HTML (protection XSS).
     * À utiliser sur TOUTE donnée affichée provenant de l'utilisateur ou de la BDD.
     */
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirige vers une URL relative et stoppe l'exécution.
     */
    function redirect(string $path = ''): void
    {
        header('Location: ' . url($path));
        exit;
    }
}