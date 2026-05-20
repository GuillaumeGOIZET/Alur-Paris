<?php

namespace App\Services;

use App\Models\Produit;
use App\Core\Database;

/**
 * Gère le panier d'achat stocké en session PHP.
 *
 * Structure en session : $_SESSION['panier'] = [id_produit => quantite, ...]
 * Les détails produits (prix, nom) sont relus depuis la BDD à l'affichage,
 * pour toujours refléter les données actuelles.
 */
class PanierService
{
    private const CLE_SESSION = 'panier';

    /**
     * Retourne le contenu brut du panier : [id_produit => quantite].
     */
    public static function contenu(): array
    {
        return $_SESSION[self::CLE_SESSION] ?? [];
    }

    /**
     * Ajoute une quantité d'un produit au panier.
     * Vérifie le stock disponible avant d'ajouter.
     *
     * @return array{succes: bool, message: string}
     */
    public static function ajouter(int $idProduit, int $quantite = 1): array
    {
        $produit = Produit::findById($idProduit);

        if ($produit === null || (int)$produit['est_publie'] !== 1) {
            return ['succes' => false, 'message' => 'Produit introuvable.'];
        }

        $quantiteActuelle = self::contenu()[$idProduit] ?? 0;
        $quantiteVoulue   = $quantiteActuelle + $quantite;

        // Vérification du stock
        if ($quantiteVoulue > (int)$produit['stock']) {
            return [
                'succes'  => false,
                'message' => 'Stock insuffisant. Il reste ' . (int)$produit['stock'] . ' unité(s).',
            ];
        }

        $_SESSION[self::CLE_SESSION][$idProduit] = $quantiteVoulue;

        return ['succes' => true, 'message' => 'Produit ajouté au panier.'];
    }

    /**
     * Met à jour la quantité d'un produit (remplace, ne s'additionne pas).
     * Si quantité <= 0, retire le produit.
     */
    public static function modifier(int $idProduit, int $quantite): array
    {
        if ($quantite <= 0) {
            return self::retirer($idProduit);
        }

        $produit = Produit::findById($idProduit);

        if ($produit === null) {
            return ['succes' => false, 'message' => 'Produit introuvable.'];
        }

        if ($quantite > (int)$produit['stock']) {
            return [
                'succes'  => false,
                'message' => 'Stock insuffisant. Il reste ' . (int)$produit['stock'] . ' unité(s).',
            ];
        }

        $_SESSION[self::CLE_SESSION][$idProduit] = $quantite;

        return ['succes' => true, 'message' => 'Panier mis à jour.'];
    }

    /**
     * Retire complètement un produit du panier.
     */
    public static function retirer(int $idProduit): array
    {
        unset($_SESSION[self::CLE_SESSION][$idProduit]);
        return ['succes' => true, 'message' => 'Produit retiré du panier.'];
    }

    /**
     * Vide entièrement le panier.
     */
    public static function vider(): void
    {
        unset($_SESSION[self::CLE_SESSION]);
    }

    /**
     * Nombre total d'articles dans le panier (somme des quantités).
     */
    public static function nombreArticles(): int
    {
        return array_sum(self::contenu());
    }

    /**
     * Retourne le détail complet du panier : produits enrichis depuis la BDD
     * + totaux calculés (sous-total HT, TVA, total TTC).
     *
     * @return array{lignes: array, sous_total_ht: float, tva: float, total_ttc: float, nb_articles: int}
     */
    public static function detail(): array
    {
        $contenu = self::contenu();
        $lignes  = [];
        $sousTotalHt = 0.0;
        $totalTva    = 0.0;

        foreach ($contenu as $idProduit => $quantite) {
            $produit = Produit::findById((int)$idProduit);

            // Si le produit a été supprimé entre-temps, on l'ignore
            if ($produit === null) {
                continue;
            }

            $prixTtc = (float)$produit['prix_ttc'];
            $tauxTva = (float)$produit['taux_tva'];

            // Calcul du HT à partir du TTC : HT = TTC / (1 + taux/100)
            $prixHt    = $prixTtc / (1 + $tauxTva / 100);
            $ligneTtc  = $prixTtc * $quantite;
            $ligneHt   = $prixHt * $quantite;

            $lignes[] = [
                'id'            => (int)$produit['id'],
                'nom'           => $produit['nom'],
                'slug'          => $produit['slug'],
                'prix_ttc'      => $prixTtc,
                'contenance_ml' => (int)$produit['contenance_ml'],
                'quantite'      => $quantite,
                'stock'         => (int)$produit['stock'],
                'sous_total_ttc'=> $ligneTtc,
            ];

            $sousTotalHt += $ligneHt;
            $totalTva    += ($ligneTtc - $ligneHt);
        }

        return [
            'lignes'        => $lignes,
            'sous_total_ht' => round($sousTotalHt, 2),
            'tva'           => round($totalTva, 2),
            'total_ttc'     => round($sousTotalHt + $totalTva, 2),
            'nb_articles'   => self::nombreArticles(),
        ];
    }
}