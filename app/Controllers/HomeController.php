<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        echo "<h1>Alur Paris</h1>";
        echo "<p>✅ Le routeur fonctionne ! Tu es sur la home.</p>";
        echo "<p>Essaie d'autres URLs :</p>";
        echo "<ul>";
        echo "<li><a href='/alur-paris/parfums'>/parfums</a></li>";
        echo "<li><a href='/alur-paris/parfums/oud-royal'>/parfums/oud-royal</a></li>";
        echo "<li><a href='/alur-paris/page-qui-existe-pas'>/page-qui-existe-pas</a> (404)</li>";
        echo "</ul>";
    }
}