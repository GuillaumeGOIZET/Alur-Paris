<?php

namespace App\Services;

/**
 * Gère l'upload sécurisé d'images produits.
 * Valide le type réel, la taille, et renomme le fichier.
 */
class UploadService
{
    // Types MIME réellement autorisés (pas juste l'extension)
    private const TYPES_AUTORISES = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    // Taille max : 5 Mo
    private const TAILLE_MAX = 5 * 1024 * 1024;

    // Dossier de destination (relatif à public/)
    private const DOSSIER = 'assets/uploads/produits/';

    /**
     * Traite un fichier uploadé. Retourne le nom du fichier enregistré, ou null en cas d'erreur.
     *
     * @param array $fichier  Une entrée de $_FILES
     * @param string &$erreur Message d'erreur en cas d'échec (passé par référence)
     * @return string|null    Le nom du fichier créé, ou null
     */
    public static function imageProduit(array $fichier, ?string &$erreur = null): ?string
    {
        // 1. Vérifie qu'il n'y a pas d'erreur d'upload PHP
        if (!isset($fichier['error']) || $fichier['error'] !== UPLOAD_ERR_OK) {
            $erreur = 'Erreur lors du téléversement du fichier.';
            return null;
        }

        // 2. Vérifie la taille
        if ($fichier['size'] > self::TAILLE_MAX) {
            $erreur = 'Le fichier est trop volumineux (5 Mo maximum).';
            return null;
        }

        // 3. Vérifie le TYPE RÉEL du fichier (pas l'extension déclarée)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $typeMime = $finfo->file($fichier['tmp_name']);

        if (!isset(self::TYPES_AUTORISES[$typeMime])) {
            $erreur = 'Format non autorisé. Utilisez JPG, PNG ou WEBP.';
            return null;
        }

        // 4. Génère un nom de fichier unique et sûr (on n'utilise JAMAIS le nom d'origine)
        $extension = self::TYPES_AUTORISES[$typeMime];
        $nomFichier = 'produit_' . bin2hex(random_bytes(8)) . '_' . time() . '.' . $extension;

        // 5. Déplace le fichier vers le dossier de destination
        $cheminDestination = __DIR__ . '/../../public/' . self::DOSSIER . $nomFichier;

        if (!move_uploaded_file($fichier['tmp_name'], $cheminDestination)) {
            $erreur = 'Impossible d\'enregistrer le fichier.';
            return null;
        }

        // Succès : on retourne le nom du fichier (à stocker en BDD)
        return $nomFichier;
    }
}