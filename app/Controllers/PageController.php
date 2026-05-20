<?php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
    public function maison(): void
    {
        $this->render('about/maison', ['titre' => 'La Maison']);
    }

    public function mentions(): void
    {
        $this->render('legal/mentions', ['titre' => 'Mentions légales']);
    }

    public function cgv(): void
    {
        $this->render('legal/cgv', ['titre' => 'Conditions générales de vente']);
    }

    public function confidentialite(): void
    {
        $this->render('legal/confidentialite', ['titre' => 'Politique de confidentialité']);
    }
    public function contact(): void
    {
        $this->render('contact/index', ['titre' => 'Contact']);
    }
}