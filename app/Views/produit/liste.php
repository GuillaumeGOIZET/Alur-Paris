<!-- Fil d'ariane -->
<div class="border-b border-noir/5">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs tracking-[0.1em] uppercase text-noir/40">
        <a href="<?= url('') ?>" class="hover:text-bordeaux">Accueil</a>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir">Parfums</span>
    </div>
</div>

<!-- En-tête -->
<div class="max-w-7xl mx-auto px-6 pt-10 pb-8 text-center">
    <h1 class="font-serif text-3xl mb-3">Tous les parfums</h1>
    <p class="text-sm text-noir/50 max-w-md mx-auto leading-relaxed">
        Explorez notre collection complète de fragrances de niche, sélectionnées avec exigence.
    </p>
</div>

<div class="max-w-7xl mx-auto px-6 pb-20 grid md:grid-cols-[200px_1fr] gap-10">

    <!-- ===== FILTRES (colonne gauche) ===== -->
    <aside>
        <div class="mb-8">
            <h4 class="font-serif text-xs tracking-[0.2em] uppercase border-b border-noir pb-2 mb-4">Famille olfactive</h4>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="<?= url('parfums') ?>" 
                       class="flex justify-between <?= empty($filtreActuel['categorie_slug']) ? 'text-bordeaux' : 'text-noir/70 hover:text-bordeaux' ?>">
                        <span>Toutes</span>
                    </a>
                </li>
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="<?= url('parfums?categorie=' . e($cat['slug'])) ?>" 
                           class="flex justify-between <?= ($filtreActuel['categorie_slug'] ?? '') === $cat['slug'] ? 'text-bordeaux' : 'text-noir/70 hover:text-bordeaux' ?>">
                            <span><?= e($cat['nom']) ?></span>
                            <span class="text-noir/30"><?= (int)$cat['nb_produits'] ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
            <h4 class="font-serif text-xs tracking-[0.2em] uppercase border-b border-noir pb-2 mb-4">Genre</h4>
            <ul class="space-y-2 text-sm">
                <?php
                $genres = ['femme' => 'Femme', 'homme' => 'Homme', 'mixte' => 'Mixte'];
                foreach ($genres as $valeur => $label):
                    // On garde le filtre catégorie actif quand on filtre par genre
                    $params = array_filter([
                        'categorie' => $filtreActuel['categorie_slug'] ?? null,
                        'genre'     => $valeur,
                    ]);
                    $lien = url('parfums?' . http_build_query($params));
                ?>
                    <li>
                        <a href="<?= $lien ?>" 
                           class="<?= ($filtreActuel['genre'] ?? '') === $valeur ? 'text-bordeaux' : 'text-noir/70 hover:text-bordeaux' ?>">
                            <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>

    <!-- ===== GRILLE PRODUITS (colonne droite) ===== -->
    <div>
        <!-- Barre de tri -->
        <div class="flex justify-between items-center mb-6 text-xs tracking-[0.1em] uppercase text-noir/40">
            <span><?= count($produits) ?> parfum<?= count($produits) > 1 ? 's' : '' ?></span>
        </div>

        <?php if (empty($produits)): ?>
            <p class="text-noir/50 py-12 text-center">Aucun parfum ne correspond à votre sélection.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($produits as $produit): ?>
                    <a href="<?= url('parfums/' . e($produit['slug'])) ?>" class="group block">
                        <div class="aspect-[3/4] bg-sable mb-4 overflow-hidden flex items-center justify-center relative">
                            <?php if (!empty($produit['image_principale'])): ?>
                                <img src="<?= url('assets/uploads/produits/' . basename($produit['image_principale'])) ?>"
                                     alt="<?= e($produit['nom']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                                <span class="font-serif text-noir/30 text-sm">Visuel à venir</span>
                            <?php endif; ?>

                            <?php if ((int)$produit['stock'] === 0): ?>
                                <span class="absolute top-2 right-2 bg-noir text-blanc text-[9px] tracking-[0.15em] uppercase px-2 py-1">Rupture</span>
                            <?php elseif ((int)$produit['stock'] < 5): ?>
                                <span class="absolute top-2 right-2 bg-bordeaux text-blanc text-[9px] tracking-[0.15em] uppercase px-2 py-1">Stock limité</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-[10px] tracking-[0.2em] uppercase text-noir/40 mb-1"><?= e($produit['categorie_nom']) ?></p>
                        <h3 class="font-serif text-lg mb-1 group-hover:text-bordeaux transition-colors"><?= e($produit['nom']) ?></h3>
                        <p class="text-sm text-noir/70">
                            <?= number_format((float)$produit['prix_ttc'], 2, ',', ' ') ?> €
                            <span class="text-noir/40">· <?= (int)$produit['contenance_ml'] ?>ml</span>
                        </p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>