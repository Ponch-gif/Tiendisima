<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Credenciales sencillas para la escuela (puedes cambiarlas aquí mismo)
    if ($usuario === "admin" && $password === "admin123") {
        $_SESSION['admin_logueado'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Abarrotes Don Ponce - Login Administrador</title>
    <link rel="stylesheet" href="estilos/styles.css">
</head>
<body>

    <!-- ENCABEZADO / NAVBAR -->
    <div class="header">
        <div class="navbar">
            <div class="brand">
                <h1 class="titulo-tienda">Abarrotes Don Ponce</h1>
            </div>
            <div class="nav-links">
                <a href="index.php">Inicio</a>
            </div>
        </div>
    </div>

    <!-- CONTENEDOR DEL LOGIN -->
    <div class="form-container" style="max-width: 400px; text-align: center;">
        <h2>🔐 Acceso Administrador</h2>
        <p class="subtitle-form">Ingresa tus credenciales para administrar el inventario.</p>

        <?php if (!empty($error)) { ?>
            <p style="color: red; font-size: 13px; font-weight: bold;"><?php echo $error; ?></p>
        <?php } ?>

        <form action="login.php" method="POST" style="text-align: left;">
            <div class="campo-form">
                <label>Usuario:</label>
                <input type="text" name="usuario" required placeholder="admin">
            </div>

            <div class="campo-form">
                <label>Contraseña:</label>
                <input type="password" name="password" required placeholder="admin123">
            </div>

            <button type="submit" class="btn-enviar" style="background-color: #ff9900; color: black;">Entrar al Sistema</button>
        </form>

        <div class="enlace-volver" style="margin-top: 20px;">
            <a href="index.php">← Regresar a la tienda</a>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Abarrotes Don Ponce S.A. de C.V. - Todos los derechos reservados.</p>
    </div>

</body>
</html>