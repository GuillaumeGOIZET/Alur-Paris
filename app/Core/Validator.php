<?php

namespace App\Core;

/**
 * Validation simple de données de formulaire.
 * Accumule les erreurs, accessibles via erreurs().
 */
class Validator
{
    private array $donnees;
    private array $erreurs = [];

    public function __construct(array $donnees)
    {
        $this->donnees = $donnees;
    }

    /**
     * Champ obligatoire (non vide).
     */
    public function requis(string $champ, string $message): self
    {
        if (empty(trim($this->donnees[$champ] ?? ''))) {
            $this->erreurs[$champ] = $message;
        }
        return $this;
    }

    /**
     * Email valide.
     */
    public function email(string $champ, string $message): self
    {
        $valeur = $this->donnees[$champ] ?? '';
        if (!empty($valeur) && !filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs[$champ] = $message;
        }
        return $this;
    }

    /**
     * Longueur minimale.
     */
    public function min(string $champ, int $longueur, string $message): self
    {
        $valeur = $this->donnees[$champ] ?? '';
        if (!empty($valeur) && mb_strlen($valeur) < $longueur) {
            $this->erreurs[$champ] = $message;
        }
        return $this;
    }

    /**
     * Deux champs identiques (ex: mot de passe + confirmation).
     */
    public function identique(string $champ1, string $champ2, string $message): self
    {
        if (($this->donnees[$champ1] ?? '') !== ($this->donnees[$champ2] ?? '')) {
            $this->erreurs[$champ2] = $message;
        }
        return $this;
    }

    /**
     * Champ qui doit être coché (cases à cocher type CGV).
     */
    public function accepte(string $champ, string $message): self
    {
        if (empty($this->donnees[$champ])) {
            $this->erreurs[$champ] = $message;
        }
        return $this;
    }

    /**
     * La validation a-t-elle réussi ?
     */
    public function valide(): bool
    {
        return empty($this->erreurs);
    }

    /**
     * Retourne les erreurs.
     */
    public function erreurs(): array
    {
        return $this->erreurs;
    }
}