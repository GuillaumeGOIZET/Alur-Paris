<?php
$ancien = \App\Core\Session::get('_ancien', []);
\App\Core\Session::supprimer('_ancien');
?>

<section class="max-w-md mx-auto px-6 py-16">
    <div class="text-center mb-10">
        <h1 class="font-serif text-3xl mb-2">Connexion</h1>
        <p class="text-sm text-noir/50">Accédez à votre espace personnel</p>
    </div>

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

    <form method="POST" action="<?= url('connexion') ?>" class="space-y-5">
        <?= \App\Core\Csrf::champ() ?>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Email</label>
            <input type="email" name="email" value="<?= e($ancien['email'] ?? '') ?>"
                   class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Mot de passe</label>
            <input type="password" name="mot_de_passe"
                   class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
        </div>

        <button type="submit" class="w-full bg-noir text-blanc text-xs tracking-[0.25em] uppercase py-4 hover:bg-bordeaux transition-colors">
            Se connecter
        </button>
    </form>

    <p class="text-center text-sm text-noir/50 mt-8">
        Pas encore de compte ?
        <a href="<?= url('inscription') ?>" class="text-noir underline hover:text-bordeaux">Créer un compte</a>
    </p>
</section>