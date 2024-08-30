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
        $sql = "SELECT * FROM Tipos_Empleados WHERE id_tipo_empleado = :id_tipo_empleado";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_tipo_empleado' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $descripcion = $row['descripcion'];
        } else {
            echo "<p class='text-danger'>Cargo no encontrado</p>";
            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $descripcion = $_POST['descripcion'];
        $id = $_POST['id_tipo_empleado'];
        $sql = "UPDATE Tipos_Empleados SET descripcion = :descripcion WHERE id_tipo_empleado = :id_tipo_empleado";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':descripcion' => $descripcion, ':id_tipo_empleado' => $id]);

        // Redirigir de vuelta a la página principal
        header("Location: TipoEmpleado.php");
        exit(); // Asegúrate de usar exit() después de header() para detener la ejecución del script
    }

} catch (PDOException $e) {
    echo "<p class='text-danger'>Error: " . $e->getMessage() . "</p>";
}

// Limpiar el buffer de salida y enviar el contenido al navegador
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cargo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card ">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Editar Cargo</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <input type="hidden" name="id_tipo_empleado" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($descripcion); ?>" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>
                        <a href="TipoEmpleado.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRar6i2ewd6L4a6vK8KaFfIl6zYDPQ6od5xnRd5t" crossorigin="anonymous"></script>
</body>

</html>
