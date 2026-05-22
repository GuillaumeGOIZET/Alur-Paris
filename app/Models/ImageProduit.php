<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

/**
 * Modèle des images de produits.
 */
class ImageProduit extends Model
{
    protected static string $table = 'image_produit';

    /**
     * Ajoute une image à un produit.
     * Si c'est la première image du produit, elle devient principale.
     */
    public static function ajouter(int $idProduit, string $cheminFichier, ?string $alt = null): int
    {
        $pdo = Database::getConnection();

        // Combien d'images ce produit a-t-il déjà ?
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM image_produit WHERE id_produit = :id");
        $stmt->execute(['id' => $idProduit]);
        $nbImages = (int)$stmt->fetchColumn();

        // La première image devient automatiquement principale
        $estPrincipale = $nbImages === 0 ? 1 : 0;

        $sql = "INSERT INTO image_produit (id_produit, chemin_fichier, texte_alternatif, est_principale, ordre_affichage)
                VALUES (:id_produit, :chemin, :alt, :principale, :ordre)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id_produit' => $idProduit,
            'chemin'     => $cheminFichier,
            'alt'        => $alt,
            'principale' => $estPrincipale,
            'ordre'      => $nbImages,
        ]);

        return (int)$pdo->lastInsertId();
    }

    /**
     * Récupère toutes les images d'un produit, principale en premier.
     */
    public static function trouverParProduit(int $idProduit): array
    {
        $sql = "SELECT * FROM image_produit
                WHERE id_produit = :id
                ORDER BY est_principale DESC, ordre_affichage ASC";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['id' => $idProduit]);
        return $stmt->fetchAll();
    }

    /**
     * Supprime une image (et retourne son chemin pour effacer le fichier).
     */
    public static function supprimer(int $idImage): ?string
    {
        $pdo = Database::getConnection();

        // Récupère le chemin avant de supprimer (pour effacer le fichier physique)
        $stmt = $pdo->prepare("SELECT chemin_fichier FROM image_produit WHERE id = :id");
        $stmt->execute(['id' => $idImage]);
        $chemin = $stmt->fetchColumn();

        if ($chemin === false) {
            return null;
        }

        $stmt = $pdo->prepare("DELETE FROM image_produit WHERE id = :id");
        $stmt->execute(['id' => $idImage]);

        return $chemin;
    }
    /**
     * Définit une image comme principale (et retire le statut aux autres du même produit).
     */
    public static function definirPrincipale(int $idImage, int $idProduit): bool
    {
        $pdo = Database::getConnection();

        // 1. Retire "principale" à toutes les images du produit
        $stmt = $pdo->prepare("UPDATE image_produit SET est_principale = 0 WHERE id_produit = :id_produit");
        $stmt->execute(['id_produit' => $idProduit]);

        // 2. Met "principale" sur l'image choisie
        $stmt = $pdo->prepare("UPDATE image_produit SET est_principale = 1 WHERE id = :id AND id_produit = :id_produit");
        return $stmt->execute(['id' => $idImage, 'id_produit' => $idProduit]);
    }

    /**
     * Récupère l'id_produit d'une image (utile avant suppression/modification).
     */
    public static function trouverIdProduit(int $idImage): ?int
    {
        $stmt = Database::getConnection()->prepare("SELECT id_produit FROM image_produit WHERE id = :id");
        $stmt->execute(['id' => $idImage]);
        $resultat = $stmt->fetchColumn();
        return $resultat === false ? null : (int)$resultat;
    }
}