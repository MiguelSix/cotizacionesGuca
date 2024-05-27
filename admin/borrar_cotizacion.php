<?php
require_once '../database.php';

$idCotizacion = $_GET['id'];

$sql = "DELETE FROM cotizacioness WHERE id_cotizacion = :idCotizacion";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':idCotizacion', $idCotizacion);

if ($stmt->execute()) {
    echo "Cotización eliminada correctamente.";
} else {
    echo "Error al eliminar la cotización.";
}
?>