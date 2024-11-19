<?php
session_start();
include 'includes/conexion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario']['id'];
    $carrito = $_SESSION['carrito'] ?? [];
    $subtotal = 0;

    foreach ($carrito as $item) {
        $subtotal += $item['precio'] * $item['cantidad'];
    }

    $impuesto = $subtotal * 0.07; // 7% de impuesto
    $total = $subtotal + $impuesto;

    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Insertar pedido
        $query = $pdo->prepare("INSERT INTO pedidos (usuario_id, subtotal, total, fecha) VALUES (:usuario_id, :subtotal, :total, NOW())");
        $query->execute([
            'usuario_id' => $usuario_id,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);

        $pedido_id = $pdo->lastInsertId();

        // Insertar detalles del pedido
        $query_detalle = $pdo->prepare("INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio) VALUES (:pedido_id, :producto_id, :cantidad, :precio)");

        foreach ($carrito as $item) {
            $query_detalle->execute([
                'pedido_id' => $pedido_id,
                'producto_id' => $item['id'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio'],
            ]);
        }

        // Vaciar el carrito
        unset($_SESSION['carrito']);

        // Confirmar transacción
        $pdo->commit();

        header('Location: gracias.php');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error al procesar el pedido: " . $e->getMessage();
    }
}
?>
