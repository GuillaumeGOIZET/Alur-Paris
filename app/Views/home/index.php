<!-- ===== HERO ===== -->
<section class="relative bg-noir text-blanc">
    <div class="max-w-7xl mx-auto px-6 py-28 md:py-36">
        <div class="max-w-xl">
            <p class="text-xs tracking-[0.3em] uppercase text-blanc/50 mb-6">Parfumerie de niche</p>
            <h1 class="font-serif text-4xl md:text-5xl leading-tight mb-6">
                L'art du parfum,<br>sans concession
            </h1>
            <p class="text-sm text-blanc/70 leading-relaxed mb-8">
                Une sélection curatée de fragrances de niche, conçues par des maîtres parfumeurs indépendants. Des matières rares, un savoir-faire d'exception.
            </p>
            <a href="<?= url('parfums') ?>" class="inline-block px-8 py-3 border border-blanc text-blanc text-xs tracking-[0.25em] uppercase hover:bg-blanc hover:text-noir transition-colors">
                Découvrir la collection
            </a>
        </div>
    </div>
</section>

<!-- ===== SÉLECTION ICONIQUE ===== -->
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
        <p class="text-xs tracking-[0.3em] uppercase text-noir/40 mb-2">Notre sélection</p>
        <h2 class="font-serif text-3xl">Iconiques de la maison</h2>
    </div>

    <?php if (empty($produitsVedette)): ?>
        <p class="text-center text-noir/50">Aucun produit en vedette pour le moment.</p>
    <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($produitsVedette as $produit): ?>
                <a href="<?= url('parfums/' . e($produit['slug'])) ?>" class="group block">
                    <!-- Image -->
                    <div class="aspect-[3/4] bg-sable mb-4 overflow-hidden flex items-center justify-center">
                        <?php if (!empty($produit['image_principale'])): ?>
                            <img src="<?= url('assets/uploads/produits/' . basename($produit['image_principale'])) ?>"
                                 alt="<?= e($produit['nom']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <span class="font-serif text-noir/30 text-sm">Visuel à venir</span>
                        <?php endif; ?>
                    </div>
                    <!-- Infos -->
                    <p class="text-[10px] tracking-[0.2em] uppercase text-noir/40 mb-1"><?= e($produit['categorie_nom']) ?></p>
                    <h3 class="font-serif text-lg mb-1 group-hover:text-bordeaux transition-colors"><?= e($produit['nom']) ?></h3>
                    <p class="text-sm text-noir/70"><?= number_format((float)$produit['prix_ttc'], 2, ',', ' ') ?> €</p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- ===== UNIVERS DE LA MARQUE ===== -->
<section class="bg-noir text-blanc">
    <div class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
        <div class="aspect-[4/5] bg-bordeaux/30"></div>
        <div>
            <p class="text-xs tracking-[0.3em] uppercase text-blanc/50 mb-4">La Maison</p>
            <h2 class="font-serif text-3xl mb-6 leading-tight">Un savoir-faire d'exception</h2>
            <p class="text-sm text-blanc/70 leading-relaxed mb-8">
                Depuis notre atelier parisien, nous travaillons les matières premières les plus rares avec le respect dû à un art ancestral. Chaque fragrance est le fruit d'une recherche minutieuse, d'une obsession du détail.
            </p>
            <a href="<?= url('maison') ?>" class="inline-block px-8 py-3 border border-blanc text-blanc text-xs tracking-[0.25em] uppercase hover:bg-blanc hover:text-noir transition-colors">
                En savoir plus
            </a>
        </div>
    </div>
</section>