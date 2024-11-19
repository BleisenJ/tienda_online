<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    if (!empty($nombre) && !empty($descripcion) && is_numeric($precio)) {
        try {
            $query = $pdo->prepare("INSERT INTO productos (nombre, descripción, precio) VALUES (:nombre, :descripcion, :precio)");
            $query->execute([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
            ]);
            header('Location: admin_productos.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error al agregar el producto: " . $e->getMessage();
        }
    } else {
        $error = "Todos los campos son obligatorios y el precio debe ser numérico.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
</head>
<body>
    <h1>Crear Producto</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea>
        <br>
        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" step="0.01" required>
        <br>
        <button type="submit">Guardar Producto</button>
    </form>
    <a href="admin_productos.php">Volver a la lista de productos</a>
</body>
</html>
