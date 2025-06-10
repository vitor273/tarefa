<?php
session_start();

// Verifica se os arquivos existem
if (!file_exists('email.json') || !file_exists('senha.json')) {
    file_put_contents('email.json', json_encode([]));
    file_put_contents('senha.json', json_encode([]));
}

// Lê os arquivos
$emails = json_decode(file_get_contents('email.json'), true);
$senhas = json_decode(file_get_contents('senha.json'), true);

// Dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica se o e-mail está na lista
$index = array_search($email, $emails);
if ($index !== false && isset($senhas[$index]) && $senhas[$index] === $senha) {
    $_SESSION['usuario'] = $email;
    header("Location: inicial.php");
    exit;
} else {
    header("Location: index.php?erro=1");
    exit;
}