# Journal de bord — Alur Paris

---

## Jour 1 — Lundi [18/06/2026]

### Ce que j'ai fait
- Conception complète (CDC, MCD, MLD, archi MVC, planning, Kanban)
- Préparation environnement
- ...

### Difficultés rencontrées
[Honnête : ce qui a été dur ou hésitant]

### Décisions clés prises
[Choix techniques justifiés ce jour]

### Pour demain
- ...

---

## Jour 2 — Mardi [19/05/2026]

### Ce que j'ai fait
- Configuration de l'environnement de développement (XAMPP, Composer, .env, Git)
- Conception et écriture du schéma SQL complet (9 tables avec FK et indexes)
- Données initiales : 5 catégories, 5 statuts, compte admin
- Catalogue de 20 produits avec descriptions et notes olfactives
- Installation des dépendances Composer (Stripe SDK, PHPMailer, Dotenv)
- Configuration de l'URL rewriting via .htaccess (architecture front controller)
- Structure complète des dossiers MVC

### Difficultés rencontrées
- Comprendre la différence entre BOOLEAN DEFAULT 'false' et DEFAULT FALSE
- Erreur de hash bcrypt tronqué — résolue en regénérant avec PHP
- Configuration SSH GitHub remplacée par HTTPS pour gagner du temps

### Décisions clés prises
- Choix de DECIMAL(10,2) sur les prix pour éviter les erreurs d'arrondi FLOAT
- Architecture front controller avec public/ comme seule racine web exposée (sécurité)
- Adresse de livraison figée dans la table commande (préservation de l'historique)
- Séparation données système (seeds) vs données métier (data_produits)

### Pour demain
- Coder le Core MVC : Router, Database singleton, Controller mère, Model mère
- Commencer le layout public (header, footer) avec Tailwind
- Si temps : démarrer la page d'accueil