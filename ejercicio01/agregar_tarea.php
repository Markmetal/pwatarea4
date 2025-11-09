<?php
require_once 'config.php';
requiereLogin();

if (!esAdministrador()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

$usuarios = $conn->query("SELECT id, nombre, email FROM usuarios ORDER BY nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = limpiarInput($_POST['titulo']);
    $descripcion = limpiarInput($_POST['descripcion']);
    $prioridad = limpiarInput($_POST['prioridad']);
    $usuario_id = (int)$_POST['usuario_id'];

    if (empty($titulo)) {
        $error = 'El título es obligatorio.';
    } else {
        $stmt = $conn->prepare("INSERT INTO tareas (titulo, descripcion, prioridad, usuario_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $titulo, $descripcion, $prioridad, $usuario_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Tarea creada exitosamente.';
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Error al crear la tarea.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Tarea - Sistema de Tareas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <div class="form-box">
            <h1>➕ Nueva Tarea</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="titulo">Título de la Tarea *</label>
                    <input type="text" id="titulo" name="titulo" required 
                           placeholder="Ej: Completar informe mensual">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" 
                              placeholder="Describe los detalles de la tarea..."></textarea>
                </div>

                <div class="form-group">
                    <label for="prioridad">Prioridad</label>
                    <select id="prioridad" name="prioridad" required>
                        <option value="baja">🟢 Baja</option>
                        <option value="media" selected>🟡 Media</option>
                        <option value="alta">🔴 Alta</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="usuario_id">Asignar a Usuario</label>
                    <select id="usuario_id" name="usuario_id" required>
                        <?php while($usuario = $usuarios->fetch_assoc()): ?>
                            <option value="<?php echo $usuario['id']; ?>">
                                <?php echo htmlspecialchars($usuario['nombre']) . ' (' . htmlspecialchars($usuario['email']) . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">✓ Crear Tarea</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>