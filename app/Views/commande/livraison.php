<div class="border-b border-noir/5">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs tracking-[0.1em] uppercase text-noir/40">
        <a href="<?= url('panier') ?>" class="hover:text-bordeaux">Panier</a>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir">Livraison</span>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir/30">Paiement</span>
    </div>
</div>

<section class="max-w-5xl mx-auto px-6 py-12 grid md:grid-cols-[1fr_320px] gap-10">

    <!-- Formulaire -->
    <div>
        <h1 class="font-serif text-2xl mb-8">Adresse de livraison</h1>

        <form method="POST" action="<?= url('commande/livraison') ?>" class="space-y-5">
            <?= \App\Core\Csrf::champ() ?>

            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Email</label>
                <input type="email" name="email"
                       value="<?= e($donnees['email'] ?? $utilisateur['email'] ?? '') ?>"
                       class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
                <?php if (isset($erreurs['email'])): ?>
                    <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Prénom</label>
                    <input type="text" name="prenom"
                           value="<?= e($donnees['prenom'] ?? $utilisateur['prenom'] ?? '') ?>"
                           class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
                    <?php if (isset($erreurs['prenom'])): ?>
                        <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['prenom']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Nom</label>
                    <input type="text" name="nom"
                           value="<?= e($donnees['nom'] ?? $utilisateur['nom'] ?? '') ?>"
                           class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
                    <?php if (isset($erreurs['nom'])): ?>
                        <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['nom']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Adresse</label>
                <input type="text" name="adresse" value="<?= e($donnees['adresse'] ?? '') ?>"
                       class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
                <?php if (isset($erreurs['adresse'])): ?>
                    <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['adresse']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Complément d'adresse <span class="text-noir/30 normal-case">(optionnel)</span></label>
                <input type="text" name="adresse_complement" value="<?= e($donnees['adresse_complement'] ?? '') ?>"
                       class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Code postal</label>
                    <input type="text" name="code_postal" value="<?= e($donnees['code_postal'] ?? '') ?>"
                           class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
                    <?php if (isset($erreurs['code_postal'])): ?>
                        <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['code_postal']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Ville</label>
                    <input type="text" name="ville" value="<?= e($donnees['ville'] ?? '') ?>"
                           class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
                    <?php if (isset($erreurs['ville'])): ?>
                        <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['ville']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Téléphone <span class="text-noir/30 normal-case">(optionnel)</span></label>
                <input type="tel" name="telephone" value="<?= e($donnees['telephone'] ?? '') ?>"
                       class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
            </div>

            <div class="flex gap-4 pt-4">
                <a href="<?= url('panier') ?>" class="px-6 py-3 border border-noir/20 text-xs tracking-[0.15em] uppercase hover:border-noir transition-colors">
                    Retour
                </a>
                <button type="submit" class="flex-1 bg-noir text-blanc text-xs tracking-[0.25em] uppercase py-3 hover:bg-bordeaux transition-colors">
                    Continuer vers le paiement
                </button>
            </div>
        </form>
    </div>

    <!-- Récap panier (rappel) -->
    <aside class="bg-sable p-6 h-fit">
        <h2 class="font-serif text-sm tracking-[0.15em] uppercase border-b border-noir/10 pb-3 mb-4">Votre commande</h2>
        <div class="space-y-3 mb-4 text-sm">
            <?php foreach ($panier['lignes'] as $ligne): ?>
                <div class="flex justify-between">
                    <span class="text-noir/70"><?= e($ligne['nom']) ?> <span class="text-noir/40">×<?= $ligne['quantite'] ?></span></span>
                    <span><?= number_format($ligne['sous_total_ttc'], 2, ',', ' ') ?> €</span>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-between font-serif text-lg border-t border-noir/10 pt-4">
            <span>Total TTC</span>
            <span><?= number_format($panier['total_ttc'], 2, ',', ' ') ?> €</span>
        </div>
    </aside>
</section>