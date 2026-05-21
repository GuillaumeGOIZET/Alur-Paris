<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

/**
 * Modèle représentant une commande client.
 * Gère la création transactionnelle (commande + lignes + décrément stock).
 */
class Commande extends Model
{
    protected static string $table = 'commande';

    /**
     * Crée une commande complète en base, de façon transactionnelle.
     *
     * @param array $donnees   Infos de livraison + paiement
     * @param array $lignes    Lignes du panier (depuis PanierService::detail())
     * @param array $totaux    Totaux calculés (ht, tva, ttc)
     * @return int             L'id de la commande créée
     * @throws \Throwable       Si une étape échoue, tout est annulé
     */
    public static function creer(array $donnees, array $lignes, array $totaux): int
    {
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            // 1. Génère un numéro de commande unique
            $numero = self::genererNumero();

            // 2. Insère la commande
            $sql = "INSERT INTO commande (
                        numero_commande, id_utilisateur, email_contact,
                        nom_invite, prenom_invite, id_statut,
                        montant_sous_total_ht, montant_tva, montant_livraison, montant_total_ttc,
                        livraison_nom, livraison_prenom, livraison_ligne_1, livraison_ligne_2,
                        livraison_code_postal, livraison_ville, livraison_pays, livraison_telephone,
                        stripe_payment_intent_id, statut_paiement, paye_le, cree_le
                    ) VALUES (
                        :numero, :id_utilisateur, :email_contact,
                        :nom_invite, :prenom_invite, 1,
                        :ht, :tva, :livraison, :ttc,
                        :liv_nom, :liv_prenom, :liv_ligne1, :liv_ligne2,
                        :liv_cp, :liv_ville, :liv_pays, :liv_tel,
                        :stripe_id, 'reussi', NOW(), NOW()
                    )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'numero'         => $numero,
                'id_utilisateur' => $donnees['id_utilisateur'] ?? null,
                'email_contact'  => $donnees['email'],
                'nom_invite'     => $donnees['nom'] ?? null,
                'prenom_invite'  => $donnees['prenom'] ?? null,
                'ht'             => $totaux['sous_total_ht'],
                'tva'            => $totaux['tva'],
                'livraison'      => $totaux['livraison'] ?? 0,
                'ttc'            => $totaux['total_ttc'],
                'liv_nom'        => $donnees['nom'],
                'liv_prenom'     => $donnees['prenom'],
                'liv_ligne1'     => $donnees['adresse'],
                'liv_ligne2'     => $donnees['adresse_complement'] ?? null,
                'liv_cp'         => $donnees['code_postal'],
                'liv_ville'      => $donnees['ville'],
                'liv_pays'       => $donnees['pays'] ?? 'France',
                'liv_tel'        => $donnees['telephone'] ?? null,
                'stripe_id'      => $donnees['stripe_payment_intent_id'] ?? null,
            ]);

            $idCommande = (int)$pdo->lastInsertId();

            // 3. Insère chaque ligne de commande + décrémente le stock
            $sqlLigne = "INSERT INTO ligne_commande (
                            id_commande, id_produit, nom_produit, contenance_ml,
                            prix_unitaire_ht, prix_unitaire_ttc, taux_tva,
                            quantite, sous_total_ht, sous_total_ttc
                         ) VALUES (
                            :id_commande, :id_produit, :nom, :contenance,
                            :pu_ht, :pu_ttc, :tva,
                            :quantite, :st_ht, :st_ttc
                         )";
            $stmtLigne = $pdo->prepare($sqlLigne);

            $sqlStock = "UPDATE produit SET stock = stock - :qte WHERE id = :id AND stock >= :qte2";
            $stmtStock = $pdo->prepare($sqlStock);

            foreach ($lignes as $ligne) {
                // Calcul du HT à partir du TTC pour figer les valeurs
                $prixTtc = $ligne['prix_ttc'];
                $prixHt  = $prixTtc / 1.20; // TVA 20%

                $stmtLigne->execute([
                    'id_commande' => $idCommande,
                    'id_produit'  => $ligne['id'],
                    'nom'         => $ligne['nom'],
                    'contenance'  => $ligne['contenance_ml'],
                    'pu_ht'       => round($prixHt, 2),
                    'pu_ttc'      => $prixTtc,
                    'tva'         => 20.00,
                    'quantite'    => $ligne['quantite'],
                    'st_ht'       => round($prixHt * $ligne['quantite'], 2),
                    'st_ttc'      => $ligne['sous_total_ttc'],
                ]);

                // Décrément du stock (avec garde : stock >= quantité)
                $stmtStock->execute([
                    'qte'  => $ligne['quantite'],
                    'id'   => $ligne['id'],
                    'qte2' => $ligne['quantite'],
                ]);

                // Si aucune ligne affectée, le stock était insuffisant → on annule tout
                if ($stmtStock->rowCount() === 0) {
                    throw new \RuntimeException("Stock insuffisant pour le produit : " . $ligne['nom']);
                }
            }

            $pdo->commit();
            return $idCommande;

        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Génère un numéro de commande unique : ALUR-20260521-XXXX
     */
    private static function genererNumero(): string
    {
        return 'ALUR-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    }

    /**
     * Récupère une commande par son numéro, avec ses lignes.
     */
    public static function trouverParNumero(string $numero): ?array
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM commande WHERE numero_commande = :num LIMIT 1");
        $stmt->execute(['num' => $numero]);
        $commande = $stmt->fetch();

        if ($commande === false) {
            return null;
        }

        $stmtLignes = $pdo->prepare("SELECT * FROM ligne_commande WHERE id_commande = :id");
        $stmtLignes->execute(['id' => $commande['id']]);
        $commande['lignes'] = $stmtLignes->fetchAll();

        return $commande;
    }
}