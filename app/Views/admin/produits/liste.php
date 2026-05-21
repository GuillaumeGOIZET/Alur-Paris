<div class="px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="font-serif text-2xl">Produits</h1>
        <a href="<?= url('admin/produits/nouveau') ?>" class="bg-noir text-blanc text-xs tracking-[0.2em] uppercase px-5 py-3 hover:bg-bordeaux transition-colors">
            + Nouveau produit
        </a>
    </div>

    <div class="bg-blanc border border-noir/5">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs tracking-[0.1em] uppercase text-noir/40 border-b border-noir/5">
                    <th class="px-6 py-3 font-normal">Nom</th>
                    <th class="px-6 py-3 font-normal">Catégorie</th>
                    <th class="px-6 py-3 font-normal">Prix</th>
                    <th class="px-6 py-3 font-normal">Stock</th>
                    <th class="px-6 py-3 font-normal">Statut</th>
                    <th class="px-6 py-3 font-normal text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produits)): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-noir/50">Aucun produit.</td></tr>
                <?php else: ?>
                    <?php foreach ($produits as $produit): ?>
                        <tr class="border-b border-noir/5 last:border-0 hover:bg-sable/50 transition-colors">
                            <td class="px-6 py-4 font-medium"><?= e($produit['nom']) ?></td>
                            <td class="px-6 py-4 text-noir/70"><?= e($produit['categorie_nom']) ?></td>
                            <td class="px-6 py-4"><?= number_format((float)$produit['prix_ttc'], 2, ',', ' ') ?> €</td>
                            <td class="px-6 py-4">
                                <?php $stock = (int)$produit['stock']; ?>
                                <span class="<?= $stock === 0 ? 'text-bordeaux' : ($stock < 5 ? 'text-amber-600' : 'text-noir/70') ?>">
                                    <?= $stock ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ((int)$produit['est_publie'] === 1): ?>
                                    <span class="inline-block text-[10px] tracking-[0.1em] uppercase bg-green-100 text-green-800 px-2 py-1">Publié</span>
                                <?php else: ?>
                                    <span class="inline-block text-[10px] tracking-[0.1em] uppercase bg-noir/10 text-noir/60 px-2 py-1">Brouillon</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?= url('admin/produits/' . $produit['id'] . '/editer') ?>" class="text-xs text-bordeaux hover:underline">Modifier</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>