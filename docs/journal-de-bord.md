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

## Jour 2 — Mardi 19 mai

### Ce que j'ai fait
- Configuration environnement (XAMPP, Composer, Git, Stripe test, Mailtrap)
- Conception SQL : schéma complet de 9 tables avec FK et indexes
- Données initiales (catégories, statuts) + catalogue de 20 parfums
- Architecture MVC : Database singleton, Model abstrait, Router avec paramètres 
  dynamiques, Controller mère, point d'entrée index.php
- Test réussi de connexion BDD (les 5 catégories s'affichent depuis la BDD)

### Difficultés rencontrées
- Erreur Dotenv liée à un espace dans une valeur non quotée (SMTP_FROM_NAME=Alur Paris).
  Résolu en quotant les valeurs avec espace, ce qui m'a appris les règles strictes 
  de parsing des fichiers .env.
- Configuration AllowOverride à vérifier dans httpd.conf de XAMPP.
- URL rewriting Apache qui ne fonctionne pas en sous-dossier (/alur-paris/) sur 
  XAMPP. Le code MVC est en place et fonctionne quand on accède directement à 
  /public/index.php, mais la réécriture vers index.php depuis la racine n'est 
  pas active. À résoudre demain matin (priorité 1).

### Décisions clés prises
- DECIMAL(10,2) pour les prix (vs FLOAT) pour éviter les erreurs d'arrondi
- Singleton pour la connexion PDO (une seule instance par requête HTTP)
- Adresses figées dans la commande pour préserver l'historique
- Architecture front controller (un seul point d'entrée index.php)
- Séparation public/ vs app/ pour la sécurité (seul public/ exposé au web)

### Pour demain
- PRIORITÉ 1 : résoudre le rewriting .htaccess pour les URLs propres
- Démarrer le layout HTML (header, footer) avec Tailwind CDN
- Coder la page d'accueil avec rendu des produits depuis la BDD
- Si temps : coder la liste catalogue et la fiche produit

## REPRISE DEMAIN MATIN

### Status au coucher (mardi 1h du matin)
- Core MVC entièrement codé et fonctionnel
- BDD opérationnelle (test réussi : 5 catégories affichées)
- ✅ http://localhost/alur-paris/public/index.php fonctionne
- ❌ http://localhost/alur-paris/ donne 404
- AllowOverride All confirmé
- mod_rewrite activé
- .htaccess en place mais pas effectifs sur la racine

### À tester demain en priorité
1. Une approche unique .htaccess (sans double redirection)
2. Alternative : créer un VirtualHost dédié alur.local