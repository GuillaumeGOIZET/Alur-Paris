<header class="sticky top-0 z-50 bg-blanc border-b border-noir/10">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <!-- Logo -->
        <a href="<?= url('') ?>" class="flex flex-col items-center leading-none">
            <span class="font-serif text-2xl tracking-[0.18em] text-noir">ALUR</span>
            <span class="text-[9px] tracking-[0.4em] text-noir mt-0.5">PARIS</span>
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
            <a href="<?= url('compte') ?>" aria-label="Mon compte" class="hover:text-bordeaux transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </a>
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