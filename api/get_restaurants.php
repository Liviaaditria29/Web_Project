<?php
header('Content-Type: application/json');

require_once '../includes/db.php';

$sql = "SELECT id, name, lat, lng, rating, promo FROM restaurants";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode(['restaurants' => $data]);
$conn->close();
?>
