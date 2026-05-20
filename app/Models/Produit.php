<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

/**
 * Modèle représentant un parfum du catalogue.
 * Hérite des méthodes CRUD de base de Model (findAll, findById, etc.).
 */
class Produit extends Model
{
    /** Nom de la table SQL associée */
    protected static string $table = 'produit';

    /**
     * Récupère les produits mis en avant pour la page d'accueil.
     * Joint la catégorie et l'image principale.
     *
     * @param int $limite Nombre maximum de produits à retourner
     * @return array<int, array<string, mixed>>
     */
    public static function trouverMisEnAvant(int $limite = 4): array
    {
        $sql = "SELECT p.*, 
                       c.nom AS categorie_nom, 
                       c.slug AS categorie_slug,
                       img.chemin_fichier AS image_principale
                FROM produit p
                INNER JOIN categorie c ON p.id_categorie = c.id
                LEFT JOIN image_produit img 
                    ON img.id_produit = p.id AND img.est_principale = 1
                WHERE p.est_mis_en_avant = 1
                  AND p.est_publie = 1
                  AND p.supprime_le IS NULL
                ORDER BY p.cree_le DESC
                LIMIT :limite";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}