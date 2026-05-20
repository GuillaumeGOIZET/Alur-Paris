<div id="banniere-cookies" class="fixed bottom-0 left-0 right-0 z-[60] bg-noir text-blanc px-6 py-5 hidden">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-4 justify-between">
        <p class="text-xs text-blanc/70 leading-relaxed max-w-2xl">
            Ce site utilise des cookies strictement nécessaires à son fonctionnement (session, panier).
            En poursuivant votre navigation, vous acceptez notre
            <a href="<?= url('politique-confidentialite') ?>" class="underline hover:text-blanc">politique de confidentialité</a>.
        </p>
        <div class="flex gap-3 shrink-0">
            <button type="button" id="cookies-refuser" class="px-5 py-2 border border-blanc/30 text-blanc text-[10px] tracking-[0.2em] uppercase hover:border-blanc transition-colors">
                Refuser
            </button>
            <button type="button" id="cookies-accepter" class="px-5 py-2 bg-blanc text-noir text-[10px] tracking-[0.2em] uppercase hover:bg-bordeaux hover:text-blanc transition-colors">
                Accepter
            </button>
        </div>
    </div>
</div>