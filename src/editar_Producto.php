<?php
// Iniciar el buffer de salida
ob_start();

// Configuración de la base de datos
$host = 'postgres-master';
$db = 'Proyecto_U1_G1';
$user = 'postgres';
$password = 'root';

try {
    // Crear una nueva conexión a la base de datos usando PDO
    $dsn = "pgsql:host=$host;dbname=$db";
    $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM Productos WHERE id_producto = :id_producto";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_producto' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $nombre = $row['nombre'];
            $id_tipo_producto = $row['id_tipo_producto'];
            $precio = $row['precio'];
            $cantidad = $row['cantidad'];
        } else {
            echo "<p class='alert alert-warning'>Producto no encontrado</p>";
            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id_producto'];
        $nombre = $_POST['nombre'];
        $id_tipo_producto = $_POST['id_tipo_producto'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];

        $sql = "UPDATE Productos SET nombre = :nombre, id_tipo_producto = :id_tipo_producto, precio = :precio, cantidad = :cantidad WHERE id_producto = :id_producto";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':id_tipo_producto' => $id_tipo_producto,
            ':precio' => $precio,
            ':cantidad' => $cantidad,
            ':id_producto' => $id
        ]);

        // Redirigir a Producto.php
        header("Location: Producto.php");
        exit(); // Asegúrate de usar exit() después de header() para detener la ejecución del script
    }

} catch (PDOException $e) {
    echo "<p class='alert alert-danger'>Error: " . $e->getMessage() . "</p>";
}

// Limpiar el buffer de salida y enviar el contenido al navegador
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Editar Producto</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_tipo_producto" class="form-label">Tipo de Producto:</label>
                        <select class="form-select" id="id_tipo_producto" name="id_tipo_producto" required>
                            <?php
                            try {
                                $sql = "SELECT id_tipo_producto, descripcion FROM Tipos_Productos";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $tipos_productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($tipos_productos as $tipo) {
                                    echo "<option value='" . htmlspecialchars($tipo["id_tipo_producto"]) . "' " . ($tipo["id_tipo_producto"] == $id_tipo_producto ? "selected" : "") . ">" . htmlspecialchars($tipo["descripcion"]) . "</option>";
                                }

                            } catch (PDOException $e) {
                                echo "<p class='alert alert-danger'>Error: " . $e->getMessage() . "</p>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($precio); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="Producto.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
