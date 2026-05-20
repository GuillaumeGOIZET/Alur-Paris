<?php

namespace App\Core;

/**
 * Gère l'état d'authentification de l'utilisateur courant.
 */
class Auth
{
    private const CLE = 'utilisateur';

    /**
     * Connecte un utilisateur : stocke ses infos essentielles en session.
     * On ne stocke JAMAIS le mot de passe, même haché.
     */
    public static function connecter(array $utilisateur): void
    {
        Session::regenerer(); // anti session-fixation

        Session::set(self::CLE, [
            'id'     => (int)$utilisateur['id'],
            'email'  => $utilisateur['email'],
            'nom'    => $utilisateur['nom'],
            'prenom' => $utilisateur['prenom'],
            'role'   => $utilisateur['role'],
        ]);
    }

    /**
     * Déconnecte l'utilisateur courant.
     */
    public static function deconnecter(): void
    {
        Session::supprimer(self::CLE);
        Session::regenerer();
    }

    /**
     * L'utilisateur est-il connecté ?
     */
    public static function estConnecte(): bool
    {
        return Session::existe(self::CLE);
    }

    /**
     * Récupère l'utilisateur connecté (ou null).
     */
    public static function utilisateur(): ?array
    {
        return Session::get(self::CLE);
    }

    /**
     * Récupère l'id de l'utilisateur connecté (ou null).
     */
    public static function id(): ?int
    {
        return self::utilisateur()['id'] ?? null;
    }

    /**
     * L'utilisateur connecté est-il administrateur ?
     */
    public static function estAdmin(): bool
    {
        return self::estConnecte() && self::utilisateur()['role'] === 'admin';
    }
}