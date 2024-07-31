<?php
session_start();
include('db.php');

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Consulta para verificar las credenciales
        $stmt = $conn->prepare("SELECT * FROM login WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            // Credenciales correctas
            $_SESSION['username'] = $username;
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['role'] = $row['role'];
            header('Location: Index.php'); // Redirige a la página de bienvenida
            exit(); // Asegúrate de llamar a exit después de redirigir
        } else {
            // Credenciales incorrectas
            header('Location: login.php?error=' . urlencode('Usuario o contraseña incorrectos.'));
            exit();
        }
    } catch (PDOException $e) {
        header('Location: login.php?error=' . urlencode('Error en la consulta: ' . $e->getMessage()));
        exit();
    }
} else {
    header('Location: login.php?error=' . urlencode('Por favor, complete el formulario.'));
    exit();
}
