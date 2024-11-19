<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Procesar compra
$mensaje = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subtotal = 0;

    foreach ($_SESSION['carrito'] as $item) {
        $subtotal += $item['precio'] * $item['cantidad'];
    }

    $impuesto = $subtotal * 0.07;
    $total = $subtotal + $impuesto;

    try {
        // Guardar pedido
        $query = $pdo->prepare("INSERT INTO pedidos (usuario_id, subtotal, total, fecha) VALUES (:usuario_id, :subtotal, :total, NOW())");
        $query->execute([
            'usuario_id' => $_SESSION['usuario']['id'],
            'subtotal' => $subtotal,
            'total' => $total,
        ]);

        // Limpiar carrito
        $_SESSION['carrito'] = [];
        $mensaje = "¡Compra procesada con éxito!";
    } catch (PDOException $e) {
        $error = "Error al procesar la compra: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
</head>
<body>
    <h1>Carrito de Compras</h1>
    <?php if (!empty($mensaje)): ?>
        <p style="color: green;"><?= htmlspecialchars($mensaje) ?></p>
    <?php elseif (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <div class="carrito">
        <?php if (!empty($_SESSION['carrito'])): ?>
            <table border="1">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
                <?php $subtotal = 0; ?>
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre']); ?></td>
                        <td>$<?= number_format($item['precio'], 2); ?></td>
                        <td><?= $item['cantidad']; ?></td>
                        <td>$<?= number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                    </tr>
                    <?php $subtotal += $item['precio'] * $item['cantidad']; ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3">Subtotal</td>
                    <td>$<?= number_format($subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3">Impuesto (7%)</td>
                    <td>$<?= number_format($subtotal * 0.07, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>$<?= number_format($subtotal + ($subtotal * 0.07), 2); ?></strong></td>
                </tr>
            </table>
            <form action="" method="POST">
                <button type="submit">Procesar Compra</button>
            </form>
        <?php else: ?>
            <p>El carrito está vacío.</p>
        <?php endif; ?>
    </div>
    <a href="../index.php">Volver al catálogo</a>
</body>
</html>