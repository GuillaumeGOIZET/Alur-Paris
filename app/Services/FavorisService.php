<?php

namespace App\Services;

use App\Models\Produit;

/**
 * Gère la liste des favoris (wishlist) stockée en session.
 * Structure : $_SESSION['favoris'] = [id_produit, id_produit, ...]
 */
class FavorisService
{
    private const CLE_SESSION = 'favoris';

    /**
     * Retourne les ids des produits favoris.
     */
    public static function contenu(): array
    {
        return $_SESSION[self::CLE_SESSION] ?? [];
    }

    /**
     * Ajoute ou retire un produit des favoris (toggle).
     */
    public static function basculer(int $idProduit): array
    {
        $favoris = self::contenu();

        if (in_array($idProduit, $favoris, true)) {
            $favoris = array_values(array_filter($favoris, fn($id) => $id !== $idProduit));
            $_SESSION[self::CLE_SESSION] = $favoris;
            return ['succes' => true, 'etat' => 'retire', 'message' => 'Retiré des favoris.'];
        }

        $produit = Produit::findById($idProduit);
        if ($produit === null) {
            return ['succes' => false, 'etat' => 'aucun', 'message' => 'Produit introuvable.'];
        }

        $favoris[] = $idProduit;
        $_SESSION[self::CLE_SESSION] = $favoris;
        return ['succes' => true, 'etat' => 'ajoute', 'message' => 'Ajouté aux favoris.'];
    }

    /**
     * Vérifie si un produit est dans les favoris.
     */
    public static function estFavori(int $idProduit): bool
    {
        return in_array($idProduit, self::contenu(), true);
    }

    /**
     * Nombre de favoris.
     */
    public static function nombre(): int
    {
        return count(self::contenu());
    }

    /**
     * Retourne les produits favoris complets (depuis la BDD).
     */
    public static function detail(): array
    {
        $favoris = self::contenu();
        if (empty($favoris)) {
            return [];
        }
        return Produit::trouverParIds(array_map('intval', $favoris));
    }
}