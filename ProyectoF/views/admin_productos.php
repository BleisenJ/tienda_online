<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Obtener productos
$query = $pdo->query("SELECT * FROM productos");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Productos</title>
</head>
<body>
    <h1>Administrar Productos</h1>
    <a href="crear_producto.php">Agregar Producto</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['id']; ?></td>
                <td><?= htmlspecialchars($producto['nombre']); ?></td>
                <td><?= htmlspecialchars($producto['descripción']); ?></td>
                <td>$<?= number_format($producto['precio'], 2); ?></td>
                <td>
                    <a href="editar_producto.php?id=<?= $producto['id']; ?>">Editar</a>
                    <a href="eliminar_producto.php?id=<?= $producto['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
