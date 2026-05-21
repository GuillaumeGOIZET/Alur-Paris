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
        $uri = $this->parseUri();

        foreach ($this->routes as $pattern => $handler) {
            [$routeMethod, $routePath] = explode(' ', $pattern, 2);

            if ($routeMethod !== $method) {
                continue;
            }

            $regex = $this->buildRegex($routePath);

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);

                // Le handler peut être une string ('Ctrl@methode')
                // ou un tableau (['Ctrl@methode', 'middleware'])
                if (is_array($handler)) {
                    $cible = $handler[0];
                    $middleware = $handler[1] ?? null;
                } else {
                    $cible = $handler;
                    $middleware = null;
                }

                // Exécute le middleware s'il y en a un
                if ($middleware !== null) {
                    $this->executerMiddleware($middleware);
                }

                $this->callHandler($cible, $matches);
                return;
            }
        }

        $this->renderNotFound();
    }

    /**
     * Instancie et exécute un middleware par son nom court.
     */
    private function executerMiddleware(string $nom): void
    {
        $map = [
            'auth' => \App\Middleware\AuthMiddleware::class,
            'admin' => \App\Middleware\AdminMiddleware::class,
        ];

        if (!isset($map[$nom])) {
            throw new \RuntimeException("Middleware inconnu : {$nom}");
        }

        $classe = $map[$nom];
        (new $classe())->handle();
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
    /**
     * Affiche la page 404 stylée (même rendu partout).
     */
    private function renderNotFound(): void
    {
        http_response_code(404);

        $titre = 'Page introuvable';

        // Capture la vue 404 dans $content
        ob_start();
        $viewPath = __DIR__ . '/../Views/errors/404.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo '<h1>404 — Page non trouvée</h1>';
        }
        $content = ob_get_clean();

        // Enveloppe dans le layout public
        $layoutPath = __DIR__ . '/../Views/layouts/public.php';
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content; // fallback si pas de layout
        }
    }
}