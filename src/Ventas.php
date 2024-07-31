<?php
include 'access_control.php';
checkAccess(['Admin', 'Vendedor']); // Admin y Vendedor tienen acceso a esta página
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="lead">
    <!-- Cabecera -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand h2" href="#">Palma Africana</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
            <li><a class="nav-link active" href="Ventas.php">Ventas</a></li>
            <li><a class="nav-link" href="Facturas.php">Detalles de Ventas</a></li>
        </nav>

        <article>
            <h1>Registro de Ventas</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <fieldset>
                    <legend>Datos de la Venta</legend>
                    <div class="form-group">
                        <label for="id_cliente">Cliente:</label>
                        <select class="form-control" id="id_cliente" name="id_cliente" required>
                            <option selected>Seleccione una opción</option>
                            <?php
                            try {
                                $dsn = "pgsql:host=postgres;dbname=Proyecto_U1_G1";
                                $conn = new PDO($dsn, 'postgres', 'root');
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $query = "SELECT id_cliente, nombre FROM Clientes";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . htmlspecialchars($row['id_cliente']) . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo "<option>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_empleado">Empleado:</label>
                        <select class="form-control" id="id_empleado" name="id_empleado" required>
                            <option selected>Seleccione una opción</option>
                            <?php
                            try {
                                $query = "SELECT id_empleado, nombre FROM Empleados e 
                                          INNER JOIN tipos_empleados t ON t.id_tipo_empleado = e.id_tipo_empleado 
                                          WHERE t.descripcion = 'Vendedor'";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . htmlspecialchars($row['id_empleado']) . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo "<option>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha_venta">Fecha de Venta:</label>
                        <input type="datetime-local" class="form-control" id="fecha_venta" name="fecha_venta" required>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Detalles de la Venta</legend>
                    <div id="productos">
                        <div class="producto">
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="id_producto">Producto:</label>
                                    <select class="form-control" id="id_producto" name="id_producto[]" required onchange="actualizarPrecio(this)">
                                        <option selected>Seleccione una opción</option>
                                        <?php
                                        try {
                                            $query = "SELECT id_producto, nombre FROM Productos p 
                                                      INNER JOIN tipos_productos tp ON p.id_tipo_producto = tp.id_tipo_producto 
                                                      WHERE tp.descripcion = 'Venta'";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . htmlspecialchars($row['id_producto']) . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "<option>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <label for="cantidad">Cantidad:</label>
                                    <input type="number" class="form-control" name="cantidad[]" required>
                                </div>
                                <div class="form-group col">
                                    <label for="precio_unitario">Precio Unitario:</label>
                                    <input type="number" step="0.01" class="form-control" name="precio_unitario[]" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger mt-2" onclick="eliminarProducto(this)">Eliminar</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="agregarProducto()">Agregar Producto</button>
                </fieldset>
                <input type="submit" class="btn btn-success mt-2" value="Registrar Venta">
            </form>
        </article>
    </section>

    <!-- Pie de página -->
    <footer class="bg-light text-center py-4">
        <p>&copy; 2024 Palma Africana. Todos los derechos reservados.</p>
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="resultadoModal" tabindex="-1" aria-labelledby="resultadoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultadoModalLabel">Resultado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="resultadoModalBody">
                    <!-- Mensaje del modal -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function agregarProducto() {
            const productosDiv = document.getElementById('productos');
            const nuevoProducto = document.createElement('div');
            nuevoProducto.classList.add('producto');
            nuevoProducto.innerHTML = `
                <div class="form-row">
                    <div class="form-group col">
                        <label for="id_producto">Producto:</label>
                        <select class="form-control" name="id_producto[]" required onchange="actualizarPrecio(this)">
                            <option selected>Seleccione una opción</option>
                            <?php
                            try {
                                $query = "SELECT id_producto, nombre FROM Productos p 
                                          INNER JOIN tipos_productos tp ON p.id_tipo_producto = tp.id_tipo_producto 
                                          WHERE tp.descripcion = 'Venta'";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . htmlspecialchars($row['id_producto']) . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo "<option>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" class="form-control" name="cantidad[]" required>
                    </div>
                    <div class="form-group col">
                        <label for="precio_unitario">Precio Unitario:</label>
                        <input type="number" step="0.01" class="form-control" name="precio_unitario[]" required>
                    </div>
                </div>
                <button type="button" class="btn btn-danger mt-2" onclick="eliminarProducto(this)">Eliminar</button>
            `;
            productosDiv.appendChild(nuevoProducto);
        }

        function eliminarProducto(boton) {
            boton.parentElement.remove();
        }

        function actualizarPrecio(selectElement) {
            // Implementa la lógica para actualizar el precio basado en el producto seleccionado
            const idProducto = selectElement.value;
            const productoDiv = selectElement.closest('.producto');
            const precioInput = productoDiv.querySelector('input[name="precio_unitario[]"]');

            if (idProducto) {
                $.ajax({
                    url: 'get_product_price.php',
                    type: 'POST',
                    data: { id_producto: idProducto },
                    dataType: 'json',
                    success: function(response) {
                        if (response.precio) {
                            precioInput.value = response.precio;
                        } else {
                            precioInput.value = '';
                        }
                    },
                    error: function() {
                        precioInput.value = '';
                    }
                });
            } else {
                precioInput.value = '';
            }
        }

        // Mostrar modal con resultado
        function mostrarModal(mensaje) {
            document.getElementById('resultadoModalBody').innerText = mensaje;
            const modal = new bootstrap.Modal(document.getElementById('resultadoModal'));
            modal.show();
        }
    </script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "postgres";
    $dbname = "Proyecto_U1_G1";
    $user = "postgres";
    $password = "root";

    try {
        $dsn = "pgsql:host=$host;dbname=$dbname";
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Iniciar transacción
        $conn->beginTransaction();

        // Insertar venta
        $id_cliente = $_POST['id_cliente'];
        $id_empleado = $_POST['id_empleado'];
        $fecha_venta = $_POST['fecha_venta'];

        $sql_venta = "INSERT INTO Ventas (id_cliente, id_empleado, fecha_venta) VALUES (:id_cliente, :id_empleado, :fecha_venta) RETURNING id_venta";
        $stmt_venta = $conn->prepare($sql_venta);
        $stmt_venta->execute([
            ':id_cliente' => $id_cliente,
            ':id_empleado' => $id_empleado,
            ':fecha_venta' => $fecha_venta
        ]);
        $id_venta = $stmt_venta->fetchColumn();

        // Insertar detalles de venta
        $id_productos = $_POST['id_producto'];
        $cantidades = $_POST['cantidad'];
        $precios_unitarios = $_POST['precio_unitario'];
    
        $sql_detalle_venta = "INSERT INTO Detalles_Ventas (id_venta, id_producto, cantidad, precio_unitario) VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario)";
        $stmt_detalle_venta = $conn->prepare($sql_detalle_venta);

        $num_productos = count($id_productos);
        for ($i = 0; $i < $num_productos; $i++) {
            $stmt_detalle_venta->execute([
                ':id_venta' => $id_venta,
                ':id_producto' => $id_productos[$i],
                ':cantidad' => $cantidades[$i],
                ':precio_unitario' => $precios_unitarios[$i]
            ]);
        }

        // Confirmar la transacción
        $conn->commit();

        echo "<script>mostrarModal('Venta registrada exitosamente');</script>";
    } catch (PDOException $e) {
        // Rollback en caso de error
        $conn->rollBack();
        echo "<script>mostrarModal('Error: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }

    $conn = null;
}
?>
        
