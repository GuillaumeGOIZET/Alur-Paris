<footer class="bg-noir text-blanc mt-20">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">

            <div>
                <h4 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">La maison</h4>
                <ul class="space-y-2 text-xs text-blanc/60">
                    <li><a href="<?= url('maison') ?>" class="hover:text-blanc transition-colors">À propos</a></li>
                    <li><a href="<?= url('maison') ?>" class="hover:text-blanc transition-colors">Notre savoir-faire</a></li>
                    <li><a href="<?= url('contact') ?>" class="hover:text-blanc transition-colors">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">Aide</h4>
                <ul class="space-y-2 text-xs text-blanc/60">
                    <li><a href="#" class="hover:text-blanc transition-colors">Livraison</a></li>
                    <li><a href="#" class="hover:text-blanc transition-colors">Retours</a></li>
                    <li><a href="<?= url('cgv') ?>" class="hover:text-blanc transition-colors">CGV</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">Légal</h4>
                <ul class="space-y-2 text-xs text-blanc/60">
                    <li><a href="<?= url('mentions-legales') ?>" class="hover:text-blanc transition-colors">Mentions légales</a></li>
                    <li><a href="<?= url('politique-confidentialite') ?>" class="hover:text-blanc transition-colors">Confidentialité</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">Paiement</h4>
                <ul class="space-y-2 text-xs text-blanc/60">
                    <li>Visa · Mastercard</li>
                    <li>Paiement sécurisé SSL</li>
                    <li>3D Secure</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-blanc/10 pt-6 text-center text-[10px] tracking-[0.15em] uppercase text-blanc/40">
            © <?= date('Y') ?> Alur Paris — Tous droits réservés
        </div>
    </div>
</footer>