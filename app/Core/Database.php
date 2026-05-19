<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Singleton de connexion à la base de données MySQL via PDO.
 * 
 * Garantit qu'une seule instance PDO est utilisée pour toute la durée
 * d'une requête HTTP, évitant l'ouverture de connexions multiples.
 * 
 * Usage :
 *     $pdo = Database::getConnection();
 *     $stmt = $pdo->prepare("SELECT * FROM produit WHERE id = ?");
 *     $stmt->execute([$id]);
 */
class Database
{
    /** @var PDO|null Instance unique de PDO */
    private static ?PDO $instance = null;
    
    /**
     * Constructeur privé : empêche l'instanciation directe avec "new Database()".
     * Le seul moyen d'obtenir une instance est via getConnection().
     */
    private function __construct() {}
    
    /**
     * Empêche le clonage de l'instance (sécurité du singleton).
     */
    private function __clone() {}
    
    /**
     * Retourne l'instance unique de PDO. La crée si elle n'existe pas encore.
     *
     * @return PDO
     * @throws PDOException si la connexion échoue
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }
        
        return self::$instance;
    }
    
    /**
     * Crée la connexion PDO à partir des variables d'environnement.
     */
    private static function createConnection(): PDO
    {
        $host    = $_ENV['DB_HOST'];
        $port    = $_ENV['DB_PORT'] ?? 3306;
        $dbname  = $_ENV['DB_NAME'];
        $user    = $_ENV['DB_USER'];
        $pass    = $_ENV['DB_PASSWORD'] ?? '';
        $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
        
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            if (APP_DEBUG) {
                throw $e;
            }
            throw new PDOException("Connexion à la base de données impossible.");
        }
    }
}