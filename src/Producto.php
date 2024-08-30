<?php
include 'access_control.php';
checkAccess(['Admin', 'Palmicultor', 'Vendedor']); // Admin, Cosecha y Vendedor tienen acceso a esta página
include 'db.php';

// Lógica de la página Productos
$alertMessage = '';
$editMessage = '';
$deleteMessage = '';

if (!hasRole('Admin')) {
    $alertMessage = 'No tienes permiso para realizar esta acción.';
}

function getRoleBasedActions($id_producto) {
    if (hasRole('Admin')) {
        return "<a href='editar_producto.php?id=$id_producto' class='btn btn-warning btn-sm'>Editar</a>
                <a href='?delete_id=$id_producto' class='btn btn-danger btn-sm'>Eliminar</a>";
    } else {
        return "<button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#alertModal'>Editar</button>
                <button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#alertModal'>Eliminar</button>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="lead">

    <!-- Cabecera -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand h2" href="#">Palma Africana</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
        <?php if (hasRole('Admin')): ?>
            <li><a class="nav-link" href="TipoProducto.php">Tipos de Productos</a></li>
            <?php else: ?>
                <li><a class="nav-link" data-bs-toggle="modal" data-bs-target="#alertModal">Tipos de Productos</a></li>
                        <?php endif; ?>
            <li><a class="nav-link active" href="Producto.php">Productos</a></li>
        </nav>

        <article style="height: 650px;">
            <div class="jumbotron text-center">
                <h1 class="display-4">Listado de Productos</h1>
            </div>

            <!-- Formulario de búsqueda -->
            <form method="GET" action="Producto.php" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por Nombre o Tipo de producto">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        <?php if (hasRole('admin')): ?>
                            <a href="nuevo_producto.php" class="btn btn-success">Agregar Nuevo Producto</a>
                        <?php else: ?>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#alertModal">Agregar Nuevo Producto</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

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

                // Procesar solicitud para eliminar producto
                if (isset($_GET['delete_id'])) {
                    $delete_id = $_GET['delete_id'];
                    $sql = "DELETE FROM Productos WHERE id_producto=:id_producto";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':id_producto' => $delete_id]);
                }

                // Procesar búsqueda
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $search = htmlspecialchars($search);

                // Consulta para obtener los datos de la tabla Productos con búsqueda
                if (!empty($search)) {
                    $sql = "SELECT p.id_producto, p.nombre, tp.descripcion as tipo_producto, p.precio, p.cantidad
                            FROM Productos p
                            JOIN Tipos_Productos tp ON p.id_tipo_producto = tp.id_tipo_producto
                            WHERE p.nombre ILIKE :search OR tp.descripcion ILIKE :search";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':search' => '%' . $search . '%']);
                } else {
                    $sql = "SELECT p.id_producto, p.nombre, tp.descripcion as tipo_producto, p.precio, p.cantidad
                            FROM Productos p
                            JOIN Tipos_Productos tp ON p.id_tipo_producto = tp.id_tipo_producto";
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
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo de Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($result)) {
                        // Recorrer los resultados y mostrarlos en la tabla
                        foreach ($result as $row) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["id_producto"]) . "</td>
                                    <td>" . htmlspecialchars($row["nombre"]) . "</td>
                                    <td>" . htmlspecialchars($row["tipo_producto"]) . "</td>
                                    <td>" . htmlspecialchars($row["precio"]) . "</td>
                                    <td>" . htmlspecialchars($row["cantidad"]) . "</td>
                                    <td>
                                        " . getRoleBasedActions(htmlspecialchars($row["id_producto"])) . "
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
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

    <!-- Modal de Alerta -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Permiso Denegado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo htmlspecialchars($alertMessage); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
