<?php
session_start(); // Mantém aqui

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$sucesso = '';
$erro = '';

// Processar atualização do perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aqui você processaria a atualização do perfil
    // Por enquanto, apenas simulação
    $sucesso = "Perfil atualizado com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Conecta Saúde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include_once 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-person"></i> Meu Perfil</h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if($sucesso): ?>
                            <div class="alert alert-success"><?php echo $sucesso; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="text-center mb-4">
                                <?php
                                $foto = isset($_SESSION['usuario_foto']) ? 'uploads/' . $_SESSION['usuario_foto'] : 'assets/default-avatar.png';
                                if (!file_exists($foto)) {
                                    $foto = 'assets/default-avatar.png';
                                }
                                ?>
                                <img src="<?php echo $foto; ?>" 
                                     class="rounded-circle mb-3" 
                                     alt="Foto de perfil"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <p class="text-muted">Clique na imagem para alterar</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" 
                                           value="<?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">E-mail</label>
                                    <input type="email" class="form-control" 
                                           value="<?php echo htmlspecialchars($_SESSION['usuario_email']); ?>" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Usuário</label>
                                    <input type="text" class="form-control" 
                                           value="<?php echo ucfirst($_SESSION['usuario_tipo'] ?? 'Paciente'); ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarPerfil">
                                    <i class="bi bi-pencil"></i> Editar Perfil
                                </button>
                                <a href="dashboard.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Perfil -->
    <div class="modal fade" id="modalEditarPerfil" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Funcionalidade em desenvolvimento...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>