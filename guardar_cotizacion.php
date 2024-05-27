<?php
require_once 'database.php';

// Obtener los datos enviados desde JavaScript
$jsonData = file_get_contents('php://input');

if (!empty($jsonData)) {
    $datos = json_decode($jsonData, true);

    if (is_array($datos) && isset($datos['empresa'], $datos['productos'], $datos['idCotizacion'])) {
        $empresa = json_encode($datos['empresa']);
        $productos = json_encode($datos['productos']);
        $idCotizacion = $datos['idCotizacion'];

        // Preparar la consulta SQL
        $sql = "INSERT INTO cotizacioness (empresa, productos, id_cotizacion) VALUES (:empresa, :productos, :idCotizacion)";
        $stmt = $conexion->prepare($sql);

        // Vincular los valores a los parámetros de la consulta
        $stmt->bindParam(':empresa', $empresa);
        $stmt->bindParam(':productos', $productos);
        $stmt->bindParam(':idCotizacion', $idCotizacion);

        // Ejecutar la consulta
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