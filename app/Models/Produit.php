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

        /**
     * Récupère les produits publiés pour le catalogue, avec filtres optionnels.
     *
     * @param array $filtres Filtres possibles : 'categorie_slug', 'genre', 'tri'
     * @return array<int, array<string, mixed>>
     */ 
    }

    public static function trouverPourCatalogue(array $filtres = []): array
        {
            $sql = "SELECT p.*, 
                        c.nom AS categorie_nom, 
                        c.slug AS categorie_slug,
                        img.chemin_fichier AS image_principale
                    FROM produit p
                    INNER JOIN categorie c ON p.id_categorie = c.id
                    LEFT JOIN image_produit img 
                        ON img.id_produit = p.id AND img.est_principale = 1
                    WHERE p.est_publie = 1
                    AND p.supprime_le IS NULL";

            $params = [];

            // Filtre par famille olfactive
            if (!empty($filtres['categorie_slug'])) {
                $sql .= " AND c.slug = :categorie_slug";
                $params['categorie_slug'] = $filtres['categorie_slug'];
            }

            // Filtre par genre
            if (!empty($filtres['genre'])) {
                $sql .= " AND p.genre = :genre";
                $params['genre'] = $filtres['genre'];
            }

            // Tri
            $sql .= match ($filtres['tri'] ?? 'nouveautes') {
                'prix_asc'  => " ORDER BY p.prix_ttc ASC",
                'prix_desc' => " ORDER BY p.prix_ttc DESC",
                'nom'       => " ORDER BY p.nom ASC",
                default     => " ORDER BY p.cree_le DESC",
            };

            $stmt = Database::getConnection()->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
        }

        /**
     * Récupère un produit publié par son slug, avec sa catégorie.
     * Retourne null si non trouvé ou non publié.
     */
    public static function trouverParSlug(string $slug): ?array
    {
        $sql = "SELECT p.*, 
                       c.nom AS categorie_nom, 
                       c.slug AS categorie_slug
                FROM produit p
                INNER JOIN categorie c ON p.id_categorie = c.id
                WHERE p.slug = :slug
                  AND p.est_publie = 1
                  AND p.supprime_le IS NULL
                LIMIT 1";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['slug' => $slug]);

        $produit = $stmt->fetch();
        return $produit === false ? null : $produit;
    }

    /**
     * Récupère toutes les images d'un produit, principale en premier.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function trouverImages(int $idProduit): array
    {
        $sql = "SELECT * FROM image_produit
                WHERE id_produit = :id
                ORDER BY est_principale DESC, ordre_affichage ASC";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['id' => $idProduit]);

        return $stmt->fetchAll();
    }

    /**
     * Récupère des produits de la même catégorie (suggestions "vous aimerez aussi"),
     * en excluant le produit courant.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function trouverSimilaires(int $idCategorie, int $idExclu, int $limite = 3): array
    {
        $sql = "SELECT p.*, 
                       c.nom AS categorie_nom,
                       img.chemin_fichier AS image_principale
                FROM produit p
                INNER JOIN categorie c ON p.id_categorie = c.id
                LEFT JOIN image_produit img 
                    ON img.id_produit = p.id AND img.est_principale = 1
                WHERE p.id_categorie = :id_categorie
                  AND p.id != :id_exclu
                  AND p.est_publie = 1
                  AND p.supprime_le IS NULL
                ORDER BY RAND()
                LIMIT :limite";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id_categorie', $idCategorie, \PDO::PARAM_INT);
        $stmt->bindValue(':id_exclu', $idExclu, \PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}