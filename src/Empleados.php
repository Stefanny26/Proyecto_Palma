<?php
include 'access_control.php';
checkAccess(['Admin', 'Palmicultor', 'Vendedor']); // Admin, Cosecha y Vendedor tienen acceso a esta página
include 'db.php';

// Lógica de la página Empleados
$alertMessage = '';
$editMessage = '';
$deleteMessage = '';

if (!hasRole('Admin')) {
    $alertMessage = 'No tienes permiso para realizar esta acción.';
}

function getRoleBasedActions($id_empleado) {
    if (hasRole('Admin')) {
        return "<a href='editar_empleado.php?id=$id_empleado' class='btn btn-warning btn-sm'>Editar</a>
                <a href='?delete_id=$id_empleado' class='btn btn-danger btn-sm'>Eliminar</a>";
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
    <title>Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>

<body class="d-flex flex-column lead">

    <!-- Cabecera -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand h2" href="#">Palma Africana</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <section class="container flex-grow-1">
        <nav class="nav flex-column nav-pills nav-fill">
            <?php if (hasRole('Admin')): ?>
                <li><a class="nav-link" href="TipoEmpleado.php">Cargos de personal</a></li>
            <?php endif; ?>
            <li><a class="nav-link active" href="Empleados.php">Empleados</a></li>
            <?php if (hasRole('Admin') || hasRole('Vendedor')): ?>
                <li><a class="nav-link" href="EmpleadosVentas.php">Empleados con más ventas</a></li>
            <?php endif; ?>
        </nav>

        <article>
            <div class="jumbotron1 text-center">
                <h1 class="display-3">Listado de Empleados</h1>
            </div>

            <!-- Formulario de búsqueda -->
            <form method="GET" action="Empleados.php" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por Nombre o Cargo">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                        <?php if (hasRole('Admin')): ?>
                            <a href="nuevo_empleado.php" class="btn btn-success">Agregar Nuevo Empleado</a>
                        <?php else: ?>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#alertModal">Agregar Nuevo Empleado</button>
                        <?php endif; ?>
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

                // Procesar solicitud para eliminar empleado
                if (isset($_GET['delete_id'])) {
                    $delete_id = $_GET['delete_id'];
                    $sql = "DELETE FROM Empleados WHERE id_empleado=:id_empleado";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':id_empleado' => $delete_id]);
                }

                // Procesar búsqueda
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $search = htmlspecialchars($search);

                // Filtrar empleados según el rol del usuario
                $roleFilter = '';
                if (hasRole('Vendedor')) {
                    $roleFilter = " AND te.descripcion = 'Vendedor'";
                } elseif (hasRole('Palmicultor')) {
                    $roleFilter = " AND te.descripcion = 'Palmicultores'";
                }

                // Consulta para obtener los datos de la tabla Empleados con búsqueda y filtro por rol
                if (!empty($search)) {
                    $sql = "SELECT e.id_empleado, e.nombre, e.apellido, te.descripcion, e.fecha_contratacion, e.salario
                            FROM Empleados e
                            INNER JOIN Tipos_Empleados te ON e.id_tipo_empleado = te.id_tipo_empleado
                            WHERE (e.nombre ILIKE :search OR e.apellido ILIKE :search OR te.descripcion ILIKE :search)
                            $roleFilter";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':search' => '%' . $search . '%']);
                } else {
                    $sql = "SELECT e.id_empleado, e.nombre, e.apellido, te.descripcion, e.fecha_contratacion, e.salario
                            FROM Empleados e
                            INNER JOIN Tipos_Empleados te ON e.id_tipo_empleado = te.id_tipo_empleado
                            WHERE 1=1 $roleFilter";
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
                        <th>Apellido</th>
                        <th>Cargo</th>
                        <th>Fecha de Contratación</th>
                        <th>Salario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($result)) {
                        // Recorrer los resultados y mostrarlos en la tabla
                        foreach ($result as $row) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["id_empleado"]) . "</td>
                                    <td>" . htmlspecialchars($row["nombre"]) . "</td>
                                    <td>" . htmlspecialchars($row["apellido"]) . "</td>
                                    <td>" . htmlspecialchars($row["descripcion"]) . "</td>
                                    <td>" . htmlspecialchars($row["fecha_contratacion"]) . "</td>
                                    <td>" . htmlspecialchars($row["salario"]) . "</td>
                                    <td>
                                        " . getRoleBasedActions(htmlspecialchars($row["id_empleado"])) . "
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay empleados registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
        </article>
    </section>

    <!-- Pie de página -->
    <footer class="bg-light text-center py-4 mt-4">
        <p>&copy; 2024 Palma Africana</p>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>

</html>
