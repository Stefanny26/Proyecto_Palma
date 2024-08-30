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
        $sql = "SELECT * FROM Empleados WHERE id_empleado = :id_empleado";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_empleado' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $nombre = $row['nombre'];
            $apellido = $row['apellido'];
            $id_tipo_empleado = $row['id_tipo_empleado'];
            $fecha_contratacion = $row['fecha_contratacion'];
            $salario = $row['salario'];
        } else {
            echo "<p class='text-danger'>Empleado no encontrado</p>";
            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id_empleado'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $id_tipo_empleado = $_POST['id_tipo_empleado'];
        $fecha_contratacion = $_POST['fecha_contratacion'];
        $salario = $_POST['salario'];

        $sql = "UPDATE Empleados SET nombre = :nombre, apellido = :apellido, id_tipo_empleado = :id_tipo_empleado, fecha_contratacion = :fecha_contratacion, salario = :salario WHERE id_empleado = :id_empleado";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':id_tipo_empleado' => $id_tipo_empleado,
            ':fecha_contratacion' => $fecha_contratacion,
            ':salario' => $salario,
            ':id_empleado' => $id
        ]);

        // Redirigir de vuelta a la página principal
        header("Location: Empleados.php");
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
    <title>Editar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Editar Empleado</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <input type="hidden" name="id_empleado" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido:</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_tipo_empleado" class="form-label">Cargo:</label>
                        <select class="form-control" id="id_tipo_empleado" name="id_tipo_empleado" required>
                            <?php
                            try {
                                $sql = "SELECT id_tipo_empleado, descripcion FROM Tipos_Empleados";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $tipos_empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($tipos_empleados as $tipo) {
                                    echo "<option value='" . htmlspecialchars($tipo["id_tipo_empleado"]) . "' " . ($tipo["id_tipo_empleado"] == $id_tipo_empleado ? "selected" : "") . ">" . htmlspecialchars($tipo["descripcion"]) . "</option>";
                                }

                            } catch (PDOException $e) {
                                echo "<p class='text-danger'>Error: " . $e->getMessage() . "</p>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_contratacion" class="form-label">Fecha de Contratación:</label>
                        <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" value="<?php echo htmlspecialchars($fecha_contratacion); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="salario" class="form-label">Salario:</label>
                        <input type="number" step="0.01" class="form-control" id="salario" name="salario" value="<?php echo htmlspecialchars($salario); ?>" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>
                        <a href="Empleados.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRar6i2ewd6L4a6vK8KaFfIl6zYDPQ6od5xnRd5t" crossorigin="anonymous"></script>
</body>

</html>
