<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados</title>
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
            <li><a class="nav-link  active" href="TipoProducto.php">Tipos de Productos</a></li>  
            <li><a class="nav-link " href="Producto.php">Productos</a></li>  
        </nav>

        <article style="height: 650px;">
            <div class="jumbotron text-center">
                <h1 class="display-4">Listado de Tipos de Productos</h1>
            </div>
            <a href="nuevo_tipo_Producto.php" class="btn btn-success">Agregar Nuevo Tipo de Producto</a>
            <?php
            // Configuración de la base de datos
            $host = 'postgres';
            $db = 'Proyecto_U1_G1';
            $user = 'postgres';
            $password = 'root';

            try {
                // Crear una nueva conexión a la base de datos usando PDO
                $dsn = "pgsql:host=$host;dbname=$db";
                $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                // Procesar solicitud para eliminar tipo de producto
                if (isset($_GET['delete_id'])) {
                    $delete_id = $_GET['delete_id'];
                    $sql = "DELETE FROM Tipos_Productos WHERE id_tipo_producto=:id_tipo_producto";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':id_tipo_producto' => $delete_id]);
                }

                // Consulta para obtener los datos de la tabla Tipos_Productos
                $sql = "SELECT id_tipo_producto, descripcion FROM Tipos_Productos";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($result)) {
                        // Recorrer los resultados y mostrarlos en la tabla
                        foreach ($result as $row) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["id_tipo_producto"]) . "</td>
                                    <td>" . htmlspecialchars($row["descripcion"]) . "</td>
                                    <td>
                                        <a href='editar_tipo_producto.php?id=" . htmlspecialchars($row["id_tipo_producto"]) . "' class='btn btn-warning btn-sm'>Editar</a>
                                        <a href='?delete_id=" . htmlspecialchars($row["id_tipo_producto"]) . "' class='btn btn-danger btn-sm'>Eliminar</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No hay tipos de productos registrados</td></tr>";
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
