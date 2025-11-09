<?php
require_once 'config.php';
requiereLogin();


$usuario_id = $_SESSION['usuario_id'];

if (esAdministrador()) {

    $sql_tareas = "SELECT t.*, u.nombre as nombre_usuario 
                   FROM tareas t 
                   JOIN usuarios u ON t.usuario_id = u.id 
                   ORDER BY t.fecha_creacion DESC";
    $resultado_tareas = $conn->query($sql_tareas);
    
    $sql_stats = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END) as completadas,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes
                  FROM tareas";
} else {

    $sql_tareas = "SELECT t.*, u.nombre as nombre_usuario 
                   FROM tareas t 
                   JOIN usuarios u ON t.usuario_id = u.id 
                   WHERE t.usuario_id = ? 
                   ORDER BY t.fecha_creacion DESC";
    $stmt = $conn->prepare($sql_tareas);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado_tareas = $stmt->get_result();
    
    $sql_stats = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END) as completadas,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes
                  FROM tareas 
                  WHERE usuario_id = ?";
    $stmt_stats = $conn->prepare($sql_stats);
    $stmt_stats->bind_param("i", $usuario_id);
    $stmt_stats->execute();
    $resultado_stats = $stmt_stats->get_result();
    $stats = $resultado_stats->fetch_assoc();
    $stmt_stats->close();
}

if (!isset($stats)) {
    $resultado_stats = $conn->query($sql_stats);
    $stats = $resultado_stats->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Tareas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="dashboard">

        <header class="dashboard-header">
            <div class="header-content">
                <h1>📋 Sistema de Tareas</h1>
                <div class="user-info">
                    <span class="user-name">👤 <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                    <span class="user-role <?php echo $_SESSION['rol']; ?>">
                        <?php echo $_SESSION['rol'] === 'administrador' ? '⭐ Admin' : '👨‍💼 Usuario'; ?>
                    </span>
                    <a href="logout.php" class="btn btn-logout">Salir</a>
                </div>
            </div>
        </header>

        <div class="dashboard-content">

            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total']; ?></h3>
                        <p>Total de Tareas</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pendientes']; ?></h3>
                        <p>Pendientes</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['completadas']; ?></h3>
                        <p>Completadas</p>
                    </div>
                </div>
            </div>

            <div class="actions-bar">
                <?php if (esAdministrador()): ?>
                    <a href="agregar_tarea.php" class="btn btn-primary">➕ Nueva Tarea</a>
                <?php endif; ?>
                <div class="filter-buttons">
                    <button class="btn btn-filter active" data-filter="todas">Todas</button>
                    <button class="btn btn-filter" data-filter="pendiente">Pendientes</button>
                    <button class="btn btn-filter" data-filter="completada">Completadas</button>
                </div>
            </div>

            <div class="tareas-container">
                <?php if ($resultado_tareas->num_rows > 0): ?>
                    <?php while($tarea = $resultado_tareas->fetch_assoc()): ?>
                        <div class="tarea-card <?php echo $tarea['estado']; ?>" data-estado="<?php echo $tarea['estado']; ?>">
                            <div class="tarea-header">
                                <h3><?php echo htmlspecialchars($tarea['titulo']); ?></h3>
                                <span class="prioridad-badge <?php echo $tarea['prioridad']; ?>">
                                    <?php echo ucfirst($tarea['prioridad']); ?>
                                </span>
                            </div>
                            
                            <p class="tarea-descripcion">
                                <?php echo htmlspecialchars($tarea['descripcion']); ?>
                            </p>
                            
                            <div class="tarea-meta">
                                <span>👤 <?php echo htmlspecialchars($tarea['nombre_usuario']); ?></span>
                                <span>📅 <?php echo date('d/m/Y', strtotime($tarea['fecha_creacion'])); ?></span>
                            </div>
                            
                            <div class="tarea-actions">
                                <?php if ($tarea['estado'] === 'pendiente'): ?>
                                    <form method="POST" action="marcar_completada.php" style="display: inline;">
                                        <input type="hidden" name="tarea_id" value="<?php echo $tarea['id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm">✓ Completar</button>
                                    </form>
                                <?php else: ?>
                                    <span class="estado-badge completada">✓ Completada</span>
                                <?php endif; ?>
                                
                                <?php if (esAdministrador()): ?>
                                    <a href="editar_tarea.php?id=<?php echo $tarea['id']; ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                                    <form method="POST" action="eliminar_tarea.php" style="display: inline;" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta tarea?');">
                                        <input type="hidden" name="tarea_id" value="<?php echo $tarea['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h2>📭 No hay tareas</h2>
                        <p>Comienza agregando una nueva tarea</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="js/scripts.js"></script>
</body>
</html>