<?php
$erreurs = \App\Core\Session::get('_erreurs', []);
$ancien = \App\Core\Session::get('_ancien', []);
\App\Core\Session::supprimer('_erreurs');
\App\Core\Session::supprimer('_ancien');

// Helper : valeur du champ (ancienne saisie > donnée produit > vide)
$val = function ($champ) use ($ancien, $produit) {
    return $ancien[$champ] ?? $produit[$champ] ?? '';
};
?>

<div class="px-8 py-8 max-w-3xl">
    <div class="flex items-center gap-3 mb-8">
        <a href="<?= url('admin/produits') ?>" class="text-noir/40 hover:text-bordeaux">←</a>
        <h1 class="font-serif text-2xl"><?= $produit ? 'Modifier le produit' : 'Nouveau produit' ?></h1>
    </div>

    <form method="POST" action="<?= url('admin/produits/enregistrer') ?>" enctype="multipart/form-data"
        class="space-y-6 bg-blanc border border-noir/5 p-8">
        <?= \App\Core\Csrf::champ() ?>
        <?php if ($produit): ?>
            <input type="hidden" name="id" value="<?= (int) $produit['id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Nom</label>
                <input type="text" name="nom" value="<?= e($val('nom')) ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
                <?php if (isset($erreurs['nom'])): ?>
                    <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['nom']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Slug (URL)</label>
                <input type="text" name="slug" value="<?= e($val('slug')) ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
                <?php if (isset($erreurs['slug'])): ?>
                    <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['slug']) ?></p><?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Catégorie</label>
                <select name="id_categorie"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (string) $val('id_categorie') === (string) $cat['id'] ? 'selected' : '' ?>>
                            <?= e($cat['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Genre</label>
                <select name="genre"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
                    <?php foreach (['mixte' => 'Mixte', 'femme' => 'Femme', 'homme' => 'Homme'] as $v => $label): ?>
                        <option value="<?= $v ?>" <?= $val('genre') === $v ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Marque</label>
                <input type="text" name="marque" value="<?= e($val('marque') ?: 'Alur Paris') ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Prix TTC (€)</label>
                <input type="number" step="0.01" name="prix_ttc" value="<?= e($val('prix_ttc')) ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
                <?php if (isset($erreurs['prix_ttc'])): ?>
                    <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['prix_ttc']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Contenance (ml)</label>
                <input type="number" name="contenance_ml" value="<?= e($val('contenance_ml') ?: 100) ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
                <?php if (isset($erreurs['contenance_ml'])): ?>
                    <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['contenance_ml']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Stock</label>
                <input type="number" name="stock" value="<?= e($val('stock') ?: 0) ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
            </div>
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Description courte</label>
            <input type="text" name="description_courte" value="<?= e($val('description_courte')) ?>"
                class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
        </div>

        <div>
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Description longue</label>
            <textarea name="description_longue" rows="4"
                class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir"><?= e($val('description_longue')) ?></textarea>
            <?php if (isset($erreurs['description_longue'])): ?>
                <p class="text-xs text-bordeaux mt-1"><?= e($erreurs['description_longue']) ?></p><?php endif; ?>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Notes de tête</label>
                <input type="text" name="notes_tete" value="<?= e($val('notes_tete')) ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Notes de cœur</label>
                <input type="text" name="notes_coeur" value="<?= e($val('notes_coeur')) ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
            </div>
            <div>
                <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-2">Notes de fond</label>
                <input type="text" name="notes_fond" value="<?= e($val('notes_fond')) ?>"
                    class="w-full border border-noir/20 px-4 py-2.5 text-sm focus:outline-none focus:border-noir">
            </div>
        </div>

        <!-- Upload d'une NOUVELLE image (reste dans le formulaire principal) -->
        <div class="border-t border-noir/5 pt-6">
            <label class="block text-xs tracking-[0.1em] uppercase text-noir/60 mb-3">Ajouter une image</label>
            <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                class="block w-full text-sm text-noir/60 file:mr-4 file:py-2 file:px-4 file:border file:border-noir/20 file:bg-sable file:text-xs file:tracking-[0.1em] file:uppercase file:cursor-pointer hover:file:bg-noir hover:file:text-blanc file:transition-colors">
            <p class="text-[10px] text-noir/40 mt-2">PNG, JPG ou WEBP. 5 Mo maximum.
                <?= $produit ? 'La nouvelle image s\'ajoute aux existantes.' : 'Vous pourrez ajouter l\'image après la création du produit.' ?>
            </p>
        </div>

        <div class="flex gap-8 pt-2">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="est_publie" value="1" <?= $val('est_publie') ? 'checked' : '' ?>>
                Publié
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="est_mis_en_avant" value="1" <?= $val('est_mis_en_avant') ? 'checked' : '' ?>>
                Mis en avant (accueil)
            </label>
        </div>

        <div class="flex justify-between items-center pt-4 border-t border-noir/5">
            <a href="<?= url('admin/produits') ?>" class="text-sm text-noir/50 hover:text-bordeaux">Annuler</a>
            <button type="submit"
                class="bg-noir text-blanc text-xs tracking-[0.2em] uppercase px-8 py-3 hover:bg-bordeaux transition-colors">
                <?= $produit ? 'Enregistrer les modifications' : 'Créer le produit' ?>
            </button>
        </div>
    </form>

    <?php if ($produit && !empty($images)): ?>
        <!-- Galerie des images existantes (HORS du formulaire principal) -->
        <div class="bg-blanc border border-noir/5 p-8 mt-6">
            <h2 class="text-xs tracking-[0.1em] uppercase text-noir/60 mb-4">Images existantes</h2>
            <div class="flex flex-wrap gap-4">
                <?php foreach ($images as $img): ?>
                    <div class="relative w-32">
                        <img src="<?= url('assets/uploads/produits/' . basename($img['chemin_fichier'])) ?>"
                            alt="<?= e($img['texte_alternatif'] ?? '') ?>"
                            class="w-32 h-32 object-cover border <?= (int) $img['est_principale'] === 1 ? 'border-bordeaux border-2' : 'border-noir/10' ?>">

                        <?php if ((int) $img['est_principale'] === 1): ?>
                            <span
                                class="absolute top-1 left-1 bg-bordeaux text-blanc text-[8px] tracking-[0.1em] uppercase px-1.5 py-0.5">Principale</span>
                        <?php endif; ?>

                        <div class="flex gap-2 mt-2 text-[10px]">
                            <?php if ((int) $img['est_principale'] !== 1): ?>
                                <form method="POST" action="<?= url('admin/produits/image/principale') ?>" class="inline">
                                    <?= \App\Core\Csrf::champ() ?>
                                    <input type="hidden" name="id_image" value="<?= (int) $img['id'] ?>">
                                    <button type="submit" class="text-noir/60 hover:text-bordeaux underline">Principale</button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" action="<?= url('admin/produits/image/supprimer') ?>" class="inline"
                                onsubmit="return confirm('Supprimer cette image ?');">
                                <?= \App\Core\Csrf::champ() ?>
                                <input type="hidden" name="id_image" value="<?= (int) $img['id'] ?>">
                                <button type="submit" class="text-bordeaux hover:underline">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($produit): ?>
        <!-- Suppression du produit (formulaire séparé) -->
        <form method="POST" action="<?= url('admin/produits/supprimer') ?>" class="mt-6"
            onsubmit="return confirm('Supprimer définitivement ce produit ?');">
            <?= \App\Core\Csrf::champ() ?>
            <input type="hidden" name="id" value="<?= (int) $produit['id'] ?>">
            <button type="submit" class="text-xs text-bordeaux hover:underline">Supprimer ce produit</button>
        </form>
    <?php endif; ?>
</div>