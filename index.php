<?php
function conection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "frutas";
    $port = "3307";

    $conn = new mysqli($servername, $username, $password, $database, $port);

    if ($conn->connect_error)
    {
        die("conexion fallida: " . $conn->connect_error);
    }
    return $conn;
}
function insertar(mysqli $conn, $cosas, $cantidad)
{
    $insert = "INSERT INTO  alimentos(Cosas, Cantidad) VALUES ('$cosas', '$cantidad')";
    if(mysqli_query($conn, $insert))
    {
        echo("Muy bien");
    }
    else
    {
        die('Error: ' . mysqli_error($conn));
    }
}
function  mostrar(mysqli $conn)
{
    $most = "SELECT * FROM alimentos";
    $resultado = mysqli_query($conn, $most);

    if (!$resultado)
    {
    die("Error en la consulta: " . mysqli_error($conn));
    }
    while ($fila = mysqli_fetch_assoc($resultado))
    {
        echo "<tr>";
        echo "<td>" . $fila['Cosas'] . "</td>";
        echo "<td>" . $fila['Cantidad'] . "</td>";
        echo "<td class='actions'>
                <form method='post' style='display:inline'>
                    <input type='hidden' name='id' value='{$fila['id']}'>
                    <button type='submit' name='eliminar' class='btn-action btn-delete'>
                        Eliminar
                    </button>
                </form>
              </td>";
        echo "</tr>";
    }
}
function borrar(mysqli $conn, $id)
{
    $sql = "DELETE FROM alimentos WHERE id =  $id";

    if (!mysqli_query($conn, $sql))
    {
        die('Error: ' . mysqli_error($conn));
    }
}

$Conexion = conection();
if(isset($_POST["Enviar"]))
{
    $Cosas = $_POST["producto"];
    $Cantidad = $_POST["cantidad"];

    insertar($Conexion,$Cosas, $Cantidad);
}
if(isset($_POST["eliminar"]))
{
    $ID = $_POST["id"];
    borrar($Conexion, $ID);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermercado - Gestión de Inventario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="header">
        <h1>🛒 Sistema de Inventario - Supermercado</h1>
    </header>

    <div class="content">
        <!-- Formulario para agregar productos -->
        <div class="form-section">
            <h2>Agregar Producto</h2>
            <form  method="post" id="formAgregar" class="form">
                <div class="form-group">
                    <label for="producto">Producto:</label>
                    <input type="text" id="productos" name="producto" placeholder="Ej: Manzanas" required>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidads" name="cantidad" placeholder="Ej: 100" required>
                </div>
                <button type="submit" class="btn btn-primary" name="Enviar">Agregar</button>
            </form>
        </div>
        <!-- Tabla de productos -->
        <div class="table-section">
            <div class="table-header">
                <h2>Inventario Actual</h2>
                <div class="search-box">
                    <input type="text" id="buscar" placeholder="Buscar producto...">
                </div>
            </div>

            <div class="table-wrapper">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Cosas</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody id="tablaProductos">
                    <?php
                    mostrar($Conexion);
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <p>Total de productos: <span id="totalProductos">5</span></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
