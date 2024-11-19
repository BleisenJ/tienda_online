<?php
include 'includes/conexion.php';

$query = $pdo->query("SELECT * FROM productos");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Catálogo de Productos</title>
</head>
<body>
    <h1>Catálogo de Productos</h1>
    <div class="productos">
        <?php foreach ($productos as $producto): ?>
            <div class="producto">
                <h2><?= htmlspecialchars($producto['nombre']); ?></h2>
                <p><?= htmlspecialchars($producto['descripción']); ?></p>
                <p>Precio: $<?= number_format($producto['precio'], 2); ?></p>
                <button onclick="addToCart(<?= $producto['id']; ?>)">Añadir al carrito</button>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="js/cart.js"></script>
</body>
</html>
