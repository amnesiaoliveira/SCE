<?php
include('config/db.php');

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $query = mysqli_real_escape_string($conn, $query);
    $sql = "SELECT * FROM produtos WHERE nome LIKE '%$query%'";
    $result = mysqli_query($conn, $sql);

    $produtos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produtos[] = $row;
    }

    echo json_encode($produtos);
}
?>