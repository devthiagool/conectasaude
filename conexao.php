<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'conecta_saude');
define('DB_USER', 'root');
define('DB_PASS', ''); // Senha vazia padrão do XAMPP

// Conexão com MySQLi
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica erros de conexão
if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

// Define charset para UTF-8
$mysqli->set_charset("utf8");

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Inicia sessão se não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>