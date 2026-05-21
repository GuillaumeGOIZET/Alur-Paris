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
            'prix_asc' => " ORDER BY p.prix_ttc ASC",
            'prix_desc' => " ORDER BY p.prix_ttc DESC",
            'nom' => " ORDER BY p.nom ASC",
            default => " ORDER BY p.cree_le DESC",
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

    /**
     * Récupère plusieurs produits par leurs ids, avec image principale.
     * Utilisé pour la page favoris.
     *
     * @param array<int> $ids
     * @return array<int, array<string, mixed>>
     */
    /**
     * Récupère plusieurs produits par leurs ids, avec image principale.
     */
    public static function trouverParIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT p.*, 
                       c.nom AS categorie_nom,
                       img.chemin_fichier AS image_principale
                FROM produit p
                INNER JOIN categorie c ON p.id_categorie = c.id
                LEFT JOIN image_produit img 
                    ON img.id_produit = p.id AND img.est_principale = 1
                WHERE p.id IN ({$placeholders})
                  AND p.est_publie = 1
                  AND p.supprime_le IS NULL";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(array_values($ids));

        return $stmt->fetchAll();
    }
    /**
     * Récupère tous les produits pour l'admin (publiés ou non, non supprimés),
     * avec leur catégorie. Triés par date de création décroissante.
     */
    public static function trouverTousAdmin(): array
    {
        $sql = "SELECT p.*, c.nom AS categorie_nom
                FROM produit p
                INNER JOIN categorie c ON p.id_categorie = c.id
                WHERE p.supprime_le IS NULL
                ORDER BY p.cree_le DESC";

        $stmt = Database::getConnection()->query($sql);
        return $stmt->fetchAll();
    }
    /**
     * Crée un nouveau produit. Retourne son id.
     */
    public static function creer(array $d): int
    {
        $sql = "INSERT INTO produit (
                    id_categorie, nom, slug, marque,
                    description_courte, description_longue,
                    notes_tete, notes_coeur, notes_fond,
                    genre, contenance_ml, prix_ttc, taux_tva,
                    stock, est_publie, est_mis_en_avant, cree_le, modifie_le
                ) VALUES (
                    :id_categorie, :nom, :slug, :marque,
                    :desc_courte, :desc_longue,
                    :notes_tete, :notes_coeur, :notes_fond,
                    :genre, :contenance, :prix, :tva,
                    :stock, :publie, :avant, NOW(), NOW()
                )";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(self::parametres($d));

        return (int)Database::getConnection()->lastInsertId();
    }

    /**
     * Met à jour un produit existant.
     */
    public static function mettreAJour(int $id, array $d): bool
    {
        $sql = "UPDATE produit SET
                    id_categorie = :id_categorie, nom = :nom, slug = :slug, marque = :marque,
                    description_courte = :desc_courte, description_longue = :desc_longue,
                    notes_tete = :notes_tete, notes_coeur = :notes_coeur, notes_fond = :notes_fond,
                    genre = :genre, contenance_ml = :contenance, prix_ttc = :prix, taux_tva = :tva,
                    stock = :stock, est_publie = :publie, est_mis_en_avant = :avant,
                    modifie_le = NOW()
                WHERE id = :id";

        $params = self::parametres($d);
        $params['id'] = $id;

        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Suppression "douce" : on marque supprime_le sans effacer la ligne.
     * Préserve l'intégrité des commandes passées (FK RESTRICT sur ligne_commande).
     */
    public static function supprimer(int $id): bool
    {
        $sql = "UPDATE produit SET supprime_le = NOW(), est_publie = 0 WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Prépare le tableau de paramètres communs à creer() et mettreAJour().
     */
    private static function parametres(array $d): array
    {
        return [
            'id_categorie' => (int)$d['id_categorie'],
            'nom'          => $d['nom'],
            'slug'         => $d['slug'],
            'marque'       => $d['marque'] ?? 'Alur Paris',
            'desc_courte'  => $d['description_courte'] ?? null,
            'desc_longue'  => $d['description_longue'],
            'notes_tete'   => $d['notes_tete'] ?? null,
            'notes_coeur'  => $d['notes_coeur'] ?? null,
            'notes_fond'   => $d['notes_fond'] ?? null,
            'genre'        => $d['genre'],
            'contenance'   => (int)$d['contenance_ml'],
            'prix'         => (float)$d['prix_ttc'],
            'tva'          => (float)($d['taux_tva'] ?? 20),
            'stock'        => (int)$d['stock'],
            'publie'       => !empty($d['est_publie']) ? 1 : 0,
            'avant'        => !empty($d['est_mis_en_avant']) ? 1 : 0,
        ];
    }
}