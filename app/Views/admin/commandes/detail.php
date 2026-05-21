<div class="px-8 py-8 max-w-4xl">
    <div class="flex items-center gap-3 mb-8">
        <a href="<?= url('admin/commandes') ?>" class="text-noir/40 hover:text-bordeaux">←</a>
        <h1 class="font-serif text-2xl">Commande <?= e($commande['numero_commande']) ?></h1>
        <span class="inline-block text-[10px] tracking-[0.1em] uppercase px-2 py-1 text-blanc" style="background-color: <?= e($commande['couleur_badge'] ?? '#6B7280') ?>">
            <?= e($commande['statut_libelle']) ?>
        </span>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        <!-- Colonne principale : articles -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-blanc border border-noir/5 p-6">
                <h2 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">Articles commandés</h2>
                <table class="w-full text-sm">
                    <tbody>
                        <?php foreach ($commande['lignes'] as $ligne): ?>
                            <tr class="border-b border-noir/5 last:border-0">
                                <td class="py-3"><?= e($ligne['nom_produit']) ?> <span class="text-noir/40">(<?= $ligne['contenance_ml'] ?>ml)</span></td>
                                <td class="py-3 text-noir/50 text-center">×<?= $ligne['quantite'] ?></td>
                                <td class="py-3 text-right"><?= number_format((float)$ligne['sous_total_ttc'], 2, ',', ' ') ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="border-t border-noir/10 mt-4 pt-4 space-y-1 text-sm">
                    <div class="flex justify-between text-noir/60"><span>Sous-total HT</span><span><?= number_format((float)$commande['montant_sous_total_ht'], 2, ',', ' ') ?> €</span></div>
                    <div class="flex justify-between text-noir/60"><span>TVA</span><span><?= number_format((float)$commande['montant_tva'], 2, ',', ' ') ?> €</span></div>
                    <div class="flex justify-between text-noir/60"><span>Livraison</span><span><?= number_format((float)$commande['montant_livraison'], 2, ',', ' ') ?> €</span></div>
                    <div class="flex justify-between font-serif text-lg pt-2"><span>Total TTC</span><span><?= number_format((float)$commande['montant_total_ttc'], 2, ',', ' ') ?> €</span></div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale : infos + statut -->
        <div class="space-y-6">
            <!-- Changement de statut -->
            <div class="bg-blanc border border-noir/5 p-6">
                <h2 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">Statut</h2>
                <form method="POST" action="<?= url('admin/commandes/statut') ?>">
                    <?= \App\Core\Csrf::champ() ?>
                    <input type="hidden" name="id" value="<?= (int)$commande['id'] ?>">
                    <select name="id_statut" class="w-full border border-noir/20 px-3 py-2 text-sm mb-3 focus:outline-none focus:border-noir">
                        <?php foreach ($statuts as $statut): ?>
                            <option value="<?= $statut['id'] ?>" <?= (int)$commande['id_statut'] === (int)$statut['id'] ? 'selected' : '' ?>>
                                <?= e($statut['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="w-full bg-noir text-blanc text-xs tracking-[0.2em] uppercase py-2.5 hover:bg-bordeaux transition-colors">
                        Mettre à jour
                    </button>
                </form>
            </div>

            <!-- Livraison -->
            <div class="bg-blanc border border-noir/5 p-6">
                <h2 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">Livraison</h2>
                <p class="text-sm text-noir/70 leading-relaxed">
                    <?= e($commande['livraison_prenom']) ?> <?= e($commande['livraison_nom']) ?><br>
                    <?= e($commande['livraison_ligne_1']) ?><br>
                    <?php if (!empty($commande['livraison_ligne_2'])): ?><?= e($commande['livraison_ligne_2']) ?><br><?php endif; ?>
                    <?= e($commande['livraison_code_postal']) ?> <?= e($commande['livraison_ville']) ?><br>
                    <?= e($commande['livraison_pays']) ?>
                </p>
            </div>

            <!-- Contact + paiement -->
            <div class="bg-blanc border border-noir/5 p-6">
                <h2 class="font-serif text-sm tracking-[0.15em] uppercase mb-4">Informations</h2>
                <div class="text-sm text-noir/70 space-y-1">
                    <p><span class="text-noir/40">Email :</span> <?= e($commande['email_contact']) ?></p>
                    <?php if (!empty($commande['livraison_telephone'])): ?>
                        <p><span class="text-noir/40">Tél :</span> <?= e($commande['livraison_telephone']) ?></p>
                    <?php endif; ?>
                    <p><span class="text-noir/40">Paiement :</span> <?= e($commande['statut_paiement']) ?></p>
                    <p><span class="text-noir/40">Date :</span> <?= date('d/m/Y H:i', strtotime($commande['cree_le'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>