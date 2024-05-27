<?php
require_once '../database.php';

// Consulta SQL para obtener las cotizaciones
$sql = "SELECT * FROM cotizacioness";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$cotizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver las cotizaciones como JSON
header('Content-Type: application/json');
echo json_encode($cotizaciones);
?>