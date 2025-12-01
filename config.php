<?php
$host = "localhost";
$db_user = "root";
$db_pass = "1095";
$db_name = "cadastro"; 

$con = new mysqli($host, $db_user, $db_pass, $db_name);

if ($con->connect_error) {
    die("ERRO NA CONEXÃO: " . $con->connect_error);
}

?>