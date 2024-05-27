<?php
require_once 'database.php';

$jsonData = file_get_contents('php://input');

if (!empty($jsonData)) {
    $datos = json_decode($jsonData, true);

    if (is_array($datos) && isset($datos['empresa'], $datos['productos'], $datos['idCotizacion'])) {
        $empresa = json_encode($datos['empresa']);
        $productos = json_encode($datos['productos']);
        $idCotizacion = $datos['idCotizacion'];

        $sql = "INSERT INTO cotizacioness (empresa, productos, id_cotizacion) VALUES (:empresa, :productos, :idCotizacion)";
        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(':empresa', $empresa);
        $stmt->bindParam(':productos', $productos);
        $stmt->bindParam(':idCotizacion', $idCotizacion);

        if ($stmt->execute()) {
            echo "Cotización guardada correctamente.";
        } else {
            echo "Error al guardar la cotización.";
        }
    } else {
        echo "Datos enviados inválidos.";
    }
} else {
    echo "No se recibieron datos.";
}

?>