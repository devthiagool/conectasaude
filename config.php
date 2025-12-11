<?php
// Inicia sessão se não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configurações básicas
date_default_timezone_set('America/Sao_Paulo');

// Funções úteis
function conectarBanco() {
    $host = 'localhost';
    $usuario = 'root';
    $senha = '';
    $banco = 'conecta_saude';
    
    $conexao = new mysqli($host, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }
    
    $conexao->set_charset("utf8");
    return $conexao;
}
?>