<?php
include "config.php";
header("Content-Type: application/json");

if ($conexao->connect_error) {
    echo json_encode(["error" => "Falha na conexão"]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];

/* --------------------- CREATE (POST) --------------------- */
if ($method === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $nome  = trim($data["nome"] ?? "");
    $cpf   = trim($data["cpf"] ?? "");
    $idade = $data["idade"] ?? null;

    if ($nome === "" || $cpf === "") {
        echo json_encode(["error" => "Nome e CPF são obrigatórios."]);
        exit;
    }

    $stmt = $conexao->prepare("INSERT INTO cadastro_pessoas (nome, cpf, idade) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nome, $cpf, $idade);
    $stmt->execute();

    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
    exit;
}

/* --------------------- READ (GET) --------------------- */
if ($method === "GET") {

    $result = $conexao->query("SELECT * FROM cadastro_pessoas ORDER BY id DESC");

    $dados = [];
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }

    echo json_encode($dados);
    exit;
}

/* --------------------- UPDATE (PUT) --------------------- */
if ($method === "PUT") {

    if (!isset($_GET["id"])) {
        echo json_encode(["error" => "ID não informado"]);
        exit;
    }

    $id = intval($_GET["id"]);
    $data = json_decode(file_get_contents("php://input"), true);

    $nome  = trim($data["nome"] ?? "");
    $cpf   = trim($data["cpf"] ?? "");
    $idade = $data["idade"] ?? null;

    $stmt = $conexao->prepare("UPDATE cadastro_pessoas SET nome=?, cpf=?, idade=? WHERE id=?");
    $stmt->bind_param("ssii", $nome, $cpf, $idade, $id);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
}

/* --------------------- DELETE --------------------- */
if ($method === "DELETE") {

    if (!isset($_GET["id"])) {
        echo json_encode(["error" => "ID não informado"]);
        exit;
    }

    $id = intval($_GET["id"]);

    $stmt = $conexao->prepare("DELETE FROM cadastro_pessoas WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
}

echo json_encode(["error" => "Método inválido"]);
