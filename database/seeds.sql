-- =====================================================
-- Alur Paris — Données initiales
-- =====================================================
-- Contenu : catégories, statuts de commande, compte admin
-- =====================================================

USE alur_paris;

-- =====================================================
-- Catégories
-- =====================================================
INSERT INTO categorie (nom, slug, description, ordre_affichage) VALUES
    ('Boisés', 'boises', 'Des fragrances aux accents profonds et chaleureux, construites autour de bois précieux comme l''oud, le cèdre, le santal ou le vétiver.', 1),
    ('Floraux', 'floraux', 'L''élégance pure du règne végétal : rose, jasmin, iris, tubéreuse. Des compositions raffinées qui célèbrent la nature.', 2),
    ('Orientaux', 'orientaux', 'Mystère et sensualité. Notes ambrées, épicées et résineuses pour des sillages enveloppants et envoûtants.', 3),
    ('Hespéridés', 'hesperides', 'La fraîcheur lumineuse des agrumes : bergamote, citron, néroli, pamplemousse. Pétillant et solaire.', 4),
    ('Aromatiques', 'aromatiques', 'Herbes et plantes aromatiques pour des compositions vivifiantes et naturelles : lavande, romarin, menthe.', 5);

-- =====================================================
-- Statut de commande
-- =====================================================
INSERT INTO statut_commande (code, libelle, couleur_badge, ordre_affichage) VALUES
    ('nouvelle', 'Nouvelle commande', '#6B7280', 1),
    ('en_preparation', 'En préparation', '#F59E0B', 2),
    ('expediee', 'Expédiée', '#3B82F6', 3),
    ('livree', 'Livrée', '#10B981', 4),
    ('annulee', 'Annulée', '#EF4444', 5);

-- =====================================================
-- Compte administrateur
-- =====================================================
INSERT INTO utilisateur (
    email, 
    mot_de_passe_hash, 
    nom, 
    prenom, 
    role, 
    a_accepte_cgv
) VALUES (
    'admin@alur.paris',
    '$2y$10$gpIMcgdWT1TxHnwYT9MRluCCiDe8x/Qj0jD229VLbGOGPPwWaqoIe',
    'Goizet',
    'Guillaume',
    'admin',
    TRUE
);