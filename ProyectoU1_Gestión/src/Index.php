<?php
// welcome.php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="Imagenes/favicon.ico" rel="icon" type="image/x-icon">
  <link rel="stylesheet" href="estilos.css">

</head>

<body  class="lead" >

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

  <div class="container">
    <!-- Sección principal -->
    <div class="jumbotron text-center ">
    <h1 class="display-4">Bienvenidos a Palma Africana ,  <?php echo htmlspecialchars($_SESSION['role']); ?></h1>      
    <p class="lead">Cultivamos la mejor palma africana para un futuro sostenible.</p>
    </div>

    <!-- Contenido principal -->
    <div class="container my-5">
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h2 class="card-title">Nuestra Misión</h2>
              <p class="card-text">Promover la agricultura sostenible y la producción de palma africana de alta calidad.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h2 class="card-title">Beneficios</h2>
              <p class="card-text">La palma africana ofrece numerosos beneficios tanto para la economía como para el
                medio ambiente.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h2 class="card-title">Contacto</h2>
              <p class="card-text">Ponte en contacto con nosotros para saber más sobre nuestros productos y servicios.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Galería -->
    <div class="container gallery my-5">
      <h2 class="text-center">Uso</h2>
      <div class="row">
        <div class="col-md-4">
          <img src="Imagenes/uso1.png" alt="Imagen 1">
          <h3>Aceites de Cocina</h3>
        </div>
        <div class="col-md-4">
          <img src="Imagenes/uso2.png" alt="Imagen 2">
          <h3>Jabones</h3>
        </div>
        <div class="col-md-4">
          <img src="Imagenes/uso3.png" alt="Imagen 3">
          <h3>Pastelería</h3>
        </div>
      </div>
    </div>
  </div>
  <!-- Pie de página -->
  <footer class="bg-light text-center py-4">
    <p>&copy; 2024 Palma Africana. Todos los derechos reservados.</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>