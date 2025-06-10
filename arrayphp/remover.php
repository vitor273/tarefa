<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: listagem.php");
    exit;
}

$id = (int)$_GET['id'];
$arquivo = 'produtos.json';

if (file_exists($arquivo)) {
    $dados = json_decode(file_get_contents($arquivo), true);
    if (isset($dados[$id])) {
        unset($dados[$id]);
        $dados = array_values($dados); // reindexar
        file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT));
    }
}

header("Location: listagem.php");
exit;