<?php
require '../database.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM prasarana WHERE id_prasarana = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode($data);
        } else {
            echo json_encode(["error" => "Data tidak ditemukan."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Query gagal: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "ID tidak ditemukan."]);
}

$conn->close();
?>
