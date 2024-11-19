<?php
session_start();
include '../includes/conexion.php';

// Verificar si el usuario es administrador
if ($_SESSION['usuario']['tipo_usuario'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Consultar usuarios y pedidos
$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
$pedidos = $pdo->query("SELECT * FROM pedidos")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
</head>
<body>
    <h1>Panel de Administración</h1>
    <h2>Usuarios</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Activo</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= htmlspecialchars($usuario['usuario']) ?></td>
            <td><?= htmlspecialchars($usuario['nombre'] . " " . $usuario['apellido']) ?></td>
            <td><?= htmlspecialchars($usuario['tipo_usuario']) ?></td>
            <td><?= $usuario['activo'] ? 'Sí' : 'No' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Pedidos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Total</th>
        </tr>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= $pedido['id'] ?></td>
            <td><?= $pedido['usuario_id'] ?></td>
            <td><?= $pedido['fecha'] ?></td>
            <td>$<?= number_format($pedido['total'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
