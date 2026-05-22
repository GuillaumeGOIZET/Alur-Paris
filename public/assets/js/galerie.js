/**
 * Galerie de la fiche produit Alur Paris.
 * Change la grande image au clic sur une miniature.
 */
(function () {
    const miniatures = document.querySelectorAll('.miniature');
    if (miniatures.length === 0) return;

    const grandeImage = document.getElementById('image-principale');
    if (!grandeImage) return;

    miniatures.forEach((btn) => {
        btn.addEventListener('click', () => {
            // Change la grande image
            grandeImage.src = btn.dataset.image;

            // Met en évidence la miniature active
            miniatures.forEach((m) => {
                m.classList.remove('border-bordeaux');
                m.classList.add('border-transparent');
            });
            btn.classList.remove('border-transparent');
            btn.classList.add('border-bordeaux');
        });
    });
})();