<?php

require_once 'config.php';
requiereLogin();

if (!esAdministrador()) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarea_id = (int)$_POST['tarea_id'];
    
    $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ?");
    $stmt->bind_param("i", $tarea_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Tarea eliminada exitosamente.';
    } else {
        $_SESSION['error'] = 'Error al eliminar la tarea.';
    }
    $stmt->close();
}

header('Location: dashboard.php');
exit();
?>

<?php

require_once 'config.php';
requiereLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarea_id = (int)$_POST['tarea_id'];
    $usuario_id = $_SESSION['usuario_id'];

    if (esAdministrador()) {
        $stmt = $conn->prepare("UPDATE tareas SET estado = 'completada' WHERE id = ?");
        $stmt->bind_param("i", $tarea_id);
    } else {
        $stmt = $conn->prepare("UPDATE tareas SET estado = 'completada' WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $tarea_id, $usuario_id);
    }
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $_SESSION['success'] = 'Tarea marcada como completada.';
    } else {
        $_SESSION['error'] = 'No se pudo completar la tarea.';
    }
    $stmt->close();
}

header('Location: dashboard.php');
exit();
?>

<?php

require_once 'config.php';

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header('Location: login.php');
exit();
?>