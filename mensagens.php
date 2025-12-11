<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Mensagens fake para teste
$mensagens = [
    [
        'id' => 1,
        'remetente' => 'Dr. Carlos Silva',
        'assunto' => 'Confirmação de consulta',
        'mensagem' => 'Sua consulta foi confirmada para 15/01/2024 às 14:00.',
        'data' => '2024-01-10 09:30',
        'lida' => true
    ],
    [
        'id' => 2,
        'remetente' => 'Dra. Ana Souza',
        'assunto' => 'Resultados de exames',
        'mensagem' => 'Seus exames já estão disponíveis no portal.',
        'data' => '2024-01-09 14:15',
        'lida' => false
    ],
    [
        'id' => 3,
        'remetente' => 'Sistema Conecta Saúde',
        'assunto' => 'Bem-vindo ao sistema',
        'mensagem' => 'Obrigado por se cadastrar! Esperamos que tenha uma ótima experiência.',
        'data' => '2024-01-08 10:00',
        'lida' => true
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens - Conecta Saúde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0077b6;
            --secondary: #00b4d8;
        }
        .mensagem-nao-lida {
            background-color: #f0f9ff;
            border-left: 4px solid var(--primary);
        }
        .mensagem-lida {
            background-color: #f8f9fa;
        }
        .mensagem-item {
            cursor: pointer;
            transition: all 0.3s;
        }
        .mensagem-item:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .badge-nao-lido {
            background-color: var(--primary);
            color: white;
            font-size: 0.6rem;
            padding: 3px 6px;
        }
        .btn-nova-mensagem {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <?php include_once 'navbar.php'; ?>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">
                <i class="bi bi-chat"></i> Mensagens
                <span class="badge bg-primary"><?php echo count($mensagens); ?></span>
            </h1>
            <div class="btn-group">
                <button class="btn btn-outline-primary" onclick="marcarTodasComoLidas()">
                    <i class="bi bi-check-all"></i> Marcar todas como lidas
                </button>
                <button class="btn btn-outline-danger" onclick="limparMensagens()">
                    <i class="bi bi-trash"></i> Limpar todas
                </button>
            </div>
        </div>
        
        <div class="row">
            <!-- Lista de Mensagens -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Buscar mensagens..." 
                                   id="buscarMensagens">
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <?php foreach($mensagens as $msg): ?>
                            <a href="#" class="list-group-item list-group-item-action mensagem-item 
                                <?php echo $msg['lida'] ? 'mensagem-lida' : 'mensagem-nao-lida'; ?>"
                               data-bs-toggle="modal" data-bs-target="#modalMensagem<?php echo $msg['id']; ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; color: white;">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">
                                                <?php echo $msg['remetente']; ?>
                                                <?php if(!$msg['lida']): ?>
                                                    <span class="badge-nao-lido badge ms-2">Nova</span>
                                                <?php endif; ?>
                                            </h6>
                                            <p class="mb-1 text-dark"><?php echo $msg['assunto']; ?></p>
                                            <small class="text-muted">
                                                <?php echo substr($msg['mensagem'], 0, 60); ?>...
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">
                                            <?php echo date('d/m/Y', strtotime($msg['data'])); ?>
                                        </small>
                                        <small class="text-muted">
                                            <?php echo date('H:i', strtotime($msg['data'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Modal da Mensagem -->
                            <div class="modal fade" id="modalMensagem<?php echo $msg['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?php echo $msg['assunto']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; color: white; margin-right: 15px;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo $msg['remetente']; ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y H:i', strtotime($msg['data'])); ?>
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <div class="mensagem-conteudo p-3 bg-light rounded">
                                                <?php echo nl2br(htmlspecialchars($msg['mensagem'])); ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            <button type="button" class="btn btn-primary" onclick="responderMensagem(<?php echo $msg['id']; ?>)">
                                                <i class="bi bi-reply"></i> Responder
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if(count($mensagens) == 0): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-envelope display-1 text-muted"></i>
                            <h4 class="mt-3 text-muted">Nenhuma mensagem</h4>
                            <p class="text-muted">Suas mensagens aparecerão aqui.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Contatos Frequentes -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-people"></i> Contatos Frequentes</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                 style="width: 35px; height: 35px; color: white; margin-right: 10px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <small class="fw-bold">Dr. Carlos Silva</small>
                                <small class="d-block text-muted">Cardiologista</small>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" 
                                 style="width: 35px; height: 35px; color: white; margin-right: 10px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <small class="fw-bold">Dra. Ana Souza</small>
                                <small class="d-block text-muted">Pediatra</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botão Nova Mensagem -->
    <button class="btn btn-primary btn-nova-mensagem" data-bs-toggle="modal" data-bs-target="#modalNovaMensagem">
        <i class="bi bi-plus-lg"></i>
    </button>

    <!-- Modal Nova Mensagem -->
    <div class="modal fade" id="modalNovaMensagem" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-envelope-plus"></i> Nova Mensagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNovaMensagem">
                        <div class="mb-3">
                            <label for="destinatario" class="form-label">Para:</label>
                            <select class="form-select" id="destinatario" required>
                                <option value="">Selecione um destinatário</option>
                                <option value="1">Dr. Carlos Silva (Cardiologista)</option>
                                <option value="2">Dra.