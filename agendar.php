<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'paciente') {
    header('Location: login.php');
    exit;
}

$sucesso = '';
$erro = '';

// Médicos fake para teste
$medicos = [
    ['id' => 1, 'nome' => 'Dr. Carlos Silva', 'especialidade' => 'Cardiologia'],
    ['id' => 2, 'nome' => 'Dra. Ana Souza', 'especialidade' => 'Pediatria'],
    ['id' => 3, 'nome' => 'Dr. Roberto Lima', 'especialidade' => 'Ortopedia'],
    ['id' => 4, 'nome' => 'Dra. Mariana Costa', 'especialidade' => 'Dermatologia'],
    ['id' => 5, 'nome' => 'Dr. Pedro Santos', 'especialidade' => 'Neurologia']
];

// Processar agendamento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $medico_id = $_POST['medico'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $tipo = $_POST['tipo'];
    $motivo = trim($_POST['motivo']);
    
    if (empty($medico_id) || empty($data) || empty($hora) || empty($motivo)) {
        $erro = "Preencha todos os campos obrigatórios!";
    } else {
        // Simular sucesso
        $sucesso = "Consulta agendada com sucesso para " . date('d/m/Y H:i', strtotime("$data $hora")) . "!";
        
        // Simular armazenamento (em produção, salvaria no banco)
        $_SESSION['ultimo_agendamento'] = [
            'medico' => $medicos[array_search($medico_id, array_column($medicos, 'id'))]['nome'],
            'data' => "$data $hora",
            'tipo' => $tipo,
            'motivo' => $motivo
        ];
        
        // Limpar formulário
        $_POST = [];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta - Conecta Saúde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0077b6;
            --secondary: #00b4d8;
        }
        .agendamento-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: #005a87;
        }
        .medico-card {
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }
        .medico-card:hover, .medico-card.selected {
            border-color: var(--primary);
            background-color: rgba(0, 119, 182, 0.05);
        }
        .calendar-day {
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .calendar-day:hover {
            background-color: rgba(0, 119, 182, 0.1);
        }
        .calendar-day.selected {
            background-color: var(--primary);
            color: white;
        }
        .time-slot {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .time-slot:hover, .time-slot.selected {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <?php include_once 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="agendamento-card">
                    <div class="card-header text-center py-3">
                        <h4 class="mb-0"><i class="bi bi-calendar-plus"></i> Agendar Nova Consulta</h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if($sucesso): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <h5><i class="bi bi-check-circle"></i> Sucesso!</h5>
                                <?php echo $sucesso; ?>
                                <div class="mt-3">
                                    <a href="minhas-consultas.php" class="btn btn-success">Ver Minhas Consultas</a>
                                    <a href="dashboard.php" class="btn btn-outline-success">Voltar ao Dashboard</a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($erro): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $erro; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" id="formAgendamento">
                            <!-- Passo 1: Escolher Médico -->
                            <div class="mb-5">
                                <h5 class="mb-4 text-primary">
                                    <i class="bi bi-heart-pulse"></i> Passo 1: Escolha um Profissional
                                </h5>
                                <div class="row">
                                    <?php foreach($medicos as $medico): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card medico-card p-3" 
                                                 onclick="selectMedico(<?php echo $medico['id']; ?>, this)">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; color: white; margin-right: 15px;">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1"><?php echo $medico['nome']; ?></h6>
                                                        <small class="text-muted">
                                                            <i class="bi bi-star"></i> <?php echo $medico['especialidade']; ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="medico" id="medicoSelecionado" required>
                            </div>
                            
                            <!-- Passo 2: Escolher Data -->
                            <div class="mb-5">
                                <h5 class="mb-4 text-primary">
                                    <i class="bi bi-calendar"></i> Passo 2: Escolha a Data
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="data" class="form-label">Data da Consulta *</label>
                                        <input type="date" class="form-control" id="data" name="data" 
                                               min="<?php echo date('Y-m-d'); ?>" 
                                               value="<?php echo $_POST['data'] ?? ''; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="hora" class="form-label">Horário *</label>
                                        <select class="form-select" id="hora" name="hora" required>
                                            <option value="">Selecione um horário</option>
                                            <?php
                                            $horarios = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00'];
                                            foreach ($horarios as $horario): ?>
                                                <option value="<?php echo $horario; ?>" 
                                                    <?php echo ($_POST['hora'] ?? '') == $horario ? 'selected' : ''; ?>>
                                                    <?php echo $horario; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Passo 3: Tipo de Consulta -->
                            <div class="mb-5">
                                <h5 class="mb-4 text-primary">
                                    <i class="bi bi-laptop"></i> Passo 3: Tipo de Consulta
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card text-center p-4 tipo-consulta" 
                                             onclick="selectTipo('presencial')">
                                            <i class="bi bi-building fs-1 text-primary mb-3"></i>
                                            <h6>Consulta Presencial</h6>
                                            <small class="text-muted">No consultório do médico</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card text-center p-4 tipo-consulta" 
                                             onclick="selectTipo('online')">
                                            <i class="bi bi-camera-video fs-1 text-primary mb-3"></i>
                                            <h6>Consulta Online</h6>
                                            <small class="text-muted">Via videochamada</small>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="tipo" id="tipoConsulta" value="presencial" required>
                            </div>
                            
                            <!-- Passo 4: Motivo -->
                            <div class="mb-5">
                                <h5 class="mb-4 text-primary">
                                    <i class="bi bi-chat"></i> Passo 4: Motivo da Consulta
                                </h5>
                                <div class="mb-3">
                                    <label for="motivo" class="form-label">Descreva o motivo da consulta *</label>
                                    <textarea class="form-control" id="motivo" name="motivo" rows="4" 
                                              placeholder="Ex: Dor de cabeça frequente, check-up anual, etc." 
                                              required><?php echo $_POST['motivo'] ?? ''; ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="dashboard.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-calendar-check"></i> Confirmar Agendamento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let medicoSelecionado = null;
    let tipoSelecionado = 'presencial';
    
    function selectMedico(id, element) {
        // Remove seleção anterior
        document.querySelectorAll('.medico-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Adiciona seleção atual
        element.classList.add('selected');
        document.getElementById('medicoSelecionado').value = id;
        medicoSelecionado = id;
    }
    
    function selectTipo(tipo) {
        tipoSelecionado = tipo;
        document.getElementById('tipoConsulta').value = tipo;
        
        // Atualiza visual
        document.querySelectorAll('.tipo-consulta').forEach(card => {
            card.classList.remove('selected');
            card.style.borderColor = '#dee2e6';
        });
        
        // Destaca o selecionado
        if (tipo === 'presencial') {
            document.querySelectorAll('.tipo-consulta')[0].style.borderColor = '#0077b6';
        } else {
            document.querySelectorAll('.tipo-consulta')[1].style.borderColor = '#0077b6';
        }
    }
    
    // Inicializa tipo presencial
    selectTipo('presencial');
    
    // Validação do formulário
    document.getElementById('formAgendamento').addEventListener('submit', function(e) {
        if (!medicoSelecionado) {
            e.preventDefault();
            alert('Por favor, selecione um profissional!');
            return false;
        }
        
        const motivo = document.getElementById('motivo').value.trim();
        if (motivo.length < 10) {
            e.preventDefault();
            alert('Por favor, descreva melhor o motivo da consulta (mínimo 10 caracteres).');
            return false;
        }
        
        return true;
    });
    
    // Configura data mínima para hoje
    document.getElementById('data').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>