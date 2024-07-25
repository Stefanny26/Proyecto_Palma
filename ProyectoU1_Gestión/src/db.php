<?php
// Configuración de la base de datos
$host = 'postgres'; // Dirección IP del contenedor PostgreSQL
$db = 'Proyecto_U1_G1';
$user = 'postgres';
$password = 'root';

try {
    // Crear una nueva conexión a la base de datos usando PDO
    $dsn = "pgsql:host=$host;dbname=$db";
    $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    // echo "Conexión exitosa"; // Comentar o eliminar esta línea
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
