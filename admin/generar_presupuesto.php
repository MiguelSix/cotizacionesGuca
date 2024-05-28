<?php
require_once '../database.php';
require_once '../libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$datosPost = file_get_contents('php://input');
$datos = json_decode($datosPost, true);

$idCotizacion = $datos['idCotizacion'];
$comentarios = $datos['comentarios'];
$precios = $datos['precios'];

$sql = "SELECT empresa, productos FROM cotizacioness WHERE id_cotizacion = :idCotizacion";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':idCotizacion', $idCotizacion);
$stmt->execute();
$cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cotizacion) {
    $cotizacion['empresa'] = json_decode($cotizacion['empresa'], true);
    $cotizacion['productos'] = json_decode($cotizacion['productos'], true);

    $dompdf = new Dompdf();

    $encargado = 'Nombre del Encargado'; // Reemplaza con el nombre del encargado
    $fechaActual = date('d/m/Y');

    // Estructura HTML del PDF con Bootstrap embebido y colores
    $html = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <style>
            .header, .footer {
                width: 100%;
                position: fixed;
            }
            .header {
                top: 0px;
                text-align: right;
                padding-right: 20px;
            }
            .footer {
                bottom: 0px;
                font-size: 12px;
            }
            .table th, .table td {
                vertical-align: middle;
                text-align: center;
            }
            .container {
                margin-top: 120px; /* Adjust margin to fit header */
                padding-bottom: 150px; /* Adjust padding to fit footer */
            }
            body {
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                padding: 20px;
            }
            .table thead {
                background-color: #343a40;
                color: white;
            }
            .table tbody tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            .table tbody tr:hover {
                background-color: #ddd;
            }
            .contact-info, .payment-methods {
                font-size: 12px;
            }
            .contact-info .icon, .payment-methods .icon {
                margin-right: 5px;
                color: blue;
            }
            .metodosPago {
                list-style: none;
                padding-left: 0;
            }
        </style>
        <title>Presupuesto</title>
    </head>
    <body>
        <div class="header">
            <p>ID de la Cotización: ' . $idCotizacion . '</p>
            <p>Fecha: ' . $fechaActual . '</p>
            <p>Encargado: ' . $encargado . '</p>
        </div>
        <div class="container mt-5 pt-5">
            <h2 class="mt-4">Detalles de la empresa</h2>
            <p><strong>Nombre:</strong> ' . $cotizacion['empresa']['nombre'] . '</p>
            <p><strong>Teléfono:</strong> ' . $cotizacion['empresa']['telefono'] . '</p>
            <p><strong>Correo:</strong> ' . $cotizacion['empresa']['correo'] . '</p>
            <h2>Productos</h2>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Color</th>
                        <th>Dimensiones</th>
                        <th>Material</th>
                        <th>Precio</th>
                        <th>Comentarios</th>
                    </tr>
                </thead>
                <tbody>';

    $i = 0;
    foreach ($cotizacion['productos'] as $producto) {
        $html .= '
                    <tr>
                        <td>' . $producto['nombre'] . '</td>
                        <td>' . $producto['marca'] . '</td>
                        <td>' . $producto['cantidad'] . '</td>
                        <td>' . $producto['descripcion'] . '</td>
                        <td>' . $producto['color'] . '</td>
                        <td>' . $producto['dimensiones'] . '</td>
                        <td>' . $producto['material'] . '</td>
                        <td>$' . $precios[$i]['precio'] . '</td>
                        <td>' . $comentarios[$i] . '</td>
                    </tr>';
        $i++;
    }

    $html .= '
                </tbody>
            </table>
        </div>
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 contact-info">
                        <div class="d-flex align-items-center mb-2">
                            <span class="material-icons icon">call</span>
                            <div>
                                <span style="font-weight: bold;">TELÉFONO:</span>
                                <span>442 457 5646 &nbsp; 442 862 4169</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="material-icons icon">mail</span>
                            <div>
                                <span style="font-weight: bold;">CORREO:</span>
                                <span>ventas@gucadistribucion.com</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="material-icons icon">home</span>
                            <div>
                                <span style="font-weight: bold;">PÁGINA WEB:</span>
                                <span>www.gucadistribucion.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 payment-methods">
                        <i class="material-icons icon">payment</i>
                        <span style="font-weight: bold;">MÉTODOS DE PAGO:</span>
                        <ul class="metodosPago">
                            <li><i class="material-icons icon">credit_card</i>PAGO CON TARJETA.</li>
                            <li><i class="material-icons icon">money</i>PAGO EN EFECTIVO.</li>
                            <li><i class="material-icons icon">account_balance</i>PAGO CON TRANSFERENCIA.</li>
                            <li>BANCO: BBVA BANCOMER</li>
                            <li>CLABE INTERBANCARIA: 012 680 00483610535 0</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';

    // Cargar el contenido HTML en Dompdf
    $dompdf->loadHtml($html);

    // Renderizar el PDF
    $dompdf->render();

    // Enviar el PDF al navegador para descarga
    $dompdf->stream('presupuesto.pdf', ['Attachment' => true]);
} else {
    echo "Cotización no encontrada.";
}
?>
