<?php
namespace App\Controllers;

use App\Helpers\Auth;

class DashboardController
{
    public function index()
    {
        Auth::authorize(['master', 'admin', 'fornecedor']);
        view('dashboard/home', ['title' => 'Painel de Controle']);
    }
}
