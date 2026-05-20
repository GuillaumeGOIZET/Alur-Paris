<section class="max-w-3xl mx-auto px-6 py-16">
    <h1 class="font-serif text-3xl mb-2">Mon compte</h1>
    <p class="text-sm text-noir/50 mb-10">Bonjour <?= e($utilisateur['prenom']) ?>, bienvenue dans votre espace.</p>

    <div class="bg-sable p-8">
        <h2 class="font-serif text-sm tracking-[0.2em] uppercase border-b border-noir/10 pb-3 mb-4">Mes informations</h2>
        <div class="space-y-2 text-sm">
            <p><span class="text-noir/40">Nom :</span> <?= e($utilisateur['prenom']) ?> <?= e($utilisateur['nom']) ?></p>
            <p><span class="text-noir/40">Email :</span> <?= e($utilisateur['email']) ?></p>
        </div>
    </div>

    <div class="mt-8">
        <a href="<?= url('deconnexion') ?>" class="text-sm text-bordeaux hover:underline">Se déconnecter</a>
    </div>
</section>