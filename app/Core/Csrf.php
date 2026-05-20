<?php

namespace App\Core;

/**
 * Protection CSRF (Cross-Site Request Forgery).
 * 
 * Génère un jeton unique stocké en session, à inclure dans chaque
 * formulaire. À la soumission, on vérifie que le jeton correspond.
 */
class Csrf
{
    private const CLE = '_csrf_token';

    /**
     * Génère (ou récupère) le jeton CSRF de la session.
     */
    public static function token(): string
    {
        if (!Session::existe(self::CLE)) {
            Session::set(self::CLE, bin2hex(random_bytes(32)));
        }
        return Session::get(self::CLE);
    }

    /**
     * Génère le champ HTML caché à insérer dans un formulaire.
     */
    public static function champ(): string
    {
        $token = self::token();
        return '<input type="hidden" name="_csrf" value="' . $token . '">';
    }

    /**
     * Vérifie qu'un jeton soumis correspond à celui de la session.
     */
    public static function verifier(?string $token): bool
    {
        if ($token === null || !Session::existe(self::CLE)) {
            return false;
        }
        // hash_equals compare de façon sécurisée (résistante aux attaques temporelles)
        return hash_equals(Session::get(self::CLE), $token);
    }
}