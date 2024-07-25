<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body  class="lead">

  <!-- Cabecera -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand h2" href="#">Palma Africana</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a class="nav-link" href="Index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="Empleados.php">Sobre Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="Clientes.php">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="Producto.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="Produccion.php">Productividad</a></li>
                <li class="nav-item"><a class="nav-link" href="Ventas.php">Ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
    </div>
  </nav>

    <section class="container">
        <nav class="nav flex-column nav-pills nav-fill">
            <li><a class="nav-link " href="Ventas.php">Ventas</a></li>  
            <li><a class="nav-link active" href="Facturas.php">Detalles de venta</a></li>  

        </nav>

        <article>

        <h1 class="text-center">Ventas con Detalles</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Empleado</th>
                    <th>Productos</th>
                    <th>Total Cantidad</th>
                    <th>Total Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Configuración de la base de datos
                $host = 'postgres';
                $dbname = 'Proyecto_U1_G1';
                $user = 'postgres';
                $password = 'root';

                try {
                    // Conexión a la base de datos PostgreSQL
                    $dsn = "pgsql:host=$host;dbname=$dbname";
                    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                    // Consulta a la vista VentasConDetalles
                    $query = "select * from VentasConProductos";
              $stmt = $pdo->prepare($query);
              $stmt->execute();

              // Mostrar los resultados en la tabla
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr><td>{$row['id_venta']}</td><td>{$row['nombre']}</td><td>{$row['n_empleado']}</td><td>{$row['productos']}</td><td>{$row['total_cantidad']}</td><td>{$row['total_precio']}</td></tr>";
              }
                } catch (PDOException $e) {
                    // Mostrar el mensaje de error si falla la conexión o la consulta
                    echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>

        </article>
    </section>

    <!-- Pie de página -->
    <footer class="bg-light text-center py-4">
        <p>&copy; 2024 Palma Africana. Todos los derechos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>