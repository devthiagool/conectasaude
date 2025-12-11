<?php
// Inicia sessão no TOPO do arquivo
session_start();

// Configuração do banco (simplificada)
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'conecta_saude';

$erro = '';

// Conectar ao banco
$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    // Se não conseguir conectar, continua sem banco (para desenvolvimento)
    $modo_desenvolvimento = true;
}

// Processar login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $senha_form = $_POST['senha'] ?? '';
    
    // Validação simples
    if (empty($email) || empty($senha_form)) {
        $erro = "Preencha todos os campos!";
    } else {
        // Modo desenvolvimento: login falso para testar
        if (isset($modo_desenvolvimento)) {
            // Login fake para testes
            $_SESSION['usuario_id'] = 1;
            $_SESSION['usuario_nome'] = "Usuário Teste";
            $_SESSION['usuario_email'] = $email;
            $_SESSION['usuario_tipo'] = 'paciente';
            
            header('Location: index.php');
            exit;
        } else {
            // Login real com banco de dados
            $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($user = $result->fetch_assoc()) {
                if (password_verify($senha_form, $user['senha'])) {
                    $_SESSION['usuario_id'] = $user['id'];
                    $_SESSION['usuario_nome'] = $user['nome'];
                    $_SESSION['usuario_email'] = $user['email'];
                    $_SESSION['usuario_tipo'] = $user['tipo'];
                    
                    header('Location: index.php');
                    exit;
                } else {
                    $erro = "Senha incorreta!";
                }
            } else {
                $erro = "Usuário não encontrado!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Conecta Saúde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0077b6;
            --secondary: #00b4d8;
            --light: #e0f7fa;
            --dark: #023e8a;
        }
        body {
            background: linear-gradient(135deg, var(--light), #ffffff);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .login-header {
            background-color: var(--primary);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: var(--dark);
            border-color: var(--dark);
        }
        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.25rem rgba(0, 180, 216, 0.25);
        }
    </style>
</head>
<body>
    <!-- Navbar SIMPLES (dentro do mesmo arquivo) -->
    <nav style="background: #0077b6; padding: 15px 0;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="index.php" style="color: white; text-decoration: none; font-size: 1.2rem;">
                    <strong>🏥 Conecta Saúde</strong>
                </a>
                <div>
                    <a href="index.php" style="color: white; margin: 0 10px; text-decoration: none;">Home</a>
                    <a href="cadastro.php" style="color: white; margin: 0 10px; text-decoration: none;">Cadastrar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header text-center">
                <h4 class="mb-0">Login</h4>
            </div>
            
            <div class="card-body p-4">
                <?php if($erro): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $erro; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo $_POST['email'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="lembrar">
                        <label class="form-check-label" for="lembrar">Lembrar-me</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
                    
                    <div class="text-center mb-3">
                        <a href="#" style="text-decoration: none;">Esqueci minha senha</a>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p class="mb-0">Não tem uma conta?</p>
                        <a href="cadastro.php" class="btn btn-outline-primary mt-2 w-100">Cadastre-se</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer SIMPLES (dentro do mesmo arquivo) -->
    <footer style="background: #023e8a; color: white; padding: 20px 0; margin-top: auto;">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Conecta Saúde. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>