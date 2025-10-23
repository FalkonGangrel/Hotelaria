<?php
namespace App\Controllers;
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../Helpers/functions.php';


class DashboardController {
    public function index() {
        $user = auth();
        view('dashboard/home.php', ['user'=>$user]);
    }
}