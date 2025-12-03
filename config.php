<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "cadastro";

$conexao = new mysqli($host, $user, $pass, $db);

if ($conexao->connect_error) {
    die("Erro ao conectar: " . $conexao->connect_error);
}
?>