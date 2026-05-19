-- =====================================================
-- Alur Paris — Données du catalogue produits
-- =====================================================
-- Contenu : 20 parfums + leurs images principales
-- 
-- Répartition :
--   - Boisés : 5 références
--   - Floraux : 5 références
--   - Orientaux : 5 références
--   - Hespéridés : 4 références
--   - Aromatiques : 1 référence
-- 
-- Stock varié pour pouvoir tester les différents états :
--   - Rupture (stock = 0) : 1 produit (test du blocage panier)
--   - Stock limité (stock < 5) : 3 produits (test du badge)
--   - Stock normal : 16 produits
-- 
-- 3 produits sont mis en avant pour la home (est_mis_en_avant = TRUE)
-- =====================================================

USE alur_paris;

-- =====================================================
-- 1. Insertion des 20 produits
-- =====================================================
-- Note : les id_categorie correspondent à l'ordre d'insertion dans seeds.sql :
--   1 = Boisés, 2 = Floraux, 3 = Orientaux, 4 = Hespéridés, 5 = Aromatiques
-- =====================================================

INSERT INTO produit (
    id_categorie, nom, slug, marque,
    description_courte, description_longue,
    notes_tete, notes_coeur, notes_fond,
    genre, contenance_ml, prix_ttc, taux_tva,
    stock, est_publie, est_mis_en_avant
) VALUES

-- ===== BOISÉS (catégorie 1) =====

(1, 'Oud Royal', 'oud-royal', 'Alur Paris',
 'Un hommage moderne à l''oud, matière sacrée de la parfumerie orientale.',
 'Au cœur de cette composition rare, le bois précieux dialogue avec l''ambre et la rose de Damas pour révéler une fragrance d''une intensité veloutée. Oud Royal incarne le luxe absolu de la parfumerie de niche : une matière première extraordinaire, sublimée par un travail d''orfèvre. Un sillage qui marque les esprits sans jamais s''imposer.',
 'Bergamote, Safran, Poivre noir',
 'Rose de Damas, Patchouli, Cardamome',
 'Oud, Ambre, Vanille bourbon, Musc',
 'mixte', 100, 185.00, 20.00,
 3, TRUE, TRUE),

(1, 'Cèdre Noir', 'cedre-noir', 'Alur Paris',
 'La majesté du cèdre dans une lecture contemporaine, minérale et profonde.',
 'Inspiré des forêts du Liban et des cèdres centenaires de l''Atlas, ce parfum capture la noblesse d''un bois immortel. Une composition épurée où le cèdre dialogue avec des notes minérales et un musc blanc d''une pureté absolue. La signature d''un esthète.',
 'Pamplemousse rose, Élémi, Genièvre',
 'Cèdre de l''Atlas, Vétiver, Iris noir',
 'Cèdre du Liban, Musc blanc, Ambre gris',
 'homme', 100, 190.00, 20.00,
 12, TRUE, FALSE),

(1, 'Santal Sacré', 'santal-sacre', 'Alur Paris',
 'Le santal de Mysore dans toute sa splendeur lactée et hypnotique.',
 'Un voyage olfactif vers l''Inde sacrée, où le santal règne en maître. Cette composition rend hommage à un bois devenu rare, désormais cultivé selon des pratiques durables. Une chaleur enveloppante, une douceur presque charnelle.',
 'Cardamome verte, Bergamote, Élémi',
 'Bois de santal de Mysore, Rose, Iris',
 'Santal, Lait de coco, Tonka, Musc blanc',
 'mixte', 100, 180.00, 20.00,
 8, TRUE, FALSE),

(1, 'Vétiver Brut', 'vetiver-brut', 'Alur Paris',
 'Un vétiver à l''état pur, terreux et puissamment masculin.',
 'Le vétiver d''Haïti distillé à l''ancienne, sans concession. Cette fragrance célèbre la matière brute, fumée, presque sauvage. Pour ceux qui cherchent une signature olfactive authentique et intemporelle.',
 'Mandarine verte, Poivre rose',
 'Vétiver d''Haïti, Tabac blond, Cuir',
 'Vétiver, Bois fumé, Ambre, Mousse de chêne',
 'homme', 100, 170.00, 20.00,
 15, TRUE, FALSE),

