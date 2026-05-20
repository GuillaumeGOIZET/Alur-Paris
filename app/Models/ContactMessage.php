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
}