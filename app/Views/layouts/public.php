<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? e($titre) . ' — Alur Paris' : 'Alur Paris — Parfumerie de niche' ?></title>
    <meta name="description"
        content="<?= isset($metaDescription) ? e($metaDescription) : 'Alur Paris, maison de parfumerie de niche.' ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= url('assets/css/fonts.css') ?>">
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
                        serif: ['Griffon', '"Playfair Display"', 'serif'],
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans text-noir bg-blanc antialiased">

    <body class="font-sans text-noir bg-blanc antialiased">

        <!-- Bandeau promotionnel -->
        <div class="bg-bordeaux text-blanc text-center py-2 px-4">
            <p class="text-[11px] tracking-[0.15em] uppercase">
                Livraison offerte dès 150 € d'achat · échantillon offert avec votre commande · Retours gratuits
            </p>
        </div>

        <?php require __DIR__ . '/../partials/header.php'; ?>

        <main>
            <?php if ($flashSucces = \App\Core\Session::flash('succes')): ?>
                <div class="bg-green-50 border-b border-green-200 text-green-800 text-sm text-center py-3 px-6">
                    <?= e($flashSucces) ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>

        <?php require __DIR__ . '/../partials/footer.php'; ?>

        <!-- Toast de notification (caché par défaut) -->
        <div id="toast"
            class="fixed bottom-6 right-6 bg-noir text-blanc text-sm px-6 py-3 opacity-0 pointer-events-none transition-opacity duration-300 z-50">
        </div>

        <?php require __DIR__ . '/../partials/cookie-banner.php'; ?>

        <script>
            window.ALUR_BASE_URL = '<?= APP_URL ?>';
        </script>
        <script src="<?= url('assets/js/panier.js') ?>"></script>
        <script src="<?= url('assets/js/favoris.js') ?>"></script>
        <script src="<?= url('assets/js/cookie-banner.js') ?>"></script>

    </body>

</html>