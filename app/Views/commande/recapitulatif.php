<div class="border-b border-noir/5">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs tracking-[0.1em] uppercase text-noir/40">
        <a href="<?= url('panier') ?>" class="hover:text-bordeaux">Panier</a>
        <span class="mx-2 text-noir/20">/</span>
        <a href="<?= url('commande/livraison') ?>" class="hover:text-bordeaux">Livraison</a>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir">Paiement</span>
    </div>
</div>

<section class="max-w-5xl mx-auto px-6 py-12 grid md:grid-cols-[1fr_320px] gap-10">

    <div>
        <h1 class="font-serif text-2xl mb-8">Vérifiez votre commande</h1>

        <!-- Adresse de livraison -->
        <div class="border border-noir/10 p-6 mb-6">
            <div class="flex justify-between items-start mb-3">
                <h2 class="font-serif text-sm tracking-[0.15em] uppercase">Livraison</h2>
                <a href="<?= url('commande/livraison') ?>" class="text-xs text-bordeaux hover:underline">Modifier</a>
            </div>
            <p class="text-sm text-noir/70 leading-relaxed">
                <?= e($livraison['prenom']) ?> <?= e($livraison['nom']) ?><br>
                <?= e($livraison['adresse']) ?><br>
                <?php if (!empty($livraison['adresse_complement'])): ?>
                    <?= e($livraison['adresse_complement']) ?><br>
                <?php endif; ?>
                <?= e($livraison['code_postal']) ?> <?= e($livraison['ville']) ?><br>
                <?= e($livraison['email']) ?>
                <?php if (!empty($livraison['telephone'])): ?>
                    · <?= e($livraison['telephone']) ?>
                <?php endif; ?>
            </p>
        </div>

        <!-- Articles -->
        <div class="border border-noir/10 p-6">
            <h2 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">Articles</h2>
            <?php foreach ($panier['lignes'] as $ligne): ?>
                <div class="flex justify-between py-2 text-sm border-b border-noir/5 last:border-0">
                    <span class="text-noir/70"><?= e($ligne['nom']) ?> <span class="text-noir/40">×<?= $ligne['quantite'] ?></span></span>
                    <span><?= number_format($ligne['sous_total_ttc'], 2, ',', ' ') ?> €</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Récap + bouton paiement -->
    <aside class="bg-sable p-6 h-fit">
        <h2 class="font-serif text-sm tracking-[0.15em] uppercase border-b border-noir/10 pb-3 mb-4">Total</h2>
        <div class="space-y-2 text-sm mb-4">
            <div class="flex justify-between text-noir/70">
                <span>Sous-total HT</span>
                <span><?= number_format($panier['sous_total_ht'], 2, ',', ' ') ?> €</span>
            </div>
            <div class="flex justify-between text-noir/70">
                <span>TVA (20%)</span>
                <span><?= number_format($panier['tva'], 2, ',', ' ') ?> €</span>
            </div>
            <div class="flex justify-between text-noir/70">
                <span>Livraison</span>
                <span><?= $panier['total_ttc'] >= 150 ? 'Offerte' : '6,90 €' ?></span>
            </div>
        </div>
        <div class="flex justify-between font-serif text-lg border-t border-noir/10 pt-4 mb-6">
            <span>Total TTC</span>
            <span><?= number_format($panier['total_ttc'], 2, ',', ' ') ?> €</span>
        </div>

        <!-- Bouton paiement (Stripe sera branché ici demain) -->
        <button type="button" disabled class="w-full bg-noir/20 text-blanc text-xs tracking-[0.25em] uppercase py-4 cursor-not-allowed">
            Paiement (à venir)
        </button>
        <p class="text-[10px] text-noir/40 text-center mt-3 leading-relaxed">
            Le paiement sécurisé Stripe sera intégré à l'étape suivante.
        </p>
    </aside>
</section>