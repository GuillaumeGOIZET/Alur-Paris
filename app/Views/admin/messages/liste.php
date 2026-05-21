<div class="px-8 py-8">
    <h1 class="font-serif text-2xl mb-8">Messages de contact</h1>

    <?php if (empty($messages)): ?>
        <div class="bg-blanc border border-noir/5 px-6 py-8 text-center text-noir/50">
            Aucun message pour le moment.
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($messages as $msg): ?>
                <div class="bg-blanc border border-noir/5 p-6 <?= (int)$msg['est_traite'] === 0 ? 'border-l-4 border-l-bordeaux' : '' ?>">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-medium"><?= e($msg['nom']) ?> <span class="text-noir/40 font-normal text-sm"><?= e($msg['email']) ?></span></p>
                            <?php if (!empty($msg['sujet'])): ?>
                                <p class="text-sm text-noir/60 mt-1"><?= e($msg['sujet']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs text-noir/40"><?= date('d/m/Y H:i', strtotime($msg['cree_le'])) ?></span>
                            <?php if ((int)$msg['est_traite'] === 0): ?>
                                <span class="text-[10px] tracking-[0.1em] uppercase bg-bordeaux text-blanc px-2 py-1">Nouveau</span>
                            <?php else: ?>
                                <span class="text-[10px] tracking-[0.1em] uppercase bg-noir/10 text-noir/50 px-2 py-1">Traité</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="text-sm text-noir/70 leading-relaxed mb-4"><?= nl2br(e($msg['message'])) ?></p>
                    <div class="flex gap-4">
                        <a href="mailto:<?= e($msg['email']) ?>" class="text-xs text-bordeaux hover:underline">Répondre par email</a>
                        <?php if ((int)$msg['est_traite'] === 0): ?>
                            <form method="POST" action="<?= url('admin/messages/traiter') ?>" class="inline">
                                <?= \App\Core\Csrf::champ() ?>
                                <input type="hidden" name="id" value="<?= (int)$msg['id'] ?>">
                                <button type="submit" class="text-xs text-noir/50 hover:text-bordeaux">Marquer comme traité</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>