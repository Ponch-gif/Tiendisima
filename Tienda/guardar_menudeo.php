<?php
include("conexion.php");

$nombre = $_POST['nombre_cliente'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$cantidades = $_POST['cantidades']; 

$resumen_pedido = "";
$hay_productos = false;

$conexion->begin_transaction();

try {
    foreach ($cantidades as $id_producto => $cantidad) {
        $cantidad = intval($cantidad);

        if ($cantidad > 0) {
            $hay_productos = true;

            $sql_prod = $conexion->query("SELECT nombre, stock, precio FROM productos WHERE id = $id_producto");
            $prod = $sql_prod->fetch_assoc();

            if ($cantidad > $prod['stock']) {
                throw new Exception("Lo sentimos, no hay suficiente stock para: " . $prod['nombre'] . " (Disponibles: " . $prod['stock'] . ")");
            }

            $nuevo_stock = $prod['stock'] - $cantidad;
            $conexion->query("UPDATE productos SET stock = $nuevo_stock WHERE id = $id_producto");

            $subtotal = $cantidad * $prod['precio'];
            $resumen_pedido .= "- " . $prod['nombre'] . " (Cant: $cantidad) - Subtotal: $" . number_format($subtotal, 2) . "\n";
        }
    }

    if (!$hay_productos) {
        throw new Exception("Debes seleccionar al menos un producto para hacer el pedido.");
    }

    $sql_pedido = $conexion->prepare("INSERT INTO pedidos_menudeo (nombre_cliente, telefono, direccion, detalles_pedido) VALUES (?, ?, ?, ?)");
    $sql_pedido->bind_param("ssss", $nombre, $telefono, $direccion, $resumen_pedido);
    $sql_pedido->execute();

    $conexion->commit();
    $conexion->close();

    // Redirigir a una página limpia de éxito o mostrar mensaje en pantalla sin alertas
    header("Location: exito.php");
    exit();

} catch (Exception $e) {
    $conexion->rollback();
    $conexion->close();
    // Si hay error, regresamos y mostramos el texto básico
    echo "<h3 style='color:red; font-family:Arial;'>Error: " . $e->getMessage() . "</h3>";
    echo "<br><a href='menudeo.php'>Regresar al formulario</a>";
}
?>