<header class="sticky top-0 z-50 bg-blanc border-b border-noir/10">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <!-- Logo -->
        <a href="<?= url('') ?>" class="flex flex-col items-center leading-none">
            <img src="<?= url('assets/images/Logo.png') ?>" alt="Alur Paris" class="h-7 w-auto">
        </a>

        <!-- Navigation centrale -->
        <nav class="hidden md:flex gap-8 text-xs tracking-[0.15em] uppercase">
            <a href="<?= url('parfums') ?>" class="hover:text-bordeaux transition-colors">Parfums</a>
            <a href="<?= url('maison') ?>" class="hover:text-bordeaux transition-colors">Maison</a>
            <a href="<?= url('contact') ?>" class="hover:text-bordeaux transition-colors">Contact</a>
        </nav>

        <!-- Icônes -->
        <div class="flex items-center gap-5 text-noir">
            <a href="<?= url('parfums') ?>" aria-label="Rechercher" class="hover:text-bordeaux transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </a>
            <?php if (\App\Core\Auth::estConnecte()): ?>
                <?php $u = \App\Core\Auth::utilisateur(); ?>
                <div class="relative group">
                    <a href="<?= url('compte') ?>" aria-label="Mon compte"
                        class="flex items-center gap-1 hover:text-bordeaux transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </a>
                    <!-- Menu déroulant -->
                    <div
                        class="absolute right-0 top-full mt-2 w-48 bg-blanc border border-noir/10 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                        <div class="px-4 py-3 border-b border-noir/5">
                            <p class="text-xs text-noir/40">Connecté en tant que</p>
                            <p class="text-sm font-medium truncate"><?= e($u['prenom']) ?>     <?= e($u['nom']) ?></p>
                        </div>
                        <a href="<?= url('compte') ?>" class="block px-4 py-2 text-sm hover:bg-sable transition-colors">Mon
                            compte</a>
                        <?php if (\App\Core\Auth::estAdmin()): ?>
                            <a href="<?= url('admin') ?>"
                                class="block px-4 py-2 text-sm hover:bg-sable transition-colors">Administration</a>
                        <?php endif; ?>
                        <a href="<?= url('deconnexion') ?>"
                            class="block px-4 py-2 text-sm text-bordeaux hover:bg-sable transition-colors">Déconnexion</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= url('connexion') ?>" aria-label="Connexion" class="hover:text-bordeaux transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </a>
                <a href="<?= url('favoris') ?>" aria-label="Mes favoris"
                    class="relative hover:text-bordeaux transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    <?php if (\App\Services\FavorisService::nombre() > 0): ?>
                        <span id="badge-favoris"
                            class="absolute -top-2 -right-2 w-4 h-4 bg-bordeaux text-blanc rounded-full text-[9px] flex items-center justify-center">
                            <?= \App\Services\FavorisService::nombre() ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            <a href="<?= url('panier') ?>" aria-label="Mon panier"
                class="relative hover:text-bordeaux transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <!-- Badge nombre d'articles (à dynamiser plus tard) -->
                <span id="badge-panier"
                    class="absolute -top-2 -right-2 w-4 h-4 bg-bordeaux text-blanc rounded-full text-[9px] flex items-center justify-center"><?= \App\Services\PanierService::nombreArticles() ?></span>
            </a>
        </div>
    </div>
</header>