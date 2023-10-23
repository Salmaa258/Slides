<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Pantalla d'inici</title>
</head>

<body>
    <h2>Presentaciones</h2>
        <div id="global">
        <div id="añadirPresentacion">
            <a href="html/crear_p.html">
                <div class="caja_añadir">
                    <img src="icons/crear.svg" alt="Crear">
                </div>
            </a>
            <p id="añadir">AÑADIR</p>
        </div>
            <?php
            // Punto de entrada principal
            define('VALID_ENTRY_POINT', true);

            include 'php/config.php';

            // Incluir la clase de base de datos
            require_once ROOT_PATH . 'php/clases/db.php';

            // Obtener la única instancia de la base de datos
            $db = Database::getInstance();

            // Obtener la conexión a la base de datos
            $conn = $db->getConnection();

            // Consultar todas las presentaciones
            $query = "SELECT id, titulo FROM presentacion";
            $result = $conn->query($query);

            // Generar dinámicamente los divs con class="caja"
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="caja" onmouseover="mostrarImagen(this)" onmouseout="ocultarImagen(this)">';
                echo '<p>' . htmlspecialchars($row['titulo']) . '</p>';
                echo '<button class="opciones-btn" onclick="mostrarOpciones(event)">+</button>';
                echo '<div class="opciones" id="opciones' . $row['id'] . '">';
                echo '<button class="editar"><img src="icons/editar.svg" alt="Editar">Editar</button>';
                echo '<button class="clonar"><img src="icons/clonar.svg" alt="Clonar">Clonar</button>';
                echo '<button class="eliminar"><img src="icons/eliminar.svg" alt="Eliminar">Eliminar</button>';
                echo '</div>';
                echo '<div class="imagen-overlay"></div>';
                echo '</div>';
            }
            ?>
        </div>


    <script src="js/home.js"></script>

</body>

</html>