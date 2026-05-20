/**
 * ===== BANNIÈRE COOKIES RGPD =====
 */
(function () {
    const banniere = document.getElementById('banniere-cookies');
    if (!banniere) return;

    // Lit un cookie par son nom
    function lireCookie(nom) {
        const valeur = document.cookie
            .split('; ')
            .find((ligne) => ligne.startsWith(nom + '='));
        return valeur ? valeur.split('=')[1] : null;
    }

    // Écrit un cookie (durée en jours)
    function ecrireCookie(nom, valeur, jours) {
        const date = new Date();
        date.setTime(date.getTime() + jours * 24 * 60 * 60 * 1000);
        document.cookie = `${nom}=${valeur}; expires=${date.toUTCString()}; path=/; SameSite=Lax`;
    }

    // Si aucun choix n'a été fait, on affiche la bannière
    if (!lireCookie('cookies_consent')) {
        banniere.classList.remove('hidden');
    }

    // Bouton Accepter
    document.getElementById('cookies-accepter')?.addEventListener('click', () => {
        ecrireCookie('cookies_consent', 'accepte', 365);
        banniere.classList.add('hidden');
    });

    // Bouton Refuser
    document.getElementById('cookies-refuser')?.addEventListener('click', () => {
        ecrireCookie('cookies_consent', 'refuse', 365);
        banniere.classList.add('hidden');
    });
})();