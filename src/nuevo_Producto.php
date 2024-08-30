<?php
// Configuración de la base de datos
$host = 'postgres-master';
$db = 'Proyecto_U1_G1';
$user = 'postgres';
$password = 'root';

try {
    // Crear una nueva conexión a la base de datos usando PDO
    $dsn = "pgsql:host=$host;dbname=$db";
    $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger los datos del formulario
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $tipo_producto = $_POST['tipo_producto'];

        // Insertar un nuevo registro en la tabla Productos
        $sql = "INSERT INTO Productos (nombre, id_tipo_producto, precio, cantidad) VALUES (:nombre, :id_tipo_producto, :precio, :cantidad)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id_tipo_producto', $tipo_producto);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->execute();

        // Redirigir de vuelta a la página principal
        header("Location: Producto.php");
        exit();
    }

} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h2 class="mb-0">Agregar Nuevo Producto</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_producto" class="form-label">Tipo de Producto:</label>
                        <select class="form-control" id="tipo_producto" name="tipo_producto" required>
                            <option selected disabled>Seleccione una opción</option>
                            <?php
                            try {
                                // Query para obtener los tipos de producto
                                $query = "SELECT id_tipo_producto, descripcion FROM Tipos_Productos";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();

                                // Mostrar opciones en el select
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . htmlspecialchars($row['id_tipo_producto']) . "'>" . htmlspecialchars($row['descripcion']) . "</option>";
                                }

                            } catch (PDOException $e) {
                                echo "<option disabled>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>
                        <a href="Producto.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRar6i2ewd6L4a6vK8KaFfIl6zYDPQ6od5xnRd5t" crossorigin="anonymous">
    </script>
</body>

</html>
