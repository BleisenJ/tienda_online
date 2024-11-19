<?php
session_start();
include '../includes/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    try {
        $query = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $query->execute(['usuario' => $usuario]);
        $usuarioData = $query->fetch(PDO::FETCH_ASSOC);

        if ($usuarioData && password_verify($contraseña, $usuarioData['contraseña'])) {
            $_SESSION['usuario'] = [
                'id' => $usuarioData['id'],
                'usuario' => $usuarioData['usuario'],
                'tipo_usuario' => $usuarioData['tipo_usuario']
            ];
            if ($usuarioData['tipo_usuario'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: ../index.php');
            }
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    } catch (PDOException $e) {
        $error = "Error al iniciar sesión: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required>
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" id="contraseña" required>
        <br>
        <button type="submit">Ingresar</button>
    </form>
    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>
</body>
</html>