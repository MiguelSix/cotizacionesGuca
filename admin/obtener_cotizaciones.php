<?php
require_once '../database.php';

$sql = "SELECT * FROM cotizacioness";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$cotizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($cotizaciones);
?>