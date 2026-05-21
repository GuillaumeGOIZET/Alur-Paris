<div class="px-8 py-8">
    <h1 class="font-serif text-2xl mb-8">Commandes</h1>

    <div class="bg-blanc border border-noir/5">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs tracking-[0.1em] uppercase text-noir/40 border-b border-noir/5">
                    <th class="px-6 py-3 font-normal">Numéro</th>
                    <th class="px-6 py-3 font-normal">Client</th>
                    <th class="px-6 py-3 font-normal">Montant</th>
                    <th class="px-6 py-3 font-normal">Statut</th>
                    <th class="px-6 py-3 font-normal">Date</th>
                    <th class="px-6 py-3 font-normal text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($commandes)): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-noir/50">Aucune commande pour le moment.</td></tr>
                <?php else: ?>
                    <?php foreach ($commandes as $cmd): ?>
                        <tr class="border-b border-noir/5 last:border-0 hover:bg-sable/50 transition-colors">
                            <td class="px-6 py-4 font-medium"><?= e($cmd['numero_commande']) ?></td>
                            <td class="px-6 py-4 text-noir/70"><?= e($cmd['livraison_prenom']) ?> <?= e($cmd['livraison_nom']) ?></td>
                            <td class="px-6 py-4"><?= number_format((float)$cmd['montant_total_ttc'], 2, ',', ' ') ?> €</td>
                            <td class="px-6 py-4">
                                <span class="inline-block text-[10px] tracking-[0.1em] uppercase px-2 py-1 text-blanc" style="background-color: <?= e($cmd['couleur_badge'] ?? '#6B7280') ?>">
                                    <?= e($cmd['statut_libelle']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-noir/50"><?= date('d/m/Y H:i', strtotime($cmd['cree_le'])) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?= url('admin/commandes/' . $cmd['id']) ?>" class="text-xs text-bordeaux hover:underline">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>