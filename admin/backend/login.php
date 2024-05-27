<?php 
session_start();
$host = "localhost"; 
$bd = "guca"; 
$usuario = "root"; 
$contrasenia = ""; 

try {
    $conexion = new PDO("mysql:host=$host;dbname=$bd", $usuario, $contrasenia);
    if ($conexion) {
        echo "Conexión exitosa ";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Prepara la consulta
    $sql = "SELECT * FROM `users` WHERE `username` = :username AND `password` = :password;";
    $stmt = $conexion->prepare($sql);
    
    // Vincula los parámetros
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    
    // Ejecuta la consulta
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Credenciales válidas
        $_SESSION['admin'] = $username;
        header("Location: ../admin.html");
        exit();
    } else {
        echo "Invalid username or password.";
    }
    
    $stmt = null;
    $conexion = null;
}
?>