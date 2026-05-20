<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

/**
 * Modèle représentant une famille olfactive (catégorie de parfums).
 */
class Categorie extends Model
{
    protected static string $table = 'categorie';

    /**
     * Récupère toutes les catégories, triées par ordre d'affichage,
     * avec le nombre de produits publiés dans chacune.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function trouverToutesAvecCompte(): array
    {
        $sql = "SELECT c.*, 
                       COUNT(p.id) AS nb_produits
                FROM categorie c
                LEFT JOIN produit p 
                    ON p.id_categorie = c.id 
                    AND p.est_publie = 1 
                    AND p.supprime_le IS NULL
                GROUP BY c.id
                ORDER BY c.ordre_affichage ASC";

        $stmt = Database::getConnection()->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupère une catégorie par son slug.
     */
    public static function trouverParSlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }
}