<?php
include("conexion.php");

$nombre_negocio  = $_POST['nombre_negocio'];
$nombre_contacto = $_POST['nombre_contacto'];
$telefono        = $_POST['telefono'];
$rfc             = !empty($_POST['rfc']) ? $_POST['rfc'] : 'NO APLICA';
$direccion       = $_POST['direccion'];
$cantidades      = $_POST['cantidades']; 

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
                throw new Exception("Stock insuficiente para: " . $prod['nombre'] . " (Disponibles: " . $prod['stock'] . ")");
            }

            $nuevo_stock = $prod['stock'] - $cantidad;
            $conexion->query("UPDATE productos SET stock = $nuevo_stock WHERE id = $id_producto");

            $subtotal = $cantidad * $prod['precio'];
            $resumen_pedido .= "- " . $prod['nombre'] . " x$cantidad unidades - Subtotal: $" . number_format($subtotal, 2) . "\n";
        }
    }

    if (!$hay_productos) {
        throw new Exception("Debes seleccionar al menos un producto para registrar el pedido de mayoreo.");
    }

    $sql_pedido = $conexion->prepare("INSERT INTO pedidos_mayoreo (nombre_negocio, nombre_contacto, telefono, rfc, direccion, detalles_pedido) VALUES (?, ?, ?, ?, ?, ?)");
    $sql_pedido->bind_param("ssssss", $nombre_negocio, $nombre_contacto, $telefono, $rfc, $direccion, $resumen_pedido);
    $sql_pedido->execute();

    $conexion->commit();
    $conexion->close();

    header("Location: exito.php");
    exit();

} catch (Exception $e) {
    $conexion->rollback();
    $conexion->close();
    echo "<h3 style='color:red; font-family:Arial;'>Error en mayoreo: " . $e->getMessage() . "</h3>";
    echo "<br><a href='mayoreo.php'>Regresar al formulario</a>";
}
?>