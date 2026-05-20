<div class="border-b border-noir/5">
    <div class="max-w-7xl mx-auto px-6 py-4 text-xs tracking-[0.1em] uppercase text-noir/40">
        <a href="<?= url('') ?>" class="hover:text-bordeaux">Accueil</a>
        <span class="mx-2 text-noir/20">/</span>
        <span class="text-noir">Contact</span>
    </div>
</div>

<section class="max-w-2xl mx-auto px-6 py-16">
    <div class="text-center mb-10">
        <h1 class="font-serif text-3xl mb-2">Contact</h1>
        <p class="text-sm text-noir/50">Une question ? Nous sommes à votre écoute.</p>
    </div>

    <form method="POST" action="<?= url('contact') ?>" class="space-y-5">
        <?= \App\Core\Csrf::champ() ?>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Nom</label>
                <input type="text" name="nom" class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Email</label>
                <input type="email" name="email" class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
            </div>
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Sujet</label>
            <input type="text" name="sujet" class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors">
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Message</label>
            <textarea name="message" rows="6" class="w-full border border-noir/20 px-4 py-3 text-sm focus:outline-none focus:border-noir transition-colors"></textarea>
        </div>

        <button type="submit" class="w-full bg-noir text-blanc text-xs tracking-[0.25em] uppercase py-4 hover:bg-bordeaux transition-colors">
            Envoyer le message
        </button>
    </form>

    <div class="text-center mt-10 text-sm text-noir/50">
        <p>Ou écrivez-nous directement à <a href="mailto:contact@alur.paris" class="text-noir underline hover:text-bordeaux">contact@alur.paris</a></p>
    </div>
</section>