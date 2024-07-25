<?php
// Configuración de la base de datos
$host = 'postgres';
$db = 'Proyecto_U1_G1';
$user = 'postgres';
$password = 'root';
$dsn = "pgsql:host=$host;dbname=$db";

try {
    // Crear una nueva conexión a la base de datos usando PDO
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Procesamiento del formulario PHP para insertar la cosecha
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        // Recoger los datos del formulario
        $id_parcela = $_POST['id_parcela'];
        $id_empleado = $_POST['id_empleado'];
        $fecha_cosecha = $_POST['fecha_cosecha'];
        $cantidad_cosechada = $_POST['cantidad_cosechada'];

        // Insertar un nuevo registro en la tabla Cosecha
        $sql = "INSERT INTO Cosecha (id_parcela, id_empleado, fecha_cosecha, cantidad_cosechada) 
                VALUES (:id_parcela, :id_empleado, :fecha_cosecha, :cantidad_cosechada)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_parcela', $id_parcela);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->bindParam(':fecha_cosecha', $fecha_cosecha);
        $stmt->bindParam(':cantidad_cosechada', $cantidad_cosechada);
        $stmt->execute();

        // Redirigir de vuelta a la página principal
        header("Location: Cosecha.php");
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
    <title>Agregar Nueva Cosecha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h2 class="mb-0">Agregar Nueva Cosecha</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="id_parcela" class="form-label">ID Parcela:</label>
                        <input type="number" class="form-control" id="id_parcela" name="id_parcela" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_empleado" class="form-label">Empleado:</label>
                        <select class="form-control" id="id_empleado" name="id_empleado" required>
                            <option value="" disabled selected>Seleccione una opción</option>
                            <?php
                            try {
                                // Query para obtener empleados
                                $query = "SELECT id_empleado, nombre
                                    FROM Empleados e 
                                    INNER JOIN tipos_empleados as t
                                        ON t.id_tipo_empleado = e.id_tipo_empleado
                                    WHERE t.descripcion = 'Palmicultores'";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();

                                // Mostrar opciones en el select
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . htmlspecialchars($row['id_empleado']) . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                                }

                            } catch (PDOException $e) {
                                echo "<option disabled>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_cosecha" class="form-label">Fecha de Cosecha:</label>
                        <input type="date" class="form-control" id="fecha_cosecha" name="fecha_cosecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad_cosechada" class="form-label">Cantidad Cosechada:</label>
                        <input type="number" step="0.01" class="form-control" id="cantidad_cosechada" name="cantidad_cosechada" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2" name="submit">Guardar</button>
                        <a href="Cosecha.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRar6i2ewd6L4a6vK8KaFfIl6zYDPQ6od5xnRd5t" crossorigin="anonymous"></script>
</body>

</html>
