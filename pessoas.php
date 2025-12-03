<?php
include "config.php";
header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conexao->prepare("INSERT INTO cadastro_pessoas (nome, cpf, idade) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $data["nome"], $data["cpf"], $data["idade"]);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
}

if ($method === "GET") {
    $result = $conexao->query("SELECT * FROM cadastro_pessoas ORDER BY id DESC");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    exit;
}

if ($method === "PUT") {
    parse_str($_SERVER["QUERY_STRING"], $params);
    $id = $params["id"];

    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conexao->prepare("UPDATE cadastro_pessoas SET nome=?, cpf=?, idade=? WHERE id=?");
    $stmt->bind_param("ssii", $data["nome"], $data["cpf"], $data["idade"], $id);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
}

if ($method === "DELETE") {
    parse_str($_SERVER["QUERY_STRING"], $params);
    $id = $params["id"];

    $stmt = $conexao->prepare("DELETE FROM cadastro_pessoas WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
}

echo json_encode(["error" => "Método inválido"]);
