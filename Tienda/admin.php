<?php
session_start();

// Si no ha iniciado sesión, lo regresamos al login
if (!isset($_SESSION['admin_logueado']) || $_SESSION['admin_logueado'] !== true) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

// Procesar si mandaron a agregar un nuevo producto
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_producto'])) {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    $sql_insert = $conexion->prepare("INSERT INTO productos (nombre, categoria, precio, stock) VALUES (?, ?, ?, ?)");
    $sql_insert->bind_param("ssdi", $nombre, $categoria, $precio, $stock);
    
    if ($sql_insert->execute()) {
        $mensaje = "¡Producto agregado con éxito al inventario!";
    } else {
        $mensaje = "Error al agregar el producto: " . $conexion->error;
    }
    $sql_insert->close();
}

// Consultar productos actuales
$resultado_productos = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Abarrotes Don Ponce - Panel Administrador</title>
    <link rel="stylesheet" href="estilos/styles.css">
</head>
<body>

    <!-- ENCABEZADO / NAVBAR -->
    <div class="header">
        <div class="navbar">
            <div class="brand">
                <h1 class="titulo-tienda">Panel de Control - Administrador</h1>
            </div>
            <div class="nav-links">
                <a href="index.php" target="_blank">Ver Tienda</a>
                <a href="logout.php" style="background-color: #dc3545;">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <!-- CONTENEDOR GENERAL DEL PANEL -->
    <div class="main-container" style="max-width: 1000px;">
        
        <?php if (!empty($mensaje)) { ?>
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px; text-align: center; font-weight: bold;">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>

        <!-- SECCIÓN 1: AGREGAR NUEVO PRODUCTO -->
        <div class="form-container" style="margin: 0 auto 30px auto; max-width: 100%;">
            <h3>➕ Agregar Nuevo Producto al Inventario</h3>
            <form action="admin.php" method="POST">
                <div style="display: inline-block; width: 23%; margin-right: 2%;">
                    <label>Nombre del Producto:</label>
                    <input type="text" name="nombre" required placeholder="Ej. Galletas Marías">
                </div>
                <div style="display: inline-block; width: 23%; margin-right: 2%;">
                    <label>Categoría:</label>
                    <input type="text" name="categoria" required placeholder="Ej. Galletas">
                </div>
                <div style="display: inline-block; width: 20%; margin-right: 2%;">
                    <label>Precio ($):</label>
                    <input type="number" step="0.01" name="precio" required placeholder="18.50">
                </div>
                <div style="display: inline-block; width: 20%; margin-right: 2%;">
                    <label>Stock (Cant.):</label>
                    <input type="number" name="stock" required placeholder="50">
                </div>
                <div style="display: inline-block; width: 100%; margin-top: 15px;">
                    <button type="submit" name="agregar_producto" class="btn-enviar" style="background-color: #28a745;">Guardar Producto</button>
                </div>
            </form>
        </div>

        <!-- SECCIÓN 2: LISTADO DE PRODUCTOS ACTUALES -->
        <div class="form-container" style="margin: 0 auto; max-width: 100%;">
            <h3>📋 Inventario Actual en Base de Datos</h3>
            <table border="1" style="margin-top: 15px;">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock Disponible</th>
                </tr>
                <?php while($row = $resultado_productos->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><strong><?php echo $row['nombre']; ?></strong></td>
                    <td><?php echo $row['categoria']; ?></td>
                    <td>$<?php echo number_format($row['precio'], 2); ?></td>
                    <td><?php echo $row['stock']; ?> unidades</td>
                </tr>
                <?php } ?>
            </table>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Abarrotes Don Ponce S.A. de C.V. - Todos los derechos reservados.</p>
    </div>

</body>
</html>