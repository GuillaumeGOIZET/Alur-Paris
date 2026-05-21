<section class="max-w-2xl mx-auto px-6 py-20 text-center">
    <div class="w-16 h-16 bg-bordeaux/10 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-bordeaux" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
    </div>

    <h1 class="font-serif text-3xl mb-4">Merci pour votre commande !</h1>
    <p class="text-sm text-noir/60 leading-relaxed mb-2">
        Votre paiement a été accepté et votre commande est confirmée.
    </p>
    <p class="text-sm text-noir/60 mb-8">
        Numéro de commande : <strong class="text-noir"><?= e($commande['numero_commande']) ?></strong>
    </p>

    <div class="bg-sable p-6 text-left mb-8">
        <h2 class="font-serif text-sm tracking-[0.15em] uppercase border-b border-noir/10 pb-3 mb-4">Récapitulatif</h2>
        <?php foreach ($commande['lignes'] as $ligne): ?>
            <div class="flex justify-between py-2 text-sm">
                <span class="text-noir/70"><?= e($ligne['nom_produit']) ?> <span class="text-noir/40">×<?= $ligne['quantite'] ?></span></span>
                <span><?= number_format((float)$ligne['sous_total_ttc'], 2, ',', ' ') ?> €</span>
            </div>
        <?php endforeach; ?>
        <div class="flex justify-between font-serif text-lg border-t border-noir/10 pt-4 mt-2">
            <span>Total TTC</span>
            <span><?= number_format((float)$commande['montant_total_ttc'], 2, ',', ' ') ?> €</span>
        </div>
    </div>

    <p class="text-xs text-noir/50 mb-8">
        Un email de confirmation a été envoyé à <?= e($commande['email_contact']) ?>.
    </p>

    <a href="<?= url('parfums') ?>" class="inline-block px-8 py-3 bg-noir text-blanc text-xs tracking-[0.25em] uppercase hover:bg-bordeaux transition-colors">
        Continuer mes achats
    </a>
</section>