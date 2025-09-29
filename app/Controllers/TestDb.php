<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use Config\Database;

class TestDb extends Controller
{
    public function index()
    {
        $db = Database::connect();
        if ($db->connID) {
            echo "✅ Conexión exitosa a la base de datos.";
        } else {
            echo "❌ No se pudo conectar.";
        }
    }
}
