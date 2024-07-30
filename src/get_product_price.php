<?php
if (isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];

    try {
        $host = 'postgres';
        $db = 'Proyecto_U1_G1';
        $user = 'postgres';
        $password = 'root';
        
        // Conexión a la base de datos usando PDO
        $dsn = "pgsql:host=$host;dbname=$db";
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Query para obtener el precio del producto
        $query = "SELECT precio FROM Productos WHERE id_producto = :id_producto";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo json_encode(['precio' => $row['precio']]);
        } else {
            echo json_encode(['precio' => '']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID de producto no proporcionado']);
}
?>
