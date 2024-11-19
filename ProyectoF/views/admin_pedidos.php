<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Obtener pedidos
$query = $pdo->query("SELECT pedidos.*, usuarios.usuario FROM pedidos JOIN usuarios ON pedidos.usuario_id = usuarios.id ORDER BY pedidos.fecha DESC");
$pedidos = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Pedidos</title>
</head>
<body>
    <h1>Pedidos Realizados</h1>
    <table border="1">
        <tr>
            <th>ID Pedido</th>
            <th>Usuario</th>
            <th>Subtotal</th>
            <th>Total</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido['id']; ?></td>
                <td><?= htmlspecialchars($pedido['usuario']); ?></td>
                <td>$<?= number_format($pedido['subtotal'], 2); ?></td>
                <td>$<?= number_format($pedido['total'], 2); ?></td>
                <td><?= $pedido['fecha']; ?></td>
                <td>
                    <a href="ver_pedido.php?id=<?= $pedido['id']; ?>">Ver Detalles</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>