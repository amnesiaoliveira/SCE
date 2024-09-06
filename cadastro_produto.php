<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];

    // Verifica se o código do produto já existe
    $query_check = "SELECT * FROM produtos WHERE codigo = '$codigo'";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        $error = "Código de produto já existente!";
    } else {
        $query = "INSERT INTO produtos (codigo, nome, categoria) 
                  VALUES ('$codigo', '$nome', '$categoria')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Produto cadastrado com sucesso!";
        } else {
            $error = "Erro ao cadastrar o produto: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            color: #005073;
        }
        .container {
            margin-left: 176px; /* Alinha o conteúdo ao lado da sidebar */
            padding: 20px;
            width: calc(100% - 176px); /* Ajusta a largura do container */
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Alinha o conteúdo no topo */
            align-items: flex-start;
            min-height: 100vh;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 600px;
            width: 100%;
        }
        .form-group {
            flex: 1 1 48%;
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="text"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #079DC2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #005073;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include('dashboard.php'); ?> <!-- Inclui o menu lateral -->

    <div class="container">
        <h2>Cadastro de Produtos</h2>
        <form method="POST">
            <div class="form-group">
                <label for="codigo">Código do Produto:</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoria:</label>
                <input type="text" id="categoria" name="categoria" required>
            </div>
            <input type="submit" value="Cadastrar Produto">
        </form>
        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>
</body>
</html>
