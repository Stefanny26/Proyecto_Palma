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

    // Procesamiento del formulario PHP para insertar una nueva parcela
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger los datos del formulario
        $cantidad_de_plantas = $_POST['cantidad_de_plantas'];
        $tipo_suelo = $_POST['tipo_suelo'];
        $fecha_plantacion = $_POST['fecha_plantacion'];

        // Insertar un nuevo registro en la tabla Parcelas
        $sql = "INSERT INTO Parcelas (cantidad_de_plantas, tipo_suelo, fecha_plantacion) VALUES (:cantidad_de_plantas, :tipo_suelo, :fecha_plantacion)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':cantidad_de_plantas' => $cantidad_de_plantas,
            ':tipo_suelo' => $tipo_suelo,
            ':fecha_plantacion' => $fecha_plantacion
        ]);

        // Redirigir de vuelta a la página principal
        header("Location: Produccion.php");
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
    <title>Agregar Nueva Parcela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h2 class="mb-0">Agregar Nueva Parcela</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="cantidad_de_plantas" class="form-label">Cantidad de Plantas:</label>
                        <input type="number" class="form-control" id="cantidad_de_plantas" name="cantidad_de_plantas" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_suelo" class="form-label">Tipo de Suelo:</label>
                        <input type="text" class="form-control" id="tipo_suelo" name="tipo_suelo" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_plantacion" class="form-label">Fecha de Plantación:</label>
                        <input type="date" class="form-control" id="fecha_plantacion" name="fecha_plantacion" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Guardar</button>
                        <a href="Produccion.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRar6i2ewd6L4a6vK8KaFfIl6zYDPQ6od5xnRd5t" crossorigin="anonymous"></script>
</body>

</html>
