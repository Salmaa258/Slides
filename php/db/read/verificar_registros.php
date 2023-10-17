<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Incluye la conexión a la base de datos
include '../db.php';

$response = [];

// Prepara la consulta para contar los registros en la tabla 'Presentacions'
$sql = "SELECT COUNT(*) as total FROM Presentacions";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $totalRegistros = (int)$row['total'];

    $response['hasRecords'] = ($totalRegistros > 0);
} else {
    $response['error'] = "Error al consultar la base de datos: " . $conn->error;
}

echo json_encode($response);
?>
