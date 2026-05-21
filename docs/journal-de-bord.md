# Journal de bord — Alur Paris

---

## Jour 1 — Lundi [18/06/2026]

### Ce que j'ai fait
- Analyse du cahier des charges et cadrage du projet : définition d'un site e-commerce 
  de parfumerie de niche mono-marque (« Alur Paris »).
- Définition du périmètre fonctionnel (MVP) : front public (catalogue, fiche produit, 
  panier, tunnel de commande, paiement), espace client, back-office administrateur, 
  gestion de stock. Identification de ce qui est hors périmètre (marketplace, application 
  mobile, programme de fidélité).
- Conception de l'arborescence du site (pages publiques, espace client, espace admin) 
  avec définition des URLs.
- Modélisation de la base de données selon la méthode Merise :
  - MCD (Modèle Conceptuel de Données) : 10 entités, règles de gestion.
  - MLD (Modèle Logique de Données) : 9 tables, passage en 3e forme normale.
- Choix de la stack technique : PHP 8 orienté objet avec une architecture MVC maison, 
  MySQL via PDO, HTML/Tailwind CSS, JavaScript vanilla, Stripe (mode test) pour le 
  paiement, PHPMailer pour les emails.
- Mise en place de l'environnement de développement : XAMPP (Apache + MySQL), 
  Composer pour la gestion des dépendances, dépôt Git/GitHub, fichier de configuration 
  .env, planification du travail (Trello/Kanban).

### Difficultés rencontrées
- Ambiguïté initiale du cahier des charges : le brief évoquait une dimension 
  « marketplace » avec plusieurs vendeurs, ce qui était irréaliste à réaliser en 5 jours. 
  J'ai dû trancher et recentrer le projet sur un e-commerce mono-marque, en justifiant 
  ce choix par la contrainte de temps.
- Conception du modèle de données : déterminer les bonnes relations entre les tables 
  (notamment la gestion du panier, des commandes et le figeage des prix/adresses au 
  moment de la commande) a demandé de la réflexion. J'ai compris pourquoi il faut 
  « figer » certaines données dans la commande plutôt que de pointer vers les tables 
  d'origine (préserver l'historique même si un produit ou un prix change ensuite).
- Choix de stockage des prix : hésitation entre FLOAT et DECIMAL. J'ai retenu 
  DECIMAL(10,2) pour éviter les erreurs d'arrondi sur les montants monétaires.

### Décisions clés prises
- Recentrage du projet sur un e-commerce mono-marque (abandon de la dimension marketplace) 
  pour garantir un livrable fonctionnel en 5 jours.
- Architecture MVC développée « à la main » plutôt qu'avec un framework, afin de 
  démontrer la compréhension des mécanismes sous-jacents (routage, séparation des 
  responsabilités) attendue pour la certification.
- Base de données en 3e forme normale, avec figeage des données de commande (adresse 
  et prix copiés dans la commande) pour préserver l'intégrité de l'historique.
- DECIMAL plutôt que FLOAT pour tous les montants, par souci de précision.
- Utilisation de PDO avec requêtes préparées dès la conception, pour intégrer la 
  sécurité (anti-injection SQL) dès le départ plutôt qu'en correctif.
- Séparation stricte du dossier public/ (seul exposé au web) du reste du code, 
  pour des raisons de sécurité.
- Mise en place de Git dès le premier jour avec une convention de messages de commit, 
  pour assurer la traçabilité du travail.

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

---

## Jour 3 - Mercredi 20 mai

### Ce que j'ai fait
- Résolution définitive du problème de routage (.htaccess) après diagnostic approfondi
- Système MVC pleinement fonctionnel (accueil, catalogue, fiche produit)
- Panier asynchrone complet : ajout/modification/suppression en AJAX, gestion du stock, 
  calcul des totaux HT/TVA/TTC, badge dynamique et notifications toast
- Authentification sécurisée : hachage bcrypt, protection CSRF, validation des données, 
  régénération de session, gestion des rôles (client/admin)
- Middlewares de protection des routes (/compte et /admin)
- Tunnel de commande (hors paiement) : mode invité/connecté, formulaire de livraison, 
  récapitulatif
- Pages légales (mentions, CGV, RGPD) et formulaire de contact avec envoi d'email (PHPMailer)
- Bannière de consentement cookies (RGPD)
- Système de favoris/wishlist (bonus)

### Difficultés rencontrées
- Blocage majeur sur la réécriture d'URL (.htaccess)
   Problème : toutes les URLs renvoyaient une erreur 404, alors que mod_rewrite était 
   activé et AllowOverride configuré sur All.
   Démarche : j'ai procédé par élimination — test d'accès direct à index.php, vérification 
   de la configuration Apache, lecture du journal d'erreurs (error.log).
   Cause réelle : un fichier .htaccess avait été mal généré via une commande terminal 
   (le mot "cat" s'était retrouvé écrit dans le fichier). 
   Solution : recréer le fichier directement dans VS Code plutôt que par le terminal.
   Leçon : TOUJOURS lire les logs Apache en cas de 404/500 inexpliqué — le navigateur 
   ne dit pas la vérité, le log si. Et créer les fichiers de config via l'éditeur, 
   pas par des commandes heredoc.
- Double balise <body> dans le layout
   Problème : le JavaScript du panier ne fonctionnait pas du tout.
   Cause : en ajoutant les scripts, j'avais créé un second <body>, ce qui faisait 
   exécuter le JS sur une page encore vide (les boutons n'existaient pas encore).
   Leçon : les scripts doivent être en bas du body, après le contenu HTML. J'ai compris 
   concrètement pourquoi.
- Accolades mal placées en PHP et JavaScript
   À deux reprises (modèle Produit, puis fonction JS), un bloc de code s'est retrouvé 
   au mauvais endroit à cause d'une accolade. 
   Leçon : quand un bloc "ne s'exécute pas", vérifier d'abord les accolades. 
   J'ai développé ce réflexe.

### Décisions clés prises
- Panier et favoris stockés en session (pas en BDD) : ils sont temporaires et propres 
  à la navigation, ils ne deviennent persistants qu'à la commande.
- Hachage bcrypt des mots de passe + protection CSRF sur tous les formulaires : 
  je n'ai jamais stocké de mot de passe en clair.
- Centralisation des URLs via un helper url() basé sur une variable d'environnement, 
  pour rendre le code portable entre dev et production.
- Construction du tunnel de commande SANS le paiement d'abord, pour réduire le risque 
  sur la journée de jeudi (paiement Stripe = partie la plus délicate).