(1, 'Bois d''Encens', 'bois-d-encens', 'Alur Paris',
 'La spiritualité des temples antiques en flacon.',
 'Mariage subtil entre le bois sombre et l''encens d''Oman. Une fragrance méditative, presque liturgique, qui évoque les vieilles cathédrales et les monastères tibétains. Un parfum pour les âmes contemplatives.',
 'Encens d''Oman, Bergamote, Élémi',
 'Bois de gaïac, Myrrhe, Patchouli',
 'Encens, Bois fumé, Ambre, Cuir',
 'mixte', 100, 195.00, 20.00,
 6, TRUE, FALSE),

-- ===== FLORAUX (catégorie 2) =====

(2, 'Rose Impériale', 'rose-imperiale', 'Alur Paris',
 'La reine des fleurs dans sa version la plus aristocratique.',
 'Rose de Mai cueillie à Grasse au petit matin. Cette composition révèle une rose multidimensionnelle : poudrée, miellée, légèrement épicée. Loin des roses banales, c''est un hymne à la fleur de tous les superlatifs.',
 'Litchi, Bergamote, Poivre rose',
 'Rose de Mai, Pivoine, Magnolia',
 'Patchouli, Musc blanc, Bois de cachemire',
 'femme', 100, 165.00, 20.00,
 10, TRUE, TRUE),

(2, 'Iris Pâle', 'iris-pale', 'Alur Paris',
 'L''iris dans toute sa noblesse poudrée et minérale.',
 'L''une des matières les plus chères de la parfumerie, l''iris pallida, est ici sublimée dans une composition d''une élégance racée. Une fragrance qui évoque les poudriers de cristal et les vestiges d''un monde révolu.',
 'Mandarine, Poivre vert',
 'Iris pallida, Violette, Carotte',
 'Bois de santal, Musc, Vétiver',
 'mixte', 100, 215.00, 20.00,
 4, TRUE, FALSE),

(2, 'Tubéreuse', 'tubereuse', 'Alur Paris',
 'L''opulence d''une fleur blanche enivrante et solaire.',
 'La tubéreuse d''Inde dans une lecture moderne, débarrassée de ses excès. Cette composition révèle une fleur charnelle, presque sensuelle, équilibrée par des notes vertes qui apportent fraîcheur et lumière.',
 'Galbanum, Mandarine, Néroli',
 'Tubéreuse absolue, Jasmin, Gardénia',
 'Bois de santal, Musc, Vanille',
 'femme', 100, 175.00, 20.00,
 7, TRUE, FALSE),

(2, 'Jasmin Nuit', 'jasmin-nuit', 'Alur Paris',
 'Le jasmin sambac à l''heure où il libère toute sa puissance.',
 'Cueilli à la nuit tombée, le jasmin sambac révèle ici toute sa magie : indolique, sensuelle, presque animale. Une fragrance qui s''épanouit à la peau et raconte une histoire qui se prolonge longtemps après son passage.',
 'Bergamote, Cassis',
 'Jasmin sambac, Ylang-ylang, Rose',
 'Musc blanc, Bois de santal, Vanille',
 'femme', 100, 170.00, 20.00,
 11, TRUE, FALSE),

(2, 'Magnolia', 'magnolia', 'Alur Paris',
 'La grâce solaire d''une fleur blanche printanière.',
 'Le magnolia capturé à son apogée, dans cette brève période où ses pétales libèrent le maximum de leur parfum. Fraîcheur citronnée, douceur florale, sillage lumineux : une signature qui célèbre les beaux jours.',
 'Citron, Bergamote, Poivre rose',
 'Magnolia, Pivoine, Fleur d''oranger',
 'Musc, Bois blonds, Cèdre',
 'femme', 100, 155.00, 20.00,
 0, TRUE, FALSE),

-- ===== ORIENTAUX (catégorie 3) =====

(3, 'Ambre d''Orient', 'ambre-d-orient', 'Alur Paris',
 'La chaleur enveloppante d''un ambre travaillé à la main.',
 'Une fragrance qui évoque les coffres en bois précieux des palais ottomans. L''ambre, matière mythique, s''entrelace ici avec le benjoin et la vanille pour créer un sillage hypnotique. Réconfortant, sensuel, intemporel.',
 'Cannelle, Cardamome, Mandarine',
 'Ambre, Rose, Encens',
 'Vanille, Benjoin, Musc, Tonka',
 'mixte', 100, 175.00, 20.00,
 9, TRUE, TRUE),

