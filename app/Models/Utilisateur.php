<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

/**
 * Modèle représentant un utilisateur (client ou admin).
 */
class Utilisateur extends Model
{
    protected static string $table = 'utilisateur';

    /**
     * Récupère un utilisateur par son email.
     */
    public static function trouverParEmail(string $email): ?array
    {
        return self::findBy('email', $email);
    }

    /**
     * Vérifie si un email est déjà utilisé.
     */
    public static function emailExiste(string $email): bool
    {
        return self::trouverParEmail($email) !== null;
    }

    /**
     * Crée un nouveau compte client.
     * Hache le mot de passe avant stockage.
     *
     * @return int L'id du nouvel utilisateur créé
     */
    public static function creer(array $donnees): int
    {
        $sql = "INSERT INTO utilisateur 
                    (email, mot_de_passe_hash, nom, prenom, telephone, role, a_accepte_cgv, cree_le)
                VALUES 
                    (:email, :mot_de_passe_hash, :nom, :prenom, :telephone, 'client', :a_accepte_cgv, NOW())";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            'email'            => $donnees['email'],
            'mot_de_passe_hash'=> password_hash($donnees['mot_de_passe'], PASSWORD_BCRYPT),
            'nom'              => $donnees['nom'],
            'prenom'           => $donnees['prenom'],
            'telephone'        => $donnees['telephone'] ?? null,
            'a_accepte_cgv'    => !empty($donnees['a_accepte_cgv']) ? 1 : 0,
        ]);

        return (int)Database::getConnection()->lastInsertId();
    }

    /**
     * Vérifie les identifiants de connexion.
     * Retourne l'utilisateur si OK, null sinon.
     */
    public static function verifierIdentifiants(string $email, string $motDePasse): ?array
    {
        $utilisateur = self::trouverParEmail($email);

        if ($utilisateur === null) {
            return null;
        }

        // Compare le mot de passe fourni avec le hash stocké
        if (!password_verify($motDePasse, $utilisateur['mot_de_passe_hash'])) {
            return null;
        }

        return $utilisateur;
    }
}