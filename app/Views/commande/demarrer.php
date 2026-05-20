<div class="border-b border-noir/5">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs tracking-[0.1em] uppercase text-noir/40">
        <a href="<?= url('panier') ?>" class="hover:text-bordeaux">Panier</a>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir">Commander</span>
    </div>
</div>

<section class="max-w-2xl mx-auto px-6 py-16">
    <h1 class="font-serif text-3xl mb-10 text-center">Comment souhaitez-vous commander ?</h1>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Connexion -->
        <div class="border border-noir/15 p-8 text-center">
            <h2 class="font-serif text-xl mb-3">J'ai un compte</h2>
            <p class="text-sm text-noir/50 mb-6 leading-relaxed">Connectez-vous pour retrouver vos informations et suivre vos commandes.</p>
            <a href="<?= url('connexion') ?>" class="inline-block w-full bg-noir text-blanc text-xs tracking-[0.25em] uppercase py-3 hover:bg-bordeaux transition-colors">
                Se connecter
            </a>
        </div>

        <!-- Invité -->
        <div class="border border-noir/15 p-8 text-center">
            <h2 class="font-serif text-xl mb-3">Commander en invité</h2>
            <p class="text-sm text-noir/50 mb-6 leading-relaxed">Pas besoin de compte. Renseignez simplement vos coordonnées de livraison.</p>
            <a href="<?= url('commande/livraison') ?>" class="inline-block w-full border border-noir text-noir text-xs tracking-[0.25em] uppercase py-3 hover:bg-noir hover:text-blanc transition-colors">
                Continuer en invité
            </a>
        </div>
    </div>

    <p class="text-center text-sm text-noir/40 mt-8">
        <a href="<?= url('panier') ?>" class="hover:text-bordeaux">← Retour au panier</a>
    </p>
</section>