<?php
require '../database.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT pm.*, p.nama_prasarana 
              FROM peminjaman pm 
              JOIN prasarana p ON pm.id_prasarana = p.id_prasarana 
              WHERE pm.id_peminjaman = ?";
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
        echo json_encode(["error" => "Kesalahan pada query: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "ID tidak ditemukan."]);
}

$conn->close();
