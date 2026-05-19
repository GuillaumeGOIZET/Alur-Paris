<?php

require_once __DIR__ . '/../config/config.php';

use App\Core\Database;

echo "<h1>Test du Core Database & Model</h1>";

try {
    // Test 1 : connexion à la BDD
    $pdo = Database::getConnection();
    echo "<p>✅ Connexion BDD OK</p>";
    
    // Test 2 : récupération des catégories
    $stmt = $pdo->query("SELECT * FROM categorie ORDER BY ordre_affichage");
    $categories = $stmt->fetchAll();
    
    echo "<h2>Catégories en BDD :</h2><ul>";
    foreach ($categories as $cat) {
        echo "<li>" . htmlspecialchars($cat['nom']) . " (slug: " . htmlspecialchars($cat['slug']) . ")</li>";
    }
    echo "</ul>";
    
    // Test 3 : vérifier le singleton
    $pdo2 = Database::getConnection();
    if ($pdo === $pdo2) {
        echo "<p>✅ Singleton OK : même instance PDO renvoyée</p>";
    } else {
        echo "<p>❌ Singleton KO : deux instances différentes</p>";
    }
    
} catch (Throwable $e) {
    echo "<p>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}