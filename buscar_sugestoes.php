<?php
include('config/db.php');

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    
    // Consulta para buscar produtos por nome ou código
    $sql = "SELECT codigo, nome FROM produtos WHERE nome LIKE '%$query%' OR codigo LIKE '%$query%'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li onclick=\"selecionarSugestao('{$row['codigo']}', '{$row['nome']}')\">{$row['codigo']} - {$row['nome']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Produto não encontrado!</p>";
    }
}
?>
<style>
.suggestions-box {
    border: 1px solid #ccc;
    background-color: #fff;
    position: absolute;
    max-height: 150px;
    overflow-y: auto;
    z-index: 1000;
    width: 90%; /* Largura igual à do input */
    display: none;
}

.suggestions-box ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.suggestions-box li {
    padding: 10px;
    cursor: pointer;
}

.suggestions-box li:hover {
    background-color: #f0f0f0;
}
</style>
