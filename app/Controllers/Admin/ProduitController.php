<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;

class ProduitController extends Controller
{
    /**
     * Liste tous les produits.
     */
    public function index(): void
    {
        $produits = Produit::trouverTousAdmin();

        $this->render('admin/produits/liste', [
            'titre'    => 'Gestion des produits',
            'produits' => $produits,
        ], 'admin');
    }
    /**
     * Affiche le formulaire de création.
     */
    public function nouveau(): void
    {
        $this->render('admin/produits/formulaire', [
            'titre'      => 'Nouveau produit',
            'produit'    => null, // null = mode création
            'categories' => Categorie::findAll(),
        ], 'admin');
    }

    /**
     * Affiche le formulaire d'édition pré-rempli.
     */
    public function editer(string $id): void
    {
        $produit = Produit::findById((int)$id);

        if ($produit === null) {
            Session::flash('erreur', 'Produit introuvable.');
            redirect('admin/produits');
        }

        $this->render('admin/produits/formulaire', [
            'titre'      => 'Modifier : ' . $produit['nom'],
            'produit'    => $produit, // données existantes = mode édition
            'categories' => Categorie::findAll(),
        ], 'admin');
    }

    /**
     * Traite la création OU la mise à jour (selon présence de l'id).
     */
    public function enregistrer(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée, veuillez réessayer.');
            redirect('admin/produits');
        }

        // Validation
        $validator = new Validator($_POST);
        $validator
            ->requis('nom', 'Le nom est obligatoire.')
            ->requis('slug', 'Le slug est obligatoire.')
            ->requis('description_longue', 'La description est obligatoire.')
            ->requis('id_categorie', 'La catégorie est obligatoire.')
            ->requis('prix_ttc', 'Le prix est obligatoire.')
            ->requis('contenance_ml', 'La contenance est obligatoire.');

        if (!$validator->valide()) {
            Session::set('_erreurs', $validator->erreurs());
            Session::set('_ancien', $_POST);
            $id = $_POST['id'] ?? null;
            redirect($id ? "admin/produits/{$id}/editer" : 'admin/produits/nouveau');
        }

        $id = $_POST['id'] ?? null;

        if ($id) {
            Produit::mettreAJour((int)$id, $_POST);
            Session::flash('succes', 'Produit mis à jour.');
        } else {
            Produit::creer($_POST);
            Session::flash('succes', 'Produit créé.');
        }

        redirect('admin/produits');
    }

    /**
     * Supprime (soft delete) un produit.
     */
    public function supprimer(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée.');
            redirect('admin/produits');
        }

        $id = (int)($_POST['id'] ?? 0);
        Produit::supprimer($id);
        Session::flash('succes', 'Produit supprimé.');
        redirect('admin/produits');
    }
}