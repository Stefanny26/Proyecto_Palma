<?php
include 'access_control.php';
checkAccess(['Admin', 'Vendedor']); // Admin y Vendedores tienen acceso a esta página
include 'db.php';

// Lógica de la página Clientes
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="lead">

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
                <li class="nav-item active">
                    <a class="nav-link" href="Index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Empleados.php">Sobre Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Clientes.php">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Producto.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Produccion.php">Productividad</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Ventas.php">Ventas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar sesión</a>
                </li>
                </ul>
      </div>
        </div>
    </nav>

    <section class="container">
        <nav class="nav flex-column nav-pills nav-fill">
            <li><a class="nav-link active" href="">Clientes</a></li>
        </nav>

        <article >
            <div class="jumbotron text-center">
                <h1 class="display-4">Lista de Clientes</h1>
            </div>

            <!-- Formulario de búsqueda -->
            <form method="GET" action="Clientes.php" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Buscar</button> 
                        <a href="nuevo_cliente.php" class="btn btn-success">Agregar Nuevo Cliente</a>
                    </div>
                </div>
            </form>

            <?php
                // Configuración de la base de datos
                $host = 'postgres-master'; // Dirección IP del contenedor PostgreSQL
                $db = 'Proyecto_U1_G1';
                $user = 'postgres';
                $password = 'root';

                try {
                    // Crear una nueva conexión a la base de datos usando PDO
                    $dsn = "pgsql:host=$host;dbname=$db";
                    $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                // Procesar solicitud para eliminar cliente
                if (isset($_GET['delete_id'])) {
                    $delete_id = $_GET['delete_id'];
                    $sql = "DELETE FROM Clientes WHERE id_cliente = :id_cliente";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':id_cliente' => $delete_id]);
                }

                // Procesar búsqueda
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $search = htmlspecialchars($search);

                // Consulta para obtener los datos de la tabla Clientes con búsqueda
                if (!empty($search)) {
                    $sql = "SELECT id_cliente, nombre, contacto, direccion FROM Clientes 
                            WHERE nombre ILIKE :search OR contacto ILIKE :search";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':search' => '%' . $search . '%']);
                } else {
                    $sql = "SELECT id_cliente, nombre, contacto, direccion FROM Clientes";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                }

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Cliente</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>direccion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($result)) {
                        // Recorrer los resultados y mostrarlos en la tabla
                        foreach ($result as $row) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["id_cliente"]) . "</td>
                                    <td>" . htmlspecialchars($row["nombre"]) . "</td>
                                    <td>" . htmlspecialchars($row["contacto"]) . "</td>
                                    <td>" . htmlspecialchars($row["direccion"]) . "</td>
                                    <td>
                                        <a href='editar_clientes.php?id=" . htmlspecialchars($row["id_cliente"]) . "' class='btn btn-warning btn-sm'>Editar</a>
                                        <a href='?delete_id=" . htmlspecialchars($row["id_cliente"]) . "' class='btn btn-danger btn-sm'>Eliminar</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay clientes registrados</td></tr>";
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
