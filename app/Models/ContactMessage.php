<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

/**
 * Modèle représentant un message envoyé via le formulaire de contact.
 */
class ContactMessage extends Model
{
    protected static string $table = 'contact_message';

    /**
     * Enregistre un nouveau message de contact.
     */
    public static function creer(array $donnees): int
    {
        $sql = "INSERT INTO contact_message (nom, email, sujet, message, est_traite, cree_le)
                VALUES (:nom, :email, :sujet, :message, 0, NOW())";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            'nom'     => $donnees['nom'],
            'email'   => $donnees['email'],
            'sujet'   => $donnees['sujet'] ?? null,
            'message' => $donnees['message'],
        ]);

        return (int)Database::getConnection()->lastInsertId();
    }
    /**
     * Liste tous les messages, non traités en premier, puis par date.
     */
    public static function trouverTousAdmin(): array
    {
        $sql = "SELECT * FROM contact_message
                ORDER BY est_traite ASC, cree_le DESC";
        return Database::getConnection()->query($sql)->fetchAll();
    }

    /**
     * Marque un message comme traité (ou non traité).
     */
    public static function marquerTraite(int $id, bool $traite = true): bool
    {
        $sql = "UPDATE contact_message SET est_traite = :traite WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute(['traite' => $traite ? 1 : 0, 'id' => $id]);
    }
}