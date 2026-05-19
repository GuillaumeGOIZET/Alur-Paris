<?php

namespace App\Core;

use PDO;

/**
 * Classe mère abstraite pour tous les modèles de l'application.
 * 
 * Fournit les opérations CRUD de base (findAll, findById, find, delete)
 * que chaque modèle héritera automatiquement.
 * 
 * Chaque classe enfant DOIT définir la propriété statique $table :
 * 
 *     class Produit extends Model {
 *         protected static string $table = 'produit';
 *     }
 */
abstract class Model
{
    /** @var string Nom de la table SQL (à définir dans chaque classe enfant) */
    protected static string $table;
    
    /**
     * Récupère toutes les lignes de la table.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function findAll(): array
    {
        $sql  = "SELECT * FROM " . static::$table;
        $stmt = Database::getConnection()->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère une ligne par son id.
     *
     * @return array<string, mixed>|null Null si pas trouvé
     */
    public static function findById(int $id): ?array
    {
        $sql  = "SELECT * FROM " . static::$table . " WHERE id = :id LIMIT 1";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }
    
    /**
     * Récupère une ligne selon un critère (colonne = valeur).
     *
     * @return array<string, mixed>|null
     */
    public static function findBy(string $column, mixed $value): ?array
    {
        $sql  = "SELECT * FROM " . static::$table . " WHERE {$column} = :value LIMIT 1";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['value' => $value]);
        
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }
    
    /**
     * Supprime une ligne par son id.
     */
    public static function delete(int $id): bool
    {
        $sql  = "DELETE FROM " . static::$table . " WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}