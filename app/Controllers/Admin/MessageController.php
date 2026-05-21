<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ContactMessage;

class MessageController extends Controller
{
    public function index(): void
    {
        $messages = ContactMessage::trouverTousAdmin();

        $this->render('admin/messages/liste', [
            'titre'    => 'Messages de contact',
            'messages' => $messages,
        ], 'admin');
    }

    public function traiter(): void
    {
        if (!Csrf::verifier($_POST['_csrf'] ?? null)) {
            Session::flash('erreur', 'Session expirée.');
            redirect('admin/messages');
        }

        $id = (int)($_POST['id'] ?? 0);
        ContactMessage::marquerTraite($id, true);
        Session::flash('succes', 'Message marqué comme traité.');

        redirect('admin/messages');
    }
}