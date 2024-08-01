<?php
// Conexión base datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda-online";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener fechas no disponibles
    $stmt = $conn->prepare("SELECT fecha FROM citas");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $unavailableDates = array_map(function ($item) {
        return $item['fecha'];
    }, $result);

    echo json_encode(['unavailableDates' => $unavailableDates]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
