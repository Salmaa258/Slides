<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// Incluye la conexión a la base de datos
include '../db.php';

// Verifica y recibe los datos del formulario
$titol = isset($_POST['titol']) ? $_POST['titol'] : '';
$descripcio = isset($_POST['descripcio']) ? $_POST['descripcio'] : '';

// Prepara la sentencia SQL para insertar los datos en la tabla 'Presentacions'
$stmt = $conn->prepare("INSERT INTO Presentacions (Títol, Descripció) VALUES (?, ?)");
$stmt->bind_param("ss", $titol, $descripcio);

// Inicializa el array de respuesta
$response = [];

// Intenta ejecutar la sentencia y configura la respuesta en función del éxito o fracaso
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

// Envía la respuesta en formato JSON
echo json_encode($response);
?>
