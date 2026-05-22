<section class="max-w-4xl mx-auto px-6 py-16">
    <h1 class="font-serif text-3xl mb-2">Mon compte</h1>
    <p class="text-sm text-noir/50 mb-10">Bonjour <?= e($utilisateur['prenom']) ?>, bienvenue dans votre espace.</p>

    <?php if ($succesMsg = \App\Core\Session::flash('succes')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 mb-6">
            <?= e($succesMsg) ?>
        </div>
    <?php endif; ?>
    <?php if ($erreurMsg = \App\Core\Session::flash('erreur')): ?>
        <div class="bg-bordeaux/10 border border-bordeaux/30 text-bordeaux text-sm px-4 py-3 mb-6">
            <?= e($erreurMsg) ?>
        </div>
    <?php endif; ?>

    <div class="grid md:grid-cols-2 gap-8">

        <!-- Mes informations (modifiables) -->
        <div class="bg-sable p-8">
            <h2 class="font-serif text-sm tracking-[0.2em] uppercase border-b border-noir/10 pb-3 mb-5">Mes informations</h2>

            <form method="POST" action="<?= url('compte/modifier') ?>" class="space-y-4">
                <?= \App\Core\Csrf::champ() ?>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs tracking-[0.1em] uppercase text-noir/50 mb-2">Prénom</label>
                        <input type="text" name="prenom" value="<?= e($ancien['prenom'] ?? $utilisateur['prenom']) ?>" class="w-full border border-noir/20 px-4 py-2.5 text-sm bg-blanc focus:outline-none focus:border-noir">
                        <?php if (isset($erreurs['prenom'])): ?><p class="text-xs text-bordeaux mt-1"><?= e($erreurs['prenom']) ?></p><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.1em] uppercase text-noir/50 mb-2">Nom</label>
                        <input type="text" name="nom" value="<?= e($ancien['nom'] ?? $utilisateur['nom']) ?>" class="w-full border border-noir/20 px-4 py-2.5 text-sm bg-blanc focus:outline-none focus:border-noir">
                        <?php if (isset($erreurs['nom'])): ?><p class="text-xs text-bordeaux mt-1"><?= e($erreurs['nom']) ?></p><?php endif; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs tracking-[0.1em] uppercase text-noir/50 mb-2">Email</label>
                    <input type="email" value="<?= e($utilisateur['email']) ?>" disabled class="w-full border border-noir/10 px-4 py-2.5 text-sm bg-noir/5 text-noir/50 cursor-not-allowed">
                    <p class="text-[10px] text-noir/40 mt-1">L'email ne peut pas être modifié.</p>
                </div>

                <div>
                    <label class="block text-xs tracking-[0.1em] uppercase text-noir/50 mb-2">Téléphone</label>
                    <input type="tel" name="telephone" value="<?= e($ancien['telephone'] ?? $utilisateur['telephone'] ?? '') ?>" class="w-full border border-noir/20 px-4 py-2.5 text-sm bg-blanc focus:outline-none focus:border-noir">
                </div>

                <button type="submit" class="w-full bg-noir text-blanc text-xs tracking-[0.2em] uppercase py-3 hover:bg-bordeaux transition-colors">
                    Enregistrer
                </button>
            </form>
        </div>

        <!-- Historique des commandes -->
        <div>
            <h2 class="font-serif text-sm tracking-[0.2em] uppercase border-b border-noir/10 pb-3 mb-5">Mes commandes</h2>

            <?php if (empty($commandes)): ?>
                <div class="bg-sable p-8 text-center">
                    <p class="text-sm text-noir/50 mb-4">Vous n'avez pas encore passé de commande.</p>
                    <a href="<?= url('parfums') ?>" class="text-xs text-bordeaux hover:underline tracking-[0.1em] uppercase">Découvrir la collection</a>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($commandes as $cmd): ?>
                        <div class="bg-sable p-5">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-sm"><?= e($cmd['numero_commande']) ?></span>
                                <span class="inline-block text-[10px] tracking-[0.1em] uppercase px-2 py-1 text-blanc" style="background-color: <?= e($cmd['couleur_badge'] ?? '#6B7280') ?>">
                                    <?= e($cmd['statut_libelle']) ?>
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-noir/60">
                                <span><?= date('d/m/Y', strtotime($cmd['cree_le'])) ?></span>
                                <span class="font-medium text-noir"><?= number_format((float)$cmd['montant_total_ttc'], 2, ',', ' ') ?> €</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-10 pt-6 border-t border-noir/5">
        <a href="<?= url('deconnexion') ?>" class="text-sm text-bordeaux hover:underline">Se déconnecter</a>
    </div>
</section>