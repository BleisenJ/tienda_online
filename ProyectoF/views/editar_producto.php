<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = '';
$id = $_GET['id'] ?? null;

if ($id) {
    $query = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
    $query->execute(['id' => $id]);
    $producto = $query->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        header('Location: admin_productos.php');
        exit;
    }
} else {
    header('Location: admin_productos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    if (!empty($nombre) && !empty($descripcion) && is_numeric($precio)) {
        try {
            $query = $pdo->prepare("UPDATE productos SET nombre = :nombre, descripción = :descripcion, precio = :precio WHERE id = :id");
            $query->execute([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'id' => $id,
            ]);
            header('Location: admin_productos.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error al actualizar el producto: " . $e->getMessage();
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
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($producto['nombre']); ?>" required>
        <br>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required><?= htmlspecialchars($producto['descripción']); ?></textarea>
        <br>
        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" step="0.01" value="<?= htmlspecialchars($producto['precio']); ?>" required>
        <br>
        <button type="submit">Guardar Cambios</button>
    </form>
    <a href="admin_productos.php">Volver a la lista de productos</a>
</body>
</html>
