<div class="px-8 py-8">
    <h1 class="font-serif text-2xl mb-8">Clients</h1>

    <div class="bg-blanc border border-noir/5">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs tracking-[0.1em] uppercase text-noir/40 border-b border-noir/5">
                    <th class="px-6 py-3 font-normal">Nom</th>
                    <th class="px-6 py-3 font-normal">Email</th>
                    <th class="px-6 py-3 font-normal">Téléphone</th>
                    <th class="px-6 py-3 font-normal">Commandes</th>
                    <th class="px-6 py-3 font-normal">Inscrit le</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clients)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-noir/50">Aucun client inscrit.</td></tr>
                <?php else: ?>
                    <?php foreach ($clients as $client): ?>
                        <tr class="border-b border-noir/5 last:border-0 hover:bg-sable/50 transition-colors">
                            <td class="px-6 py-4 font-medium"><?= e($client['prenom']) ?> <?= e($client['nom']) ?></td>
                            <td class="px-6 py-4 text-noir/70"><?= e($client['email']) ?></td>
                            <td class="px-6 py-4 text-noir/70"><?= e($client['telephone'] ?? '—') ?></td>
                            <td class="px-6 py-4"><?= (int)$client['nb_commandes'] ?></td>
                            <td class="px-6 py-4 text-noir/50"><?= date('d/m/Y', strtotime($client['cree_le'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>