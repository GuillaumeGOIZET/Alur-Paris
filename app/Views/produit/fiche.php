<!-- Fil d'ariane -->
<div class="border-b border-noir/5">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs tracking-[0.1em] uppercase text-noir/40">
        <a href="<?= url('') ?>" class="hover:text-bordeaux">Accueil</a>
        <span class="mx-2 text-noir/20">/</span>
        <a href="<?= url('parfums') ?>" class="hover:text-bordeaux">Parfums</a>
        <span class="mx-2 text-noir/20">/</span>
        <a href="<?= url('parfums?categorie=' . e($produit['categorie_slug'])) ?>" class="hover:text-bordeaux"><?= e($produit['categorie_nom']) ?></a>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir"><?= e($produit['nom']) ?></span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-2 gap-12">

    <!-- ===== GALERIE ===== -->
    <div>
        <div class="aspect-[4/5] bg-sable flex items-center justify-center overflow-hidden">
            <?php if (!empty($images)): ?>
                <img src="<?= url('assets/uploads/produits/' . basename($images[0]['chemin_fichier'])) ?>"
                     alt="<?= e($images[0]['texte_alternatif'] ?? $produit['nom']) ?>"
                     class="w-full h-full object-cover">
            <?php else: ?>
                <span class="font-serif text-noir/30">Visuel à venir</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== INFOS ===== -->
    <div>
        <span class="inline-block text-[10px] tracking-[0.25em] uppercase text-bordeaux border border-bordeaux px-3 py-1 mb-5">
            <?= e($produit['categorie_nom']) ?>
        </span>

        <h1 class="font-serif text-4xl mb-2 leading-tight"><?= e($produit['nom']) ?></h1>
        <p class="text-xs tracking-[0.1em] text-noir/50 mb-6">
            Eau de Parfum · <?= ucfirst(e($produit['genre'])) ?>
        </p>

        <div class="flex items-baseline gap-3 mb-2">
            <span class="font-serif text-3xl"><?= number_format((float)$produit['prix_ttc'], 2, ',', ' ') ?> €</span>
            <span class="text-sm text-noir/40">· <?= (int)$produit['contenance_ml'] ?> ml</span>
        </div>

        <!-- Indicateur de stock -->
        <?php $stock = (int)$produit['stock']; ?>
        <div class="flex items-center gap-2 mb-6 text-xs">
            <?php if ($stock === 0): ?>
                <span class="w-2 h-2 rounded-full bg-noir/40"></span>
                <span class="text-noir/50 tracking-[0.05em]">Rupture de stock</span>
            <?php elseif ($stock < 5): ?>
                <span class="w-2 h-2 rounded-full bg-bordeaux"></span>
                <span class="text-bordeaux font-medium tracking-[0.05em]">Plus que <?= $stock ?> en stock</span>
            <?php else: ?>
                <span class="w-2 h-2 rounded-full bg-green-600"></span>
                <span class="text-noir/60 tracking-[0.05em]">En stock</span>
            <?php endif; ?>
        </div>

        <p class="text-sm text-noir/70 leading-relaxed mb-8"><?= e($produit['description_longue']) ?></p>

        <!-- Notes olfactives -->
        <div class="bg-sable p-6 mb-8">
            <h3 class="font-serif text-xs tracking-[0.2em] uppercase border-b border-noir/10 pb-3 mb-4">Notes olfactives</h3>
            <?php if (!empty($produit['notes_tete'])): ?>
                <div class="flex gap-4 py-2">
                    <span class="font-serif text-xs tracking-[0.15em] uppercase text-bordeaux w-16 shrink-0">Tête</span>
                    <span class="text-xs text-noir leading-relaxed"><?= e($produit['notes_tete']) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($produit['notes_coeur'])): ?>
                <div class="flex gap-4 py-2">
                    <span class="font-serif text-xs tracking-[0.15em] uppercase text-bordeaux w-16 shrink-0">Cœur</span>
                    <span class="text-xs text-noir leading-relaxed"><?= e($produit['notes_coeur']) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($produit['notes_fond'])): ?>
                <div class="flex gap-4 py-2">
                    <span class="font-serif text-xs tracking-[0.15em] uppercase text-bordeaux w-16 shrink-0">Fond</span>
                    <span class="text-xs text-noir leading-relaxed"><?= e($produit['notes_fond']) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Ajout au panier -->
        <?php if ($stock > 0): ?>
            <div class="flex gap-3">
                <div class="flex items-center border border-noir">
                    <button type="button" id="qte-moins" class="w-10 h-11 text-lg hover:bg-sable transition-colors">−</button>
                    <input type="number" id="quantite-produit" value="1" min="1" max="<?= $stock ?>" class="w-12 h-11 text-center border-x border-noir focus:outline-none" readonly>
                    <button type="button" id="qte-plus" class="w-10 h-11 text-lg hover:bg-sable transition-colors">+</button>
                </div>
                <button type="button" class="btn-ajouter-panier flex-1 bg-noir text-blanc text-xs tracking-[0.25em] uppercase hover:bg-bordeaux transition-colors" data-id="<?= (int)$produit['id'] ?>">
                    Ajouter au panier
                </button>
            </div>
        <?php else: ?>
            <button type="button" disabled class="w-full bg-noir/20 text-blanc text-xs tracking-[0.25em] uppercase py-3 cursor-not-allowed">
                Indisponible
            </button>
        <?php endif; ?>

        <!-- Réassurance -->
        <div class="flex flex-wrap gap-4 mt-6 pt-6 border-t border-noir/5 text-[10px] tracking-[0.05em] uppercase text-noir/50">
            <span>Livraison offerte dès 150€</span>
            <span>Paiement sécurisé</span>
            <span>Retour 14 jours</span>
        </div>
    </div>
</div>

<!-- ===== VOUS AIMEREZ AUSSI ===== -->
<?php if (!empty($similaires)): ?>
<section class="bg-sable mt-10">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="text-center mb-10">
            <p class="text-xs tracking-[0.3em] uppercase text-noir/40 mb-2">Dans la même famille</p>
            <h2 class="font-serif text-2xl">Vous aimerez aussi</h2>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <?php foreach ($similaires as $sim): ?>
                <a href="<?= url('parfums/' . e($sim['slug'])) ?>" class="group block text-center">
                    <div class="aspect-[3/4] bg-blanc mb-4 overflow-hidden flex items-center justify-center">
                        <?php if (!empty($sim['image_principale'])): ?>
                            <img src="<?= url('assets/uploads/produits/' . basename($sim['image_principale'])) ?>"
                                 alt="<?= e($sim['nom']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <span class="font-serif text-noir/30 text-xs">Visuel à venir</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-[9px] tracking-[0.2em] uppercase text-noir/40 mb-1"><?= e($sim['categorie_nom']) ?></p>
                    <h3 class="font-serif text-sm mb-1 group-hover:text-bordeaux transition-colors"><?= e($sim['nom']) ?></h3>
                    <p class="text-xs text-noir/70"><?= number_format((float)$sim['prix_ttc'], 2, ',', ' ') ?> €</p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>