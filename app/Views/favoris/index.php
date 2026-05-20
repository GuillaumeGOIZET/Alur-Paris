<div class="border-b border-noir/5">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs tracking-[0.1em] uppercase text-noir/40">
        <a href="<?= url('') ?>" class="hover:text-bordeaux">Accueil</a>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir">Mes favoris</span>
    </div>
</div>

<section class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="font-serif text-3xl mb-10 text-center">Mes favoris</h1>

    <?php if (empty($produits)): ?>
        <div class="text-center py-16">
            <p class="text-noir/50 mb-8 leading-relaxed">Vous n'avez pas encore de favoris.</p>
            <a href="<?= url('parfums') ?>" class="inline-block px-8 py-3 bg-noir text-blanc text-xs tracking-[0.25em] uppercase hover:bg-bordeaux transition-colors">
                Découvrir la collection
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($produits as $produit): ?>
                <div class="group relative" data-favori-carte="<?= $produit['id'] ?>">
                    <a href="<?= url('parfums/' . e($produit['slug'])) ?>" class="block">
                        <div class="aspect-[3/4] bg-sable mb-4 overflow-hidden flex items-center justify-center">
                            <?php if (!empty($produit['image_principale'])): ?>
                                <img src="<?= url('assets/uploads/produits/' . basename($produit['image_principale'])) ?>"
                                     alt="<?= e($produit['nom']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                                <span class="font-serif text-noir/30 text-sm">Visuel à venir</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-[10px] tracking-[0.2em] uppercase text-noir/40 mb-1"><?= e($produit['categorie_nom']) ?></p>
                        <h3 class="font-serif text-lg mb-1 group-hover:text-bordeaux transition-colors"><?= e($produit['nom']) ?></h3>
                        <p class="text-sm text-noir/70"><?= number_format((float)$produit['prix_ttc'], 2, ',', ' ') ?> €</p>
                    </a>
                    <button type="button" class="btn-favori absolute top-3 right-3 w-9 h-9 bg-blanc/90 rounded-full flex items-center justify-center hover:bg-blanc transition-colors" data-id="<?= $produit['id'] ?>" aria-label="Retirer des favoris">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-bordeaux" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                        </svg>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>