<?php
$erreurs = \App\Core\Session::get('_erreurs', []);
$ancien  = \App\Core\Session::get('_ancien', []);
\App\Core\Session::supprimer('_erreurs');
\App\Core\Session::supprimer('_ancien');
?>

<section class="max-w-md mx-auto px-6 py-16">
    <div class="text-center mb-10">
        <h1 class="font-serif text-3xl mb-2">Créer un compte</h1>
        <p class="text-sm text-noir/50">Rejoignez la maison Alur Paris</p>
    </div>

    <?php $erreurMsg = \App\Core\Session::flash('erreur'); ?>
    <?php if ($erreurMsg): ?>
        <div class="bg-bordeaux/10 border border-bordeaux/30 text-bordeaux text-sm px-4 py-3 mb-6">
            <?= e($erreurMsg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('inscription') ?>" class="space-y-5">
        <?= \App\Core\Csrf::champ() ?>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Prénom</label>
                <input type="text" name="prenom" value="<?= e($ancien['prenom'] ?? '') ?>"
                       class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
                <?php if (isset($erreurs['prenom'])): ?>
                    <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['prenom']) ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Nom</label>
                <input type="text" name="nom" value="<?= e($ancien['nom'] ?? '') ?>"
                       class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
                <?php if (isset($erreurs['nom'])): ?>
                    <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['nom']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Email</label>
            <input type="email" name="email" value="<?= e($ancien['email'] ?? '') ?>"
                   class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
            <?php if (isset($erreurs['email'])): ?>
                <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['email']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Téléphone <span class="text-noir/30 normal-case">(optionnel)</span></label>
            <input type="tel" name="telephone" value="<?= e($ancien['telephone'] ?? '') ?>"
                   class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Mot de passe</label>
            <input type="password" name="mot_de_passe"
                   class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
            <?php if (isset($erreurs['mot_de_passe'])): ?>
                <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['mot_de_passe']) ?></p>
            <?php else: ?>
                <p class="text-xs text-noir/40 mt-1">Au moins 8 caractères</p>
            <?php endif; ?>
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Confirmer le mot de passe</label>
            <input type="password" name="mot_de_passe_confirmation"
                   class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
            <?php if (isset($erreurs['mot_de_passe_confirmation'])): ?>
                <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['mot_de_passe_confirmation']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label class="flex items-start gap-3 text-xs text-noir/60 leading-relaxed">
                <input type="checkbox" name="a_accepte_cgv" value="1" class="mt-0.5">
                <span>J'accepte les <a href="<?= url('cgv') ?>" class="underline hover:text-bordeaux">conditions générales de vente</a> et la politique de confidentialité.</span>
            </label>
            <?php if (isset($erreurs['a_accepte_cgv'])): ?>
                <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['a_accepte_cgv']) ?></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="w-full bg-noir text-blanc text-xs tracking-[0.25em] uppercase py-4 hover:bg-bordeaux transition-colors">
            Créer mon compte
        </button>
    </form>

    <p class="text-center text-sm text-noir/50 mt-8">
        Déjà un compte ?
        <a href="<?= url('connexion') ?>" class="text-noir underline hover:text-bordeaux">Se connecter</a>
    </p>
</section>