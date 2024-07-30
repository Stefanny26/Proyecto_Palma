<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nueva Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="estilos.css">
    <script>
        function addProductRow() {
            var container = document.getElementById("products-container");
            var newRow = document.createElement("div");
            newRow.className = "product-row row mb-3";

            newRow.innerHTML = `
                <div class="col-md-3">
                    <label for="id_producto">Producto:</label>
                    <select class="form-control" name="id_producto[]" required>
                        <?php
                        // Configuración de la base de datos
                        $host = 'postgres';
                        $db = 'Proyecto_U1_G1';
                        $user = 'postgres';
                        $password = 'root';

                        try {
                            // Conexión a la base de datos usando PDO
                            $dsn = "pgsql:host=$host;dbname=$db";
                            $conn = new PDO($dsn, $user, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query para obtener los productos
                            $query = "SELECT id_producto, nombre FROM Productos";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            // Mostrar opciones en el select
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['id_producto'] . "'>" . $row['nombre'] . "</option>";
                            }

                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" class="form-control" name="cantidad[]" required>
                </div>
                <div class="col-md-3">
                    <label for="precio_total">Precio Total:</label>
                    <input type="number" step="0.01" class="form-control" name="precio_total[]" required>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger form-control" onclick="removeProductRow(this)">Eliminar</button>
                </div>
            `;

            container.appendChild(newRow);
        }

        function removeProductRow(button) {
            var row = button.closest(".product-row");
            row.remove();
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <h2>Agregar Nueva Factura</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="id_cliente">Cliente:</label>
                <select class="form-control" id="id_cliente" name="id_cliente" required>
                    <?php
                    try {
                        // Query para obtener los clientes
                        $query = "SELECT id_cliente, nombre FROM Clientes";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();

                        // Mostrar opciones en el select
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['id_cliente'] . "'>" . $row['nombre'] . "</option>";
                        }

                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_empleado">Empleado:</label>
                <select class="form-control" id="id_empleado" name="id_empleado" required>
                    <?php
                    try {
                        // Query para obtener los empleados
                        $query = "SELECT id_empleado, nombre FROM Empleados";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();

                        // Mostrar opciones en el select
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['id_empleado'] . "'>" . $row['nombre'] . "</option>";
                        }

                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>

            <div id="products-container">
                <div class="product-row row mb-3">
                    <div class="col-md-3">
                        <label for="id_producto">Producto:</label>
                        <select class="form-control" name="id_producto[]" required>
                            <?php
                            try {
                                // Query para obtener los productos
                                $query = "SELECT id_producto, nombre FROM Productos";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();

                                // Mostrar opciones en el select
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $row['id_producto'] . "'>" . $row['nombre'] . "</option>";
                                }

                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" class="form-control" name="cantidad[]" required>
                    </div>
                    <div class="col-md-3">
                        <label for="precio_total">Precio Total:</label>
                        <input type="number" step="0.01" class="form-control" name="precio_total[]" required>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger form-control" onclick="removeProductRow(this)">Eliminar</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success mt-3" onclick="addProductRow()">Agregar Producto</button>
            <div class="form-group">
                <label for="fecha_venta">Fecha de Venta:</label>
                <input type="date" class="form-control" id="fecha_venta" name="fecha_venta" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3" name="submit">Guardar</button>
            <a href="Ventas.php" class="btn btn-secondary mt-3">Cancelar</a>
        </form>

        <?php
        // Procesamiento del formulario PHP para insertar la factura
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            try {
                // Crear una nueva conexión a la base de datos usando PDO
                $dsn = "pgsql:host=$host;dbname=$db";
                $conn = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                // Recoger los datos del formulario
                $id_cliente = $_POST['id_cliente'];
                $id_empleado = $_POST['id_empleado'];
                $fecha_venta = $_POST['fecha_venta'];
                $id_producto = $_POST['id_producto'];
                $cantidad = $_POST['cantidad'];
                $precio_total = $_POST['precio_total'];

                // Insertar un nuevo registro en la tabla Factura
                foreach ($id_producto as $key => $producto) {
                    $sql = "INSERT INTO factura (id_producto, id_cliente, id_empleado, cantidad, precio_total, fecha_venta) VALUES (:id_producto, :id_cliente, :id_empleado, :cantidad, :precio_total, :fecha_venta)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':id_producto', $producto);
                    $stmt->bindParam(':id_cliente', $id_cliente);
                    $stmt->bindParam(':id_empleado', $id_empleado);
                    $stmt->bindParam(':cantidad', $cantidad[$key]);
                    $stmt->bindParam(':precio_total', $precio_total[$key]);
                    $stmt->bindParam(':fecha_venta', $fecha_venta);
                    $stmt->execute();
                }

                // Redirigir de vuelta a la página principal
                header("Location: Ventas.php");
                exit();

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
    </div>
</body>

</html>
