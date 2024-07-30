<?php
// Configuración de la base de datos
$host = 'postgres';
$db = 'Proyecto_U1_G1';
$user = 'postgres';
$password = 'root';
$dsn = "pgsql:host=$host;dbname=$db";

// Procesamiento del formulario PHP para insertar un nuevo empleado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $id_tipo_empleado = $_POST['id_tipo_empleado'];
    $fecha_contratacion = $_POST['fecha_contratacion'];
    $salario = $_POST['salario'];

    try {
        // Crear una nueva conexión a la base de datos usando PDO
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insertar un nuevo registro en la tabla Empleados
        $sql = "INSERT INTO Empleados (nombre, apellido, id_tipo_empleado, fecha_contratacion, salario) 
                VALUES (:nombre, :apellido, :id_tipo_empleado, :fecha_contratacion, :salario)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':id_tipo_empleado' => $id_tipo_empleado,
            ':fecha_contratacion' => $fecha_contratacion,
            ':salario' => $salario
        ]);

        // Redirigir a la página de empleados después de insertar
        header("Location: Empleados.php");
        exit();

    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h2 class="mb-0">Agregar Nuevo Empleado</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido:</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_tipo_empleado" class="form-label">Cargo:</label>
                        <select class="form-control" id="id_tipo_empleado" name="id_tipo_empleado" required>
                            <option selected>Seleccione una opción</option>
                            <?php
                            try {
                                // Crear una nueva conexión a la base de datos usando PDO
                                $conn = new PDO($dsn, $user, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                // Consulta para obtener los tipos de empleados
                                $sql = "SELECT id_tipo_empleado, descripcion FROM Tipos_Empleados";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $tipos_empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($tipos_empleados as $tipo) {
                                    echo "<option value='" . htmlspecialchars($tipo["id_tipo_empleado"]) . "'>" . htmlspecialchars($tipo["descripcion"]) . "</option>";
                                }

                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_contratacion" class="form-label">Fecha de Contratación:</label>
                        <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="salario" class="form-label">Salario:</label>
                        <input type="number" step="0.01" class="form-control" id="salario" name="salario" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Agregar</button>
                        <a href="Empleados.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRar6i2ewd6L4a6vK8KaFfIl6zYDPQ6od5xnRd5t" crossorigin="anonymous"></script>
</body>

</html>
