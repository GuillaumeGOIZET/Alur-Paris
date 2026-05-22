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
    /**
     * Liste tous les clients (role = client) avec leur nombre de commandes.
     */
    public static function trouverClientsAdmin(): array
    {
        $sql = "SELECT u.id, u.email, u.nom, u.prenom, u.telephone, u.cree_le,
                       COUNT(c.id) AS nb_commandes
                FROM utilisateur u
                LEFT JOIN commande c ON c.id_utilisateur = u.id
                WHERE u.role = 'client'
                GROUP BY u.id
                ORDER BY u.cree_le DESC";

        return Database::getConnection()->query($sql)->fetchAll();
    }
    /**
     * Met à jour les informations de profil d'un utilisateur.
     * (Ne touche ni à l'email, ni au mot de passe, ni au rôle.)
     */
    public static function mettreAJourProfil(int $id, array $d): bool
    {
        $sql = "UPDATE utilisateur SET
                    nom = :nom, prenom = :prenom, telephone = :telephone
                WHERE id = :id";

        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute([
            'nom'       => $d['nom'],
            'prenom'    => $d['prenom'],
            'telephone' => $d['telephone'] ?? null,
            'id'        => $id,
        ]);
    }
}