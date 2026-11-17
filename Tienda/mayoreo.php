<?php
include("conexion.php");
$resultado_productos = $conexion->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Abarrotes Don Ponce - Pedidos al Mayoreo</title>
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
                <a href="menudeo.php">Pedidos A Domicilio</a>
                <a href="login.php" class="btn-admin">Administrador</a>
            </div>
        </div>
    </div>

    <!-- CONTENEDOR DEL FORMULARIO -->
    <div class="form-container" style="max-width: 800px;">
        <h2>📦 Pedido al Mayoreo (Comerciantes)</h2>
        <p class="subtitle-form">Servicio especializado para negocios y compras por volumen. Cotiza y realiza tu pedido directamente.</p>

        <form action="guardar_mayoreo.php" method="POST">
            
            <!-- DATOS DEL NEGOCIO / CLIENTE -->
            <div class="campo-form">
                <label>Nombre del Negocio / Tienda:</label>
                <input type="text" name="nombre_negocio" required placeholder="Ej. Abarrotes La Bendición">
            </div>

            <div class="campo-form">
                <label>Nombre del Contacto:</label>
                <input type="text" name="nombre_contacto" required placeholder="Ej. Carlos Mendoza">
            </div>

            <div class="campo-form">
                <label>Teléfono de Contacto:</label>
                <input type="text" name="telefono" required placeholder="Ej. 5587654321">
            </div>

            <div class="campo-form">
                <label>RFC:</label>
                <input type="text" name="rfc" placeholder="Ej. ABCD900101XXX">
            </div>

            <div class="campo-form">
                <label>Dirección del Negocio o Entrega:</label>
                <textarea name="direccion" rows="2" required placeholder="Calle, número, colonia, municipio o referencias de entrega"></textarea>
            </div>

            <h3 style="margin-top: 25px; color: #0066c0;">Selección de Productos Mayoreo:</h3>
            <p class="subtitle-aside">Indica las unidades/cajas requeridas para cada producto:</p>

            <!-- TABLA DE SELECCIÓN DE PRODUCTOS -->
            <table border="1" style="margin-bottom: 20px;">
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Stock Actual</th>
                    <th>Cantidad / Unidades</th>
                </tr>
                <?php 
                if ($resultado_productos && $resultado_productos->num_rows > 0) {
                    while($row = $resultado_productos->fetch_assoc()) { 
                ?>
                    <tr>
                        <td><strong><?php echo $row['nombre']; ?></strong><br><small><?php echo $row['categoria']; ?></small></td>
                        <td>$<?php echo number_format($row['precio'], 2); ?></td>
                        <td><?php echo $row['stock']; ?> disp.</td>
                        <td>
                            <input type="number" name="cantidades[<?php echo $row['id']; ?>]" min="0" max="<?php echo $row['stock']; ?>" value="0" style="width: 80px; padding: 5px;">
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="4">No hay productos disponibles para mayoreo.</td>
                    </tr>
                <?php } ?>
            </table>

            <button type="submit" class="btn-enviar" style="background-color: #0066c0;">Enviar Pedido de Mayoreo</button>
        </form>

        <div class="enlace-volver">
            <a href="index.php">← Regresar a la página principal</a>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Abarrotes Don Ponce S.A. de C.V. - Todos los derechos reservados.</p>
    </div>

</body>
</html>