<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? e($titre) . ' — Admin Alur Paris' : 'Administration — Alur Paris' ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bordeaux: '#681223',
                        noir: '#1f1f1f',
                        blanc: '#fffefe',
                        sable: '#f9f6f1',
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans text-noir bg-sable antialiased">

    <div class="flex min-h-screen">

        <!-- Barre latérale -->
        <aside class="w-64 bg-noir text-blanc flex flex-col shrink-0">
            <div class="px-6 py-6 border-b border-blanc/10">
                <a href="<?= url('admin') ?>" class="flex flex-col leading-none">
                    <span class="font-serif text-xl tracking-[0.18em]">ALUR</span>
                    <span class="text-[8px] tracking-[0.4em] text-blanc/50">ADMINISTRATION</span>
                </a>
            </div>

            <nav class="flex-1 py-6">
                <?php
                $menuAdmin = [
                    'admin'           => ['Tableau de bord', 'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5'],
                    'admin/produits'  => ['Produits', 'm21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9'],
                    'admin/commandes' => ['Commandes', 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z'],
                    'admin/clients'   => ['Clients', 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'],
                    'admin/messages'  => ['Messages', 'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75'],
                ];
                $uriCourante = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
                $uriCourante = str_replace('alur-paris/public/', '', $uriCourante);

                foreach ($menuAdmin as $lien => $info):
                    $actif = ($uriCourante === $lien);
                ?>
                    <a href="<?= url($lien) ?>" class="flex items-center gap-3 px-6 py-3 text-sm transition-colors <?= $actif ? 'bg-bordeaux text-blanc' : 'text-blanc/70 hover:bg-blanc/5 hover:text-blanc' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="<?= $info[1] ?>" />
                        </svg>
                        <?= $info[0] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="px-6 py-4 border-t border-blanc/10">
                <a href="<?= url('') ?>" class="block text-xs text-blanc/50 hover:text-blanc transition-colors mb-2">← Voir le site</a>
                <a href="<?= url('deconnexion') ?>" class="block text-xs text-bordeaux hover:text-blanc transition-colors">Déconnexion</a>
            </div>
        </aside>

        <!-- Contenu principal -->
        <main class="flex-1 overflow-x-auto">
            <?php if ($flashSucces = \App\Core\Session::flash('succes')): ?>
                <div class="bg-green-50 border-b border-green-200 text-green-800 text-sm px-8 py-3">
                    <?= e($flashSucces) ?>
                </div>
            <?php endif; ?>
            <?php if ($flashErreur = \App\Core\Session::flash('erreur')): ?>
                <div class="bg-bordeaux/10 border-b border-bordeaux/30 text-bordeaux text-sm px-8 py-3">
                    <?= e($flashErreur) ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>

</body>
</html>