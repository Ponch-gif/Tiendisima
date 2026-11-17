<?php
include("conexion.php");
$resultado_productos = $conexion->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Abarrotes Don Ponce - Pedidos a Domicilio</title>
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
                <a href="mayoreo.php">Pedidos Mayoreo</a>
                <a href="login.php" class="btn-admin">Administrador</a>
            </div>
        </div>
    </div>

    <!-- CONTENEDOR DEL FORMULARIO -->
    <div class="form-container" style="max-width: 800px;">
        <h2>🚚 Pedido a Domicilio (Menudeo)</h2>
        <p class="subtitle-form">Ingresa tus datos de entrega y selecciona las cantidades de los productos que deseas.</p>

        <form action="guardar_menudeo.php" method="POST">
            
            <!-- DATOS DEL CLIENTE -->
            <div class="campo-form">
                <label>Nombre Completo:</label>
                <input type="text" name="nombre_cliente" required placeholder="Ej. Juan Pérez">
            </div>

            <div class="campo-form">
                <label>Teléfono de Contacto:</label>
                <input type="text" name="telefono" required placeholder="Ej. 5512345678">
            </div>

            <div class="campo-form">
                <label>Dirección de Entrega:</label>
                <textarea name="direccion" rows="2" required placeholder="Calle, número, colonia y referencias"></textarea>
            </div>

            <h3 style="margin-top: 25px; color: #0066c0;">Selecciona tus Productos:</h3>
            <p class="subtitle-aside">Indica la cantidad que requieres de cada artículo:</p>

            <!-- TABLA DE SELECCIÓN DE PRODUCTOS -->
            <table border="1" style="margin-bottom: 20px;">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Disponible</th>
                    <th>Cantidad a pedir</th>
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
                            <!-- Input que manda un arreglo con el ID del producto y la cantidad -->
                            <input type="number" name="cantidades[<?php echo $row['id']; ?>]" min="0" max="<?php echo $row['stock']; ?>" value="0" style="width: 70px; padding: 5px;">
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="4">No hay productos disponibles por el momento.</td>
                    </tr>
                <?php } ?>
            </table>

            <button type="submit" class="btn-enviar">Confirmar y Enviar Pedido</button>
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