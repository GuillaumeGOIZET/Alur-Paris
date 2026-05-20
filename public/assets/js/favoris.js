/**
 * Gestion asynchrone des favoris Alur Paris.
 * Communique avec l'endpoint /favoris/basculer via fetch (AJAX).
 */

const FAV_BASE_URL = window.ALUR_BASE_URL || '';

/**
 * Envoie la requête de bascule (ajout/retrait) d'un favori.
 */
async function requeteFavori(id) {
    const formData = new FormData();
    formData.append('id_produit', id);

    const reponse = await fetch(`${FAV_BASE_URL}/favoris/basculer`, {
        method: 'POST',
        body: formData,
    });

    return await reponse.json();
}

document.querySelectorAll('.btn-favori').forEach((btn) => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const id = btn.dataset.id;

        const resultat = await requeteFavori(id);

        // Toast (réutilise la fonction globale de panier.js si présente)
        if (typeof afficherToast === 'function') {
            afficherToast(resultat.message, resultat.succes);
        }

        if (!resultat.succes) return;

        // Met à jour le badge favoris du header en temps réel
        const badgeFav = document.getElementById('badge-favoris');
        if (resultat.nombre > 0) {
            if (badgeFav) {
                badgeFav.textContent = resultat.nombre;
            }
            // Note : si le badge n'existait pas (0 favori au chargement),
            // il faudra recharger pour le voir apparaître la première fois
        } else if (badgeFav) {
            badgeFav.remove();
        }

        // Sur la page favoris : retirer = enlever la carte
        if (resultat.etat === 'retire') {
            const carte = document.querySelector(`[data-favori-carte="${id}"]`);
            if (carte) carte.remove();
        }

        // Sur la fiche produit : basculer l'apparence du cœur
        const svg = btn.querySelector('svg');
        if (svg) {
            if (resultat.etat === 'ajoute') {
                svg.setAttribute('fill', 'currentColor');
                svg.classList.add('text-bordeaux');
                svg.classList.remove('text-noir/40');
            } else if (resultat.etat === 'retire') {
                svg.setAttribute('fill', 'none');
                svg.classList.remove('text-bordeaux');
                svg.classList.add('text-noir/40');
            }
        }
    });
});