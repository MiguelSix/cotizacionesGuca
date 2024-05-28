<?php
require_once '../database.php';

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

    $nombreEmpresa = $cotizacion['empresa']['nombre'];

    $encargado = 'ERIC ROJAS';
    $fechaActual = date('d/m/Y');

    $total = 0;
    $subtotal = 0;

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
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #f8f9fa;
                padding: 10px 0;
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
            .container-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .info, .info2 {
                font-size: 12px;
            }
            .header-logo img {
                width: 100px;
            }
            .header-title h1 {
                margin: 0;
                font-size: 24px;
            }
            .header-cotizacion {
                text-align: right;
            }
            .header-cotizacion h2 {
                margin: 0;
                font-size: 20px;
            }
            .tabla2 td {
                padding: 5px 10px;
            }
        </style>
        <title>Presupuesto</title>
    </head>
    <body>
        <header class="header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8 d-flex align-items-center">
                        <div class="header-title">
                            <h1>GUCA DISTRIBUCIÓN</h1>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="header-cotizacion text-right">
                            <h2>COTIZACIÓN</h2>
                            <p>FOLIO: ' . $idCotizacion . '</p>
                            <p>FECHA: ' . $fechaActual . '</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <br/>
        <div class="container">
            <div class="container-row">
                <div class="col-md-6">
                    <div class="info">
                        <div><strong>YAEL GUERRERO ROJAS</strong></div>
                        <div>CALLE LOS CACAHUATES # 54</div>
                        <div>COL VALLE DE LOS OLIVOS C.P. 76902</div>
                        <div>QUERÉTARO, QRO.</div>
                        <div>R.F.C.: GURE020706HY6</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info2">
                        <div><strong>RECEPTOR</strong></div>
                        <div><strong>EMPRESA:</strong> ' . $nombreEmpresa . '</div>
                        <div><strong>VENDEDOR:</strong> ' . $encargado . '</div>
                    </div>
                </div>
            </div>
            <br/>
            <h3>Productos</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Comentarios/th>
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
                        <td>$' . $precios[$i]['precio'] . '</td>
                        <td>' . $comentarios[$i] . '</td>
                    </tr>';
        $i++;
    }

    $html .= '
                </tbody>
            </table>
            <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Términos y Condiciones</h5>
                        <ul>
                            <li>PAGOS DE CONTADO</li>
                            <li>ENVÍO A DOMICILIO GRATIS SI EL MONTO ES MAYOR A $3500, SI ES MENOR SE COBRA CONFORME LA DISTANCIA.</li>
                            <li>MATERIAL COTIZADO SALVO PREVIA VENTA</li>
                            <li>PRECIOS SUJETOS A EXISTENCIAS Y LISTAS ACTUALES</li>
                            <li>VIGENCIA DE 1 SEMANA</li>
                            <li>PRECIOS VÁLIDOS AL CONFIRMAR EL 100% DE LA COTIZACIÓN</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Resumen</h5>
                            <table class="tabla2">
    ';

    foreach ($cotizacion['productos'] as $key => $producto) {
        $precio = floatval($precios[$key]['precio']); // Convertir a float
        $subtotal += $precio;
        $total += $precio;
    }

    $iva = $total * 0.16; // Calculando el IVA al 16%
    $total += $iva;

    $html .= '
                                <tr>
                                    <td><strong>SUBTOTAL</strong></td>
                                    <td>$' . number_format($subtotal, 2) . '</td>
                                </tr>
                                <tr>
                                    <td><strong>IVA</strong></td>
                                    <td>$' . number_format($iva, 2) . '</td>
                                </tr>
                                <tr style="background-color: #30779D; color: white;">
                                    <td><strong>TOTAL</strong></td>
                                    <td>$' . number_format($total, 2) . '</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Métodos de Pago</h5>
                            <ul class="metodosPago">
                                <li><i class="bi bi-credit-card-fill" style="margin-right: 5px; color: blue;"></i>PAGO CON TARJETA.</li>
                                <li><i class="bi bi-cash" style="margin-right: 5px; color: blue;"></i>PAGO EN EFECTIVO.</li>
                                <li><i class="bi bi-bank" style="margin-right: 5px; color: blue;"></i>PAGO CON TRANSFERENCIA.</li>
                                <li>BANCO: BBVA BANCOMER</li>
                                <li>CLABE INTERBANCARIA: 012 680 00483610535 0</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="footer">
                <div class="container-row">
                    <div class="col-md-6">
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
                </div>
            </div>
        </footer> 
    </body>
    </html>';

    echo $html;
}
?>