(3, 'Vanille Noire', 'vanille-noire', 'Alur Paris',
 'La vanille de Madagascar dans sa version la plus profonde et fumée.',
 'Une vanille sans concession, intense, légèrement boisée et fumée. Cette composition dépasse largement les vanilles gourmandes pour proposer une matière sophistiquée, sensuelle, addictive. Un best-seller à venir.',
 'Bergamote, Café noir',
 'Vanille bourbon, Fève tonka, Réglisse',
 'Bois de santal, Ambre noir, Musc',
 'mixte', 100, 160.00, 20.00,
 18, TRUE, FALSE),

(3, 'Cuir Précieux', 'cuir-precieux', 'Alur Paris',
 'L''élégance d''un cuir noble travaillé par les meilleurs artisans.',
 'Inspiré du cuir de Russie et des selles équestres, cette fragrance évoque les intérieurs feutrés des grands hôtels parisiens. Notes fumées, boisées, animales : tout l''univers du cuir dans sa version la plus raffinée.',
 'Safran, Bergamote, Poivre',
 'Cuir, Rose, Iris',
 'Bois de oud, Patchouli, Musc, Castoréum',
 'mixte', 100, 200.00, 20.00,
 5, TRUE, FALSE),

(3, 'Encens Mystique', 'encens-mystique', 'Alur Paris',
 'Le pouvoir évocateur de l''encens dans une composition contemporaine.',
 'Une fragrance presque liturgique qui invite au recueillement. L''encens d''Oman se marie à des résines précieuses et à un bois patiné par le temps. Pour les amateurs de fragrances introspectives et profondes.',
 'Encens, Élémi, Cardamome',
 'Myrrhe, Oliban, Cèdre',
 'Patchouli, Vétiver, Ambre, Musc',
 'mixte', 100, 185.00, 20.00,
 13, TRUE, FALSE),

(3, 'Myrrhe', 'myrrhe', 'Alur Paris',
 'La résine sacrée des pharaons dans une lecture moderne et boisée.',
 'La myrrhe d''Éthiopie, matière biblique par excellence, est ici sublimée dans une composition à la fois ancienne et contemporaine. Notes balsamiques, résineuses, légèrement fumées : un sillage qui traverse les époques.',
 'Bergamote, Cardamome',
 'Myrrhe, Encens, Rose',
 'Bois de santal, Ambre, Vanille, Musc',
 'mixte', 100, 180.00, 20.00,
 2, TRUE, FALSE),

-- ===== HESPÉRIDÉS (catégorie 4) =====

(4, 'Bergamote Sicilienne', 'bergamote-sicilienne', 'Alur Paris',
 'La fraîcheur lumineuse de la bergamote de Calabre.',
 'Une fragrance solaire qui célèbre la quintessence des agrumes italiens. La bergamote, reine des hespéridés, déploie ici tous ses facettes : pétillante, légèrement amère, profondément aromatique. Un parfum qui réveille les sens.',
 'Bergamote, Citron, Mandarine',
 'Néroli, Petit grain, Romarin',
 'Bois blanc, Musc, Ambre clair',
 'mixte', 100, 145.00, 20.00,
 20, TRUE, FALSE),

(4, 'Néroli', 'neroli', 'Alur Paris',
 'La fleur d''oranger dans sa version la plus noble et raffinée.',
 'Distillation du néroli de Tunisie, cette matière rare apporte sa lumière à une composition d''une élégance simple. Floral et hespéridé à la fois, c''est un parfum qui plaît instantanément et reste en mémoire.',
 'Néroli, Bergamote, Pamplemousse',
 'Fleur d''oranger, Petit grain, Jasmin',
 'Musc blanc, Bois de santal, Cèdre',
 'mixte', 100, 150.00, 20.00,
 14, TRUE, FALSE),

(4, 'Cédrat Vert', 'cedrat-vert', 'Alur Paris',
 'L''énergie d''un agrume vert et tonique.',
 'Le cédrat, plus connu en parfumerie sous son nom italien de cedro, apporte ici une fraîcheur incomparable. Une composition vivifiante, presque énergétique, parfaite pour les beaux jours et les peaux chaudes.',
 'Cédrat, Citron vert, Menthe verte',
 'Basilic, Thé vert, Cardamome',
 'Vétiver, Musc, Cèdre',
 'mixte', 100, 148.00, 20.00,
 16, TRUE, FALSE),

