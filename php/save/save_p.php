<?php

// Incluye la configuración y la clase Database
require_once '../clases/db.php';

// Instancia la clase y obtiene la conexión
$db = new Database();
$conn = $db->getConnection();

// Verifica y recibe los datos del formulario
$titol = isset($_POST['p_titulo']) ? $_POST['p_titulo'] : '';
$descripcio = isset($_POST['p_descripcion']) ? $_POST['p_descripcion'] : '';

// Prepara la sentencia SQL usando PDO para insertar la presentación
$stmt = $conn->prepare("INSERT INTO Presentacions (Títol, Descripció) VALUES (?, ?)");
$stmt->bindParam(1, $titol);
$stmt->bindParam(2, $descripcio);
$stmt->execute();

// Obtén el ID de la presentación insertada
$presentacio_id = $conn->lastInsertId();

// Mostrar el ID de la presentación
echo "ID de la presentación: " . $presentacio_id . "<br>";

var_dump($_POST);

// Verifica si las claves existen y si son arrays
$d_tipos = isset($_POST['d_tipo']) && is_array($_POST['d_tipo']) ? $_POST['d_tipo'] : array();

// Recorrer todos los elementos en $d_tipos
for ($i = 0; $i < count($d_tipos); $i++) {
    // Obtener el tipo
    $tipo = $d_tipos[$i];

    // Obtener el ID del tipo de Diapositives_Tipus
    $stmtTipo = $conn->prepare("SELECT ID FROM Diapositives_Tipus WHERE Tipus = ?");
    $stmtTipo->bindParam(1, $tipo);
    $stmtTipo->execute();
    $tipo_id = $stmtTipo->fetchColumn();

    // Mostrar el ID del tipo
    echo "ID del tipo (" . $tipo . "): " . $tipo_id . "<br>";

    // Preparar sentencia SQL para insertar la diapositiva
    $stmt = $conn->prepare("INSERT INTO Diapositives (presentació_id, diapositives_tipus_id) VALUES (?, ?)");

    // Vincular los parámetros a la sentencia SQL
    $stmt->bindParam(1, $presentacio_id);
    $stmt->bindParam(2, $tipo_id);

    // Ejecutar la sentencia SQL
    $stmt->execute();
}

// Redirige al usuario de nuevo a la página HTML original
header("Location: /grup4-alfred_emilio_salma/html/crear_p.html");
exit;

