<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $query = $pdo->prepare("DELETE FROM productos WHERE id = :id");
        $query->execute(['id' => $id]);
    } catch (PDOException $e) {
        die("Error al eliminar el producto: " . $e->getMessage());
    }
}

header('Location: admin_productos.php');
exit;
?>
