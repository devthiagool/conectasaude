<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'paciente') {
    header('Location: login.php');
    exit;
}

// Consultas fake para teste
$consultas = [
    [
        'id' => 1,
        'medico' => 'Dr. Carlos Silva',
        'especialidade' => 'Cardiologia',
        'data' => '2024-01-15 14:00',
        'tipo' => 'presencial',
        'status' => 'agendado',
        'motivo' => 'Dor no peito'
    ],
    [
        'id' => 2,
        'medico' => 'Dra. Ana Souza',
        'especialidade' => 'Pediatria',
        'data' => '2024-01-20 10:30',
        'tipo' => 'online',
        'status' => 'confirmado',
        'motivo' => 'Check-up infantil'
    ],
    [
        'id' => 3,
        'medico' => 'Dr. Roberto Lima',
        'especialidade' => 'Ortopedia',
        'data' => '2024-01-25 16:15',
        'tipo' => 'presencial',
        'status' => 'pendente',
        'motivo' => 'Dor na coluna'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Consultas - Conecta Saúde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0077b6;
            --secondary: #00b4d8;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-agendado { background-color: #fff3cd; color: #856404; }
        .status-confirmado { background-color: #d1ecf1; color: #0c5460; }
        .status-realizado { background-color: #d4edda; color: #155724; }
        .status-cancelado { background-color: #f8d7da; color: #721c24; }
        .status-pendente { background-color: #e2e3e5; color: #383d41; }
        .consulta-card {
            border-left: 4px solid var(--primary);
            transition: all 0.3s;
        }
        .consulta-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .filter-badge {
            cursor: pointer;
            transition: all 0.3s;
        }
        .filter-badge:hover {
            transform: scale(1.05);
        }
        .filter-badge.active {
            background-color: var(--primary) !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <?php include_once 'navbar.php'; ?>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">
                <i class="bi bi-calendar-check"></i> Minhas Consultas
            </h1>
            <a href="agendar.php" class="btn btn-primary">
                <i class="bi bi-calendar-plus"></i> Nova Consulta
            </a>
        </div>
        
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="mb-3">Filtrar por:</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark filter-badge active" data-filter="all">Todas</span>
                    <span class="badge bg-light text-dark filter-badge" data-filter="agendado">Agendadas</span>
                    <span class="badge bg-light text-dark filter-badge" data-filter="confirmado">Confirmadas</span>
                    <span class="badge bg-light text-dark filter-badge" data-filter="pendente">Pendentes</span>
                    <span class="badge bg-light text-dark filter-badge" data-filter="realizado">Realizadas</span>
                    <span class="badge bg-light text-dark filter-badge" data-filter="cancelado">Canceladas</span>
                </div>
            </div>
        </div>
        
        <!-- Lista de Consultas -->
        <div class="row">
            <?php foreach($consultas as $consulta): ?>
                <div class="col-lg-6 mb-4 consulta-item" data-status="<?php echo $consulta['status']; ?>">
                    <div class="card consulta-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1"><?php echo $consulta['medico']; ?></h5>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-star"></i> <?php echo $consulta['especialidade']; ?>
                                    </p>
                                </div>
                                <span class="status-badge status-<?php echo $consulta['status']; ?>">
                                    <?php echo ucfirst($consulta['status']); ?>
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-2">
                                    <i class="bi bi-calendar"></i> 
                                    <strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($consulta['data'])); ?>
                                </p>
                                <p class="mb-2">
                                    <i class="bi bi-laptop"></i> 
                                    <strong>Tipo:</strong> 
                                    <span class="badge bg-light text-dark">
                                        <?php echo $consulta['tipo'] == 'presencial' ? 'Presencial' : 'Online'; ?>
                                    </span>
                                </p>
                                <p class="mb-0">
                                    <i class="bi bi-chat"></i> 
                                    <strong>Motivo:</strong> <?php echo $consulta['motivo']; ?>
                                </p>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <a href="ver-consulta.php?id=<?php echo $consulta['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Ver Detalhes
                                </a>
                                
                                <?php if($consulta['status'] == 'agendado' || $consulta['status'] == 'confirmado'): ?>
                                    <a href="remarcar.php?id=<?php echo $consulta['id']; ?>" 
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-calendar-x"></i> Remarcar
                                    </a>
                                <?php endif; ?>
                                
                                <?php if($consulta['status'] == 'agendado'): ?>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="cancelarConsulta(<?php echo $consulta['id']; ?>)">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(count($consultas) == 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-calendar-x display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">Nenhuma consulta agendada</h4>
                <p class="text-muted">Agende sua primeira consulta agora mesmo!</p>
                <a href="agendar.php" class="btn btn-primary btn-lg mt-2">
                    <i class="bi bi-calendar-plus"></i> Agendar Consulta
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Filtro de consultas
    document.querySelectorAll('.filter-badge').forEach(badge => {
        badge.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Atualiza badges ativos
            document.querySelectorAll('.filter-badge').forEach(b => {
                b.classList.remove('active');
            });
            this.classList.add('active');
            
            // Filtra consultas
            document.querySelectorAll('.consulta-item').forEach(item => {
                if (filter === 'all' || item.getAttribute('data-status') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    function cancelarConsulta(id) {
        if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
            alert('Consulta #' + id + ' cancelada com sucesso!');
            // Em produção, aqui faria uma requisição AJAX para cancelar
            window.location.reload();
        }
    }
    </script>
</body>
</html>