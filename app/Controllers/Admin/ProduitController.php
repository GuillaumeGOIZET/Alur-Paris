<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Services\UploadService;
use App\Models\ImageProduit;

class ProduitController extends Controller
{
    /**
     * Liste tous les produits.
     */
    public function index(): void
    {
        $produits = Produit::trouverTousAdmin();

        $this->render('admin/produits/liste', [
            'titre' => 'Gestion des produits',
            'produits' => $produits,
        ], 'admin');
    }
    /**
     * Affiche le formulaire de création.
     */
    public function nouveau(): void
    {
        $this->render('admin/produits/formulaire', [
            'titre' => 'Nouveau produit',
            'produit' => null, // null = mode création
            'categories' => Categorie::findAll(),
            'images' => [], // ← ajout
        ], 'admin');
    }

    /**
     * Affiche le formulaire d'édition pré-rempli.
     */
    public function editer(string $id): void
    {
        $produit = Produit::findById((int) $id);

        if ($produit === null) {
            Session::flash('erreur', 'Produit introuvable.');
            redirect('admin/produits');
        }

        $this->render('admin/produits/formulaire', [
            'titre' => 'Modifier : ' . $produit['nom'],
            'produit' => $produit,
            'categories' => Categorie::findAll(),
            'images' => \App\Models\ImageProduit::trouverParProduit((int) $id), // ← ajout
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
            Produit::mettreAJour((int) $id, $_POST);
            $idProduit = (int) $id;
            $message = 'Produit mis à jour.';
        } else {
            $idProduit = Produit::creer($_POST);
            $message = 'Produit créé.';
        }

        // Traite l'upload d'image s'il y en a une
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $erreurUpload = null;
            $nomFichier = UploadService::imageProduit($_FILES['image'], $erreurUpload);

            if ($nomFichier !== null) {
                ImageProduit::ajouter($idProduit, $nomFichier, $_POST['nom'] ?? null);
            } else {
                Session::flash('erreur', 'Produit enregistré, mais l\'image n\'a pas pu être ajoutée : ' . $erreurUpload);
                redirect('admin/produits/' . $idProduit . '/editer');
            }
        }

        Session::flash('succes', $message);
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

        $id = (int) ($_POST['id'] ?? 0);
        Produit::supprimer($id);
        Session::flash('succes', 'Produit supprimé.');
        redirect('admin/produits');
    }
    /**
     * Supprime une image (BDD + fichier physique).
     */
    public function supprimerImage(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée.');
            redirect('admin/produits');
        }

        $idImage   = (int)($_POST['id_image'] ?? 0);
        $idProduit = ImageProduit::trouverIdProduit($idImage);

        if ($idProduit === null) {
            Session::flash('erreur', 'Image introuvable.');
            redirect('admin/produits');
        }

        // Récupère si c'était la principale (pour réaffecter ensuite)
        $imagesAvant = ImageProduit::trouverParProduit($idProduit);
        $etaitPrincipale = false;
        foreach ($imagesAvant as $img) {
            if ((int)$img['id'] === $idImage && (int)$img['est_principale'] === 1) {
                $etaitPrincipale = true;
            }
        }

        // Supprime la ligne et récupère le chemin du fichier
        $chemin = ImageProduit::supprimer($idImage);

        // Efface le fichier physique
        if ($chemin !== null) {
            $cheminComplet = __DIR__ . '/../../../public/assets/uploads/produits/' . basename($chemin);
            if (file_exists($cheminComplet)) {
                unlink($cheminComplet);
            }
        }

        // Si on a supprimé la principale, désigne la première image restante comme principale
        if ($etaitPrincipale) {
            $imagesRestantes = ImageProduit::trouverParProduit($idProduit);
            if (!empty($imagesRestantes)) {
                ImageProduit::definirPrincipale((int)$imagesRestantes[0]['id'], $idProduit);
            }
        }

        Session::flash('succes', 'Image supprimée.');
        redirect('admin/produits/' . $idProduit . '/editer');
    }

    /**
     * Définit une image comme principale.
     */
    public function imagePrincipale(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée.');
            redirect('admin/produits');
        }

        $idImage   = (int)($_POST['id_image'] ?? 0);
        $idProduit = ImageProduit::trouverIdProduit($idImage);

        if ($idProduit === null) {
            Session::flash('erreur', 'Image introuvable.');
            redirect('admin/produits');
        }

        ImageProduit::definirPrincipale($idImage, $idProduit);
        Session::flash('succes', 'Image principale mise à jour.');
        redirect('admin/produits/' . $idProduit . '/editer');
    }
}