<?php
require_once 'config.php';

$error = '';
$success = '';

if (estaLogueado()) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = limpiarInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        $stmt = $conn->prepare("SELECT nombre, email, clave, FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($password, $usuario['password'])) {
                $_SESSION['usuarios'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['rol'] = $usuario['rol'];
                
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Credenciales incorrectas.';
            }
        } else {
            $error = 'Credenciales incorrectas.';
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
    <title>Login - Sistema de Tareas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>🔐 Iniciar Sesión</h1>
            <p class="subtitle">Sistema de Gestión de Tareas</p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    ⚠️ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    ✓ <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">📧 Correo Electrónico</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="tu@email.com">
                </div>

                <div class="form-group">
                    <label for="password">🔒 Contraseña</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary">Ingresar</button>
            </form>

            <div class="demo-credentials">
                <h3>👥 administrador</h3>
                <div class="credential-box">
                    <strong>Administrador:</strong><br>
                    Email: mark666dark@gmail.com<br>
                    Password: 12345
                </div>
                <div class="credential-box">
                    <strong>Usuario:</strong><br>
                    Email:admin@gmail.com<br>
                    Password: 1234
                </div>
            </div>

            <a href="../" class="back-link">← Volver al menú principal</a>
        </div>
    </div>
</body>
</html>