(4, 'Pamplemousse Rose', 'pamplemousse-rose', 'Alur Paris',
 'Le pamplemousse rose dans sa version la plus pétillante et solaire.',
 'Une fragrance moderne et joyeuse qui capture la fraîcheur d''un matin d''été. Le pamplemousse rose, légèrement amer et fruité, se marie à des notes florales pour un résultat délicieusement addictif.',
 'Pamplemousse rose, Mandarine, Poivre rose',
 'Rose, Fleur de pamplemousse, Cassis',
 'Patchouli, Musc, Cèdre blanc',
 'femme', 100, 152.00, 20.00,
 9, TRUE, FALSE),

-- ===== AROMATIQUES (catégorie 5) =====

(5, 'Lavande Sauvage', 'lavande-sauvage', 'Alur Paris',
 'La lavande de Haute-Provence dans une version contemporaine et masculine.',
 'Loin des lavandes classiques, cette composition révèle une matière sauvage, presque âpre. Une fragrance aromatique et boisée qui évoque les champs violets du plateau de Sault sous le soleil de juillet.',
 'Lavande sauvage, Bergamote, Romarin',
 'Sauge, Géranium, Lavandin',
 'Bois de cèdre, Vétiver, Tonka, Musc',
 'homme', 100, 158.00, 20.00,
 11, TRUE, FALSE);


-- =====================================================
-- 2. Insertion des images principales
-- =====================================================
-- Une image principale par produit. Le chemin pointe vers
-- /public/assets/uploads/produits/ (à créer côté serveur).
-- Les images secondaires pourront être ajoutées via l'admin.
-- =====================================================

INSERT INTO image_produit (id_produit, chemin_fichier, texte_alternatif, est_principale, ordre_affichage) VALUES
    (1,  '/assets/uploads/produits/oud-royal-1.jpg',           'Flacon Oud Royal vue de face',           TRUE, 1),
    (2,  '/assets/uploads/produits/cedre-noir-1.jpg',          'Flacon Cèdre Noir vue de face',          TRUE, 1),
    (3,  '/assets/uploads/produits/santal-sacre-1.jpg',        'Flacon Santal Sacré vue de face',        TRUE, 1),
    (4,  '/assets/uploads/produits/vetiver-brut-1.jpg',        'Flacon Vétiver Brut vue de face',        TRUE, 1),
    (5,  '/assets/uploads/produits/bois-d-encens-1.jpg',       'Flacon Bois d''Encens vue de face',      TRUE, 1),
    (6,  '/assets/uploads/produits/rose-imperiale-1.jpg',      'Flacon Rose Impériale vue de face',      TRUE, 1),
    (7,  '/assets/uploads/produits/iris-pale-1.jpg',           'Flacon Iris Pâle vue de face',           TRUE, 1),
    (8,  '/assets/uploads/produits/tubereuse-1.jpg',           'Flacon Tubéreuse vue de face',           TRUE, 1),
    (9,  '/assets/uploads/produits/jasmin-nuit-1.jpg',         'Flacon Jasmin Nuit vue de face',         TRUE, 1),
    (10, '/assets/uploads/produits/magnolia-1.jpg',            'Flacon Magnolia vue de face',            TRUE, 1),
    (11, '/assets/uploads/produits/ambre-d-orient-1.jpg',      'Flacon Ambre d''Orient vue de face',     TRUE, 1),
    (12, '/assets/uploads/produits/vanille-noire-1.jpg',       'Flacon Vanille Noire vue de face',       TRUE, 1),
    (13, '/assets/uploads/produits/cuir-precieux-1.jpg',       'Flacon Cuir Précieux vue de face',       TRUE, 1),
    (14, '/assets/uploads/produits/encens-mystique-1.jpg',     'Flacon Encens Mystique vue de face',     TRUE, 1),
    (15, '/assets/uploads/produits/myrrhe-1.jpg',              'Flacon Myrrhe vue de face',              TRUE, 1),
    (16, '/assets/uploads/produits/bergamote-sicilienne-1.jpg','Flacon Bergamote Sicilienne vue de face',TRUE, 1),
    (17, '/assets/uploads/produits/neroli-1.jpg',              'Flacon Néroli vue de face',              TRUE, 1),
    (18, '/assets/uploads/produits/cedrat-vert-1.jpg',         'Flacon Cédrat Vert vue de face',         TRUE, 1),
    (19, '/assets/uploads/produits/pamplemousse-rose-1.jpg',   'Flacon Pamplemousse Rose vue de face',   TRUE, 1),
    (20, '/assets/uploads/produits/lavande-sauvage-1.jpg',     'Flacon Lavande Sauvage vue de face',     TRUE, 1);