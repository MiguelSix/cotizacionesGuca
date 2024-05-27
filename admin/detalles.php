<?php
require_once '../database.php';

$idCotizacion = $_GET['id'];

$sql = "SELECT empresa, productos FROM cotizacioness WHERE id_cotizacion = :idCotizacion";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':idCotizacion', $idCotizacion);
$stmt->execute();
$cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cotizacion) {
    $cotizacion['empresa'] = json_decode($cotizacion['empresa']);
    $cotizacion['productos'] = json_decode($cotizacion['productos']);
    header('Content-Type: application/json');
    echo json_encode($cotizacion);
} else {
    echo "Cotización no encontrada.";
}
?>