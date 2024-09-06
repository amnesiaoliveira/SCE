<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = $_POST['codigo'];
    $produto = $_POST['produto'];
    $quantidade = $_POST['quantidade'];
    $data_lancamento = $_POST['data_lancamento'];
    $numero_lote = $_POST['numero_lote'];
    $data_fabricacao = $_POST['data_fabricacao'];
    $validade = $_POST['validade'];

    // Registrar a entrada
    $query_entrada = "INSERT INTO entradas (codigo, produto, quantidade, data_lancamento, numero_lote, data_fabricacao, validade) 
                      VALUES ('$codigo', '$produto', $quantidade, '$data_lancamento', '$numero_lote', '$data_fabricacao', '$validade')";
    if (!mysqli_query($conn, $query_entrada)) {
        die("Erro ao registrar entrada: " . mysqli_error($conn));
    }

    // Atualizar a quantidade do produto
    $query_atualizar = "UPDATE produtos SET quantidade = quantidade + $quantidade WHERE codigo = '$codigo' AND nome = '$produto'";
    if (!mysqli_query($conn, $query_atualizar)) {
        die("Erro ao atualizar quantidade do produto: " . mysqli_error($conn));
    }

    $success = "Entrada registrada com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #005073;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        .container {
            margin-left: 176px; /* Ajusta a largura do container para ao lado da sidebar */
            padding: 20px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        form {
            max-width: 1000px;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Ajusta o espaçamento entre os campos */
        }
        .form-group {
            flex: 1 1 calc(30% - 10px); /* Ajusta a largura dos campos, considerando a margem */
            margin-bottom: 20px;
        }
        .form-validade {
            flex: 1 1 calc(30% - 10px); /* Ajusta a largura dos campos, considerando a margem */
            margin-bottom: 20px;
        }
        .form-validade label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"], 
        .form-group input[type="number"], 
        .form-group input[type="date"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #00A2E8;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            flex-basis: 100%; /* Faz com que o botão ocupe toda a largura disponível */
        }
        input[type="submit"]:hover {
            background-color: #005073;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
    <script>
        function buscarProduto() {
            var codigo = document.getElementById('codigo').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'buscar_produto.php?codigo=' + codigo, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById('produto').value = xhr.responseText;
                    } else {
                        document.getElementById('produto').value = 'Produto não encontrado!';
                    }
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <?php include('dashboard.php'); ?> <!-- Inclui o menu lateral -->

    <div class="container">
        <h2>Entrada de Produtos</h2>
        <form method="POST">
            <div class="form-group">
                <label for="codigo">Código do Produto:</label>
                <input type="text" id="codigo" name="codigo" onblur="buscarProduto()" required>
            </div>
            <div class="form-group">
                <label for="produto">Nome do Produto:</label>
                <input type="text" id="produto" name="produto" readonly required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" required>
            </div>
            <div class="form-group">
                <label for="data_lancamento">Data de Lançamento:</label>
                <input type="date" id="data_lancamento" name="data_lancamento" required>
            </div>
            <div class="form-group">
                <label for="numero_lote">Número do Lote:</label>
                <input type="text" id="numero_lote" name="numero_lote" required>
            </div>
            <div class="form-group">
                <label for="data_fabricacao">Data de Fabricação:</label>
                <input type="date" id="data_fabricacao" name="data_fabricacao" required>
            </div>
            <div class="form-validade">
                <label for="validade">Validade:</label>
                <input type="date" id="validade" name="validade" required>
            </div>
            <input type="submit" value="Registrar Entrada">
        </form>
        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
    </div>
</body>
</html>
