<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    $query = $pdo->prepare("SELECT detalles_pedido.*, productos.nombre FROM detalles_pedido JOIN productos ON detalles_pedido.producto_id = productos.id WHERE pedido_id = :id");
    $query->execute(['id' => $id]);
    $detalles = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: admin_pedidos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
</head>
<body>
    <h1>Detalles del Pedido #<?= htmlspecialchars($id); ?></h1>
    <table border="1">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($detalles as $detalle): ?>
            <tr>
                <td><?= htmlspecialchars($detalle['nombre']); ?></td>
                <td><?= $detalle['cantidad']; ?></td>
                <td>$<?= number_format($detalle['precio'], 2); ?></td>
                <td>$<?= number_format($detalle['cantidad'] * $detalle['precio'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="admin_pedidos.php">Volver a Pedidos</a>
</body>
</html>
