<?php
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);

    try {
        $query = $pdo->prepare("INSERT INTO usuarios (usuario, nombre, apellido, contraseña, tipo_usuario, activo) VALUES (:usuario, :nombre, :apellido, :contraseña, 'cliente', 1)");
        $query->execute([
            'usuario' => $usuario,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'contraseña' => $contraseña,
        ]);
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $error = "Error al registrar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required>
        <br>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required>
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" id="contraseña" required>
        <br>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>