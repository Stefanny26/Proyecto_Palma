<?php
// Iniciar el buffer de salida
ob_start();

// Configuración de la base de datos
$host = 'postgres';
$db = 'Proyecto_U1_G1';
$user = 'postgres';
$password = 'root';

try {
    // Crear una nueva conexión a la base de datos usando PDO
    $dsn = "pgsql:host=$host;dbname=$db";
    $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM parcelas WHERE id_parcela = :id_parcela";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_parcela' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $cantidad_de_plantas = $row['cantidad_de_plantas'];
            $tipo_suelo = $row['tipo_suelo'];
            $fecha_plantacion = $row['fecha_plantacion'];
        } else {
            echo "<p class='alert alert-warning'>Parcela no encontrada</p>";
            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id_parcela'];
        $cantidad_de_plantas = $_POST['cantidad_de_plantas'];
        $tipo_suelo = $_POST['tipo_suelo'];
        $fecha_plantacion = $_POST['fecha_plantacion'];

        $sql = "UPDATE parcelas SET cantidad_de_plantas = :cantidad_de_plantas, tipo_suelo = :tipo_suelo, fecha_plantacion = :fecha_plantacion WHERE id_parcela = :id_parcela";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':cantidad_de_plantas' => $cantidad_de_plantas,
            ':tipo_suelo' => $tipo_suelo,
            ':fecha_plantacion' => $fecha_plantacion,
            ':id_parcela' => $id
        ]);

        // Redirigir a Produccion.php
        header("Location: Produccion.php");
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
    <title>Editar Parcela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Editar Parcela</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <input type="hidden" name="id_parcela" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="mb-3">
                        <label for="cantidad_de_plantas" class="form-label">Cantidad de Plantas:</label>
                        <input type="number" class="form-control" id="cantidad_de_plantas" name="cantidad_de_plantas" value="<?php echo htmlspecialchars($cantidad_de_plantas); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_suelo" class="form-label">Tipo de Suelo:</label>
                        <input type="text" class="form-control" id="tipo_suelo" name="tipo_suelo" value="<?php echo htmlspecialchars($tipo_suelo); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_plantacion" class="form-label">Fecha de Plantación:</label>
                        <input type="date" class="form-control" id="fecha_plantacion" name="fecha_plantacion" value="<?php echo htmlspecialchars($fecha_plantacion); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="Produccion.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
