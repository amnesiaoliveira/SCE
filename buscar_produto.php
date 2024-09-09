<?php
include('config/db.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Consultar o nome do produto no banco de dados
    $query = "SELECT nome FROM produtos WHERE codigo = '$codigo'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['nome']; // Retorna o nome do produto
    } else {
        echo "Produto não encontrado!"; // Mensagem caso o produto não seja encontrado
    }
}
?>
