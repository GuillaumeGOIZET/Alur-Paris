<div class="border-b border-noir/5">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs tracking-[0.1em] uppercase text-noir/40">
        <a href="<?= url('') ?>" class="hover:text-bordeaux">Accueil</a>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir">Panier</span>
    </div>
</div>

<section class="max-w-5xl mx-auto px-6 py-12" id="panier-page" data-empty="<?= empty($panier['lignes']) ? '1' : '0' ?>">
    <h1 class="font-serif text-3xl mb-10 text-center">Mon panier</h1>

    <!-- Panier vide -->
    <div id="panier-vide" class="<?= empty($panier['lignes']) ? '' : 'hidden' ?> text-center py-16">
        <p class="text-noir/50 mb-8 leading-relaxed">Votre panier est vide.</p>
        <a href="<?= url('parfums') ?>" class="inline-block px-8 py-3 bg-noir text-blanc text-xs tracking-[0.25em] uppercase hover:bg-bordeaux transition-colors">
            Découvrir la collection
        </a>
    </div>

    <!-- Panier rempli -->
    <div id="panier-contenu" class="<?= empty($panier['lignes']) ? 'hidden' : '' ?> grid md:grid-cols-[1fr_320px] gap-10">

        <!-- Liste des articles -->
        <div id="panier-lignes">
            <?php foreach ($panier['lignes'] as $ligne): ?>
                <div class="flex gap-4 py-5 border-b border-noir/5" data-ligne="<?= $ligne['id'] ?>">
                    <div class="w-20 h-24 bg-sable shrink-0 flex items-center justify-center">
                        <span class="font-serif text-noir/30 text-[10px]">Visuel</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-serif text-lg"><?= e($ligne['nom']) ?></h3>
                        <p class="text-xs text-noir/40 mb-3"><?= $ligne['contenance_ml'] ?> ml</p>

                        <div class="flex items-center gap-4">
                            <!-- Sélecteur quantité -->
                            <div class="flex items-center border border-noir/20 text-sm">
                                <button type="button" class="w-8 h-8 hover:bg-sable transition-colors btn-qte" data-action="moins" data-id="<?= $ligne['id'] ?>">−</button>
                                <span class="px-3 qte-valeur" data-id="<?= $ligne['id'] ?>"><?= $ligne['quantite'] ?></span>
                                <button type="button" class="w-8 h-8 hover:bg-sable transition-colors btn-qte" data-action="plus" data-id="<?= $ligne['id'] ?>" data-stock="<?= $ligne['stock'] ?>">+</button>
                            </div>
                            <!-- Supprimer -->
                            <button type="button" class="text-xs text-noir/40 hover:text-bordeaux transition-colors btn-retirer" data-id="<?= $ligne['id'] ?>">
                                Retirer
                            </button>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-serif text-lg ligne-total" data-id="<?= $ligne['id'] ?>">
                            <?= number_format($ligne['sous_total_ttc'], 2, ',', ' ') ?> €
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Récapitulatif -->
        <aside class="bg-sable p-6 h-fit">
            <h2 class="font-serif text-sm tracking-[0.15em] uppercase border-b border-noir/10 pb-3 mb-4">Récapitulatif</h2>

            <div class="space-y-2 text-sm mb-4">
                <div class="flex justify-between text-noir/70">
                    <span>Sous-total HT</span>
                    <span id="recap-ht"><?= number_format($panier['sous_total_ht'], 2, ',', ' ') ?> €</span>
                </div>
                <div class="flex justify-between text-noir/70">
                    <span>TVA (20%)</span>
                    <span id="recap-tva"><?= number_format($panier['tva'], 2, ',', ' ') ?> €</span>
                </div>
                <div class="flex justify-between text-noir/70">
                    <span>Livraison</span>
                    <span><?= $panier['total_ttc'] >= 150 ? 'Offerte' : '6,90 €' ?></span>
                </div>
            </div>

            <div class="flex justify-between font-serif text-lg border-t border-noir/10 pt-4 mb-6">
                <span>Total TTC</span>
                <span id="recap-total"><?= number_format($panier['total_ttc'], 2, ',', ' ') ?> €</span>
            </div>

            <a href="<?= url('commande') ?>" class="block text-center bg-noir text-blanc text-xs tracking-[0.25em] uppercase py-3 hover:bg-bordeaux transition-colors">
                Passer commande
            </a>
            <a href="<?= url('parfums') ?>" class="block text-center text-xs text-noir/50 hover:text-bordeaux transition-colors mt-4">
                Continuer mes achats
            </a>
        </aside>
    </div>
</section>