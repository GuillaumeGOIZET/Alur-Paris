-- =====================================================
-- Alur Paris — Schéma de base de données
-- =====================================================
-- SGBD     : MySQL 8
-- Encodage : utf8mb4
-- Moteur   : InnoDB
-- Auteur   : GOIZET Guillaume
-- =====================================================

-- Reset complet de la BDD pour permettre les ré-imports en dev
DROP DATABASE IF EXISTS alur_paris;
CREATE DATABASE alur_paris CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alur_paris;


CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe_hash VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NULL,
    role ENUM('client','admin') NOT NULL DEFAULT 'client',
    a_accepte_cgv BOOLEAN NOT NULL DEFAULT FALSE,
    consentement_marketing BOOLEAN NOT NULL DEFAULT FALSE,
    cree_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    derniere_connexion_le DATETIME NULL,
    supprime_le DATETIME NULL,
    -- Index
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(80) NOT NULL UNIQUE,
    slug VARCHAR(80) NOT NULL UNIQUE,
    description TEXT NULL,
    ordre_affichage INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE statut_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    libelle VARCHAR(100) NOT NULL,
    couleur_badge VARCHAR(20) NULL,
    ordre_affichage INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    sujet VARCHAR(200) NULL,
    message TEXT NOT NULL,
    est_traite BOOLEAN NOT NULL DEFAULT FALSE,
    cree_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    -- Index pour le tri admin "messages non traités d'abord"
    INDEX idx_traite (est_traite)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE adresse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    libelle VARCHAR(50) NULL,
    nom_destinataire VARCHAR(100) NOT NULL,
    prenom_destinataire VARCHAR(100) NOT NULL,
    ligne_1 VARCHAR(255) NOT NULL,
    ligne_2 VARCHAR(255) NULL,
    code_postal VARCHAR(20) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    pays VARCHAR(100) NOT NULL DEFAULT 'France',
    telephone VARCHAR(20) NULL,
    est_defaut BOOLEAN NOT NULL DEFAULT FALSE,
    cree_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    nom VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    marque VARCHAR(100) NULL,
    description_courte VARCHAR(255) NULL,
    description_longue TEXT NOT NULL,
    notes_tete VARCHAR(255) NULL,
    notes_coeur VARCHAR(255) NULL,
    notes_fond VARCHAR(255) NULL,
    genre ENUM('femme','homme','mixte') NOT NULL DEFAULT 'mixte',
    contenance_ml INT NOT NULL,
    prix_ttc DECIMAL(10,2) NOT NULL,
    taux_tva DECIMAL(5,2) NOT NULL DEFAULT 20.00,
    stock INT NOT NULL DEFAULT 0,
    est_publie BOOLEAN NOT NULL DEFAULT FALSE,
    est_mis_en_avant BOOLEAN NOT NULL DEFAULT FALSE,
    cree_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modifie_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    supprime_le DATETIME NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id) ON DELETE RESTRICT,
    INDEX idx_publie (est_publie),
    INDEX idx_mis_en_avant (est_mis_en_avant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE image_produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produit INT NOT NULL,
    chemin_fichier VARCHAR(255) NOT NULL,
    texte_alternatif VARCHAR(255) NULL,
    est_principale BOOLEAN NOT NULL DEFAULT FALSE,
    ordre_affichage INT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(20) NOT NULL UNIQUE,
    id_utilisateur INT NULL,
    email_contact VARCHAR(255) NOT NULL,
    nom_invite VARCHAR(100) NULL,
    prenom_invite VARCHAR(100) NULL,
    id_statut INT NOT NULL,
    montant_sous_total_ht DECIMAL(10,2) NOT NULL,
    montant_tva DECIMAL(10,2) NOT NULL,
    montant_livraison DECIMAL(10,2) NOT NULL DEFAULT 0,
    montant_total_ttc DECIMAL(10,2) NOT NULL,
    livraison_nom VARCHAR(100) NOT NULL,
    livraison_prenom VARCHAR(100) NOT NULL,
    livraison_ligne_1 VARCHAR(255) NOT NULL,
    livraison_ligne_2 VARCHAR(255) NULL,
    livraison_code_postal VARCHAR(20) NOT NULL,
    livraison_ville VARCHAR(100) NOT NULL,
    livraison_pays VARCHAR(100) NOT NULL,
    livraison_telephone VARCHAR(20) NULL,
    stripe_payment_intent_id VARCHAR(255) NULL,
    statut_paiement ENUM('en_attente','reussi','echec','rembourse') NOT NULL DEFAULT 'en_attente',
    paye_le DATETIME NULL,
    commentaire_client TEXT NULL,
    cree_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modifie_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE SET NULL,
    FOREIGN KEY (id_statut) REFERENCES statut_commande(id) ON DELETE RESTRICT,
    INDEX idx_cree (cree_le),
    INDEX idx_statut_paiement (statut_paiement)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ligne_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_produit INT NOT NULL,
    nom_produit VARCHAR(150) NOT NULL,
    contenance_ml INT NOT NULL,
    prix_unitaire_ht DECIMAL(10,2) NOT NULL,
    prix_unitaire_ttc DECIMAL(10,2) NOT NULL,
    taux_tva DECIMAL(5,2) NOT NULL,
    quantite INT NOT NULL,
    sous_total_ht DECIMAL(10,2) NOT NULL,
    sous_total_ttc DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_commande) REFERENCES commande(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;