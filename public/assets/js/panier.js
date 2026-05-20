/**
 * Gestion asynchrone du panier Alur Paris.
 * Communique avec les endpoints /panier/* via fetch (AJAX).
 */

const BASE_URL = window.ALUR_BASE_URL || '';

/**
 * Affiche un message temporaire en bas de l'écran.
 */
function afficherToast(message, succes = true) {
    const toast = document.getElementById('toast');
    if (!toast) return;

    toast.textContent = message;
    toast.classList.toggle('bg-bordeaux', !succes);
    toast.classList.toggle('bg-noir', succes);
    toast.style.opacity = '1';

    setTimeout(() => {
        toast.style.opacity = '0';
    }, 2500);
}

/**
 * Met à jour le badge du panier dans le header.
 */
function majBadgePanier(nbArticles) {
    const badge = document.getElementById('badge-panier');
    if (badge) {
        badge.textContent = nbArticles;
    }
}

/**
 * Formate un nombre en prix français : 185.5 => "185,50 €"
 */
function formaterPrix(montant) {
    return montant.toLocaleString('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }) + ' €';
}

/**
 * Envoie une requête POST aux endpoints panier.
 */
async function requetePanier(action, donnees) {
    const formData = new FormData();
    for (const cle in donnees) {
        formData.append(cle, donnees[cle]);
    }

    const reponse = await fetch(`${BASE_URL}/panier/${action}`, {
        method: 'POST',
        body: formData,
    });

    return await reponse.json();
}

/**
 * ===== AJOUT AU PANIER (depuis fiche produit / catalogue) =====
 */
document.querySelectorAll('.btn-ajouter-panier').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const idProduit = btn.dataset.id;
        const quantite = document.getElementById('quantite-produit')?.value || 1;

        const resultat = await requetePanier('ajouter', {
            id_produit: idProduit,
            quantite: quantite,
        });

        afficherToast(resultat.message, resultat.succes);

        if (resultat.succes) {
            majBadgePanier(resultat.nb_articles);
        }
    });
});

/**
 * ===== ACTIONS SUR LA PAGE PANIER =====
 */

// Modifier la quantité (+ / −)
document.querySelectorAll('.btn-qte').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const action = btn.dataset.action;
        const spanQte = document.querySelector(`.qte-valeur[data-id="${id}"]`);
        let quantite = parseInt(spanQte.textContent, 10);

        quantite = action === 'plus' ? quantite + 1 : quantite - 1;

        const resultat = await requetePanier('modifier', {
            id_produit: id,
            quantite: quantite,
        });

        if (resultat.succes) {
            rafraichirPanier(resultat.panier);
        } else {
            afficherToast(resultat.message, false);
        }
    });
});

// Retirer un produit
document.querySelectorAll('.btn-retirer').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;

        const resultat = await requetePanier('retirer', {
            id_produit: id,
        });

        if (resultat.succes) {
            const ligne = document.querySelector(`[data-ligne="${id}"]`);
            if (ligne) ligne.remove();
            rafraichirPanier(resultat.panier);
            afficherToast(resultat.message, true);
        }
    });
});

/**
 * Rafraîchit l'affichage du panier (totaux, badge, quantités) sans recharger.
 */
function rafraichirPanier(panier) {
    majBadgePanier(panier.nb_articles);

    if (panier.nb_articles === 0) {
        document.getElementById('panier-vide')?.classList.remove('hidden');
        document.getElementById('panier-contenu')?.classList.add('hidden');
        return;
    }

    panier.lignes.forEach((ligne) => {
        const spanQte = document.querySelector(`.qte-valeur[data-id="${ligne.id}"]`);
        if (spanQte) spanQte.textContent = ligne.quantite;

        const spanTotal = document.querySelector(`.ligne-total[data-id="${ligne.id}"]`);
        if (spanTotal) spanTotal.textContent = formaterPrix(ligne.sous_total_ttc);
    });

    document.getElementById('recap-ht').textContent = formaterPrix(panier.sous_total_ht);
    document.getElementById('recap-tva').textContent = formaterPrix(panier.tva);
    document.getElementById('recap-total').textContent = formaterPrix(panier.total_ttc);
}

/**
 * ===== SÉLECTEUR DE QUANTITÉ SUR LA FICHE PRODUIT =====
 */
const champQte = document.getElementById('quantite-produit');
if (champQte) {
    const max = parseInt(champQte.max, 10);
    document.getElementById('qte-plus')?.addEventListener('click', () => {
        let val = parseInt(champQte.value, 10);
        if (val < max) champQte.value = val + 1;
    });
    document.getElementById('qte-moins')?.addEventListener('click', () => {
        let val = parseInt(champQte.value, 10);
        if (val > 1) champQte.value = val - 1;
    });
}