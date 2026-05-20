<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class CompteController extends Controller
{
    public function index(): void
    {
        $this->render('compte/index', [
            'titre'       => 'Mon compte',
            'utilisateur' => Auth::utilisateur(),
        ]);
    }
}