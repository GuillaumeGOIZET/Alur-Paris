<?php

namespace App\Core;

/**
 * Classe mère abstraite de tous les contrôleurs.
 * 
 * Fournit les méthodes utilitaires communes :
 *  - render() : afficher une vue avec un layout
 *  - redirect() : rediriger vers une autre URL
 *  - json() : renvoyer une réponse JSON (pour les requêtes AJAX)
 */
abstract class Controller
{
    /**
     * Rend une vue dans un layout donné.
     * 
     * @param string $view    Chemin relatif de la vue (ex: "produit/fiche")
     * @param array  $data    Variables à passer à la vue
     * @param string $layout  Layout à utiliser (par défaut : public)
     */
    protected function render(string $view, array $data = [], string $layout = 'public'): void
    {
        // Extrait le tableau en variables : ['nom' => 'Oud'] devient $nom = 'Oud'
        extract($data, EXTR_SKIP);
        
        // Capture le contenu de la vue dans une variable
        ob_start();
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue introuvable : {$viewPath}");
        }
        
        require $viewPath;
        $content = ob_get_clean();
        
        // Charge le layout qui utilisera $content pour insérer la vue
        $layoutPath = __DIR__ . '/../Views/layouts/' . $layout . '.php';
        
        if (!file_exists($layoutPath)) {
            throw new \RuntimeException("Layout introuvable : {$layoutPath}");
        }
        
        require $layoutPath;
    }
    
    /**
     * Redirige vers une URL et arrête l'exécution.
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Renvoie une réponse JSON (utile pour AJAX, futur API).
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}