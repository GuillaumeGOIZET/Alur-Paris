<?php

namespace App\Core;

/**
 * Routeur HTTP de l'application.
 * 
 * Reçoit une URL et une méthode HTTP, cherche la route correspondante
 * dans config/routes.php, instancie le bon contrôleur et appelle sa méthode.
 * 
 * Supporte les paramètres dynamiques style /parfums/{slug}.
 */
class Router
{
    /** @var array<string, string> Tableau associatif des routes */
    private array $routes;
    
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }
    
    /**
     * Point d'entrée du routeur : analyse la requête HTTP courante
     * et appelle le contrôleur correspondant.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $this->parseUri();
        
        foreach ($this->routes as $pattern => $handler) {
            // Sépare la méthode HTTP du chemin
            [$routeMethod, $routePath] = explode(' ', $pattern, 2);
            
            if ($routeMethod !== $method) {
                continue;
            }
            
            // Transforme /parfums/{slug} en regex pour matcher /parfums/oud-royal
            $regex = $this->buildRegex($routePath);
            
            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches); // Retire la chaîne complète, garde juste les groupes capturés
                $this->callHandler($handler, $matches);
                return;
            }
        }
        
        // Aucune route ne matche : 404
        $this->renderNotFound();
    }
    
    /**
     * Extrait le chemin de la requête sans le préfixe du dossier (/alur-paris/public).
     */
    private function parseUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        
        // Retire le préfixe du sous-dossier (utile en local avec XAMPP)
        $basePath = '/alur-paris/public';
        if (str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // Retire le slash final (sauf pour la racine)
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }
        
        return $uri === '' ? '/' : $uri;
    }
    
    /**
     * Transforme un pattern de route (/parfums/{slug}) en regex.
     */
    private function buildRegex(string $routePath): string
    {
        // Remplace {param} par un groupe regex qui capture tout sauf "/"
        $regex = preg_replace('/\{([a-z_]+)\}/', '([^/]+)', $routePath);
        return '#^' . $regex . '$#';
    }
    
    /**
     * Instancie le contrôleur et appelle sa méthode avec les paramètres extraits.
     */
    private function callHandler(string $handler, array $params): void
    {
        [$controllerName, $method] = explode('@', $handler);
        
        $controllerClass = "App\\Controllers\\{$controllerName}";
        
        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Contrôleur introuvable : {$controllerClass}");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Méthode introuvable : {$controllerClass}::{$method}");
        }
        
        $controller->$method(...$params);
    }
    
    /**
     * Affiche une page 404 simple.
     */
    private function renderNotFound(): void
    {
        http_response_code(404);
        echo "<h1>404 — Page non trouvée</h1>";
        echo "<p>La page demandée n'existe pas.</p>";
        echo '<p><a href="' . APP_URL . '/">Retour à l\'accueil</a></p>';
    }
}