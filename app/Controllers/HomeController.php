<?php
namespace App\Controllers;

use App\Database\Connection;
use App\Models\Book;

class HomeController
{
    public function index()
    {
        // Placeholder for home page logic
        // Example: fetch some books (not required now)
        $pdo = Connection::getInstance();
        // $stmt = $pdo->query('SELECT * FROM books');
        // $books = $stmt->fetchAll();
        // return view('home', ['books' => $books]);
    }
}
?>