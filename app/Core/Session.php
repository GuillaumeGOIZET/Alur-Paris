<?php

namespace App\Core;

/**
 * Encapsule la gestion de la session PHP.
 * Centralise lecture/écriture/suppression des données de session.
 */
class Session
{
    /**
     * Démarre la session si ce n'est pas déjà fait.
     */
    public static function demarrer(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Enregistre une valeur en session.
     */
    public static function set(string $cle, mixed $valeur): void
    {
        $_SESSION[$cle] = $valeur;
    }

    /**
     * Récupère une valeur de session (ou une valeur par défaut).
     */
    public static function get(string $cle, mixed $defaut = null): mixed
    {
        return $_SESSION[$cle] ?? $defaut;
    }

    /**
     * Vérifie si une clé existe en session.
     */
    public static function existe(string $cle): bool
    {
        return isset($_SESSION[$cle]);
    }

    /**
     * Supprime une valeur de session.
     */
    public static function supprimer(string $cle): void
    {
        unset($_SESSION[$cle]);
    }

    /**
     * Détruit toute la session (déconnexion complète).
     */
    public static function detruire(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * Régénère l'ID de session (anti session-fixation).
     * À appeler après une connexion réussie.
     */
    public static function regenerer(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /**
     * Messages flash : message affiché une seule fois (puis supprimé).
     * Utile pour "Connexion réussie", "Erreur", etc.
     */
    public static function flash(string $cle, ?string $message = null): ?string
    {
        if ($message !== null) {
            // Écriture du flash
            $_SESSION['_flash'][$cle] = $message;
            return null;
        }

        // Lecture + suppression du flash
        $valeur = $_SESSION['_flash'][$cle] ?? null;
        unset($_SESSION['_flash'][$cle]);
        return $valeur;
    }
}