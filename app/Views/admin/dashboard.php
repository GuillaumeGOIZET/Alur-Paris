<div class="px-8 py-8">
    <h1 class="font-serif text-2xl mb-8">Tableau de bord</h1>

    <!-- KPIs -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-blanc border border-noir/5 p-6">
            <p class="text-xs tracking-[0.15em] uppercase text-noir/40 mb-2">Chiffre d'affaires</p>
            <p class="font-serif text-3xl"><?= number_format($stats['ca'], 2, ',', ' ') ?> €</p>
        </div>
        <div class="bg-blanc border border-noir/5 p-6">
            <p class="text-xs tracking-[0.15em] uppercase text-noir/40 mb-2">Commandes</p>
            <p class="font-serif text-3xl"><?= $stats['nb_commandes'] ?></p>
        </div>
        <div class="bg-blanc border border-noir/5 p-6">
            <p class="text-xs tracking-[0.15em] uppercase text-noir/40 mb-2">Panier moyen</p>
            <p class="font-serif text-3xl"><?= number_format($stats['panier_moyen'], 2, ',', ' ') ?> €</p>
        </div>
        <div class="bg-blanc border border-noir/5 p-6">
            <p class="text-xs tracking-[0.15em] uppercase text-noir/40 mb-2">Produits actifs</p>
            <p class="font-serif text-3xl"><?= $nbProduits ?></p>
        </div>
    </div>

    <!-- Raccourcis -->
    <div class="grid grid-cols-2 gap-6 mb-10">
        <a href="<?= url('admin/clients') ?>" class="bg-blanc border border-noir/5 p-6 hover:border-bordeaux transition-colors">
            <p class="text-xs tracking-[0.15em] uppercase text-noir/40 mb-2">Clients inscrits</p>
            <p class="font-serif text-2xl"><?= $nbClients ?></p>
        </a>
        <a href="<?= url('admin/messages') ?>" class="bg-blanc border border-noir/5 p-6 hover:border-bordeaux transition-colors">
            <p class="text-xs tracking-[0.15em] uppercase text-noir/40 mb-2">Messages non traités</p>
            <p class="font-serif text-2xl <?= $nbMessages > 0 ? 'text-bordeaux' : '' ?>"><?= $nbMessages ?></p>
        </a>
    </div>

    <!-- Commandes récentes -->
    <div class="bg-blanc border border-noir/5">
        <div class="px-6 py-4 border-b border-noir/5 flex justify-between items-center">
            <h2 class="font-serif text-lg">Dernières commandes</h2>
            <a href="<?= url('admin/commandes') ?>" class="text-xs text-bordeaux hover:underline">Tout voir</a>
        </div>
        <?php if (empty($stats['recentes'])): ?>
            <p class="px-6 py-8 text-sm text-noir/50 text-center">Aucune commande pour le moment.</p>
        <?php else: ?>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs tracking-[0.1em] uppercase text-noir/40 border-b border-noir/5">
                        <th class="px-6 py-3 font-normal">Numéro</th>
                        <th class="px-6 py-3 font-normal">Client</th>
                        <th class="px-6 py-3 font-normal">Montant</th>
                        <th class="px-6 py-3 font-normal">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['recentes'] as $cmd): ?>
                        <tr class="border-b border-noir/5 last:border-0 hover:bg-sable/50 transition-colors">
                            <td class="px-6 py-4 font-medium"><?= e($cmd['numero_commande']) ?></td>
                            <td class="px-6 py-4 text-noir/70"><?= e($cmd['livraison_prenom']) ?> <?= e($cmd['livraison_nom']) ?></td>
                            <td class="px-6 py-4"><?= number_format((float)$cmd['montant_total_ttc'], 2, ',', ' ') ?> €</td>
                            <td class="px-6 py-4 text-noir/50"><?= date('d/m/Y', strtotime($cmd['cree_le'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>