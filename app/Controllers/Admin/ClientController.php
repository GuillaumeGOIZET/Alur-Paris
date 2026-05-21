<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Utilisateur;

class ClientController extends Controller
{
    public function index(): void
    {
        $clients = Utilisateur::trouverClientsAdmin();

        $this->render('admin/clients/liste', [
            'titre'   => 'Gestion des clients',
            'clients' => $clients,
        ], 'admin');
    }
}