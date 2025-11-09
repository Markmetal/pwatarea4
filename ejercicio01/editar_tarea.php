<?php
require_once 'config.php';
requiereLogin();

if (!esAdministrador()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$tarea_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM tareas WHERE id = ?");
$stmt->bind_param("i", $tarea_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header('Location: dashboard.php');
    exit();
}

$tarea = $resultado->fetch_assoc();
$stmt->close();

$usuarios = $conn->query("SELECT id, nombre, email FROM usuarios ORDER BY nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = limpiarInput($_POST['titulo']);
    $descripcion = limpiarInput($_POST['descripcion']);
    $prioridad = limpiarInput($_POST['prioridad']);
    $estado = limpiarInput($_POST['estado']);
    $usuario_id = (int)$_POST['usuario_id'];

    if (empty($titulo)) {
        $error = 'El título es obligatorio.';
    } else {
        $stmt = $conn->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, prioridad = ?, estado = ?, usuario_id = ? WHERE id = ?");
        $stmt->bind_param("ssssii", $titulo, $descripcion, $prioridad, $estado, $usuario_id, $tarea_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Tarea actualizada exitosamente.';
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Error al actualizar la tarea.';
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
    <title>Editar Tarea - Sistema de Tareas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <div class="form-box">
            <h1>✏️ Editar Tarea</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="titulo">Título de la Tarea *</label>
                    <input type="text" id="titulo" name="titulo" required 
                           value="<?php echo htmlspecialchars($tarea['titulo']); ?>">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($tarea['descripcion']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="prioridad">Prioridad</label>
                    <select id="prioridad" name="prioridad" required>
                        <option value="baja" <?php echo $tarea['prioridad'] === 'baja' ? 'selected' : ''; ?>>🟢 Baja</option>
                        <option value="media" <?php echo $tarea['prioridad'] === 'media' ? 'selected' : ''; ?>>🟡 Media</option>
                        <option value="alta" <?php echo $tarea['prioridad'] === 'alta' ? 'selected' : ''; ?>>🔴 Alta</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        <option value="pendiente" <?php echo $tarea['estado'] === 'pendiente' ? 'selected' : ''; ?>>⏳ Pendiente</option>
                        <option value="completada" <?php echo $tarea['estado'] === 'completada' ? 'selected' : ''; ?>>✅ Completada</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="usuario_id">Asignar a Usuario</label>
                    <select id="usuario_id" name="usuario_id" required>
                        <?php while($usuario = $usuarios->fetch_assoc()): ?>
                            <option value="<?php echo $usuario['id']; ?>" 
                                    <?php echo $usuario['id'] === $tarea['usuario_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($usuario['nombre']) . ' (' . htmlspecialchars($usuario['email']) . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">✓ Actualizar Tarea</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>